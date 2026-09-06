<?php
/**
 * Authentication Functions
 */

require_once __DIR__ . '/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is logged in and has specific role
 */
function hasRole($role) {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('ADMIN');
}

/**
 * Check if user is petugas absensi
 */
function isPetugasAbsensi() {
    return hasRole('ABSENSI');
}

/**
 * Check if user is siswa
 */
function isSiswa() {
    return hasRole('SISWA');
}

/**
 * Login user
 */
function loginUser($username, $password, $role) {
    global $pdo;
    
    try {
        if ($role === 'SISWA') {
            $stmt = $pdo->prepare('SELECT id, nama_lengkap, username, password_hash, kelas, foto, status_akun FROM students WHERE (username = ? OR nis = ?) AND status_akun = "AKTIF"');
            $stmt->execute([$username, $username]);
        } else {
            $stmt = $pdo->prepare('SELECT id, nama_lengkap, username, password_hash, role, status FROM users WHERE username = ? AND role = ? AND status = "AKTIF"');
            $stmt->execute([$username, $role]);
        }
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerate session ID
            session_regenerate_id(true);
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $role;
            
            if ($role === 'SISWA') {
                $_SESSION['kelas'] = $user['kelas'];
                $_SESSION['foto'] = $user['foto'];
            }
            
            return true;
        }
        
        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Logout user
 */
function logoutUser() {
    session_destroy();
    return true;
}

/**
 * Get current user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'nama_lengkap' => $_SESSION['nama_lengkap'],
        'role' => $_SESSION['role']
    ];
}

/**
 * Require login
 */
function requireLogin($redirectTo = '/login.php') {
    if (!isLoggedIn()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Require role
 */
function requireRole($role, $redirectTo = '/') {
    if (!hasRole($role)) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
