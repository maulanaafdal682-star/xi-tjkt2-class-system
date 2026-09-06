<?php
/**
 * Tugas / PR
 */

require_once 'includes/header.php';

$tugas = getAssignments();
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">📝 Tugas / PR</h1>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php if (count($tugas) > 0): ?>
                    <?php foreach ($tugas as $item): ?>
                        <div id="tugas-<?= $item['id'] ?>" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                <div>
                                    <h2 style="color: #1e3a8a; margin-bottom: 8px;"><?= esc($item['judul']) ?></h2>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <span style="background: #1e3a8a; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><?= esc($item['mata_pelajaran']) ?></span>
                                        <span style="color: #6b7280; font-size: 13px;">👨‍🏫 <?= esc($item['nama_guru']) ?></span>
                                    </div>
                                </div>
                                <span style="background: <?php 
                                    $deadline = strtotime($item['deadline']);
                                    $today = strtotime(date('Y-m-d'));
                                    if ($deadline < $today) {
                                        echo '#ef4444';
                                    } elseif ($deadline - $today <= 86400 * 3) {
                                        echo '#f59e0b';
                                    } else {
                                        echo '#10b981';
                                    }
                                ?>; color: white; padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                    <?php 
                                    if ($deadline < $today) {
                                        echo '❌ LEWAT';
                                    } elseif ($deadline - $today <= 86400 * 3) {
                                        echo '⚠️ HAMPIR DEADLINE';
                                    } else {
                                        echo '✓ BERLANGSUNG';
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <p style="color: #6b7280; font-size: 13px; margin-bottom: 10px;">
                                📅 Diberikan: <?= formatDate($item['tanggal_diberikan']) ?>
                                | ⏰ Deadline: <strong><?= formatDate($item['deadline']) ?></strong>
                            </p>
                            
                            <?php if ($item['deskripsi']): ?>
                                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 15px; color: #374151;">
                                    <?= nl2br(esc($item['deskripsi'])) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($item['link'] || $item['lampiran']): ?>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <?php if ($item['link']): ?>
                                        <a href="<?= esc($item['link']) ?>" target="_blank" class="btn btn-primary" style="display: inline-block; padding: 8px 14px; font-size: 13px;">🔗 Buka Link</a>
                                    <?php endif; ?>
                                    <?php if ($item['lampiran']): ?>
                                        <a href="<?= esc($item['lampiran']) ?>" class="btn btn-secondary" style="display: inline-block; padding: 8px 14px; font-size: 13px;">📎 Download</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p style="font-size: 16px;">📭 Belum ada tugas</p>
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
