<?php
/**
 * Admin - Manajemen Struktur Organisasi
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

$struktur = getClassStructure();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Struktur - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #f3f4f6; padding: 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-block; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Struktur Organisasi</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="header">
            <h1 style="color: #1e3a8a; margin: 0;">Struktur Organisasi</h1>
            <a href="#tambah" class="btn-primary">+ Tambah</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($struktur as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($item['nama_lengkap'] ?? 'Kosong') ?></td>
                        <td><?= esc($item['jabatan']) ?></td>
                        <td><?= $item['level'] ?></td>
                        <td>
                            <a href="#edit-<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
                            <a href="#delete-<?= $item['id'] ?>" class="btn-sm btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
