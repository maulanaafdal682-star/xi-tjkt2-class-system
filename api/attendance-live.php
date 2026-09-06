<?php
/**
 * API: Attendance Live Update
 */

require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !isPetugasAbsensi()) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$session_id = $_GET['session_id'] ?? 0;

if (empty($session_id)) {
    die(json_encode(['success' => false, 'message' => 'Session ID required']));
}

try {
    // Get attendance records for session
    $stmt = $pdo->prepare('SELECT ar.*, s.nama_lengkap, s.nis, s.foto FROM attendance_records ar JOIN students s ON ar.student_id = s.id WHERE ar.session_id = ? ORDER BY ar.attendance_time DESC');
    $stmt->execute([$session_id]);
    $records = $stmt->fetchAll();
    
    die(json_encode([
        'success' => true,
        'data' => $records
    ]));
    
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database error']));
}
