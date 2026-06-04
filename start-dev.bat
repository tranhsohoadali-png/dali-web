@echo off
:: ==========================================================
::  DALI - Khoi dong DEV (Windows): Laravel + Cong cu tach mau
::  Mot cu double-click chay ca 2:
::    - Cong cu tach mau (Django, python_embed) tai 127.0.0.1:18001
::    - Web Laravel (php artisan serve) tai 127.0.0.1:8000
:: ==========================================================
cd /d %~dp0

echo === Bat cong cu tach mau (cua so rieng, cong 18001) ===
start "DALI Color Tool (18001)" cmd /c "%~dp0tools\color-tool\run.bat"

echo === Bat web Laravel (cong 8000) ===
echo (Dong cua so nay de tat web; dong cua so kia de tat cong cu tach mau)
echo.
php artisan serve
