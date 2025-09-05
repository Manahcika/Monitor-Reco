# Monitor Recommendation - Quick Setup Guide

Tutorial singkat untuk menjalankan website Monitor Recommendation di XAMPP.

## Yang Dibutuhkan
- XAMPP (Apache + MySQL + PHP)
- Web Browser

## Quick Setup

### 1. Download & Install XAMPP
1. Download dari: https://www.apachefriends.org/
2. Install dengan pilih: **Apache**, **MySQL**, **PHP**, **phpMyAdmin**
3. Start **Apache** dan **MySQL** di XAMPP Control Panel

### 2. Setup Project
1. Copy semua file ke: `C:\xampp\htdocs\monitors_reco\`
2. Struktur folder:
   ```
   htdocs/monitors_reco/
   ├── index.php
   ├── admin.php
   ├── get_monitor_detail.php
   ├── config/database.php
   ├── classes/MonitorController.php
   ├── assets/css/style.css
   └── monitors_reco.sql
   ```

### 3. Setup Database
1. Buka: **http://localhost/phpmyadmin**
2. Login: username `root`, password kosong
3. Create database: `monitors_reco`
4. Import file: `monitors_reco.sql`

### 4. Test Website
- **Website**: http://localhost/monitors_reco/
- **Admin**: http://localhost/monitors_reco/admin.php
  - Username: `admin`
  - Password: `admin123`

## Checklist
- [ ] XAMPP Apache & MySQL running (hijau)
- [ ] Database `monitors_reco` imported
- [ ] File di `htdocs/monitors_reco/`
- [ ] Website terbuka: http://localhost/monitors_reco/
- [ ] Admin panel login berhasil
- [ ] Klik monitor → side modal muncul

**Done! Website siap digunakan!**

---

## Fitur Utama
- **Filter SDR/HDR** - Monitor dikategorikan otomatis
- **Side Peek Modal** - Detail monitor tanpa reload
- **Tier System** - Budget, Mid-Range, High-End, Premium  
- **Admin CRUD** - Tambah, edit, hapus monitor
- **Responsive Design** - Mobile friendly
