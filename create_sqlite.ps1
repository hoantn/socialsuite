# create_sqlite.ps1 - helper to create the SQLite file and copy env
$project = "D:\xampp\htdocs\socialsuite"
$envSrc = ".\.env.sqlite.example"
$dbFile = "$project\database\database.sqlite"

if (!(Test-Path "$project\database")) {
  New-Item -ItemType Directory "$project\database" | Out-Null
}

if (!(Test-Path $dbFile)) {
  New-Item -ItemType File $dbFile | Out-Null
  Write-Host "Created $dbFile"
} else {
  Write-Host "Exists: $dbFile"
}

Copy-Item $envSrc "$project\.env" -Force
Write-Host "Copied .env.sqlite.example -> $project\.env"