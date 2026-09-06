<?php
/**
 * Admin - Manajemen Tugas
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

$tugas = getAssignments();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
        .card { background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .card h3 { margin: 0 0 10px 0; color: #1e3a8a; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-block; margin-right: 5px; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Tugas / PR</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="header">
            <h1 style="color: #1e3a8a; margin: 0;">📝 Tugas / PR</h1>
            <a href="#tambah" class="btn-primary">+ Tambah</a>
        </div>
        
        <?php foreach ($tugas as $item): ?>
            <div class="card">
                <h3><?= esc($item['judul']) ?></h3>
                <p style="color: #6b7280; font-size: 13px; margin: 5px 0;">📚 <?= esc($item['mata_pelajaran']) ?> | Deadline: <?= formatDate($item['deadline']) ?></p>
                <div>
                    <a href="#edit-<?= $item['id'] ?>" class="btn-sm btn-edit">Edit</a>
                    <a href="#delete-<?= $item['id'] ?>" class="btn-sm btn-delete">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
