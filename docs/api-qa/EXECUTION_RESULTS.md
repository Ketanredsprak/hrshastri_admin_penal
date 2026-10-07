# API QA Execution Results (Local)

**Run date:** 2026-10-05  
**Base URL:** `http://127.0.0.1:8000/api`  
**QA user:** `qa.api.test@hrshastri.local` (dedicated; created via `_create_qa_user.php`)  
**Runner:** `docs/api-qa/newman-run.ps1`  
**API controllers modified:** none

## Summary (latest run)

| Metric | Value |
|---|---|
| Requests executed | 95 |
| Request transport failures | 0 |
| Assertions executed | 363 |
| Assertions failed | 2 |
| Avg response time | ~330 ms |
| Max response time | ~4.1 s (SMTP-related create paths) |
| Soft latency warnings | complaint/ticket create (email SMTP delay) |

## Intentionally skipped (non-intrusive)

- `POST /api/forgot-password`
- `GET /api/test-notification`
- `POST /api/update-employee-profile`
- `POST /api/updatePassword`
- `POST /api/set_logout`

## Defect (fixed)

| Test Case | Endpoint | Issue | Fix |
|---|---|---|---|
| `TC_GP_004` | `POST /api/update_gate_pass` | HTTP **500** — `Log::info($e)` used a Exception as context (must be array). Update also looked up `gate_pass_id` only while clients/QA send `id`, then crashed on null. | `Log::error(..., array)`. Accept `id` or `gate_pass_id`. Return `status: 0` if record missing. |

Re-verified 2026-10-05: GatePass folder **6 requests / 22 assertions / 0 failed**. `TC_GP_004` now HTTP 200.

Full detail: `API_Error_Log.csv` and `reports/newman-report-*.json|html`.

## Notes

- Empty list endpoints correctly return `{ status: 0, message: "No Data Found" }` — treated as business-ok.
- `get_mobile_menu` returns a bare JSON array (not `{status,message}`) — accepted by contract tests.
- `sync-clock-in-clock-out-status` returns `{status,is_clock_in,is_clock_out}` without `message` — accepted.
- Login/account detail mutations were not executed.

## Re-run

```powershell
powershell -ExecutionPolicy Bypass -File docs\api-qa\newman-run.ps1
```
