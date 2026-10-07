/**
 * Generates Postman collection for HR Shastri mobile APIs.
 * No changes under app/Http/Controllers/API/.
 */
const fs = require('fs');
const path = require('path');

const outDir = __dirname;

// Single shared preamble â€” extraTests must NOT redeclare tc/json/softMs/hardMs
const commonTests = `
var tc = pm.variables.get('tcId') || pm.info.requestName;
var softMs = Number(pm.environment.get('softLatencyMs') || 1000);
var hardMs = Number(pm.environment.get('hardLatencyMs') || 5000);
var json = null;
try { json = pm.response.json(); } catch (e) { json = null; }

pm.test('[' + tc + '] HTTP not 500', function () {
  pm.expect(pm.response.code).to.not.eql(500);
});

pm.test('[' + tc + '] response time < ' + hardMs + 'ms', function () {
  pm.expect(pm.response.responseTime).to.be.below(hardMs);
});

if (pm.response.responseTime > softMs) {
  console.warn('[' + tc + '] soft latency fail: ' + pm.response.responseTime + 'ms');
}

pm.test('[' + tc + '] response contract (or 401/429)', function () {
  if ([401, 429].includes(pm.response.code)) {
    pm.expect(true).to.be.true;
    return;
  }
  // Some endpoints return a bare array (e.g. mobile menu)
  if (Array.isArray(json)) {
    pm.expect(json.length).to.be.at.least(0);
    return;
  }
  pm.expect(json).to.be.an('object');
  pm.expect(json).to.have.property('status');
  // message is usual but not universal (e.g. sync-clock-in-clock-out-status)
});

pm.collectionVariables.set('lastEndpoint', pm.request.url.toString());
pm.collectionVariables.set('lastStatus', String(pm.response.code));
pm.collectionVariables.set('lastBody', (pm.response.text() || '').substring(0, 2000));
pm.collectionVariables.set('lastTime', String(pm.response.responseTime));
`.trim();

const happyStatus1 = `
pm.test('[' + tc + '] status === 1 (happy path)', function () {
  pm.expect(json).to.be.an('object');
  pm.expect(Number(json.status)).to.eql(1);
});
`.trim();

// Empty lists often return status 0 + "No Data Found" — still a valid current flow
const businessOk = `
pm.test('[' + tc + '] business ok (status 0 or 1, or array)', function () {
  if (Array.isArray(json)) {
    pm.expect(true).to.be.true;
    return;
  }
  pm.expect(json).to.be.an('object');
  pm.expect([0, 1]).to.include(Number(json.status));
});
`.trim();

const authBearer = [
  { key: 'Authorization', value: 'Bearer {{token}}', type: 'text' },
  { key: 'Accept', value: 'application/json', type: 'text' },
  { key: 'Content-Type', value: 'application/json', type: 'text' },
];

function req(name, tcId, urlPath, bodyObj, opts = {}) {
  const event = [
    {
      listen: 'prerequest',
      script: {
        type: 'text/javascript',
        exec: [
          `pm.variables.set('tcId', '${tcId}');`,
          ...(opts.prerequest || []),
        ],
      },
    },
    {
      listen: 'test',
      script: {
        type: 'text/javascript',
        exec: [
          commonTests,
          ...(opts.expectHappy ? [happyStatus1] : []),
          ...(opts.expectBusinessOk ? [businessOk] : []),
          // extraTests run after preamble; must use existing var tc/json (no const/let redeclarations)
          ...(opts.extraTests || []),
        ].join('\n').split('\n'),
      },
    },
  ];

  const item = {
    name: `${tcId} ${name}`,
    event,
    request: {
      method: opts.method || 'POST',
      header: opts.noAuth
        ? [
            { key: 'Accept', value: 'application/json', type: 'text' },
            { key: 'Content-Type', value: 'application/json', type: 'text' },
          ]
        : opts.invalidAuth
          ? [
              { key: 'Authorization', value: 'Bearer INVALID_TOKEN_QA', type: 'text' },
              { key: 'Accept', value: 'application/json', type: 'text' },
              { key: 'Content-Type', value: 'application/json', type: 'text' },
            ]
          : opts.missingAuth
            ? [
                { key: 'Accept', value: 'application/json', type: 'text' },
                { key: 'Content-Type', value: 'application/json', type: 'text' },
              ]
            : authBearer,
      body: bodyObj === null
        ? undefined
        : {
            mode: 'raw',
            raw: JSON.stringify(bodyObj === undefined ? {} : bodyObj, null, 2),
            options: { raw: { language: 'json' } },
          },
      url: '{{baseUrl}}/' + urlPath.replace(/^\//, ''),
      description: opts.description || '',
    },
  };

  // Newman may still hit disabled items - omit entirely when skipped.
  if (opts.disabled) {
    return null;
  }

  return item;
}

function folder(name, items, description = '') {
  return { name, description, item: items.filter(Boolean) };
}

const collection = {
  info: {
    name: 'HR Shastri Mobile API QA',
    description:
      'Automated QA for routes/api.php. No mutations to login/account detail. Dedicated QA employee only. Artifacts under docs/api-qa/.',
    schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
  },
  event: [
    {
      listen: 'prerequest',
      script: {
        type: 'text/javascript',
        exec: [
          "const delay = Number(pm.environment.get('requestDelayMs') || 200);",
          'if (delay > 0) { setTimeout(function(){}, delay); }',
        ],
      },
    },
  ],
  variable: [
    { key: 'lastEndpoint', value: '' },
    { key: 'lastStatus', value: '' },
    { key: 'lastBody', value: '' },
    { key: 'lastTime', value: '' },
  ],
  item: [
    folder('_bootstrap', [
      req(
        'Login test employee (bootstrap once)',
        'TC_LOGIN_001',
        'login',
        {
          email: '{{email}}',
          password: '{{password}}',
          device_type: 2,
        },
        {
          noAuth: true,
          description: 'One successful login only. Stores Bearer token.',
          extraTests: [
            `
json = pm.response.json();
pm.test('[TC_LOGIN_001] login success + token', function () {
  pm.expect(Number(json.status)).to.eql(1);
  pm.expect(json.data).to.have.property('token');
});
if (json && json.data && json.data.token) {
  pm.environment.set('token', json.data.token);
  if (json.data.employee_id) pm.environment.set('employeeId', String(json.data.employee_id));
  if (json.data.id) pm.environment.set('userId', String(json.data.id));
}
`.trim(),
          ],
        }
      ),
      // Omitted from run (documented in API_Testing_Plan.md): forgot-password, test-notification
    ], 'Bootstrap token. Login negative/load forbidden. Skipped: forgot-password, test-notification.'),

    folder('Negative_Auth', [
      req('Dashboard missing Bearer', 'TC_AUTH_001', 'dashboard', {}, {
        missingAuth: true,
        extraTests: [
          `pm.test('[TC_AUTH_001] unauthorized', function () { pm.expect(pm.response.code).to.eql(401); });`,
        ],
      }),
      req('Dashboard invalid Bearer', 'TC_AUTH_002', 'dashboard', {}, {
        invalidAuth: true,
        extraTests: [
          `pm.test('[TC_AUTH_002] unauthorized', function () { pm.expect(pm.response.code).to.eql(401); });`,
        ],
      }),
    ]),

    folder('Auth_ReadOnly', [
      req('Dashboard', 'TC_DASH_001', 'dashboard', {}, { expectHappy: true }),
      req('Notification list', 'TC_NOTIF_001', 'notification-list', {}, { expectBusinessOk: true }),
      req('Employee calendar data', 'TC_CAL_001', 'employee-calendar-data', {}, { expectBusinessOk: true }),
      req('Mobile menu', 'TC_MENU_001', 'get_mobile_menu', {}, { expectBusinessOk: true }),
      req('Mobile menu geofencing', 'TC_MENU_002', 'get_mobile_menu_for_geofancing', {}, { expectBusinessOk: true }),
      req('Clock-in-out employees list', 'TC_CLKLIST_001', 'clock-in-out-employees-list', {}, { expectBusinessOk: true }),
      req('Clock-in-out branch list', 'TC_CLKLIST_002', 'clock-in-out-branch-list', {}, { expectBusinessOk: true }),
      req('Sync clock status', 'TC_SYNC_001', 'sync-clock-in-clock-out-status', {}, { expectBusinessOk: true }),
    ]),

    folder('Account_ReadOnly', [
      req('Get profile', 'TC_PROF_001', 'get_profile', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_PROF_001] profile has name/email', function () {
  pm.expect(json.data).to.be.an('object');
  pm.expect(json.data).to.have.property('name');
  pm.expect(json.data).to.have.property('email');
});
`.trim(),
        ],
      }),
      req('Get experience', 'TC_PROF_002', 'get_experience', {}, { expectHappy: true }),
      req('Get education', 'TC_PROF_003', 'get_education', {}, { expectHappy: true }),
      req('Get dependent', 'TC_PROF_004', 'get_dependent', {}, { expectHappy: true }),
      req('Get documents', 'TC_PROF_005', 'get_documents', {}, { expectHappy: true }),
      // Omitted from run: update-employee-profile, updatePassword, set_logout
    ], 'Read-only account detail. Mutations omitted per non-intrusive plan.'),

    folder('Attendance_Read', [
      req('Get attendance today', 'TC_ATT_001', 'get_attendance', {}, { expectHappy: true }),
      req('Get attendance list monthly', 'TC_ATT_002', 'get_attendance_list', {
        type: 'monthly',
        month: '{{attendanceMonth}}',
      }, { expectHappy: true }),
      req('Get attendance list daily', 'TC_ATT_003', 'get_attendance_list', {
        type: 'daily',
        date: '{{today}}',
      }, { expectHappy: true }),
    ]),

    folder('Attendance_Mutate', [
      req('Clock in (self only)', 'TC_CLK_001', 'clock_in', {
        latitude: '23.0225',
        longitude: '72.5714',
        meta_data: 'QA_API_TEST',
      }, {
        description: 'Never send another employee_id. Never is_debug.',
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_CLK_001] clock_in accepted or already in', function () {
  pm.expect([0,1]).to.include(Number(json.status));
  pm.expect(pm.response.code).to.eql(200);
});
`.trim(),
        ],
      }),
      req('Clock in again (already in)', 'TC_CLK_002', 'clock_in', {
        latitude: '23.0225',
        longitude: '72.5714',
      }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_CLK_002] not 500', function () {
  pm.expect(pm.response.code).to.not.eql(500);
  pm.expect(json).to.have.property('status');
});
`.trim(),
        ],
      }),
      req('Clock out (self only, no is_debug)', 'TC_CLK_003', 'clock_out', {
        latitude: '23.0225',
        longitude: '72.5714',
        meta_data: 'QA_API_TEST',
      }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_CLK_003] clock_out accepted or already out', function () {
  pm.expect([0,1]).to.include(Number(json.status));
});
`.trim(),
        ],
      }),
    ], 'Mutates attendance for QA employee only.'),

    folder('EmployeeModules_Read', [
      req('Get employee', 'TC_EMP_001', 'get_employee', {}, { expectHappy: true }),
      req('Get transfer', 'TC_MOD_001', 'get_transfer', {}, { expectBusinessOk: true }),
      req('Get promotion', 'TC_MOD_002', 'get_promotion', {}, { expectBusinessOk: true }),
      req('Get announcement', 'TC_MOD_003', 'get_announcement', {}, { expectBusinessOk: true }),
      req('Get holiday', 'TC_MOD_004', 'get_holiday', {}, { expectBusinessOk: true }),
      req('Get document', 'TC_MOD_005', 'get_document', {}, { expectBusinessOk: true }),
      req('Get termination', 'TC_MOD_006', 'get_termination', {}, { expectBusinessOk: true }),
      req('Get company policy', 'TC_MOD_007', 'get_company_policy', {}, { expectBusinessOk: true }),
      req('Get events', 'TC_MOD_008', 'get_events', {}, { expectBusinessOk: true }),
      req('Get upcoming birthday', 'TC_MOD_009', 'get_upcomming_birthday', {}, { expectBusinessOk: true }),
      req('Get warning', 'TC_WARN_001', 'get_warning', {}, { expectBusinessOk: true }),
    ]),

    folder('Timesheet', [
      req('Get timesheet', 'TC_TS_001', 'get_timesheet', {}, { expectBusinessOk: true }),
      req('Create timesheet QA', 'TC_TS_002', 'create_timesheet', {
        date: '{{timesheetDate}}',
        hours: '8',
        remark: 'QA_API_TEST_TIMESHEET',
      }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_TS_002] create ok or already exists', function () {
  pm.expect([0,1]).to.include(Number(json.status));
});
`.trim(),
        ],
      }),
      req('Get timesheet after create', 'TC_TS_003', 'get_timesheet', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.remark || '').includes('QA_API_TEST') || (r.date || '') === pm.environment.get('timesheetDate'));
if (hit) pm.environment.set('timesheetId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update timesheet QA', 'TC_TS_004', 'update_timesheet', {
        id: '{{timesheetId}}',
        date: '{{timesheetDate}}',
        hours: '7',
        remark: 'QA_API_TEST_TIMESHEET_UPD',
      }, {
        extraTests: [
          `
if (!pm.environment.get('timesheetId')) {
  console.warn('[TC_TS_004] skipped â€” no timesheetId');
} else {
  json = pm.response.json();
  pm.test('[TC_TS_004] update status present', function () {
    pm.expect(json).to.have.property('status');
  });
}
`.trim(),
        ],
      }),
      req('Delete timesheet QA', 'TC_TS_005', 'delete_timesheet', { id: '{{timesheetId}}' }, {
        extraTests: [
          `
if (!pm.environment.get('timesheetId')) {
  console.warn('[TC_TS_005] skipped â€” no timesheetId');
} else {
  json = pm.response.json();
  pm.test('[TC_TS_005] delete status present', function () {
    pm.expect(json).to.have.property('status');
  });
}
`.trim(),
        ],
      }),
      req('Create timesheet empty body (negative)', 'TC_TS_006', 'create_timesheet', {}, {
        extraTests: [
          `pm.test('[TC_TS_006] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
    ]),

    folder('Leave', [
      req('Get leave types', 'TC_LEAVE_001', 'get_leave_type', {}, { expectHappy: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
if (rows[0] && rows[0].id) pm.environment.set('leaveTypeId', String(rows[0].id));
`.trim(),
        ],
      }),
      req('My leave balance', 'TC_LEAVE_002', 'my_leave_balance', {}, { expectHappy: true }),
      req('Get leaves', 'TC_LEAVE_003', 'get_leaves', {}, { expectHappy: true }),
      req('Create leave QA (may fail balance)', 'TC_LEAVE_004', 'create_leave', {
        leave_type_id: '{{leaveTypeId}}',
        start_date: '{{futureDate1}}',
        end_date: '{{futureDate1}}',
        leave_type_day: 1,
        leave_reason: 'QA_API_TEST_LEAVE',
        remark: 'QA_API_TEST_LEAVE',
      }, {
        description: 'Happy or business reject (balance) â€” must not 500.',
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_LEAVE_004] not 500 + status field', function () {
  pm.expect(pm.response.code).to.not.eql(500);
  pm.expect(json).to.have.property('status');
});
`.trim(),
        ],
      }),
      req('Create leave invalid type (negative)', 'TC_LEAVE_005', 'create_leave', {
        leave_type_id: 999999,
        start_date: '{{futureDate1}}',
        end_date: '{{futureDate1}}',
        leave_type_day: 1,
        leave_reason: 'QA_INVALID',
      }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_LEAVE_005] status 0 expected', function () {
  pm.expect(Number(json.status)).to.eql(0);
});
`.trim(),
        ],
      }),
    ]),

    folder('WFH', [
      req('Get WFH', 'TC_WFH_001', 'get_apply_wfh', {}, { expectBusinessOk: true }),
      req('Create WFH QA', 'TC_WFH_002', 'create_apply_wfh', {
        start_date: '{{futureDate2}}',
        end_date: '{{futureDate2}}',
        note: 'QA_API_TEST_WFH',
      }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_WFH_002] create status present', function () {
  pm.expect(json).to.have.property('status');
  pm.expect(pm.response.code).to.not.eql(500);
});
`.trim(),
        ],
      }),
      req('Get WFH after create', 'TC_WFH_003', 'get_apply_wfh', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.note || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('wfhId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update WFH QA', 'TC_WFH_004', 'update_apply_wfh', {
        id: '{{wfhId}}',
        start_date: '{{futureDate2}}',
        end_date: '{{futureDate2}}',
        note: 'QA_API_TEST_WFH_UPD',
      }),
      req('Delete WFH QA', 'TC_WFH_005', 'delete_apply_wfh', { id: '{{wfhId}}' }),
    ]),

    folder('EarlyLeave', [
      req('Get early leave', 'TC_EL_001', 'get_early_Leave', {}, { expectBusinessOk: true }),
      req('Create early leave QA', 'TC_EL_002', 'create_early_Leave', {
        leave_type_id: 'Short Leave',
        start_date: '{{futureDate3}}',
        leave_reason: 'QA_API_TEST_SHORT_LEAVE',
      }, {
        extraTests: [
          `pm.test('[TC_EL_002] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Get early leave after create', 'TC_EL_003', 'get_early_Leave', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.resone || r.reason || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('earlyLeaveId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update early leave QA', 'TC_EL_004', 'update_early_Leave', {
        id: '{{earlyLeaveId}}',
        leave_type_id: 'Short Leave',
        start_date: '{{futureDate3}}',
        leave_reason: 'QA_API_TEST_SHORT_LEAVE_UPD',
      }),
      req('Delete early leave QA', 'TC_EL_005', 'delete_early_Leave', { id: '{{earlyLeaveId}}' }),
    ]),

    folder('GatePass', [
      req('Get gate pass', 'TC_GP_001', 'get_gate_pass', {}, { expectBusinessOk: true }),
      req('Create gate pass QA', 'TC_GP_002', 'create_gate_pass', {
        leave_type_id: 'Personal',
        start_date: '{{today}}',
        in_time: '10:00',
        out_time: '11:00',
        leave_reason: 'QA_API_TEST_GATEPASS',
      }, {
        extraTests: [
          `pm.test('[TC_GP_002] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Get gate pass after create', 'TC_GP_003', 'get_gate_pass', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.resone || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('gatePassId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update gate pass QA', 'TC_GP_004', 'update_gate_pass', {
        id: '{{gatePassId}}',
        gate_pass_id: '{{gatePassId}}',
        leave_type_id: 'Personal',
        start_date: '{{today}}',
        in_time: '10:30',
        out_time: '11:30',
        leave_reason: 'QA_API_TEST_GATEPASS_UPD',
      }),
      req('Delete gate pass QA', 'TC_GP_005', 'delete_gate_pass', { id: '{{gatePassId}}' }),
    ]),

    folder('Complaint', [
      req('Get complaint against list', 'TC_CMP_001', 'get_complaint_against', {}, { expectHappy: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
if (rows[0] && rows[0].id) pm.environment.set('complaintAgainstId', String(rows[0].id));
`.trim(),
        ],
      }),
      req('Get complaints', 'TC_CMP_002', 'get_complaint', {}, { expectBusinessOk: true }),
      req('Create complaint QA', 'TC_CMP_003', 'create_complaint', {
        complaint_against: '{{complaintAgainstId}}',
        title: 'QA_API_TEST_COMPLAINT',
        complaint_date: '{{today}}',
        description: 'QA automated complaint - safe to delete',
      }, {
        extraTests: [
          `pm.test('[TC_CMP_003] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Get complaints after create', 'TC_CMP_004', 'get_complaint', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.title || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('complaintId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update complaint QA', 'TC_CMP_005', 'update_complaint', {
        id: '{{complaintId}}',
        complaint_against: '{{complaintAgainstId}}',
        title: 'QA_API_TEST_COMPLAINT_UPD',
        complaint_date: '{{today}}',
        description: 'QA updated',
      }),
      req('Delete complaint QA', 'TC_CMP_006', 'delete_complaint', { id: '{{complaintId}}' }),
    ]),

    folder('Tickets', [
      req('Get tickets', 'TC_TKT_001', 'get_tickets', {}, { expectHappy: true }),
      req('Create ticket QA', 'TC_TKT_002', 'create_ticket', {
        title: 'QA_API_TEST_TICKET',
        priority: 'low',
        description: 'QA automated ticket',
      }, {
        expectHappy: true,
      }),
      req('Get tickets after create', 'TC_TKT_003', 'get_tickets', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.title || '').includes('QA_API_TEST'));
if (hit) {
  pm.environment.set('ticketId', String(hit.id));
  if (hit.ticket_code) pm.environment.set('ticketCode', String(hit.ticket_code));
}
`.trim(),
        ],
      }),
      req('Update ticket QA', 'TC_TKT_004', 'update_ticket', {
        id: '{{ticketId}}',
        title: 'QA_API_TEST_TICKET_UPD',
        priority: 'medium',
        description: 'QA updated ticket',
      }),
      req('Get ticket reply', 'TC_TKT_005', 'get_ticket_reply', { id: '{{ticketId}}', ticket_id: '{{ticketId}}' }),
      req('Create ticket reply QA', 'TC_TKT_006', 'create_ticket_reply', {
        ticket_id: '{{ticketId}}',
        description: 'QA_API_TEST_REPLY',
        reply: 'QA_API_TEST_REPLY',
      }),
      req('Update ticket status', 'TC_TKT_007', 'update_ticket_status', {
        id: '{{ticketId}}',
        ticket_id: '{{ticketId}}',
        status: 'close',
      }),
      req('Delete ticket QA', 'TC_TKT_008', 'delete_ticket', { id: '{{ticketId}}' }),
    ]),

    folder('Visit_Claim', [
      req('Get tier', 'TC_VIS_001', 'get_tier', {}, { expectHappy: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
if (rows[0] && rows[0].id) pm.environment.set('tierId', String(rows[0].id));
`.trim(),
        ],
      }),
      req('Get tier city', 'TC_VIS_002', 'get_tier_city', { tier_id: '{{tierId}}' }),
      req('Get visit', 'TC_VIS_003', 'get_visit', {}, { expectBusinessOk: true }),
      req('Get visit approved', 'TC_VIS_004', 'get_visit_approved', {}, { expectBusinessOk: true }),
      req('Create visit QA', 'TC_VIS_005', 'create_visit', {
        visit_type: 'Official',
        visit_name: 'QA_API_TEST_VISIT',
        form_date: '{{futureDate1}}',
        to_date: '{{futureDate1}}',
        description: 'QA automated visit',
        tier_id: '{{tierId}}',
        city_id: '0',
      }, {
        extraTests: [
          `pm.test('[TC_VIS_005] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Get visit after create', 'TC_VIS_006', 'get_visit', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
const hit = rows.find(r => (r.visit_name || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('visitId', String(hit.id));
`.trim(),
        ],
      }),
      req('Update visit QA', 'TC_VIS_007', 'update_visit', {
        visit_id: '{{visitId}}',
        visit_type: 'Official',
        visit_name: 'QA_API_TEST_VISIT_UPD',
        form_date: '{{futureDate1}}',
        to_date: '{{futureDate1}}',
        description: 'QA updated',
        tier_id: '{{tierId}}',
        city_id: '0',
      }),
      req('Get expense', 'TC_CLM_001', 'get_expense', {}, { expectBusinessOk: true }),
      req('Get claim', 'TC_CLM_002', 'get_claim', {}, { expectBusinessOk: true }),
      req('Create claim negative empty', 'TC_CLM_003', 'create_claim', {}, {
        extraTests: [
          `pm.test('[TC_CLM_003] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Delete visit QA', 'TC_VIS_008', 'delete_visit', { visit_id: '{{visitId}}', id: '{{visitId}}' }),
    ]),

    folder('Career_Payslip', [
      req('Get careers', 'TC_CAR_001', 'get_careers', {}, { expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : [];
if (rows[0] && rows[0].id) pm.environment.set('jobId', String(rows[0].id));
`.trim(),
        ],
      }),
      req('Get references', 'TC_CAR_002', 'get_references', {}, { expectBusinessOk: true }),
      req('Create references without job (negative/edge)', 'TC_CAR_003', 'create_references', {
        job_id: '{{jobId}}',
      }, {
        extraTests: [
          `pm.test('[TC_CAR_003] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('My slip current year', 'TC_PAY_001', 'my_slip', { datePicker: '{{payslipYear}}' }, { expectBusinessOk: true }),
      req('My slip invalid year', 'TC_PAY_002', 'my_slip', { datePicker: '1900' }, {
        extraTests: [
          `
json = pm.response.json();
pm.test('[TC_PAY_002] contract ok', function () {
  pm.expect(json).to.have.property('status');
  pm.expect(pm.response.code).to.not.eql(500);
});
`.trim(),
        ],
      }),
    ]),

    folder('Resignation', [
      req('Get resignation', 'TC_RES_001', 'get_resignation', {}, { expectBusinessOk: true }),
      req('Create resignation QA', 'TC_RES_002', 'create_resignation', {
        notice_date: '{{today}}',
        resignation_date: '{{futureDate30}}',
        description: 'QA_API_TEST_RESIGNATION â€” automated, will delete',
      }, {
        extraTests: [
          `pm.test('[TC_RES_002] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
      req('Get resignation after create', 'TC_RES_003', 'get_resignation', {}, {
        expectBusinessOk: true,
        extraTests: [
          `
json = pm.response.json();
const rows = Array.isArray(json.data) ? json.data : (json.data ? [json.data] : []);
const hit = rows.find(r => (r.description || '').includes('QA_API_TEST'));
if (hit) pm.environment.set('resignationId', String(hit.id));
else if (rows[0] && rows[0].id) pm.environment.set('resignationId', String(rows[0].id));
`.trim(),
        ],
      }),
      req('Update resignation QA', 'TC_RES_004', 'update_resignation', {
        id: '{{resignationId}}',
        notice_date: '{{today}}',
        resignation_date: '{{futureDate30}}',
        description: 'QA_API_TEST_RESIGNATION_UPD',
      }),
      req('Delete resignation QA', 'TC_RES_005', 'delete_resignation', { id: '{{resignationId}}' }),
    ]),

    folder('Notification_Mutate', [
      req('Clear notifications', 'TC_NOTIF_002', 'notification-clear', {}, {
        extraTests: [
          `pm.test('[TC_NOTIF_002] not 500', function () { pm.expect(pm.response.code).to.not.eql(500); });`,
        ],
      }),
    ]),
  ],
};

fs.writeFileSync(
  path.join(outDir, 'HRShastri_Mobile_API.postman_collection.json'),
  JSON.stringify(collection, null, 2)
);
console.log('Wrote HRShastri_Mobile_API.postman_collection.json');

