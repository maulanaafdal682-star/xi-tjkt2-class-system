-- Database XI TJKT 2 Class System
-- SMK PGRI Subang - Tahun Pelajaran 2026/2027

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('ADMIN', 'ABSENSI') NOT NULL,
  `status` ENUM('AKTIF', 'NONAKTIF') DEFAULT 'AKTIF',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_username (username),
  INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `nis` VARCHAR(20) UNIQUE NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `kelas` VARCHAR(20) DEFAULT 'XI TJKT 2',
  `jabatan` VARCHAR(100),
  `bio` TEXT,
  `foto` VARCHAR(255),
  `status_akun` ENUM('AKTIF', 'NONAKTIF') DEFAULT 'AKTIF',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_nis (nis),
  INDEX idx_username (username),
  INDEX idx_kelas (kelas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_structure` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT,
  `jabatan` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT,
  `level` INT DEFAULT 1,
  `parent_id` INT,
  `urutan` INT DEFAULT 0,
  `foto_khusus` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
  FOREIGN KEY (parent_id) REFERENCES class_structure(id) ON DELETE SET NULL,
  INDEX idx_level (level),
  INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `picket_schedule` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `hari` ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
  `urutan` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY unique_picket (student_id, hari),
  INDEX idx_hari (hari),
  INDEX idx_student_id (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(255) NOT NULL,
  `isi` LONGTEXT NOT NULL,
  `kategori` VARCHAR(50),
  `penulis` VARCHAR(100),
  `penting` BOOLEAN DEFAULT FALSE,
  `status` ENUM('DRAFT', 'PUBLISHED') DEFAULT 'DRAFT',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_penting (penting),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `assignments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `mata_pelajaran` VARCHAR(100) NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `deskripsi` LONGTEXT,
  `nama_guru` VARCHAR(100),
  `tanggal_diberikan` DATE,
  `deadline` DATE NOT NULL,
  `lampiran` VARCHAR(255),
  `link` VARCHAR(255),
  `status` ENUM('BARU', 'BERLANGSUNG', 'HAMPIR_DEADLINE', 'SELESAI') DEFAULT 'BARU',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_deadline (deadline),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_information` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(255) NOT NULL,
  `gambar` VARCHAR(255),
  `isi` LONGTEXT NOT NULL,
  `kategori` VARCHAR(100),
  `penulis` VARCHAR(100),
  `status` ENUM('DRAFT', 'PUBLISHED') DEFAULT 'DRAFT',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_kategori (kategori),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `foto` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255),
  `kategori` ENUM('Foto Bersama', 'Praktikum', 'Kegiatan Kelas', 'Acara Sekolah', 'TJKT', 'Lainnya') DEFAULT 'Lainnya',
  `featured` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_kategori (kategori),
  INDEX idx_featured (featured),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `token` VARCHAR(255) UNIQUE NOT NULL,
  `created_by` INT NOT NULL,
  `status` ENUM('ACTIVE', 'CLOSED', 'EXPIRED') DEFAULT 'ACTIVE',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_status (status),
  INDEX idx_token (token),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `attendance_date` DATE NOT NULL,
  `attendance_time` TIME,
  `status` ENUM('HADIR', 'TERLAMBAT', 'IZIN', 'SAKIT', 'ALPHA') DEFAULT 'HADIR',
  `token_used` VARCHAR(255),
  `note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY unique_attendance (session_id, student_id),
  INDEX idx_student_id (student_id),
  INDEX idx_session_id (session_id),
  INDEX idx_attendance_date (attendance_date),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) UNIQUE NOT NULL,
  `setting_value` LONGTEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `website_settings` (`setting_key`, `setting_value`) VALUES
('nama_sekolah', 'SMK PGRI Subang'),
('nama_kelas', 'XI TJKT 2'),
('tahun_ajaran', '2026/2027'),
('nama_wali_kelas', 'Nama Wali Kelas'),
('jurusan', 'Teknik Jaringan Komputer dan Telekomunikasi'),
('deskripsi_kelas', 'Kelas XI TJKT 2 adalah kelas untuk program keahlian Teknik Jaringan Komputer dan Telekomunikasi'),
('motto_kelas', 'Profesional, Berkompetensi, dan Berinovasi'),
('jumlah_siswa', '32'),
('email_kelas', 'kelas@sekolah.sch.id'),
('logo_sekolah', '/assets/img/logo-sekolah.png'),
('logo_kelas', '/assets/img/logo-kelas.png'),
('favicon', '/assets/img/favicon.png'),
('banner_utama', '/assets/img/banner.jpg'),
('foto_kelas', '/assets/img/foto-kelas.jpg'),
('warna_utama', '#1e3a8a'),
('installer_lock', '0');
