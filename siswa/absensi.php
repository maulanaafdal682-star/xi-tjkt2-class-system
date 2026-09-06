<?php
/**
 * Scan Absensi Siswa
 */

require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/login.php');
requireRole('SISWA', '/login.php');

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Absensi - XI TJKT 2</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="stylesheet" href="/assets/css/colors.css">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <style>
        .absensi-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .scanner-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        video, canvas {
            width: 100%;
            border-radius: 8px;
            display: block;
            margin-bottom: 15px;
        }
        
        .camera-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1e3a8a;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }
        
        .camera-button:hover {
            background: #162e5c;
        }
        
        .camera-button.stop {
            background: #ef4444;
        }
        
        .camera-button.stop:hover {
            background: #dc2626;
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .message.success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .message.error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .message.info {
            background: #eef;
            color: #33c;
            border: 1px solid #ccf;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <span style="cursor: pointer;" onclick="history.back()">← Kembali</span>
            </div>
        </div>
    </nav>
    
    <div class="absensi-container">
        <h1 style="color: #1e3a8a; text-align: center; margin-bottom: 30px;">📱 Scan QR Absensi</h1>
        
        <div id="message-container"></div>
        
        <div class="scanner-container">
            <h3 style="margin-top: 0;">📷 Kamera QR Scanner</h3>
            <video id="video" style="display: none;"></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <div id="camera-status" style="text-align: center; padding: 40px; background: #f3f4f6; border-radius: 8px; color: #6b7280;">
                Klik tombol di bawah untuk membuka kamera
            </div>
            
            <button class="camera-button" id="start-camera-btn" onclick="startCamera()">🎥 Buka Kamera</button>
            <button class="camera-button stop" id="stop-camera-btn" onclick="stopCamera()" style="display: none;">⏹️ Tutup Kamera</button>
        </div>
        
        <div class="scanner-container">
            <h3 style="margin-top: 0;">✅ Konfirmasi Absensi</h3>
            <div id="confirmation-area" style="display: none; text-align: center;">
                <div id="confirmation-content"></div>
                <button class="camera-button" onclick="confirmAttendance()" style="background: #10b981; margin-top: 15px;" id="confirm-btn">✓ Konfirmasi Absensi</button>
            </div>
            <div id="no-confirmation" style="text-align: center; padding: 20px; color: #6b7280;">
                Scan QR untuk melihat konfirmasi
            </div>
        </div>
    </div>
    
    <script>
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let ctx = canvas.getContext('2d');
        let currentToken = null;
        
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    video.srcObject = stream;
                    video.style.display = 'block';
                    document.getElementById('camera-status').style.display = 'none';
                    document.getElementById('start-camera-btn').style.display = 'none';
                    document.getElementById('stop-camera-btn').style.display = 'block';
                    scanQR();
                })
                .catch(err => {
                    showMessage('Gagal membuka kamera: ' + err.message, 'error');
                });
        }
        
        function stopCamera() {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.style.display = 'none';
            document.getElementById('camera-status').style.display = 'block';
            document.getElementById('start-camera-btn').style.display = 'block';
            document.getElementById('stop-camera-btn').style.display = 'none';
            document.getElementById('confirmation-area').style.display = 'none';
            document.getElementById('no-confirmation').style.display = 'block';
        }
        
        function scanQR() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height, { inversionAttempts: 1 });
                
                if (code) {
                    try {
                        const data = JSON.parse(code.data);
                        if (data.token) {
                            validateQR(data.token);
                            return;
                        }
                    } catch (e) {
                        // Not JSON format
                    }
                }
            }
            
            requestAnimationFrame(scanQR);
        }
        
        function validateQR(token) {
            fetch('/api/validate-qr.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token: token })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    currentToken = token;
                    showConfirmation(data.data);
                    showMessage(data.message, 'info');
                } else {
                    showMessage(data.message || 'QR tidak valid', 'error');
                }
            })
            .catch(err => showMessage('Error: ' + err.message, 'error'));
        }
        
        function showConfirmation(data) {
            const html = `
                <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                    <h3 style="margin-top: 0; color: #1e3a8a;">Konfirmasi Absensi</h3>
                    <p><strong>Nama:</strong> ${data.nama_lengkap}</p>
                    <p><strong>NIS:</strong> ${data.nis}</p>
                    <p><strong>Kelas:</strong> ${data.kelas}</p>
                    <p><strong>Status:</strong> <span style="color: #10b981; font-weight: 600;">✓ HADIR</span></p>
                </div>
            `;
            document.getElementById('confirmation-content').innerHTML = html;
            document.getElementById('confirmation-area').style.display = 'block';
            document.getElementById('no-confirmation').style.display = 'none';
        }
        
        function confirmAttendance() {
            if (!currentToken) return;
            
            fetch('/api/submit-attendance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token: currentToken })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message || 'Absensi berhasil!', 'success');
                    document.getElementById('confirmation-area').style.display = 'none';
                    document.getElementById('no-confirmation').style.display = 'block';
                    currentToken = null;
                    setTimeout(() => {
                        window.location.href = '/siswa/riwayat.php';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Gagal submit absensi', 'error');
                }
            })
            .catch(err => showMessage('Error: ' + err.message, 'error'));
        }
        
        function showMessage(msg, type) {
            const container = document.getElementById('message-container');
            const div = document.createElement('div');
            div.className = 'message ' + type;
            div.textContent = msg;
            container.innerHTML = '';
            container.appendChild(div);
            
            if (type === 'success') {
                setTimeout(() => div.remove(), 3000);
            }
        }
    </script>
</body>
</html>
