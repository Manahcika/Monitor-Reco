<?php
require_once 'classes/MonitorController.php';

if (!isset($_POST['monitor_id'])) {
    echo '<p>Monitor tidak ditemukan</p>';
    exit;
}

$controller = new MonitorController();
$monitor = $controller->getMonitorById($_POST['monitor_id']);

if (!$monitor) {
    echo '<p>Monitor tidak ditemukan</p>';
    exit;
}

// Update header content via JavaScript
echo "<script>
document.getElementById('monitorTitle').textContent = '{$monitor['brand']} {$monitor['model']}';
document.getElementById('monitorPrice').textContent = '" . $controller->formatPrice($monitor['price_idr']) . "';
</script>";
?>

<img src="<?= htmlspecialchars($monitor['image_url']) ?>" 
     alt="<?= htmlspecialchars($monitor['brand'] . ' ' . $monitor['model']) ?>" 
     class="monitor-image"
     onerror="this.src='assets/image/SamsungG7.jpg'">

<div class="spec-section">
    <h3 class="spec-title">Spesifikasi Dasar</h3>
    <div class="spec-grid">
        <div class="spec-item">
            <span class="spec-label">Brand</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['brand']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Model</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['model']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Harga</span>
            <span class="spec-value"><?= $controller->formatPrice($monitor['price_idr']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Tier</span>
            <span class="spec-value"><?= $controller->getTierLabel($monitor['price_idr']) ?></span>
        </div>
    </div>
</div>

<div class="spec-section">
    <h3 class="spec-title">Spesifikasi Panel</h3>
    <div class="spec-grid">
        <div class="spec-item">
            <span class="spec-label">Tipe Panel</span>
            <span class="spec-value">
                <span class="panel-type panel-<?= strtolower($monitor['panel_type']) ?>">
                    <?= $monitor['panel_type'] ?>
                </span>
            </span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Ukuran</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['panel_size']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Resolusi</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['resolution']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Aspect Ratio</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['aspect_ratio']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Panel Bit Depth</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['panel_bit_depth']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Curvature</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['curvature']) ?></span>
        </div>
    </div>
</div>

<div class="spec-section">
    <h3 class="spec-title">Performa</h3>
    <div class="spec-grid">
        <div class="spec-item">
            <span class="spec-label">Refresh Rate</span>
            <span class="spec-value refresh-rate"><?= $controller->formatRefreshRate($monitor['refresh_rate']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Response Time</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['response_time']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">HDR Support</span>
            <span class="spec-value">
                <?php if ($controller->isHDR($monitor)): ?>
                    <span class="hdr-badge">Ya</span>
                <?php else: ?>
                    <span style="color: #999;">Tidak</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<div class="spec-section">
    <h3 class="spec-title">Fitur</h3>
    <div class="spec-grid">
        <div class="spec-item">
            <span class="spec-label">Stand Capability</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['stand_capability']) ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">VESA Mount</span>
            <span class="spec-value"><?= $monitor['vesa_mount'] ? 'Ya' : 'Tidak' ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Connectivity</span>
            <span class="spec-value"><?= htmlspecialchars($monitor['connectivity']) ?></span>
        </div>
    </div>
</div>

<div class="spec-section">
    <h3 class="spec-title">Kategori Penggunaan</h3>
    <div class="usage-categories" style="margin-top: 10px;">
        <?php 
        if ($monitor['usage_categories']) {
            $categories = explode(', ', $monitor['usage_categories']);
            $colors = explode(',', $monitor['category_colors']);
            foreach ($categories as $index => $category) {
                $color = isset($colors[$index]) ? $colors[$index] : '#007bff';
                echo "<span class='usage-tag' style='background: {$color}; margin-bottom: 5px;'>{$category}</span>";
            }
        } else {
            echo "<span style='color: #999;'>Belum dikategorikan</span>";
        }
        ?>
    </div>
</div>

<div class="spec-section">
    <h3 class="spec-title">Link Pembelian</h3>
    <div class="purchase-links">
        <?php if ($monitor['tokopedia_link'] = 'https://www.tokopedia.com/samsung-official/samsung-gaming-monitor-od-g7-27-g70d-4k-ips-144hz-gsync-hdr-smart-ls27dg702eexxd-1730842079431394946?extParam=ivf%3Dfalse%26keyword%3Dmonitor+oled%26search_id%3D2025080317060537BC2F75BF1BCF08BQ8W%26src%3Dsearch&t_id=1754240774458&t_st=2&t_pp=search_result&t_efo=search_pure_goods_card&t_ef=goods_search&t_sm=&t_spt=search_result'): ?>
        <a href="<?= htmlspecialchars($monitor['tokopedia_link']) ?>" 
           target="_blank" 
           class="purchase-btn tokopedia-btn">
            Beli di Tokopedia
        </a>
        <?php endif; ?>
        
        <?php if ($monitor['shopee_link'] = 'https://s.shopee.co.id/4ApThW1Ooi'): ?>
        <a href="<?= htmlspecialchars($monitor['shopee_link']) ?>" 
           target="_blank" 
           class="purchase-btn shopee-btn">
            Beli di Shopee
        </a>
        <?php endif; ?>
        
        <?php if (!$monitor['tokopedia_link'] && !$monitor['shopee_link']): ?>
        <p style="color: #999; text-align: center; margin: 20px 0;">
            Link pembelian belum tersedia
        </p>
        <?php endif; ?>
    </div>
</div>

<div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 10px; font-size: 0.85rem; color: #666;">
    <strong>Catatan:</strong> Harga dan ketersediaan dapat berubah sewaktu-waktu. 
    Pastikan untuk mengecek detail lengkap di toko online sebelum melakukan pembelian.
</div>