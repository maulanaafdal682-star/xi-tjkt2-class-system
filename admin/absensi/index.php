<?php
/**
 * Admin - Manajemen Absensi
 */

require_once '../../config/auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireLogin('/admin/login.php');
requireRole('ADMIN', '/admin/login.php');

try {
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM attendance_sessions');
    $total_sesi = $stmt->fetch()['total'];
    
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM attendance_records');
    $total_record = $stmt->fetch()['total'];
} catch (Exception $e) {
    $total_sesi = 0;
    $total_record = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Absensi - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <style>
        body { background: #f3f4f6; }
        .container { max-width: 900px; margin: 30px auto; padding: 20px; }
        .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%); color: white; padding: 25px; border-radius: 12px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: 700; }
        .stat-label { font-size: 14px; opacity: 0.9; margin-top: 8px; }
        .action-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 30px; }
        .action-link { background: white; padding: 20px; border-radius: 12px; text-decoration: none; color: #1e3a8a; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">Admin - Absensi</div>
            <a href="/admin/" style="color: #1e3a8a; text-decoration: none;">← Kembali</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <h1 style="color: #1e3a8a; margin-top: 0;">✅ Manajemen Absensi</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_sesi ?></div>
                    <div class="stat-label">Total Sesi</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="stat-number"><?= $total_record ?></div>
                    <div class="stat-label">Total Record</div>
                </div>
            </div>
            
            <div class="action-links">
                <a href="#detail" class="action-link">📄 Lihat Detail</a>
                <a href="#rekap" class="action-link">📈 Rekap Absensi</a>
            </div>
            
            <p style="color: #6b7280; margin-top: 30px;">🛈 Admin dapat melihat, mengedit, dan membuat laporan absensi dari halaman ini.</p>
        </div>
    </div>
</body>
</html>
