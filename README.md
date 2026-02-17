# Internal System

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)

Sistem Informasi Manajemen Terpadu untuk **Syafa Group** (Contractor & Supplier). Aplikasi ini menangani operasional proyek konstruksi, manajemen stok divisi pangan (Eco), pelaporan keuangan subkon, dan portal berita publik.

## 🚀 Fitur Utama

### 1. Multi-Role Authentication
Sistem menggunakan Middleware `EnsureUserHasRole` untuk memisahkan akses:
* **Admin:** Akses penuh ke manajemen user, proyek, dan konfigurasi sistem.
* **Keuangan:** Verifikasi klaim pembayaran, pencatatan arus kas keluar, dan bukti transfer.
* **Subkon Eks (Vendor):** Input laporan progres kerja dan pengajuan klaim pembayaran.
* **Eco (Divisi Pangan):** Manajemen stok gudang, pabrik, toko, berita, dan portofolio.
* **Subkon PT (Internal):** Monitoring proyek internal.

### 2. Modul Keuangan & Klaim
* Alur kerja **Maker-Checker**: Subkon upload scan dokumen fisik -> Keuangan memverifikasi (Approve/Reject).
* Pencatatan bukti transfer dan nominal pembayaran.
* Riwayat pembayaran per proyek.

### 3. Modul Eco (Inventory & Cabang)
* CRUD Data Cabang (Pabrik, Gudang, Toko).
* Monitoring stok beras/pangan secara *real-time*.
* Manajemen Berita & Artikel untuk halaman publik.
* Manajemen Portofolio Proyek.

### 4. Dashboard Analitik
* Grafik tren pertumbuhan proyek (ApexCharts).
* Statistik User, Proyek Aktif, dan Total Stok.
* Tampilan responsif dan modern.

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel Framework (v11/v12)
* **Frontend:** Blade Templates, Tailwind CSS
* **Interactivity:** Alpine.js
* **Database:** MySQL
* **Charts:** ApexCharts.js
* **Alerts:** SweetAlert2
* **Icons:** FontAwesome 6
