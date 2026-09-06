# Website Resmi Kelas XI TJKT 2

Website resmi kelas XI TJKT 2 SMK PGRI Subang dengan sistem absensi digital QR Code, panel admin, dan dashboard siswa.

## Fitur Utama

### 1. Website Publik
- Beranda dengan informasi kelas
- Tentang kelas dengan data lengkap
- Struktur organisasi dengan bagan bergaris
- Jadwal piket dengan foto siswa
- Pemberitahuan dan pengumuman
- Daftar tugas/PR
- Informasi kelas
- Galeri foto

### 2. Panel Admin / Wali Kelas
- Manajemen siswa (CRUD lengkap)
- Manajemen struktur organisasi
- Manajemen jadwal piket
- Manajemen pemberitahuan
- Manajemen tugas/PR
- Manajemen informasi kelas
- Manajemen galeri
- Pengaturan website
- Manajemen petugas absensi
- Review dan edit absensi

### 3. Panel Absensi Digital
- Generate QR Code absensi
- Countdown timer QR (5 menit)
- Melihat kehadiran realtime
- Daftar siswa belum absen
- Riwayat sesi absensi
- Rekap absensi
- Buka/tutup sesi

### 4. Akun Siswa
- Dashboard dengan statistik kehadiran
- Scan absensi via QR Code
- Riwayat absensi
- Statistik kehadiran
- Daftar tugas/PR
- Pemberitahuan
- Profil siswa

## Teknologi

- PHP 8+ Native (tanpa framework berat)
- MySQL / MariaDB
- PDO dengan Prepared Statements
- HTML5, CSS3, JavaScript
- AJAX / Fetch API
- QR Code Library
- Camera QR Scanner

## Kompatibilitas

✅ XAMPP
✅ Laragon
✅ InfinityFree
✅ GoogieHost
✅ Shared Hosting PHP/MySQL
✅ UserLAnd / Linux

## Instalasi

### Untuk XAMPP / Laragon

1. **Extract project**
   ```
   Extract ke folder htdocs atau www
   ```

2. **Buat database**
   ```
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Klik "New" atau "Buat database baru"
   - Nama database: xi_tjkt2_class
   - Collation: utf8mb4_unicode_ci
   - Klik "Create"
   ```

3. **Import SQL**
   ```
   - Pilih database xi_tjkt2_class
   - Klik tab "Import"
   - Pilih file: database/xi_tjkt2_class.sql
   - Klik "Go" atau "Import"
   ```

4. **Edit config/database.php**
   ```php
   $db_host = 'localhost';
   $db_name = 'xi_tjkt2_class';
   $db_user = 'root';
   $db_pass = '';
   ```

5. **Jalankan installer**
   ```
   Buka di browser: http://localhost/xi-tjkt2-class-system/install/
   (sesuaikan dengan nama folder)
   
   Isi data:
   - Nama Sekolah
   - Nama Kelas
   - Tahun Ajaran
   - Nama Wali Kelas
   - Username Admin
   - Password Admin
   
   Klik "Buat Akun Admin"
   ```

6. **Login**
   ```
   Buka: http://localhost/xi-tjkt2-class-system/
   Klik ⋮ (menu) → Login Siswa
   Atau: http://localhost/xi-tjkt2-class-system/admin/
   Username: (sesuai yang dibuat)
   Password: (sesuai yang dibuat)
   ```

### Untuk InfinityFree / Shared Hosting

1. **Upload ke hosting**
   ```
   Upload seluruh folder ke public_html atau htdocs
   ```

2. **Buat database MySQL**
   ```
   - Login ke cPanel
   - Buka "MySQL Databases"
   - Database Name: (nama_awal)_xi_tjkt2_class
   - Create Database
   - Buat user MySQL dengan password
   - Berikan akses penuh ke database
   ```

3. **Buka phpMyAdmin**
   ```
   - Klik "phpMyAdmin"
   - Pilih database
   - Klik "Import"
   - Pilih file: database/xi_tjkt2_class.sql
   - Klik "Go"
   ```

4. **Ambil informasi database**
   ```
   Di cPanel → MySQL Databases
   - Database Name: (catat nama lengkapnya)
   - Username: (catat username)
   - Password: (catat password)
   - Hostname: biasanya localhost atau 127.0.0.1
   ```

5. **Edit config/database.php**
   ```php
   $db_host = 'localhost';  // atau sesuai hosting
   $db_name = 'nama_database_lengkap';
   $db_user = 'username_mysql';
   $db_pass = 'password_mysql';
   ```

6. **Jalankan installer**
   ```
   Buka di browser: https://namadomain.com/install/
   Isi data admin dan klik "Buat Akun Admin"
   ```

7. **Login dan gunakan**
   ```
   https://namadomain.com/
   ```

## Fitur Keamanan

✅ Password hashing dengan password_hash()
✅ Validasi password dengan password_verify()
✅ PDO Prepared Statements
✅ PHP Session dengan regenerate_id()
✅ Unique constraint pada attendance
✅ Server-side QR validation
✅ Server-side authorization
✅ Upload validation
✅ Input validation
✅ Output escaping

## Database

Database menggunakan tabel:
- users (admin, petugas absensi)
- students (data siswa)
- class_structure (struktur organisasi)
- picket_schedule (jadwal piket)
- announcements (pemberitahuan)
- assignments (tugas/PR)
- class_information (informasi kelas)
- gallery (galeri foto)
- attendance_sessions (sesi absensi)
- attendance_records (record absensi)
- website_settings (pengaturan website)

## Login Default

Setelah menjalankan installer, tidak ada login default. Anda harus membuat akun admin pertama melalui installer.

## Alur Absensi

```
1. Petugas Panel Absensi login ke /panel-absensi/
2. Petugas membuka sesi absensi
3. Sistem generate QR Code (valid 5 menit)
4. Siswa login ke akun siswa di /siswa/
5. Siswa buka "Scan Absensi"
6. Siswa arahkan kamera ke QR
7. Server validasi token dan session
8. Tampilkan konfirmasi absensi
9. Simpan record ke database
10. Muncul pesan sukses "Anda tercatat HADIR"
```

## Support

Untuk bantuan, silakan buat issue di repository ini.

## License

Digunakan untuk SMK PGRI Subang - Kelas XI TJKT 2 Tahun Pelajaran 2026/2027
