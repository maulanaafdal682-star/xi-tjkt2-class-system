<?php
/**
 * Dashboard Siswa
 */

require_once 'config/auth.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin('/login.php');
requireRole('SISWA', '/login.php');

$user_id = $_SESSION['user_id'];
$student = getStudent($user_id);
$stats = getAttendanceStats($user_id);
$tugas = getAssignments(5);
$pemberitahuan = getAnnouncements(5);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - XI TJKT 2</title>
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
            font-size: 18px;
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
        
        .dashboard-header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin: 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-top: 4px solid #1e3a8a;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .card-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .card-section h3 {
            color: #1e3a8a;
            margin: 0 0 15px 0;
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .list-item {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        
        .list-item:last-child {
            border-bottom: none;
        }
        
        .list-title {
            font-weight: 600;
            color: #1e3a8a;
        }
        
        .list-meta {
            color: #6b7280;
            font-size: 12px;
            margin-top: 3px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                padding: 15px;
            }
            
            .main-panel {
                margin-left: 200px;
                padding: 15px;
            }
            
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar {
                display: none;
            }
            
            .main-panel {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">📚 Menu Siswa</div>
            <ul class="sidebar-menu">
                <li><a href="/siswa/" class="active">📊 Dashboard</a></li>
                <li><a href="/siswa/absensi.php">📱 Scan Absensi</a></li>
                <li><a href="/siswa/riwayat.php">📋 Riwayat Absensi</a></li>
                <li><a href="/siswa/statistik.php">📈 Statistik</a></li>
                <li><a href="/siswa/tugas.php">📝 Tugas / PR</a></li>
                <li><a href="/siswa/pemberitahuan.php">📢 Pemberitahuan</a></li>
                <li><a href="/siswa/profil.php">👤 Profil</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="/logout.php" onclick="return confirm('Logout dari akun?');">🚪 Logout</a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-panel">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard Siswa</h1>
                <div style="text-align: right;">
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">Selamat datang,</p>
                    <p style="margin: 0; font-weight: 600; color: #1e3a8a;"><?= esc($_SESSION['nama_lengkap']) ?></p>
                </div>
            </div>
            
            <!-- Profil Section -->
            <div class="card-section" style="margin-bottom: 30px;">
                <div style="display: flex; gap: 20px; align-items: start;">
                    <?php if ($student['foto']): ?>
                        <img src="<?= esc($student['foto']) ?>" alt="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #1e3a8a;">
                    <?php else: ?>
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #1e3a8a, #0ea5e9); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">👤</div>
                    <?php endif; ?>
                    
                    <div>
                        <h2 style="margin: 0 0 5px 0; color: #1e3a8a;"><?= esc($student['nama_lengkap']) ?></h2>
                        <p style="margin: 0; color: #6b7280; font-size: 13px;">NIS: <?= esc($student['nis']) ?></p>
                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">Kelas: <?= esc($student['kelas']) ?></p>
                        <?php if ($student['jabatan']): ?>
                            <p style="margin: 5px 0 0 0; color: #1e3a8a; font-size: 13px; font-weight: 600;">💼 <?= esc($student['jabatan']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['hadir'] ?? 0 ?></div>
                    <div class="stat-label">Hadir</div>
                </div>
                <div class="stat-card" style="border-top-color: #f59e0b;">
                    <div class="stat-number" style="color: #f59e0b;"><?= $stats['terlambat'] ?? 0 ?></div>
                    <div class="stat-label">Terlambat</div>
                </div>
                <div class="stat-card" style="border-top-color: #3b82f6;">
                    <div class="stat-number" style="color: #3b82f6;"><?= $stats['izin'] ?? 0 ?></div>
                    <div class="stat-label">Izin</div>
                </div>
                <div class="stat-card" style="border-top-color: #ef4444;">
                    <div class="stat-number" style="color: #ef4444;"><?= $stats['sakit'] ?? 0 ?></div>
                    <div class="stat-label">Sakit</div>
                </div>
                <div class="stat-card" style="border-top-color: #6b7280;">
                    <div class="stat-number" style="color: #6b7280;"><?= $stats['alpha'] ?? 0 ?></div>
                    <div class="stat-label">Alpha</div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Tugas Terbaru -->
                <div class="card-section">
                    <h3>📝 Tugas / PR Terbaru</h3>
                    <?php if (count($tugas) > 0): ?>
                        <?php foreach ($tugas as $t): ?>
                            <div class="list-item">
                                <div class="list-title"><?= esc($t['judul']) ?></div>
                                <div class="list-meta">📚 <?= esc($t['mata_pelajaran']) ?></div>
                                <div class="list-meta">⏰ Deadline: <?= formatDate($t['deadline']) ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div style="margin-top: 15px; text-align: center;">
                            <a href="/siswa/tugas.php" style="color: #1e3a8a; text-decoration: none; font-weight: 600; font-size: 13px;">Lihat Semua →</a>
                        </div>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px 0;">Belum ada tugas</p>
                    <?php endif; ?>
                </div>
                
                <!-- Pemberitahuan Terbaru -->
                <div class="card-section">
                    <h3>📢 Pemberitahuan Terbaru</h3>
                    <?php if (count($pemberitahuan) > 0): ?>
                        <?php foreach ($pemberitahuan as $p): ?>
                            <div class="list-item">
                                <div class="list-title"><?= esc($p['judul']) ?></div>
                                <div class="list-meta">📅 <?= formatDate($p['created_at']) ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div style="margin-top: 15px; text-align: center;">
                            <a href="/siswa/pemberitahuan.php" style="color: #1e3a8a; text-decoration: none; font-weight: 600; font-size: 13px;">Lihat Semua →</a>
                        </div>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px 0;">Belum ada pemberitahuan</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
