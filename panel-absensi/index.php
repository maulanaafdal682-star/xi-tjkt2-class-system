<?php
/**
 * Panel Absensi - Dashboard
 */

require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/panel-absensi/login.php');
requireRole('ABSENSI', '/panel-absensi/login.php');

// Get current active session
try {
    $stmt = $pdo->prepare('SELECT id, status, created_at, expires_at FROM attendance_sessions WHERE status = "ACTIVE" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute();
    $active_session = $stmt->fetch();
    
    if ($active_session) {
        // Get attendance count
        $stmt = $pdo->prepare('SELECT COUNT(*) as hadir FROM attendance_records WHERE session_id = ?');
        $stmt->execute([$active_session['id']]);
        $hadir = $stmt->fetch()['hadir'];
        
        // Get total students
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM students WHERE status_akun = "AKTIF"');
        $total = $stmt->fetch()['total'];
    } else {
        $hadir = 0;
        $total = 0;
    }
} catch (Exception $e) {
    $active_session = null;
    $hadir = 0;
    $total = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Absensi - XI TJKT 2</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="stylesheet" href="/assets/css/colors.css">
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: #f3f4f6;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-footer a {
            display: block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 13px;
        }
        
        .sidebar-footer a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .main-panel {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #1e3a8a;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 13px;
            color: #6b7280;
        }
        
        .action-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .action-card h3 {
            color: #1e3a8a;
            margin: 0 0 15px 0;
        }
        
        .btn-large {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-large:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3);
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active {
            background: #efe;
            color: #3c3;
        }
        
        .status-inactive {
            background: #fee;
            color: #c33;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-panel {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">🚪 Panel Absensi</div>
            <ul class="sidebar-menu">
                <li><a href="/panel-absensi/" class="active">📊 Dashboard</a></li>
                <li><a href="/panel-absensi/buka.php">➕ Buka Absensi</a></li>
                <li><a href="/panel-absensi/qr.php">📷 Tampilkan QR</a></li>
                <li><a href="/panel-absensi/kehadiran.php">✅ Kehadiran</a></li>
                <li><a href="/panel-absensi/belum-absen.php">❌ Belum Absen</a></li>
                <li><a href="/panel-absensi/riwayat.php">📋 Riwayat Sesi</a></li>
                <li><a href="/panel-absensi/rekap.php">📈 Rekap</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="/logout.php" onclick="return confirm('Logout dari akun?');">🚪 Logout</a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-panel">
            <h1 style="color: #1e3a8a; margin-bottom: 30px;">📊 Dashboard Panel Absensi</h1>
            
            <?php if ($active_session): ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-number"><?= $total ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Sudah Absen</div>
                        <div class="stat-number" style="color: #10b981;"><?= $hadir ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Belum Absen</div>
                        <div class="stat-number" style="color: #ef4444;"><?= $total - $hadir ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Status Sesi</div>
                        <div style="margin: 10px 0;">
                            <span class="status-badge status-active">✓ AKTIF</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div class="action-card">
                        <h3>📷 Tampilkan QR Code</h3>
                        <p style="color: #6b7280; margin: 10px 0;">Lihat QR Code absensi yang sedang berjalan</p>
                        <a href="/panel-absensi/qr.php" class="btn-large">📷 Buka QR</a>
                    </div>
                    <div class="action-card">
                        <h3>✅ Lihat Kehadiran</h3>
                        <p style="color: #6b7280; margin: 10px 0;">Daftar siswa yang sudah melakukan absensi</p>
                        <a href="/panel-absensi/kehadiran.php" class="btn-large">✅ Lihat Kehadiran</a>
                    </div>
                    <div class="action-card">
                        <h3>❌ Belum Absen</h3>
                        <p style="color: #6b7280; margin: 10px 0;">Daftar siswa yang belum melakukan absensi</p>
                        <a href="/panel-absensi/belum-absen.php" class="btn-large">❌ Belum Absen</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="action-card" style="max-width: 500px; margin: 0 auto;">
                    <h2 style="color: #1e3a8a; margin: 0 0 15px 0;">⚡ Belum Ada Sesi Absensi</h2>
                    <p style="color: #6b7280; margin: 10px 0;">Buat sesi absensi baru untuk memulai proses absensi siswa</p>
                    <a href="/panel-absensi/buka.php" class="btn-large" style="font-size: 16px; padding: 15px 40px;">➕ Buka Sesi Absensi Baru</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
