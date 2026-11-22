@echo off
REM Pemulihan database dari berkas backup (Acara 30, tugas mandiri 1: disaster recovery).
REM Pemakaian: pulihkan_db.bat <berkas_backup.sql> [nama_database_tujuan]
REM Contoh   : pulihkan_db.bat hasil\db_workshop_20261122_1530.sql db_workshop_restore
REM Database tujuan diisi database PERCOBAAN. Skrip menolak db_workshop agar data utama tidak tertimpa.
set MYSQL=C:\xampp\mysql\bin\mysql.exe
set PORT=3307
set USER=root
set BERKAS=%~1
set TUJUAN=%~2
if "%TUJUAN%"=="" set TUJUAN=db_workshop_restore

if "%BERKAS%"=="" (
    echo Pemakaian: pulihkan_db.bat ^<berkas_backup.sql^> [nama_database_tujuan]
    exit /b 1
)
if not exist "%BERKAS%" (
    echo Berkas backup tidak ditemukan: %BERKAS%
    exit /b 1
)
if /i "%TUJUAN%"=="db_workshop" (
    echo DITOLAK: db_workshop adalah database utama. Gunakan database percobaan.
    exit /b 1
)

"%MYSQL%" -h 127.0.0.1 -P %PORT% -u %USER% -e "DROP DATABASE IF EXISTS %TUJUAN%; CREATE DATABASE %TUJUAN% CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
if errorlevel 1 (
    echo GAGAL menyiapkan database %TUJUAN%. Periksa MySQL, PORT, dan PASSWORD.
    exit /b 1
)
"%MYSQL%" -h 127.0.0.1 -P %PORT% -u %USER% %TUJUAN% < "%BERKAS%"
if errorlevel 1 (
    echo GAGAL mengimpor %BERKAS% ke %TUJUAN%.
    exit /b 1
)
echo Pemulihan selesai: %BERKAS% ^> %TUJUAN%
"%MYSQL%" -h 127.0.0.1 -P %PORT% -u %USER% -e "USE %TUJUAN%; SHOW TABLES;"
