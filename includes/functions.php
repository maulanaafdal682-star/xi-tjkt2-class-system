<?php
/**
 * Common Functions
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Escape HTML output
 */
function esc($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return trim(strip_tags($input));
}

/**
 * Get website settings
 */
function getSettings() {
    global $pdo;
    static $settings = null;
    
    if ($settings === null) {
        try {
            $stmt = $pdo->query('SELECT setting_key, setting_value FROM website_settings');
            $settings = [];
            foreach ($stmt->fetchAll() as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            $settings = [];
        }
    }
    
    return $settings;
}

/**
 * Get single setting
 */
function getSetting($key, $default = '') {
    $settings = getSettings();
    return $settings[$key] ?? $default;
}

/**
 * Get all students
 */
function getAllStudents() {
    global $pdo;
    try {
        $stmt = $pdo->query('SELECT id, nama_lengkap, nis, username, kelas, jabatan, foto, status_akun FROM students ORDER BY nama_lengkap ASC');
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get student by ID
 */
function getStudent($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get class structure
 */
function getClassStructure() {
    global $pdo;
    try {
        $stmt = $pdo->query('SELECT cs.*, s.nama_lengkap, s.foto FROM class_structure cs LEFT JOIN students s ON cs.student_id = s.id ORDER BY cs.level ASC, cs.urutan ASC');
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get picket schedule
 */
function getPicketSchedule($day = null) {
    global $pdo;
    try {
        if ($day) {
            $stmt = $pdo->prepare('SELECT ps.*, s.nama_lengkap, s.foto FROM picket_schedule ps LEFT JOIN students s ON ps.student_id = s.id WHERE ps.hari = ? ORDER BY ps.urutan ASC');
            $stmt->execute([$day]);
        } else {
            $stmt = $pdo->query('SELECT ps.*, s.nama_lengkap, s.foto FROM picket_schedule ps LEFT JOIN students s ON ps.student_id = s.id ORDER BY FIELD(ps.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"), ps.urutan ASC');
        }
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get announcements
 */
function getAnnouncements($limit = null) {
    global $pdo;
    try {
        if ($limit) {
            $stmt = $pdo->query('SELECT * FROM announcements WHERE status = "PUBLISHED" ORDER BY created_at DESC LIMIT ' . (int)$limit);
        } else {
            $stmt = $pdo->query('SELECT * FROM announcements WHERE status = "PUBLISHED" ORDER BY created_at DESC');
        }
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get assignments
 */
function getAssignments($limit = null) {
    global $pdo;
    try {
        if ($limit) {
            $stmt = $pdo->query('SELECT * FROM assignments WHERE status = "PUBLISHED" ORDER BY deadline ASC LIMIT ' . (int)$limit);
        } else {
            $stmt = $pdo->query('SELECT * FROM assignments WHERE status = "PUBLISHED" ORDER BY deadline ASC');
        }
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get gallery
 */
function getGallery($category = null, $featured = false) {
    global $pdo;
    try {
        if ($category && $featured) {
            $stmt = $pdo->prepare('SELECT * FROM gallery WHERE kategori = ? AND featured = 1 ORDER BY created_at DESC');
            $stmt->execute([$category]);
        } elseif ($category) {
            $stmt = $pdo->prepare('SELECT * FROM gallery WHERE kategori = ? ORDER BY created_at DESC');
            $stmt->execute([$category]);
        } elseif ($featured) {
            $stmt = $pdo->query('SELECT * FROM gallery WHERE featured = 1 ORDER BY created_at DESC');
        } else {
            $stmt = $pdo->query('SELECT * FROM gallery ORDER BY created_at DESC');
        }
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get attendance statistics
 */
function getAttendanceStats($student_id = null) {
    global $pdo;
    try {
        $today = date('Y-m-d');
        $month = date('Y-m');
        
        if ($student_id) {
            $stmt = $pdo->prepare('SELECT 
                COUNT(CASE WHEN status = "HADIR" THEN 1 END) as hadir,
                COUNT(CASE WHEN status = "TERLAMBAT" THEN 1 END) as terlambat,
                COUNT(CASE WHEN status = "IZIN" THEN 1 END) as izin,
                COUNT(CASE WHEN status = "SAKIT" THEN 1 END) as sakit,
                COUNT(CASE WHEN status = "ALPHA" THEN 1 END) as alpha
                FROM attendance_records 
                WHERE student_id = ? AND DATE(attendance_date) LIKE ?
            ');
            $stmt->execute([$student_id, $month . '%']);
        } else {
            $stmt = $pdo->query('SELECT 
                COUNT(CASE WHEN status = "HADIR" THEN 1 END) as hadir,
                COUNT(CASE WHEN status = "TERLAMBAT" THEN 1 END) as terlambat,
                COUNT(CASE WHEN status = "IZIN" THEN 1 END) as izin,
                COUNT(CASE WHEN status = "SAKIT" THEN 1 END) as sakit,
                COUNT(CASE WHEN status = "ALPHA" THEN 1 END) as alpha
                FROM attendance_records 
                WHERE DATE(attendance_date) LIKE "' . $month . '%"
            ');
        }
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Generate secure token
 */
function generateToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Get time ago
 */
function timeAgo($date) {
    $timestamp = strtotime($date);
    $current = time();
    $diff = $current - $timestamp;
    
    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        return round($diff / 60) . ' menit lalu';
    } elseif ($diff < 86400) {
        return round($diff / 3600) . ' jam lalu';
    } elseif ($diff < 604800) {
        return round($diff / 86400) . ' hari lalu';
    } else {
        return date('d M Y', $timestamp);
    }
}

/**
 * Upload file
 */
function uploadFile($file, $directory, $allowedTypes = ['jpg', 'jpeg', 'png', 'webp']) {
    if (!isset($file['tmp_name']) || !file_exists($file['tmp_name'])) {
        return ['success' => false, 'message' => 'File tidak ditemukan'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    
    $filename = 'IMG_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
    $filepath = __DIR__ . '/../uploads/' . $directory . '/' . $filename;
    
    if (!is_dir(dirname($filepath))) {
        mkdir(dirname($filepath), 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => '/uploads/' . $directory . '/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Gagal upload file'];
}

/**
 * Delete file
 */
function deleteFile($filepath) {
    $fullpath = __DIR__ . '/..' . $filepath;
    if (file_exists($fullpath)) {
        return unlink($fullpath);
    }
    return false;
}

/**
 * JSON response
 */
function jsonResponse($success, $message = '', $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header('Location: ' . $url);
    exit;
}

/**
 * Get message
 */
function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
