<?php
/**
 * Admin - Manajemen Siswa
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

$students = getAllStudents();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Siswa - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #f3f4f6; padding: 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-block; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Manajemen Siswa</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="header">
            <h1 style="color: #1e3a8a; margin: 0;">Data Siswa</h1>
            <a href="#tambah" class="btn-primary">+ Tambah Siswa</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Username</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($students as $student): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if ($student['foto']): ?>
                                <img src="<?= esc($student['foto']) ?>" class="avatar" alt="">
                            <?php else: ?>
                                <div style="width: 35px; height: 35px; border-radius: 50%; background: #ccc;"></div>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($student['nama_lengkap']) ?></td>
                        <td><?= esc($student['nis']) ?></td>
                        <td><?= esc($student['username']) ?></td>
                        <td><?= esc($student['kelas']) ?></td>
                        <td>
                            <span style="background: <?= $student['status_akun'] === 'AKTIF' ? '#efe' : '#fee' ?>; color: <?= $student['status_akun'] === 'AKTIF' ? '#3c3' : '#c33' ?>; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                <?= $student['status_akun'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="#edit-<?= $student['id'] ?>" class="btn-sm btn-edit">Edit</a>
                            <a href="#delete-<?= $student['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Hapus siswa ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 40px; padding: 20px; background: white; border-radius: 12px; color: #6b7280;">
            <p>🛈 Catatan: Implementasi lengkap CRUD siswa dapat ditambahkan dengan membuat file proses.php untuk menangani tambah, edit, dan hapus siswa.</p>
        </div>
    </div>
</body>
</html>
