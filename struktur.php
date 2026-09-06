<?php
/**
 * Struktur Organisasi Kelas
 */

require_once 'includes/header.php';

$struktur = getClassStructure();

// Build tree structure
$tree_items = [];
$items_by_id = [];

foreach ($struktur as $item) {
    $items_by_id[$item['id']] = $item;
}

foreach ($struktur as $item) {
    if ($item['parent_id'] === null) {
        $tree_items[] = $item;
    }
}

function renderTree($items, $items_by_id, $level = 0) {
    $html = '';
    foreach ($items as $item) {
        $children = array_filter($items_by_id, function($x) use ($item) {
            return $x['parent_id'] == $item['id'];
        });
        
        $has_children = count($children) > 0;
        
        $html .= '<div class="tree-node" style="margin-left: ' . ($level * 40) . 'px;">';
        $html .= '<div class="tree-item">';
        
        if ($item['foto_khusus'] || $item['foto']) {
            $foto = $item['foto_khusus'] ?? $item['foto'];
            $html .= '<img src="' . esc($foto) . '" alt="" class="tree-avatar">';
        } else {
            $html .= '<div class="tree-avatar-placeholder">👤</div>';
        }
        
        $html .= '<div class="tree-content">';
        $html .= '<h3>' . esc($item['nama_lengkap'] ?? 'Kosong') . '</h3>';
        $html .= '<p>' . esc($item['jabatan']) . '</p>';
        if ($item['deskripsi']) {
            $html .= '<small>' . esc($item['deskripsi']) . '</small>';
        }
        $html .= '</div>';
        
        $html .= '</div>';
        
        if ($has_children) {
            $html .= renderTree(array_values($children), $items_by_id, $level + 1);
        }
        
        $html .= '</div>';
    }
    return $html;
}
?>

<main class="main-content">
    <div class="container">
        <div style="padding: 40px 0;">
            <h1 style="color: #1e3a8a; font-size: 36px; margin-bottom: 30px;">👥 Struktur Organisasi <?= esc(getSetting('nama_kelas', 'XI TJKT 2')) ?></h1>
            
            <?php if (count($struktur) > 0): ?>
                <div class="organization-chart" style="overflow-x: auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                    <style>
                        .tree-node {
                            margin-bottom: 20px;
                            position: relative;
                        }
                        
                        .tree-item {
                            background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%);
                            color: white;
                            padding: 15px;
                            border-radius: 10px;
                            display: flex;
                            gap: 15px;
                            align-items: center;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                            min-width: 300px;
                        }
                        
                        .tree-avatar {
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            object-fit: cover;
                            border: 3px solid white;
                        }
                        
                        .tree-avatar-placeholder {
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            background: rgba(255, 255, 255, 0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                        }
                        
                        .tree-content h3 {
                            margin: 0;
                            font-size: 14px;
                            font-weight: 600;
                        }
                        
                        .tree-content p {
                            margin: 2px 0;
                            font-size: 12px;
                            opacity: 0.9;
                        }
                        
                        .tree-content small {
                            font-size: 11px;
                            opacity: 0.8;
                            display: block;
                            margin-top: 4px;
                        }
                    </style>
                    
                    <?= renderTree($tree_items, $items_by_id) ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #6b7280; background: white; border-radius: 12px;">
                    <p style="font-size: 16px;">📭 Belum ada struktur organisasi</p>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
