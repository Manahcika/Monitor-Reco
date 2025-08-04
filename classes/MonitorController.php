<?php
require_once 'config/database.php';

class MonitorController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllMonitors($type = 'all') {
        $whereClause = '';
        $params = [];

        if ($type === 'sdr') {
            $whereClause = 'WHERE panel_type != "OLED"';
        } elseif ($type === 'hdr') {
            $whereClause = 'WHERE panel_type = "OLED" OR hdr_support = 1';
        }

        $sql = "
            SELECT 
                m.*,
                GROUP_CONCAT(uc.name SEPARATOR ', ') as usage_categories,
                GROUP_CONCAT(uc.color SEPARATOR ',') as category_colors
            FROM monitors m
            LEFT JOIN monitor_usage mu ON m.id = mu.monitor_id
            LEFT JOIN usage_categories uc ON mu.category_id = uc.id
            {$whereClause}
            GROUP BY m.id
            ORDER BY m.price_idr ASC
        ";

        return $this->db->fetchAll($sql, $params);
    }

    public function getMonitorById($id) {
        $sql = "
            SELECT 
                m.*,
                GROUP_CONCAT(uc.name SEPARATOR ', ') as usage_categories,
                GROUP_CONCAT(uc.color SEPARATOR ',') as category_colors
            FROM monitors m
            LEFT JOIN monitor_usage mu ON m.id = mu.monitor_id
            LEFT JOIN usage_categories uc ON mu.category_id = uc.id
            WHERE m.id = ?
            GROUP BY m.id
        ";

        return $this->db->fetch($sql, [$id]);
    }

    public function getUsageCategories() {
        $sql = "SELECT * FROM usage_categories ORDER BY name";
        return $this->db->fetchAll($sql);
    }

    public function formatPrice($price) {
        return 'IDR ' . number_format($price, 0, ',', '.');
    }

    public function formatRefreshRate($rate) {
        return $rate . 'Hz';
    }

    public function getTierClass($price) {
        if ($price < 2000000) {
            return 'tier-budget';
        } elseif ($price < 4000000) {
            return 'tier-mid';
        } elseif ($price < 6000000) {
            return 'tier-high';
        } else {
            return 'tier-premium';
        }
    }

    public function getTierLabel($price) {
        if ($price < 2000000) {
            return 'Budget';
        } elseif ($price < 4000000) {
            return 'Mid-Range';
        } elseif ($price < 6000000) {
            return 'High-End';
        } else {
            return 'Premium';
        }
    }

    public function isHDR($monitor) {
        return $monitor['panel_type'] === 'OLED' || $monitor['hdr_support'] == 1;
    }
}
?>