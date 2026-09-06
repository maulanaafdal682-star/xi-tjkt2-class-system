<?php
/**
 * Pemberitahuan
 */

require_once 'includes/header.php';

$pemberitahuan = getAnnouncements();
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">📢 Pemberitahuan</h1>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php if (count($pemberitahuan) > 0): ?>
                    <?php foreach ($pemberitahuan as $item): ?>
                        <div id="pengumuman-<?= $item['id'] ?>" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border-left: 4px solid <?= $item['penting'] ? '#ef4444' : '#0ea5e9' ?>;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                <div>
                                    <h2 style="color: #1e3a8a; margin-bottom: 5px;"><?= esc($item['judul']) ?></h2>
                                    <p style="color: #6b7280; font-size: 13px;">
                                        📅 <?= formatDate($item['created_at']) ?>
                                        <?php if ($item['penulis']): ?>
                                            | ✍️ <?= esc($item['penulis']) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <?php if ($item['penting']): ?>
                                    <span style="background: #fee; color: #c33; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">⚡ PENTING</span>
                                <?php endif; ?>
                            </div>
                            <div style="color: #374151; line-height: 1.8;">
                                <?= nl2br(esc($item['isi'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p style="font-size: 16px;">📭 Belum ada pemberitahuan</p>
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
