$ErrorActionPreference = "SilentlyContinue"
$cfg = "config\app.php"
if (Test-Path $cfg) {
  $txt = Get-Content $cfg -Raw
  $txt2 = $txt -replace '.*Laravel\\Pail\\PailServiceProvider::class,?\s*', '' -replace '.*Laravel\\Sail\\SailServiceProvider::class,?\s*', ''
  if ($txt2 -ne $txt) {
    Set-Content -Path $cfg -Value $txt2 -Encoding UTF8
    Write-Output "Đã loại bỏ Pail/Sail provider khỏi config/app.php"
  } else {
    Write-Output "Không tìm thấy Pail/Sail trong config/app.php (bỏ qua)"
  }
}
Get-ChildItem "bootstrap\cache" -Filter *.php | Remove-Item -Force -ErrorAction SilentlyContinue
Write-Output "Đã xóa bootstrap\cache\*.php"
