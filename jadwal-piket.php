<?php
/**
 * Jadwal Piket
 */

require_once 'includes/header.php';

$hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$jadwal_piket = getPicketSchedule();

// Group by hari
$jadwal_by_hari = [];
foreach ($hari_list as $hari) {
    $jadwal_by_hari[$hari] = [];
}

foreach ($jadwal_piket as $item) {
    $jadwal_by_hari[$item['hari']][] = $item;
}
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">📅 Jadwal Piket</h1>
            
            <?php foreach ($hari_list as $hari): ?>
                <div style="margin-bottom: 40px;">
                    <h2 style="color: #1e3a8a; font-size: 22px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #1e3a8a;"><?= $hari ?></h2>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                        <?php if (count($jadwal_by_hari[$hari]) > 0): ?>
                            <?php foreach ($jadwal_by_hari[$hari] as $item): ?>
                                <div style="background: white; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                                    <?php if ($item['foto']): ?>
                                        <img src="<?= esc($item['foto']) ?>" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 10px; border: 3px solid #1e3a8a;">
                                    <?php else: ?>
                                        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #1e3a8a, #0ea5e9); margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 30px; border: 3px solid #1e3a8a;">👤</div>
                                    <?php endif; ?>
                                    
                                    <h3 style="color: #1e3a8a; font-size: 14px; font-weight: 600; margin: 10px 0;"><?= esc($item['nama_lengkap']) ?></h3>
                                    <p style="color: #6b7280; font-size: 12px; margin: 0;"><?= esc($item['nis']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: #6b7280; font-size: 14px; grid-column: 1 / -1; text-align: center; padding: 20px;">Belum ada piket</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
