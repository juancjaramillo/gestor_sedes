Set-Location "$PSScriptRoot\..\..\backend"
php artisan migrate --seed
php artisan serve
