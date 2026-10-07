# API Testing Plan Document — HR Shastri Mobile APIs

## 1. Introduction

This document defines the QA Automation plan for the HR Shastri **mobile employee APIs** registered in `routes/api.php` (prefix `/api`). Testing validates current request/response flows, error handling, response contracts, and local performance **without modifying** any code under `app/Http/Controllers/API/` and **without mutating login or account-detail data** on real users.

## 2. Objectives

- Verify each live API operates per its current controller flow
- Confirm errors return `status: 0` (or HTTP 401/422/429) — never unhandled 500s
- Validate JSON contract: `{ status, message, data? }`
- Measure response times against local thresholds
- Keep login / account-detail non-intrusive (bootstrap + read-only)
- Log every failure into `API_Error_Log.csv`

## 3. Scope

### Included

All routes in `routes/api.php` (~87 endpoints), exercised via Postman/Newman artifacts in `docs/api-qa/`.

| Group | Endpoints |
|---|---|
| Public | `login`, `forgot-password` (skipped), `test-notification` (skipped) |
| Auth / home | `dashboard`, notifications, calendar, menus, clock lists, sync status |
| Attendance | `get_attendance*`, `clock_in`, `clock_out` (QA employee only) |
| Profile read | `get_profile`, experience, education, dependent, documents |
| Modules | timesheet, leave, WFH, early leave, gate pass, complaint, warning, tickets, visit, claim, career, payslip, resignation, employee modules |

### Excluded / skipped (non-intrusive)

| Endpoint | Reason |
|---|---|
| `update-employee-profile` | Account detail mutation |
| `updatePassword` | Account credential mutation |
| `forgot-password` | Account credential side-effect |
| `set_logout` | Revokes token mid-suite |
| `test-notification` | Real push notification |
| `clock_out` + `is_debug` | Controller dump/die |
| Web `/login` (auth.php) | Out of mobile API scope |
| `ContractController` | Not wired in routes |

### Dedicated test identity

- Email: `qa.api.test@hrshastri.local`
- Created/reset by `docs/api-qa/_create_qa_user.php` (DB fixture only; not API source)

## 4. Testing Approach

| Aspect | Choice |
|---|---|
| Method | Automated Postman collection + Newman CLI |
| Location | `docs/api-qa/` only (no API controller edits) |
| Types | Functional (happy/negative/edge), contract, light performance |
| Auth | Laravel Passport Bearer from one `POST /api/login` |
| Mutation safety | `QA_` prefixed records; create → get → update → delete |
| Throttle safety | `--delay-request 200` (smartThrottle = 300/60s) |

**Tools:** Postman, Newman 6, Node.js (collection generator + CSV parser), PowerShell runner.

## 5. Test Environment

| Item | Value |
|---|---|
| App | Local Laravel (`APP_URL=http://127.0.0.1:8000`) |
| Base URL | `http://127.0.0.1:8000/api` |
| DB | Local MySQL `hrshashtri` — backup recommended before mutate runs |
| Runner | `docs/api-qa/newman-run.ps1` |

## 6. Test Cases

IDs follow `TC_<MODULE>_<NNN>`. Full request definitions live in `HRShastri_Mobile_API.postman_collection.json`.

### Global auth

- `TC_AUTH_001` missing Bearer → 401
- `TC_AUTH_002` invalid Bearer → 401
- `TC_LOGIN_001` bootstrap login (once)

### Account (read-only)

- `TC_PROF_001`–`005` profile/experience/education/dependent/documents
- `TC_PROF_SKIP_*` profile update / password / logout / forgot — disabled

### Module coverage (summary)

| Module | Happy | Negative / edge |
|---|---|---|
| Dashboard / menus / calendar / notifications | `TC_DASH_*`, `TC_MENU_*`, `TC_CAL_*`, `TC_NOTIF_*` | — |
| Attendance | `TC_ATT_*`, `TC_CLK_001/003` | `TC_CLK_002` already in |
| Timesheet | create/update/delete QA | empty body |
| Leave | types, balance, list, create | invalid leave type |
| WFH / Early leave / Gate pass | CRUD QA | — |
| Complaint / Tickets | CRUD QA | — |
| Visit / Claim | visit CRUD; claim empty body | claim validation |
| Career / Payslip | careers, references, my_slip | invalid year |
| Resignation | create → delete QA | — |

## 7. Performance Criteria

| Metric | Pass | Soft fail | Hard fail |
|---|---|---|---|
| Typical request | p95 &lt; 1s | 1–2s | &gt; 2s or HTTP 500 |
| List APIs | p95 &lt; 1.5s | 1.5–3s | &gt; 3s |
| Request timeout | 10s | — | timeout |
| Throughput | ≤ 50–200 req/min with delay | — | flood / throttle abuse on login |

## 8. Error Reporting Mechanism

File: `docs/api-qa/API_Error_Log.csv`

Columns:

`API Endpoint | Request Payload | Response Received (Status Code & Body) | Expected Response | Observed Issue | Test Case ID | Timestamp`

Populated by `parse-newman-to-csv.js` after each Newman run. HTML/JSON reports under `docs/api-qa/reports/`.

## 9. Risk Assessment

| Risk | Mitigation |
|---|---|
| Mutating real employee data | Dedicated QA user only; skip profile/password/logout |
| Token revoked mid-run | Skip `set_logout` |
| Throttle 429 | 200ms delay between requests |
| Attendance pollution | Clock only for QA employee; no foreign `employee_id` |
| Leftover QA rows | Delete steps after create; `QA_` prefix for manual cleanup |
| Push spam | Skip `test-notification` |

## How to run

```powershell
cd c:\wamp64\www\hrshashtri
# Ensure `php artisan serve` (or WAMP vhost) is up on APP_URL
powershell -ExecutionPolicy Bypass -File docs\api-qa\newman-run.ps1
```

Regenerate collection after route changes:

```powershell
node docs\api-qa\generate-collection.js
```
