# Backup dan Restore db_workshop (Acara 26)

## Backup lewat phpMyAdmin (Langkah 6)
1. Buka phpMyAdmin, pilih database `db_workshop`, tab **Export**.
2. Metode **Quick**, format **SQL**, klik **Export**. File `db_workshop.sql` terunduh.

## Restore ke database percobaan
1. Di phpMyAdmin buat database baru, misalnya `db_workshop_restore` (collation utf8mb4_general_ci).
2. Pilih database itu, tab **Import**, pilih file `db_workshop.sql`, klik **Import**.
3. Verifikasi tabel `users`, `mahasiswa`, dan `buku` ada dan jumlah barisnya sama dengan `db_workshop`.
4. Hapus database percobaan bila sudah tidak diperlukan.

## Foto tidak ikut dalam backup database
Backup database hanya memuat nama berkas pada kolom `foto`. Salin juga folder `public/uploads/` agar foto dapat dipulihkan.

## Jadwal backup (tugas mandiri 2)
Backup dijalankan setiap akhir sesi praktikum (2 x 170 menit). Script `backup_db.bat` membuat file bertanggal pada folder `backup/hasil/`.
Untuk menjalankannya otomatis di Windows (Task Scheduler), buka Command Prompt sebagai Administrator, ubah jam sesuai akhir sesi, lalu jalankan:

    schtasks /create /tn "Backup db_workshop" /tr "C:\xampp\htdocs\setoran_BKPM-1\si-akademik_acara26\backup\backup_db.bat" /sc weekly /d MON /st 15:30

Ganti `MON` dan `15:30` dengan hari dan jam berakhirnya sesi praktikum. Hapus jadwal dengan `schtasks /delete /tn "Backup db_workshop" /f`.
Catatan: script ini mengasumsikan akun root tanpa password (bawaan XAMPP). Bila memakai password, tambahkan `-p<password>` pada baris mysqldump.
