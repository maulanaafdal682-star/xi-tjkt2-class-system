<?php
/**
 * Tentang Kelas
 */

require_once 'includes/header.php';

$settings = getSettings();
$nama_sekolah = $settings['nama_sekolah'] ?? 'SMK PGRI Subang';
$nama_kelas = $settings['nama_kelas'] ?? 'XI TJKT 2';
$tahun_ajaran = $settings['tahun_ajaran'] ?? '2026/2027';
$jurusan = $settings['jurusan'] ?? 'Teknik Jaringan Komputer dan Telekomunikasi';
$deskripsi_kelas = $settings['deskripsi_kelas'] ?? '';
$motto_kelas = $settings['motto_kelas'] ?? '';
$nama_wali_kelas = $settings['nama_wali_kelas'] ?? '';
$jumlah_siswa = $settings['jumlah_siswa'] ?? '32';
$foto_kelas = $settings['foto_kelas'] ?? '/assets/img/foto-kelas.jpg';
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 20px;">📚 Tentang <?= esc($nama_kelas) ?></h1>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
                <div>
                    <img src="<?= esc($foto_kelas) ?>" alt="Foto Kelas" style="width: 100%; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);">
                </div>
                
                <div>
                    <h2 style="color: #1e3a8a; margin-bottom: 20px;">Profil Kelas</h2>
                    
                    <div style="background: #f3f4f6; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                        <p><strong>🏫 Sekolah:</strong> <?= esc($nama_sekolah) ?></p>
                        <p><strong>📖 Kelas:</strong> <?= esc($nama_kelas) ?></p>
                        <p><strong>📚 Jurusan:</strong> <?= esc($jurusan) ?></p>
                        <p><strong>📅 Tahun Ajaran:</strong> <?= esc($tahun_ajaran) ?></p>
                        <p><strong>👥 Jumlah Siswa:</strong> <?= esc($jumlah_siswa) ?> siswa</p>
                        <p><strong>👨‍🏫 Wali Kelas:</strong> <?= esc($nama_wali_kelas) ?></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%); color: white; padding: 20px; border-radius: 10px;">
                        <h3 style="margin-bottom: 10px;">💡 Motto Kelas</h3>
                        <p style="font-size: 18px; font-weight: 600; italic;">"<?= esc($motto_kelas) ?>"</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 style="color: #1e3a8a; margin-bottom: 20px;">📝 Deskripsi</h2>
                <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #1e3a8a; line-height: 1.8;">
                    <?= nl2br(esc($deskripsi_kelas)) ?>
                </div>
            </div>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="/struktur.php" class="btn btn-primary">👥 Lihat Struktur Organisasi</a>
                <a href="/" class="btn btn-secondary" style="margin-left: 10px;">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
