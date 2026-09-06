<?php
/**
 * Admin - Pengaturan Website
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

$settings = getSettings();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fields = [
            'nama_sekolah', 'nama_kelas', 'tahun_ajaran', 'nama_wali_kelas',
            'jurusan', 'deskripsi_kelas', 'motto_kelas', 'jumlah_siswa'
        ];
        
        foreach ($fields as $field) {
            $value = sanitize($_POST[$field] ?? '');
            $stmt = $pdo->prepare('UPDATE website_settings SET setting_value = ? WHERE setting_key = ?');
            $stmt->execute([$value, $field]);
        }
        
        $message = '✓ Pengaturan berhasil disimpan';
    } catch (Exception $e) {
        $message = '❌ Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 800px; margin: 30px auto; padding: 20px; }
        .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e3a8a; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: #1e3a8a; }
        .btn-save { background: #1e3a8a; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .message.success { background: #efe; color: #3c3; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Pengaturan</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <h1 style="color: #1e3a8a; margin-top: 0;">⚙️ Pengaturan Website</h1>
            
            <?php if ($message): ?>
                <div class="message success"><?= $message ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" value="<?= esc($settings['nama_sekolah'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="<?= esc($settings['nama_kelas'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" value="<?= esc($settings['tahun_ajaran'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Wali Kelas</label>
                    <input type="text" name="nama_wali_kelas" value="<?= esc($settings['nama_wali_kelas'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" value="<?= esc($settings['jurusan'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Deskripsi Kelas</label>
                    <textarea name="deskripsi_kelas" rows="4"><?= esc($settings['deskripsi_kelas'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Motto Kelas</label>
                    <input type="text" name="motto_kelas" value="<?= esc($settings['motto_kelas'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Siswa</label>
                    <input type="text" name="jumlah_siswa" value="<?= esc($settings['jumlah_siswa'] ?? '') ?>">
                </div>
                
                <button type="submit" class="btn-save">✓ Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</body>
</html>
