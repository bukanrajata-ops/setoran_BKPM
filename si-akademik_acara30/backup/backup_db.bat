@echo off
REM Backup db_workshop + folder foto (Acara 26, tugas mandiri 2).
REM Sesuaikan MYSQLDUMP, PORT, dan PASSWORD dengan XAMPP di komputer masing-masing.
set MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe
set DB=db_workshop
set PORT=3307
set USER=root
set HASIL=%~dp0hasil
set UPLOADS=%~dp0..\public\uploads

if not exist "%HASIL%" mkdir "%HASIL%"
for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyyMMdd_HHmm"') do set TS=%%i

"%MYSQLDUMP%" -h 127.0.0.1 -P %PORT% -u %USER% --routines --single-transaction %DB% > "%HASIL%\%DB%_%TS%.sql"
if errorlevel 1 (
    echo GAGAL membuat backup database. Periksa MySQL, PORT, dan PASSWORD.
    del "%HASIL%\%DB%_%TS%.sql" 2>nul
    exit /b 1
)

REM Dump database hanya berisi nama berkas foto, jadi isi folder uploads disalin juga
xcopy "%UPLOADS%" "%HASIL%\uploads_%TS%\" /E /I /Y /Q >nul

echo Backup selesai: %HASIL%\%DB%_%TS%.sql
