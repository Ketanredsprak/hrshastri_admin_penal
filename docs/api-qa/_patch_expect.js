const fs = require('fs');
const p = __dirname + '/generate-collection.js';
const lines = fs.readFileSync(p, 'utf8').split(/\r?\n/);

const businessOkMarkers = [
  'TC_NOTIF_001',
  'TC_MENU_001',
  'TC_MENU_002',
  'TC_SYNC_001',
  'TC_MOD_001',
  'TC_MOD_002',
  'TC_MOD_005',
  'TC_MOD_006',
  'TC_MOD_008',
  'TC_WARN_001',
  'TC_TS_001',
  'TC_TS_003',
  'TC_WFH_001',
  'TC_WFH_003',
  'TC_EL_001',
  'TC_EL_003',
  'TC_GP_001',
  'TC_GP_003',
  'TC_CMP_002',
  'TC_CMP_004',
  'TC_TKT_003',
  'TC_VIS_003',
  'TC_VIS_004',
  'TC_VIS_006',
  'TC_CLM_001',
  'TC_CLM_002',
  'TC_CAR_001',
  'TC_CAR_002',
  'TC_RES_001',
  'TC_RES_003',
];

let patched = 0;
const out = lines.map((line) => {
  if (!line.includes('expectHappy: true')) return line;
  if (!businessOkMarkers.some((m) => line.includes(m))) {
    // multi-line blocks: previous lines may have TC id
    return line;
  }
  patched++;
  return line.replace('expectHappy: true', 'expectBusinessOk: true');
});

// Second pass for multi-line expectHappy that follows a TC marker within 8 lines
for (let i = 0; i < out.length; i++) {
  if (!out[i].includes('expectHappy: true')) continue;
  const window = out.slice(Math.max(0, i - 8), i + 1).join('\n');
  if (businessOkMarkers.some((m) => window.includes(m))) {
    out[i] = out[i].replace('expectHappy: true', 'expectBusinessOk: true');
    patched++;
  }
}

fs.writeFileSync(p, out.join('\n'));
console.log('patched occurrences:', patched);
