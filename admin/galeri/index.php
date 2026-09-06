<?php
/**
 * Admin - Manajemen Galeri
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

$galeri = getGallery();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Galeri - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .gallery-item { position: relative; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .gallery-item img { width: 100%; height: 150px; object-fit: cover; }
        .gallery-item-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.8); color: white; padding: 10px; font-size: 12px; }
        .btn-sm { padding: 6px 12px; font-size: 11px; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 3px; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Galeri</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="header">
            <h1 style="color: #1e3a8a; margin: 0;">📷 Galeri Foto</h1>
            <a href="#tambah" class="btn-primary">+ Upload Foto</a>
        </div>
        
        <div class="gallery-grid">
            <?php foreach ($galeri as $item): ?>
                <div class="gallery-item">
                    <img src="<?= esc($item['foto']) ?>" alt="">
                    <div class="gallery-item-overlay">
                        <?= esc($item['kategori']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
