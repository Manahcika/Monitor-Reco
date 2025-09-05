<?php
session_start();
require_once 'classes/MonitorController.php';
require_once 'config/database.php';

$db = new Database();
$controller = new MonitorController();
$message = '';
$action = $_GET['action'] ?? 'login';

// Simple Authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['login'])) {
        if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $action = 'dashboard';
        } else {
            $message = '<div class="alert alert-danger">Username atau password salah!</div>';
            $action = 'login';
        }
    } else {
        $action = 'login';
    }
}

// Logout handler
if ($action === 'logout') {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// CRUD Operations
if (isset($_SESSION['admin_logged_in'])) {
    
    // ADD Monitor
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = "INSERT INTO monitors (brand, model, price_idr, panel_type, panel_size, resolution, 
                    refresh_rate, response_time, hdr_support, curvature, panel_bit_depth, aspect_ratio, 
                    stand_capability, connectivity, image_url, tokopedia_link, shopee_link) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $hdr_support = ($_POST['panel_type'] === 'OLED') ? 1 : ($_POST['hdr_support'] ?? 0);
            
            $params = [
                $_POST['brand'], $_POST['model'], $_POST['price_idr'], $_POST['panel_type'],
                $_POST['panel_size'], $_POST['resolution'], $_POST['refresh_rate'], $_POST['response_time'],
                $hdr_support, $_POST['curvature'], $_POST['panel_bit_depth'], $_POST['aspect_ratio'],
                $_POST['stand_capability'], $_POST['connectivity'], $_POST['image_url'],
                $_POST['tokopedia_link'], $_POST['shopee_link']
            ];
            
            $stmt = $db->query($sql, $params);
            $monitor_id = $db->getConnection()->lastInsertId();
            
            // Insert categories
            if (!empty($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    $db->query("INSERT INTO monitor_usage (monitor_id, category_id) VALUES (?, ?)", [$monitor_id, $category_id]);
                }
            }
            
            $message = '<div class="alert alert-success">Monitor berhasil ditambahkan!</div>';
            $action = 'dashboard';
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    
    // UPDATE Monitor
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $monitor_id = $_POST['monitor_id'];
            $sql = "UPDATE monitors SET brand=?, model=?, price_idr=?, panel_type=?, panel_size=?, 
                    resolution=?, refresh_rate=?, response_time=?, hdr_support=?, curvature=?, 
                    panel_bit_depth=?, aspect_ratio=?, stand_capability=?, connectivity=?, 
                    image_url=?, tokopedia_link=?, shopee_link=?, updated_at=CURRENT_TIMESTAMP WHERE id=?";
            
            $hdr_support = ($_POST['panel_type'] === 'OLED') ? 1 : ($_POST['hdr_support'] ?? 0);
            
            $params = [
                $_POST['brand'], $_POST['model'], $_POST['price_idr'], $_POST['panel_type'],
                $_POST['panel_size'], $_POST['resolution'], $_POST['refresh_rate'], $_POST['response_time'],
                $hdr_support, $_POST['curvature'], $_POST['panel_bit_depth'], $_POST['aspect_ratio'],
                $_POST['stand_capability'], $_POST['connectivity'], $_POST['image_url'],
                $_POST['tokopedia_link'], $_POST['shopee_link'], $monitor_id
            ];
            
            $db->query($sql, $params);
            
            // Update categories
            $db->query("DELETE FROM monitor_usage WHERE monitor_id = ?", [$monitor_id]);
            if (!empty($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    $db->query("INSERT INTO monitor_usage (monitor_id, category_id) VALUES (?, ?)", [$monitor_id, $category_id]);
                }
            }
            
            $message = '<div class="alert alert-success">Monitor berhasil diupdate!</div>';
            $action = 'dashboard';
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    
    // DELETE Monitor
    if ($action === 'delete' && isset($_POST['confirm_delete'])) {
        try {
            $monitor_id = $_POST['monitor_id'];
            $db->query("DELETE FROM monitor_usage WHERE monitor_id = ?", [$monitor_id]);
            $db->query("DELETE FROM monitors WHERE id = ?", [$monitor_id]);
            $message = '<div class="alert alert-success">Monitor berhasil dihapus!</div>';
            $action = 'dashboard';
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    
    // Get data for forms
    if (in_array($action, ['add', 'edit'])) {
        $categories = $db->fetchAll("SELECT * FROM usage_categories ORDER BY name");
    }
    
    if ($action === 'edit' || $action === 'delete') {
        $monitor_id = $_GET['id'] ?? 0;
        $monitor = $db->fetch("SELECT * FROM monitors WHERE id = ?", [$monitor_id]);
        if (!$monitor) {
            $message = '<div class="alert alert-danger">Monitor tidak ditemukan!</div>';
            $action = 'dashboard';
        } else {
            $current_categories = $db->fetchAll("SELECT category_id FROM monitor_usage WHERE monitor_id = ?", [$monitor_id]);
            $current_category_ids = array_column($current_categories, 'category_id');
        }
    }
    
    if ($action === 'dashboard' || !$action) {
        $monitors = $controller->getAllMonitors();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Monitor Recommendation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .nav-link {
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .nav-link.danger {
            background: #dc3545;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .confirm-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        .monitor-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #dc3545;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .admin-nav {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action === 'login'): ?>
        <!-- LOGIN FORM -->
        <div class="login-container">
            <div class="header">
                <h1>Admin Login</h1>
                <p>Silahkan login untuk mengakses panel admin</p>
            </div>
            
            <div class="monitor-table-container">
                <div class="table-header">Login Admin</div>
                <div style="padding: 30px;">
                    <?= $message ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Username:</label>
                            <input type="text" name="username" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password:</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">
                            Login
                        </button>
                    </form>
                    
                    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 0.9rem;">
                        <strong>Demo Credentials:</strong><br>
                        Username: admin<br>
                        Password: admin123
                    </div>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <!-- ADMIN HEADER -->
        <header class="header">
            <h1>Admin Panel</h1>
            <p>Kelola data monitor dan rekomendasi</p>
        </header>

        <!-- NAVIGATION -->
        <nav class="admin-nav">
            <div class="nav-links">
                <a href="admin.php?action=dashboard" class="nav-link">Dashboard</a>
                <a href="admin.php?action=add" class="nav-link">Tambah Monitor</a>
                <a href="index.php" class="nav-link">Lihat Website</a>
            </div>
            <a href="admin.php?action=logout" class="nav-link danger">Logout</a>
        </nav>

        <?= $message ?>

        <?php if ($action === 'dashboard'): ?>
        <!-- DASHBOARD -->
        <div class="monitor-table-container">
            <div class="table-header">
                Dashboard - Manajemen Monitor
                <span style="float: right; font-size: 0.9rem; opacity: 0.8;">
                    Total: <?= count($monitors) ?> Monitor
                </span>
            </div>
            
            <table class="monitor-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Brand & Model</th>
                        <th>Harga</th>
                        <th>Panel</th>
                        <th>Resolusi</th>
                        <th>Refresh Rate</th>
                        <th>HDR</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monitors as $monitor): ?>
                    <tr class="monitor-row <?= $controller->getTierClass($monitor['price_idr']) ?>">
                        <td><?= $monitor['id'] ?></td>
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
                            <a href="admin.php?action=edit&id=<?= $monitor['id'] ?>" 
                               class="btn btn-success btn-sm">Edit</a>
                            <a href="admin.php?action=delete&id=<?= $monitor['id'] ?>" 
                               class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($action === 'add'): ?>
        <!-- ADD FORM -->
        <div class="form-container">
            <div class="monitor-table-container">
                <div class="table-header">Tambah Monitor Baru</div>
                
                <form method="POST" action="admin.php?action=add" style="padding: 30px;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Brand *</label>
                            <input type="text" name="brand" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Model *</label>
                            <input type="text" name="model" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga (IDR) *</label>
                            <input type="number" name="price_idr" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Panel *</label>
                            <select name="panel_type" class="form-select" required onchange="toggleHDR(this)">
                                <option value="">Pilih Panel</option>
                                <option value="IPS">IPS</option>
                                <option value="VA">VA</option>
                                <option value="TN">TN</option>
                                <option value="OLED">OLED</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ukuran Panel</label>
                            <input type="text" name="panel_size" class="form-input" placeholder="27 inch">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Resolusi</label>
                            <select name="resolution" class="form-select">
                                <option value="1920x1080 (FHD)">1920x1080 (FHD)</option>
                                <option value="2560x1440 (QHD)">2560x1440 (QHD)</option>
                                <option value="3840x2160 (UHD)">3840x2160 (UHD)</option>
                                <option value="3440x1440 (UWQHD)">3440x1440 (UWQHD)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Refresh Rate (Hz)</label>
                            <input type="number" name="refresh_rate" class="form-input" value="60">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Response Time</label>
                            <input type="text" name="response_time" class="form-input" placeholder="1ms">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Curvature</label>
                            <input type="text" name="curvature" class="form-input" value="None">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Panel Bit Depth</label>
                            <input type="text" name="panel_bit_depth" class="form-input" placeholder="8 Bit">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aspect Ratio</label>
                            <select name="aspect_ratio" class="form-select">
                                <option value="16:9">16:9</option>
                                <option value="21:9">21:9</option>
                                <option value="32:9">32:9</option>
                            </select>
                        </div>
                        <div class="form-group" id="hdr-group">
                            <label class="form-label">HDR Support</label>
                            <select name="hdr_support" class="form-select">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Stand Capability</label>
                            <input type="text" name="stand_capability" class="form-input" placeholder="Tilt, Swivel, Height Adjustment">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Connectivity</label>
                            <input type="text" name="connectivity" class="form-input" placeholder="HDMI, DP, USB-C">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Image URL</label>
                            <input type="url" name="image_url" class="form-input" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link Tokopedia</label>
                            <input type="url" name="tokopedia_link" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link Shopee</label>
                            <input type="url" name="shopee_link" class="form-input">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Kategori Penggunaan</label>
                            <div class="checkbox-group">
                                <?php foreach ($categories as $category): ?>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" 
                                           id="cat_<?= $category['id'] ?>">
                                    <label for="cat_<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Simpan Monitor</button>
                        <a href="admin.php?action=dashboard" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <?php elseif ($action === 'edit'): ?>
        <!-- EDIT FORM -->
        <div class="form-container">
            <div class="monitor-table-container">
                <div class="table-header">Edit Monitor: <?= htmlspecialchars($monitor['brand'] . ' ' . $monitor['model']) ?></div>
                
                <form method="POST" action="admin.php?action=update" style="padding: 30px;">
                    <input type="hidden" name="monitor_id" value="<?= $monitor['id'] ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Brand *</label>
                            <input type="text" name="brand" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['brand']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Model *</label>
                            <input type="text" name="model" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['model']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga (IDR) *</label>
                            <input type="number" name="price_idr" class="form-input" 
                                   value="<?= $monitor['price_idr'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Panel *</label>
                            <select name="panel_type" class="form-select" required onchange="toggleHDR(this)">
                                <option value="IPS" <?= $monitor['panel_type'] === 'IPS' ? 'selected' : '' ?>>IPS</option>
                                <option value="VA" <?= $monitor['panel_type'] === 'VA' ? 'selected' : '' ?>>VA</option>
                                <option value="TN" <?= $monitor['panel_type'] === 'TN' ? 'selected' : '' ?>>TN</option>
                                <option value="OLED" <?= $monitor['panel_type'] === 'OLED' ? 'selected' : '' ?>>OLED</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ukuran Panel</label>
                            <input type="text" name="panel_size" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['panel_size']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Resolusi</label>
                            <input type="text" name="resolution" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['resolution']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Refresh Rate (Hz)</label>
                            <input type="number" name="refresh_rate" class="form-input" 
                                   value="<?= $monitor['refresh_rate'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Response Time</label>
                            <input type="text" name="response_time" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['response_time']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Curvature</label>
                            <input type="text" name="curvature" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['curvature']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Panel Bit Depth</label>
                            <input type="text" name="panel_bit_depth" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['panel_bit_depth']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aspect Ratio</label>
                            <input type="text" name="aspect_ratio" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['aspect_ratio']) ?>">
                        </div>
                        <div class="form-group" id="hdr-group">
                            <label class="form-label">HDR Support</label>
                            <select name="hdr_support" class="form-select">
                                <option value="0" <?= $monitor['hdr_support'] == 0 ? 'selected' : '' ?>>Tidak</option>
                                <option value="1" <?= $monitor['hdr_support'] == 1 ? 'selected' : '' ?>>Ya</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Stand Capability</label>
                            <input type="text" name="stand_capability" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['stand_capability']) ?>">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Connectivity</label>
                            <input type="text" name="connectivity" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['connectivity']) ?>">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Image URL</label>
                            <input type="url" name="image_url" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['image_url']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link Tokopedia</label>
                            <input type="url" name="tokopedia_link" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['tokopedia_link']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link Shopee</label>
                            <input type="url" name="shopee_link" class="form-input" 
                                   value="<?= htmlspecialchars($monitor['shopee_link']) ?>">
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Kategori Penggunaan</label>
                            <div class="checkbox-group">
                                <?php foreach ($categories as $category): ?>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" 
                                           id="cat_<?= $category['id'] ?>"
                                           <?= in_array($category['id'], $current_category_ids) ? 'checked' : '' ?>>
                                    <label for="cat_<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Update Monitor</button>
                        <a href="admin.php?action=dashboard" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <?php elseif ($action === 'delete'): ?>
        <!-- DELETE CONFIRMATION -->
        <div class="form-container">
            <div class="monitor-table-container">
                <div class="table-header">Konfirmasi Hapus Monitor</div>
                
                <div style="padding: 30px;">
                    <div class="confirm-box">
                        <h3 style="color: #dc3545; margin-bottom: 15px;">⚠️ Peringatan!</h3>
                        <p>Anda akan menghapus monitor ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    
                    <div class="monitor-info">
                        <h4><?= htmlspecialchars($monitor['brand'] . ' ' . $monitor['model']) ?></h4>
                        <p><strong>Harga:</strong> <?= $controller->formatPrice($monitor['price_idr']) ?></p>
                        <p><strong>Panel:</strong> <?= htmlspecialchars($monitor['panel_type']) ?> - <?= htmlspecialchars($monitor['panel_size']) ?></p>
                        <p><strong>Resolusi:</strong> <?= htmlspecialchars($monitor['resolution']) ?></p>
                        <p><strong>Refresh Rate:</strong> <?= $controller->formatRefreshRate($monitor['refresh_rate']) ?></p>
                        <p><strong>Dibuat:</strong> <?= date('d/m/Y H:i', strtotime($monitor['created_at'])) ?></p>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <p style="font-weight: 600; color: #dc3545; font-size: 1.1rem;">
                            Apakah Anda yakin ingin menghapus monitor ini?
                        </p>
                    </div>
                    
                    <div class="btn-group">
                        <form method="POST" action="admin.php?action=delete" style="display: inline;">
                            <input type="hidden" name="monitor_id" value="<?= $monitor['id'] ?>">
                            <button type="submit" name="confirm_delete" class="btn btn-danger">
                                Ya, Hapus Monitor
                            </button>
                        </form>
                        <a href="admin.php?action=dashboard" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        function toggleHDR(select) {
            const hdrGroup = document.getElementById('hdr-group');
            const hdrSelect = document.querySelector('select[name="hdr_support"]');
            
            if (hdrGroup && hdrSelect) {
                if (select.value === 'OLED') {
                    hdrSelect.value = '1';
                    hdrSelect.disabled = true;
                    hdrGroup.style.opacity = '0.6';
                } else {
                    hdrSelect.disabled = false;
                    hdrGroup.style.opacity = '1';
                }
            }
        }
        
        // Initialize on page load for edit form
        document.addEventListener('DOMContentLoaded', function() {
            const panelSelect = document.querySelector('select[name="panel_type"]');
            if (panelSelect) {
                toggleHDR(panelSelect);
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            });
        });

        // Confirm delete action
        function confirmDelete(monitorName) {
            return confirm('Yakin ingin menghapus monitor "' + monitorName + '"? Tindakan ini tidak dapat dibatalkan.');
        }

        // Form validation
        function validateForm(form) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return false;
            }
            
            return true;
        }

        // Add form validation to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[action*="add"], form[action*="update"]');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (!validateForm(form)) {
                        e.preventDefault();
                    }
                });
            });
        });

        // Price formatting input
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.querySelector('input[name="price_idr"]');
            if (priceInput) {
                priceInput.addEventListener('input', function(e) {
                    // Remove non-numeric characters
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = value;
                });
                
                priceInput.addEventListener('blur', function(e) {
                    // Add formatting on blur
                    let value = parseInt(e.target.value);
                    if (!isNaN(value)) {
                        // You can add thousand separators here if needed
                        e.target.value = value;
                    }
                });
            }
        });

        // Auto-generate model suggestions based on brand (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            const brandInput = document.querySelector('input[name="brand"]');
            const modelInput = document.querySelector('input[name="model"]');
            
            if (brandInput && modelInput) {
                brandInput.addEventListener('input', function(e) {
                    const brand = e.target.value.toUpperCase();
                    
                    // Set placeholder based on brand
                    switch(brand) {
                        case 'ASUS':
                            modelInput.placeholder = 'e.g., ROG XG27ACS';
                            break;
                        case 'MSI':
                            modelInput.placeholder = 'e.g., MAG 274QRF X24';
                            break;
                        case 'ACER':
                            modelInput.placeholder = 'e.g., EK251Q EBI';
                            break;
                        case 'SAMSUNG':
                            modelInput.placeholder = 'e.g., Odyssey G3 32';
                            break;
                        case 'SKYWORTH':
                            modelInput.placeholder = 'e.g., F27G67Q Pro';
                            break;
                        default:
                            modelInput.placeholder = 'Masukkan model monitor';
                    }
                });
            }
        });
    </script>
</body>
</html>