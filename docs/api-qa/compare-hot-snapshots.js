/**
 * Compare Newman JSON snapshots: same keys / calendar statusText set / row counts.
 */
const fs = require('fs');
const path = require('path');

const beforePath = process.argv[2];
const afterPath = process.argv[3];
if (!beforePath || !afterPath) {
  console.error('Usage: node compare-hot-snapshots.js before.json after.json');
  process.exit(1);
}

function bodiesByName(report) {
  const map = {};
  const executions = (report.run && report.run.executions) || [];
  for (const ex of executions) {
    const name = (ex.item && ex.item.name) || '';
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
    }
    let json = null;
    try {
      json = JSON.parse(body);
    } catch (e) {}
    map[name] = { code: ex.response ? ex.response.code : null, json, time: ex.response && ex.response.responseTime };
  }
  return map;
}

function keysOf(v, prefix = '') {
  if (v === null || v === undefined) return [prefix || '(null)'];
  if (Array.isArray(v)) {
    if (!v.length) return [prefix + '[]'];
    return keysOf(v[0], prefix + '[]');
  }
  if (typeof v === 'object') {
    return Object.keys(v).sort().flatMap((k) => keysOf(v[k], prefix ? prefix + '.' + k : k));
  }
  return [prefix];
}

const before = bodiesByName(JSON.parse(fs.readFileSync(beforePath, 'utf8')));
const after = bodiesByName(JSON.parse(fs.readFileSync(afterPath, 'utf8')));
const names = Object.keys(before).filter((n) => after[n]);
let diffs = 0;
const rows = [];

for (const name of names) {
  const b = before[name];
  const a = after[name];
  const bk = keysOf(b.json).sort().join('|');
  const ak = keysOf(a.json).sort().join('|');
  const statusB = b.json && typeof b.json === 'object' && !Array.isArray(b.json) ? b.json.status : '(array)';
  const statusA = a.json && typeof a.json === 'object' && !Array.isArray(a.json) ? a.json.status : '(array)';
  const lenB = Array.isArray(b.json)
    ? b.json.length
    : b.json && Array.isArray(b.json.data)
      ? b.json.data.length
      : null;
  const lenA = Array.isArray(a.json)
    ? a.json.length
    : a.json && Array.isArray(a.json.data)
      ? a.json.data.length
      : null;

  let calendarSet = '';
  if (name.includes('calendar') || name.includes('CAL')) {
    const set = (arr) =>
      [...new Set((arr || []).map((r) => r.statusText).filter(Boolean))].sort().join(',');
    calendarSet = ` calB=${set(b.json && b.json.data)} calA=${set(a.json && a.json.data)}`;
  }

  const ok = bk === ak && String(statusB) === String(statusA) && String(lenB) === String(lenA);
  if (!ok) diffs++;
  rows.push({
    name,
    ok,
    http: `${b.code}->${a.code}`,
    status: `${statusB}->${statusA}`,
    len: `${lenB}->${lenA}`,
    time: `${b.time}->${a.time}`,
    calendarSet,
    keyMismatch: bk === ak ? '' : 'KEYS_DIFFER',
  });
}

console.log(JSON.stringify({ compared: names.length, diffs, rows }, null, 2));
process.exit(diffs ? 1 : 0);
