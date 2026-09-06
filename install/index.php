<?php
/**
 * Installer for XI TJKT 2 Class System
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if already installed
try {
    $stmt = $pdo->query('SELECT setting_value FROM website_settings WHERE setting_key = "installer_lock"');
    $result = $stmt->fetch();
    if ($result && $result['setting_value'] == '1') {
        header('Location: /index.php');
        exit;
    }
} catch (Exception $e) {
    // Database not ready yet
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah = sanitize($_POST['nama_sekolah'] ?? '');
    $nama_kelas = sanitize($_POST['nama_kelas'] ?? '');
    $tahun_ajaran = sanitize($_POST['tahun_ajaran'] ?? '');
    $nama_wali_kelas = sanitize($_POST['nama_wali_kelas'] ?? '');
    $username_admin = sanitize($_POST['username_admin'] ?? '');
    $password_admin = $_POST['password_admin'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validation
    if (empty($nama_sekolah)) {
        $error = 'Nama sekolah harus diisi';
    } elseif (empty($nama_kelas)) {
        $error = 'Nama kelas harus diisi';
    } elseif (empty($tahun_ajaran)) {
        $error = 'Tahun ajaran harus diisi';
    } elseif (empty($nama_wali_kelas)) {
        $error = 'Nama wali kelas harus diisi';
    } elseif (empty($username_admin)) {
        $error = 'Username admin harus diisi';
    } elseif (strlen($username_admin) < 3) {
        $error = 'Username minimal 3 karakter';
    } elseif (empty($password_admin)) {
        $error = 'Password harus diisi';
    } elseif (strlen($password_admin) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password_admin !== $password_confirm) {
        $error = 'Password dan konfirmasi tidak sesuai';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Update settings
            $settings = [
                'nama_sekolah' => $nama_sekolah,
                'nama_kelas' => $nama_kelas,
                'tahun_ajaran' => $tahun_ajaran,
                'nama_wali_kelas' => $nama_wali_kelas
            ];
            
            foreach ($settings as $key => $value) {
                $stmt = $pdo->prepare('UPDATE website_settings SET setting_value = ? WHERE setting_key = ?');
                $stmt->execute([$value, $key]);
            }
            
            // Create admin account
            $password_hash = password_hash($password_admin, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $pdo->prepare('INSERT INTO users (nama_lengkap, username, password_hash, role, status) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nama_wali_kelas, $username_admin, $password_hash, 'ADMIN', 'AKTIF']);
            
            // Lock installer
            $stmt = $pdo->prepare('UPDATE website_settings SET setting_value = ? WHERE setting_key = "installer_lock"');
            $stmt->execute(['1']);
            
            $pdo->commit();
            
            $success = 'Instalasi berhasil! Silakan login dengan akun admin yang telah dibuat.';
            $show_form = false;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}

$show_form = empty($success);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - XI TJKT 2 Class System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #1e3a8a;
            color: white;
            margin-top: 10px;
        }
        
        .btn-secondary:hover {
            background: #162e5c;
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            font-size: 12px;
        }
        
        .link {
            text-align: center;
            margin-top: 20px;
        }
        
        .link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Instalasi Sistem</h1>
            <p>XI TJKT 2 Class System</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= esc($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= esc($success) ?>
            </div>
            <div class="link">
                <a href="/admin/" class="btn btn-secondary">Ke Panel Admin</a>
                <a href="/" class="btn btn-primary" style="margin-top: 10px;">Ke Beranda Website</a>
            </div>
        <?php elseif ($show_form): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="nama_sekolah">Nama Sekolah</label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah" placeholder="SMK PGRI Subang" required>
                </div>
                
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <input type="text" id="nama_kelas" name="nama_kelas" placeholder="XI TJKT 2" required>
                </div>
                
                <div class="form-group">
                    <label for="tahun_ajaran">Tahun Ajaran</label>
                    <input type="text" id="tahun_ajaran" name="tahun_ajaran" placeholder="2026/2027" required>
                </div>
                
                <div class="form-group">
                    <label for="nama_wali_kelas">Nama Wali Kelas</label>
                    <input type="text" id="nama_wali_kelas" name="nama_wali_kelas" placeholder="Nama Lengkap Wali Kelas" required>
                </div>
                
                <div class="divider">--- Akun Admin ---</div>
                
                <div class="form-group">
                    <label for="username_admin">Username Admin</label>
                    <input type="text" id="username_admin" name="username_admin" placeholder="username_admin" required>
                </div>
                
                <div class="form-group">
                    <label for="password_admin">Password Admin</label>
                    <input type="password" id="password_admin" name="password_admin" placeholder="Password (min. 6 karakter)" required>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">✓ Buat Akun Admin</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
