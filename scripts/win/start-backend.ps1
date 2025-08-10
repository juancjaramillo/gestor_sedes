param(
  [switch]$Fresh
)

Set-Location -Path "$PSScriptRoot\..\backend"

if (-not (Test-Path ".env")) {
  Copy-Item ".env.example" ".env"
}

if (-not (Test-Path "database")) {
  New-Item -ItemType Directory -Path "database" | Out-Null
}
if (-not (Test-Path "database\database.sqlite")) {
  New-Item -ItemType File -Path "database\database.sqlite" | Out-Null
}

php artisan key:generate

if ($Fresh) {
  php artisan migrate:fresh --seed
} else {
  php artisan migrate --seed
}
