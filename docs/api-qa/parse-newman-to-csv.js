/**
 * Append Newman JSON failures into API_Error_Log.csv
 */
const fs = require('fs');
const path = require('path');

const reportPath = process.argv[2];
const csvPath = process.argv[3] || path.join(__dirname, 'API_Error_Log.csv');

if (!reportPath || !fs.existsSync(reportPath)) {
  console.error('Usage: node parse-newman-to-csv.js <newman-json-report> [csv]');
  process.exit(1);
}

const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
const header =
  'API Endpoint,Request Payload,Response Received (Status Code & Body),Expected Response,Observed Issue,Test Case ID,Timestamp\n';

if (!fs.existsSync(csvPath) || fs.readFileSync(csvPath, 'utf8').trim() === '') {
  fs.writeFileSync(csvPath, header);
}

function csvEscape(v) {
  const s = String(v ?? '').replace(/\r?\n/g, ' ').replace(/"/g, '""');
  return `"${s}"`;
}

function extractTc(name) {
  const m = String(name || '').match(/TC_[A-Z0-9_]+/);
  return m ? m[0] : name || '';
}

const executions = report.run && report.run.executions ? report.run.executions : [];
const runFailures = report.run && report.run.failures ? report.run.failures : [];
let appended = 0;
const lines = [];

// Index script/assertion failures by item name for richer CSV rows
const failureByName = {};
for (const f of runFailures) {
  const n = (f.source && f.source.name) || '';
  if (!failureByName[n]) failureByName[n] = [];
  failureByName[n].push((f.error && (f.error.message || f.error.name)) || 'failure');
}

for (const ex of executions) {
  const itemName = (ex.item && ex.item.name) || '';
  const tc = extractTc(itemName);
  let url = itemName;
  if (ex.request && ex.request.url) {
    const u = ex.request.url;
    if (typeof u === 'string') {
      url = u;
    } else if (u.raw) {
      url = u.raw;
    } else {
      const host = Array.isArray(u.host) ? u.host.join('.') : u.host || '';
      const pth = Array.isArray(u.path) ? u.path.join('/') : u.path || '';
      url = `${u.protocol || 'http'}://${host}/${pth}`;
    }
  }

  let payload = '';
  if (ex.request && ex.request.body && ex.request.body.raw) payload = ex.request.body.raw;

  const failedAssertions = (ex.assertions || []).filter((a) => a.error);
  const scriptFails = failureByName[itemName] || [];
  const httpCode = ex.response ? ex.response.code : 'NO_RESPONSE';
  let body = '';
  if (ex.response && ex.response.stream) {
    try {
      const stream = ex.response.stream;
      body = Buffer.isBuffer(stream)
        ? stream.toString('utf8')
        : Buffer.from(stream.data || stream).toString('utf8');
    } catch (e) {
      body = '';
    }
  } else if (ex.response && ex.response.body) {
    body = typeof ex.response.body === 'string' ? ex.response.body : JSON.stringify(ex.response.body);
  }
  body = body.substring(0, 1500);

  const responseTime = ex.response ? ex.response.responseTime : null;
  const softFailLatency = responseTime != null && responseTime > 1000;

  if (failedAssertions.length === 0 && !softFailLatency && !ex.requestError && scriptFails.length === 0) {
    continue;
  }

  const issueParts = [];
  if (ex.requestError) issueParts.push('Request error: ' + (ex.requestError.message || JSON.stringify(ex.requestError)));
  for (const a of failedAssertions) {
    issueParts.push((a.assertion || 'assertion') + ': ' + (a.error && a.error.message ? a.error.message : 'failed'));
  }
  for (const s of scriptFails) {
    if (!issueParts.some((p) => p.includes(s))) issueParts.push('Script/run: ' + s);
  }
  if (softFailLatency && failedAssertions.length === 0 && scriptFails.length === 0) {
    issueParts.push('Soft latency: ' + responseTime + 'ms (>1000ms)');
  }

  const expected = failedAssertions.length
    ? failedAssertions.map((a) => a.assertion).join('; ')
    : 'HTTP <500, contract status/message, p95 <1s';

  const ts = new Date().toISOString().replace('T', ' ').substring(0, 19);
  lines.push(
    [
      csvEscape(url),
      csvEscape(payload),
      csvEscape(httpCode + ' - ' + body),
      csvEscape(expected),
      csvEscape(issueParts.join(' | ')),
      csvEscape(tc),
      csvEscape(ts),
    ].join(',')
  );
  appended++;
}

if (lines.length) {
  fs.appendFileSync(csvPath, lines.join('\n') + '\n');
}

const stats = report.run && report.run.stats ? report.run.stats : {};
console.log(
  JSON.stringify(
    {
      appendedFailures: appended,
      requests: stats.requests,
      assertions: stats.assertions,
      csv: csvPath,
    },
    null,
    2
  )
);
