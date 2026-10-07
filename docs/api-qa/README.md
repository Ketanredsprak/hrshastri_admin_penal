# HR Shastri Mobile API QA

Artifacts for the approved API testing plan. **No files under `app/Http/Controllers/API/` are modified.**

## Contents

| File | Purpose |
|---|---|
| `API_Testing_Plan.md` | Full test plan document |
| `HRShastri_Mobile_API.postman_collection.json` | Postman collection |
| `HRShastri_Local.postman_environment.json` | Local env (base URL + QA credentials) |
| `generate-collection.js` | Regenerates the collection |
| `newman-run.ps1` | Runs Newman + writes reports/CSV |
| `parse-newman-to-csv.js` | Maps Newman failures → `API_Error_Log.csv` |
| `API_Error_Log.csv` | Structured error log |
| `EXECUTION_RESULTS.md` | Latest local run summary |
| `_create_qa_user.php` | Ensures dedicated QA employee exists |
| `reports/` | Newman JSON/HTML outputs |

## Run

```powershell
cd c:\wamp64\www\hrshashtri
# API must be reachable at http://127.0.0.1:8000/api
powershell -ExecutionPolicy Bypass -File docs\api-qa\newman-run.ps1
```

## Safety rules

- Login once per run on `qa.api.test@hrshastri.local` only
- Profile update / password / forgot-password / logout are **disabled** in the collection
- Clock-in/out never sends another employee's id or `is_debug`
