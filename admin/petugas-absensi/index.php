<?php
/**
 * Admin - Manajemen Petugas Absensi
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

try {
    $stmt = $pdo->query('SELECT * FROM users WHERE role = "ABSENSI" ORDER BY created_at DESC');
    $petugas = $stmt->fetchAll();
} catch (Exception $e) {
    $petugas = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Petugas - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #f3f4f6; padding: 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-block; margin-right: 5px; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Petugas Absensi</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="header">
            <h1 style="color: #1e3a8a; margin: 0;">🚪 Petugas Absensi</h1>
            <a href="#tambah" class="btn-primary">+ Tambah Petugas</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($petugas as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($p['nama_lengkap']) ?></td>
                        <td><?= esc($p['username']) ?></td>
                        <td>
                            <span style="background: <?= $p['status'] === 'AKTIF' ? '#efe' : '#fee' ?>; color: <?= $p['status'] === 'AKTIF' ? '#3c3' : '#c33' ?>; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                <?= $p['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="#edit-<?= $p['id'] ?>" class="btn-sm btn-edit">Edit</a>
                            <a href="#delete-<?= $p['id'] ?>" class="btn-sm btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
