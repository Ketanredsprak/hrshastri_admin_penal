# HR Shastri Mobile API QA — Newman runner
# Does NOT modify app/Http/Controllers/API/
# Usage: powershell -ExecutionPolicy Bypass -File docs/api-qa/newman-run.ps1

$ErrorActionPreference = "Continue"
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$RepoRoot = Resolve-Path (Join-Path $Root "..\..")
Set-Location $RepoRoot

$Collection = Join-Path $Root "HRShastri_Mobile_API.postman_collection.json"
$Environment = Join-Path $Root "HRShastri_Local.postman_environment.json"
$ReportsDir = Join-Path $Root "reports"
$CsvLog = Join-Path $Root "API_Error_Log.csv"
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
$JsonReport = Join-Path $ReportsDir "newman-report-$stamp.json"
$HtmlReport = Join-Path $ReportsDir "newman-report-$stamp.html"

Write-Host "Generating / refreshing Postman collection..."
node (Join-Path $Root "generate-collection.js")
if ($LASTEXITCODE -ne 0) { throw "generate-collection.js failed" }

New-Item -ItemType Directory -Force -Path $ReportsDir | Out-Null

# Refresh dynamic dates in a runtime environment copy
$envJson = Get-Content $Environment -Raw | ConvertFrom-Json
$today = Get-Date
function Set-EnvVal([string]$key, [string]$val) {
    foreach ($item in $envJson.values) {
        if ($item.key -eq $key) { $item.value = $val; break }
    }
}
Set-EnvVal "today" ($today.ToString("yyyy-MM-dd"))
Set-EnvVal "attendanceMonth" ($today.ToString("yyyy-MM-01"))
Set-EnvVal "timesheetDate" ($today.AddDays(-1).ToString("yyyy-MM-dd"))
Set-EnvVal "futureDate1" ($today.AddDays(10).ToString("yyyy-MM-dd"))
Set-EnvVal "futureDate2" ($today.AddDays(11).ToString("yyyy-MM-dd"))
Set-EnvVal "futureDate3" ($today.AddDays(12).ToString("yyyy-MM-dd"))
Set-EnvVal "futureDate30" ($today.AddDays(30).ToString("yyyy-MM-dd"))
Set-EnvVal "payslipYear" ($today.ToString("yyyy"))
$RuntimeEnv = Join-Path $Root "HRShastri_Local.runtime.postman_environment.json"
($envJson | ConvertTo-Json -Depth 20) | Set-Content -Path $RuntimeEnv -Encoding UTF8

Write-Host "Ensuring dedicated QA employee exists..."
php (Join-Path $Root "_create_qa_user.php")

Write-Host "Running Newman..."
$exitCode = 0
npx --yes newman@6 run $Collection `
    -e $RuntimeEnv `
    --timeout-request 10000 `
    --delay-request 200 `
    --reporters cli,json `
    --reporter-json-export $JsonReport
$exitCode = $LASTEXITCODE

# Optional HTML reporter (ignore failure if package missing)
npx --yes -p newman@6 -p newman-reporter-htmlextra newman run $Collection `
    -e $RuntimeEnv `
    --timeout-request 10000 `
    --delay-request 200 `
    --reporters htmlextra `
    --reporter-htmlextra-export $HtmlReport `
    --reporter-htmlextra-title "HR Shastri Mobile API QA" 2>$null

if (Test-Path $JsonReport) {
    Write-Host "Parsing failures into API_Error_Log.csv ..."
    # Reset CSV header then append this run's failures
    Set-Content -Path $CsvLog -Value "API Endpoint,Request Payload,Response Received (Status Code & Body),Expected Response,Observed Issue,Test Case ID,Timestamp"
    node (Join-Path $Root "parse-newman-to-csv.js") $JsonReport $CsvLog
} else {
    Write-Warning "No JSON report produced; CSV not updated."
}

Write-Host ""
Write-Host "Reports:"
Write-Host "  JSON: $JsonReport"
if (Test-Path $HtmlReport) { Write-Host "  HTML: $HtmlReport" }
Write-Host "  CSV : $CsvLog"
Write-Host "Exit code: $exitCode"
exit $exitCode
