@echo off
:: ==========================================================
::  Cong cu tach mau + ma DALI  (Django - cong 18001)
::  Chay bang Python nhung san (python_embed) - KHONG can cai Python.
::  Dung khi dev tren Windows. Server Linux dung setup_ubuntu.sh.
:: ==========================================================
cd /d %~dp0

set PYEMBED=%~dp0python_embed
set PATH=%PYEMBED%;%PYEMBED%\DLLs;%PYEMBED%\Lib\site-packages;%PATH%

if not exist "%PYEMBED%\python.exe" (
  echo [LOI] Khong tim thay python_embed. Hay chay tu repo da bung python_embed.
  pause
  exit /b 1
)

:: Tao bang database lan dau (idempotent - lan sau bo qua nhanh)
"%PYEMBED%\python.exe" manage.py migrate --run-syncdb

echo.
echo === Cong cu tach mau dang chay tai http://127.0.0.1:18001 ===
echo (Dong cua so nay de tat cong cu)
echo.
"%PYEMBED%\python.exe" manage.py runserver 127.0.0.1:18001
