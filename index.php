<?php
require_once 'classes/MonitorController.php';

$controller = new MonitorController();
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$monitors = $controller->getAllMonitors($type);
$categories = $controller->getUsageCategories();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Recommendation - Rekomendasi Monitor Terbaik</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


    <header class="sticky-header">
  <div class="header-content">
    <h1>Monitor Reco</h1>
    <h3>Rekomendasi Monitor</h3>
  </div>
</header>



    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>Monitor Recommendation</h1>
            <p>Temukan monitor terbaik sesuai kebutuhan Anda dengan rekomendasi lengkap berdasarkan harga, spesifikasi, dan kategori penggunaan</p>
        </header>


        <div class="video-duo-wrapper">
  <div class="video-box">
    <h3 style="color: azure;">📌Kenali Brand OEM dan ODM Sebelum Membeli Monitor</h3><br>
    <iframe src="https://www.youtube.com/embed/oH_MXnmaGY0?si=9Mat_7hbnFrH5r-6"
            title="Video 1"
            allowfullscreen></iframe>
  </div>
  <div class="video-box">
    <h3 style="color: azure;">📌Kenali Bagaimana HDR Bekerja Pada Monitor</h3><br>
    <iframe src="https://www.youtube.com/embed/2oW2_QSa5yQ?si=dMCKOOqZl0QvlwtI"
            title="Video 2"
            allowfullscreen></iframe>
  </div>
</div>

<div class="video-duo-wrapper">
  <div class="video-box">
    <h3 style="color: azure;">📌Apakah Monitor Ultrawide Mempengaruhi FPS?</h3><br>
    <iframe src="https://www.youtube.com/embed/MqZqxPZ7MNM?si=I7qZeGxfxATf3JYY"
            title="Video 1"
            allowfullscreen></iframe>
  </div>
  <div class="video-box">
    <h3 style="color: azure;">📌Perbedaan OLED, Mini-LED, dan QLED</h3><br>
    <iframe src="https://www.youtube.com/embed/ASjIr7kmEFs?si=y3danh0v1B6L9PyI"
            title="Video 2"
            allowfullscreen></iframe>
  </div>
</div>


        <!-- Navigation Tabs -->
        <nav class="nav-tabs">
            <a href="?type=all" class="nav-tab <?= $type === 'all' ? 'active' : '' ?>">
                Semua Monitor
            </a>
            <a href="?type=sdr" class="nav-tab <?= $type === 'sdr' ? 'active' : '' ?>">
                SDR Monitor
            </a>
            <a href="?type=hdr" class="nav-tab <?= $type === 'hdr' ? 'active' : '' ?>">
                HDR Monitor
            </a>
        </nav>

        <!-- Monitor Table -->
        <div class="monitor-table-container">
            <div class="table-header">
                <?php
                switch($type) {
                    case 'sdr':
                        echo 'SDR Monitor - Standard Dynamic Range <br>';
                        echo 'Jika monitor ini mendukung HDR, itu hanyalah "Gimmick" <br> karena HDR hanya ada pada panel yang mendukung FALD (Full Array Local Diming) seperti Mini LED dan OLED.';
                        break;
                    case 'hdr':
                        echo 'HDR Monitor - High Dynamic Range & OLED';
                        break;
                    default:
                        echo 'Semua Monitor - Daftar Lengkap Rekomendasi';
                }
                ?>
                <span style="float: right; font-size: 0.9rem; opacity: 0.8;">
                    <?= count($monitors) ?> Monitor Tersedia
                </span>
            </div>
            
            <table class="monitor-table">
                <thead>
                    <tr>
                        <th>Tier</th>
                        <th>Brand & Model</th>
                        <th>Harga</th>
                        <th>Panel</th>
                        <th>Ukuran</th>
                        <th>Resolusi</th>
                        <th>Refresh Rate</th>
                        <th>HDR</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monitors as $monitor): ?>
                    <tr class="monitor-row <?= $controller->getTierClass($monitor['price_idr']) ?>" 
                        onclick="showMonitorDetail(<?= $monitor['id'] ?>)">
                        <td>
                            <span class="tier-label">
                                <?= $controller->getTierLabel($monitor['price_idr']) ?>
                            </span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($monitor['brand']) ?></strong><br>
                            <span style="font-size: 0.8rem; color: #666;">
                                <?= htmlspecialchars($monitor['model']) ?>
                            </span>
                        </td>
                        <td class="price-cell">
                            <?= $controller->formatPrice($monitor['price_idr']) ?>
                        </td>
                        <td>
                            <span class="panel-type panel-<?= strtolower($monitor['panel_type']) ?>">
                                <?= $monitor['panel_type'] ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($monitor['panel_size']) ?></td>
                        <td><?= htmlspecialchars($monitor['resolution']) ?></td>
                        <td class="refresh-rate">
                            <?= $controller->formatRefreshRate($monitor['refresh_rate']) ?>
                        </td>
                        <td>
                            <?php if ($controller->isHDR($monitor)): ?>
                                <span class="hdr-badge">HDR</span>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="usage-categories">
                                <?php 
                                if ($monitor['usage_categories']) {
                                    $categories = explode(', ', $monitor['usage_categories']);
                                    $colors = explode(',', $monitor['category_colors']);
                                    foreach ($categories as $index => $category) {
                                        $color = isset($colors[$index]) ? $colors[$index] : '#007bff';
                                        echo "<span class='usage-tag' style='background: {$color}'>{$category}</span>";
                                    }
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Side Peek Modal -->
    <div class="side-peek-overlay" id="sidePeekOverlay" onclick="closeSidePeek()"></div>
    <div class="side-peek" id="sidePeek">
        <div class="side-peek-header">
            <div class="side-peek-title" id="monitorTitle">Monitor</div>
            <div class="side-peek-price" id="monitorPrice"></div>
            <button class="close-btn" onclick="closeSidePeek()">&times;</button>
        </div>
        <div class="side-peek-content" id="sidePeekContent">
            <div class="loading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <script>
        function showMonitorDetail(monitorId) {
            const sidePeek = document.getElementById('sidePeek');
            const overlay = document.getElementById('sidePeekOverlay');
            const content = document.getElementById('sidePeekContent');
            
            // Show modal
            sidePeek.classList.add('active');
            overlay.classList.add('active');
            
            // Load content via AJAX-like approach using form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'get_monitor_detail.php';
            form.style.display = 'none';
            
            const input = document.createElement('input');
            input.name = 'monitor_id';
            input.value = monitorId;
            form.appendChild(input);
            
            document.body.appendChild(form);
            
            // Use fetch alternative for PHP
            fetch('get_monitor_detail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'monitor_id=' + monitorId
            })
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
            })
            .catch(error => {
                content.innerHTML = '<p>Error loading monitor details</p>';
            });
            
            document.body.removeChild(form);
        }

        function closeSidePeek() {
            const sidePeek = document.getElementById('sidePeek');
            const overlay = document.getElementById('sidePeekOverlay');
            
            sidePeek.classList.remove('active');
            overlay.classList.remove('active');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidePeek();
            }
        });
    </script>
</body>
</html>