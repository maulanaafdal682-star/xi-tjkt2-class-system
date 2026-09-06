<?php
/**
 * Database Configuration
 * Edit file ini sesuai dengan konfigurasi database Anda
 */

$db_host = 'localhost';
$db_name = 'xi_tjkt2_class';
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Database Connection Error: ' . $e->getMessage());
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
