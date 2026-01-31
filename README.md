# 🏛️ Sistem Informasi Lapas Kelas IIB Jombang
> **Laravel Test MSJ 2025 — Recruitment System Project**

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![AlpineJS](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-000000?style=for-the-badge&logo=mysql&logoColor=white)

Aplikasi berbasis web modern yang dirancang untuk memfasilitasi layanan publik dan manajemen internal di **Lapas Kelas IIB Jombang**. Aplikasi ini mencakup pendaftaran kunjungan tatap muka secara online, portal berita, pengumuman, serta fitur aksesibilitas ramah disabilitas.

---

## ✨ Fitur Unggulan

### 🌍 Halaman Publik (Guest)
* **📝 Pendaftaran Kunjungan Online:** Formulir pendaftaran tatap muka dengan validasi jadwal (Senin-Kamis) dan kuota harian otomatis.
* **♿ Widget Aksesibilitas Canggih:**
    * 🔊 *Text-to-Speech* (Pembaca Layar).
    * 👁️ Mode Kontras Tinggi & Grayscale.
    * 🔤 Font khusus Disleksia & *Zoom Text*.
    * 🖱️ Kursor Besar.
* **📰 Portal Informasi:** Menampilkan berita terbaru dan pengumuman resmi Lapas.
* **📱 Desain Responsif:** Tampilan optimal di Mobile, Tablet, dan Desktop.

### 🔐 Panel Admin
* **📊 Dashboard Realtime:** Statistik ringkas, jam digital live, dan tabel aktivitas terbaru.
* **📢 Manajemen Konten (CMS):** CRUD (Create, Read, Update, Delete) untuk Berita dan Pengumuman.
* **🛡️ Autentikasi Aman:** Login petugas dengan keamanan enkripsi password standar Laravel.

---

## 📸 Antarmuka Aplikasi

| Halaman Depan | Formulir Kunjungan |
| :---: | :---: |
| <img src="https://via.placeholder.com/400x200?text=Landing+Page" alt="Landing Page"> | <img src="https://via.placeholder.com/400x200?text=Form+Kunjungan" alt="Form Kunjungan"> |

| Dashboard Admin | Fitur Aksesibilitas |
| :---: | :---: |
| <img src="https://via.placeholder.com/400x200?text=Dashboard+Admin" alt="Dashboard Admin"> | <img src="https://via.placeholder.com/400x200?text=Widget+Aksesibilitas" alt="Aksesibilitas"> |

---

## 🚀 Tips Optimasi Performa

Untuk menjaga aplikasi tetap cepat dan responsif, perhatikan beberapa hal berikut:

1.  **Optimasi Gambar:**
    *   Pastikan semua gambar yang diunggah (terutama di bagian berita dan pengumuman) telah dikompresi dengan baik sebelum diunggah. Gunakan tool seperti TinyPNG, Squoosh.app, atau ImageOptim.
    *   Gunakan format gambar modern (WebP) jika memungkinkan.
2.  **Laravel Caching:**
    *   Manfaatkan caching bawaan Laravel untuk konfigurasi, rute, dan view. Jalankan perintah berikut di lingkungan produksi setelah setiap deployment:
        ```bash
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        ```
    *   Untuk membersihkan cache:
        ```bash
        php artisan cache:clear
        php artisan config:clear
        php artisan route:clear
        php artisan view:clear
        ```

---

## 🛠️ Persyaratan Sistem

Pastikan server lokal Anda memenuhi spesifikasi berikut:
* **PHP** >= 8.1
* **Composer**
* **MySQL / MariaDB**
* **Node.js & NPM** (Untuk compile aset CSS/JS)

---
