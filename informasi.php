<?php
/**
 * Informasi Kelas
 */

require_once 'includes/header.php';

try {
    $stmt = $pdo->query('SELECT * FROM class_information WHERE status = "PUBLISHED" ORDER BY created_at DESC');
    $informasi = $stmt->fetchAll();
} catch (Exception $e) {
    $informasi = [];
}
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">ℹ️ Informasi Kelas</h1>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php if (count($informasi) > 0): ?>
                    <?php foreach ($informasi as $item): ?>
                        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                            <?php if ($item['gambar']): ?>
                                <img src="<?= esc($item['gambar']) ?>" alt="" style="width: 100%; height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%);" title="No Image"></div>
                            <?php endif; ?>
                            
                            <div style="padding: 20px;">
                                <h3 style="color: #1e3a8a; margin-bottom: 8px;"><?= esc($item['judul']) ?></h3>
                                <p style="color: #6b7280; font-size: 12px; margin-bottom: 12px;">
                                    📅 <?= formatDate($item['created_at']) ?>
                                    <?php if ($item['kategori']): ?>
                                        | 🏷️ <?= esc($item['kategori']) ?>
                                    <?php endif; ?>
                                </p>
                                <p style="color: #374151; font-size: 14px; line-height: 1.5;"><?= esc(substr($item['isi'], 0, 100)) ?>...</p>
                                <a href="#info-<?= $item['id'] ?>" style="color: #1e3a8a; text-decoration: none; font-weight: 600; margin-top: 10px; display: block;">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">
                        <p style="font-size: 16px;">📭 Belum ada informasi</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
