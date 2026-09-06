<?php
/**
 * API: Submit Attendance
 */

require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

if (!isLoggedIn() || !isSiswa()) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';

if (empty($token)) {
    die(json_encode(['success' => false, 'message' => 'Token tidak ditemukan']));
}

try {
    // Validate token again
    $stmt = $pdo->prepare('SELECT id, status, expires_at FROM attendance_sessions WHERE token = ?');
    $stmt->execute([$token]);
    $session = $stmt->fetch();
    
    if (!$session) {
        die(json_encode(['success' => false, 'message' => 'QR tidak ditemukan']));
    }
    
    // Check if expired
    if (strtotime($session['expires_at']) < time()) {
        die(json_encode(['success' => false, 'message' => 'QR sudah kedaluwarsa']));
    }
    
    // Check if closed
    if ($session['status'] !== 'ACTIVE') {
        die(json_encode(['success' => false, 'message' => 'Sesi absensi sudah ditutup']));
    }
    
    // Check if already attended (double check)
    $stmt = $pdo->prepare('SELECT id FROM attendance_records WHERE session_id = ? AND student_id = ?');
    $stmt->execute([$session['id'], $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'Anda sudah melakukan absensi pada sesi ini']));
    }
    
    // Insert attendance record
    $stmt = $pdo->prepare('INSERT INTO attendance_records (session_id, student_id, attendance_date, attendance_time, status, token_used) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $session['id'],
        $_SESSION['user_id'],
        date('Y-m-d'),
        date('H:i:s'),
        'HADIR',
        $token
    ]);
    
    die(json_encode([
        'success' => true,
        'message' => 'Absensi berhasil! Anda tercatat HADIR.'
    ]));
    
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]));
}
