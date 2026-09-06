<?php
/**
 * Galeri Foto
 */

require_once 'includes/header.php';

$kategori_filter = $_GET['kategori'] ?? '';

if ($kategori_filter) {
    $galeri = getGallery($kategori_filter);
} else {
    $galeri = getGallery();
}

$kategori_list = ['Foto Bersama', 'Praktikum', 'Kegiatan Kelas', 'Acara Sekolah', 'TJKT', 'Lainnya'];
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">📷 Galeri Foto</h1>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 30px;">
                <a href="/galeri.php" class="btn <?= empty($kategori_filter) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 8px 14px;">Semua Foto</a>
                <?php foreach ($kategori_list as $kat): ?>
                    <a href="/galeri.php?kategori=<?= urlencode($kat) ?>" class="btn <?= $kategori_filter === $kat ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 8px 14px;"><?= esc($kat) ?></a>
                <?php endforeach; ?>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                <?php if (count($galeri) > 0): ?>
                    <?php foreach ($galeri as $item): ?>
                        <div style="position: relative; overflow: hidden; border-radius: 12px; background: #f3f4f6; cursor: pointer; aspect-ratio: 1;" onclick="openLightbox('<?= esc($item['foto']) ?>')">
                            <img src="<?= esc($item['foto']) ?>" alt="<?= esc($item['caption']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); color: white; padding: 15px; opacity: 0; transition: opacity 0.3s ease;" onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';">
                                <?php if ($item['caption']): ?>
                                    <p style="font-size: 13px; margin: 0;"><?= esc($item['caption']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">
                        <p style="font-size: 16px;">📭 Belum ada foto</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<!-- Lightbox -->
<div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.9); z-index: 2000; justify-content: center; align-items: center;" onclick="closeLightbox(event)">
    <img id="lightbox-img" src="" alt="" style="max-width: 90%; max-height: 90%; border-radius: 8px;">
    <button onclick="closeLightbox()" style="position: absolute; top: 20px; right: 20px; background: white; border: none; border-radius: 50%; width: 40px; height: 40px; font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
</div>

<script>
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox(e) {
        if (e && e.target.id !== 'lightbox') return;
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

<?php require_once 'includes/footer.php'; ?>
