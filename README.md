# TakoSaya - Website Katalog & Sistem Order UMKM

## 📋 Deskripsi Singkat

**TakoSaya** adalah website UMKM/restoran sederhana yang menampilkan katalog produk menu dan memungkinkan pelanggan untuk melakukan pemesanan secara online. Website ini dilengkapi dengan admin panel untuk mengelola produk, pesanan, dan testimoni.

Proyek ini dibuat sebagai tugas dalam pembelajaran RPL (Rekayasa Perangkat Lunak) dengan fokus pada fungsionalitas dasar website penjualan online.

---

## ✨ Fitur Utama

### 📱 Untuk Pelanggan (Frontend)
- **Halaman Beranda** - Menampilkan informasi toko dan menu unggulan
- **Katalog Produk** - Daftar lengkap menu dengan fitur pencarian
- **Detail Produk** - Informasi lengkap produk (nama, harga, deskripsi)
- **Sistem Pemesanan** - Pelanggan dapat memesan produk melalui form online
- **Validasi Anti-Bot** - Menggunakan Google reCAPTCHA untuk keamanan
- **Testimoni** - Menampilkan ulasan pelanggan

### 🔐 Untuk Admin (Backend)
- **Login Admin** - Autentikasi dengan session untuk keamanan akses
- **Dashboard** - Ringkasan pesanan dan produk
- **Manajemen Produk** - CRUD (Create, Read, Update, Delete) untuk menu
- **Manajemen Testimoni** - CRUD untuk ulasan pelanggan
- **Manajemen Pesanan** - Melihat, mengedit, dan melacak status order
- **Notifikasi Otomatis** - Pengiriman notifikasi ke WhatsApp dan Telegram saat ada pesanan baru
- **Cetak Laporan** - Fitur Export/Print data pesanan (belum sempurna)

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP** - Server-side scripting (MySQLi untuk database)
- **MySQL** - Database untuk menyimpan produk, order, admin, dan testimoni

### Frontend
- **HTML5** - Struktur halaman
- **Tailwind CSS** - Framework styling untuk desain responsif
- **JavaScript (Vanilla)** - Interaksi dan logika client-side
- **jQuery** - Manipulasi DOM dan AJAX
- **DataTables** - Tabel interaktif untuk admin panel

### Integrasi Pihak Ketiga
- **Google reCAPTCHA v3** - Verifikasi anti-bot pada form order
- **WhatsApp API** - Notifikasi pesanan ke admin via WhatsApp

---

## 👤 Peran Saya dalam Proyek

Saya mengembangkan sebagian **besar sistem**, termasuk frontend, struktur database, dan integrasi fitur utama, dengan bantuan AI pada beberapa bagian backend dan debugging, mencakup:

- **Struktur Database** - Merancang dan membuat tabel untuk produk, pesanan, admin, testimoni
- **Frontend Pages** - Membuat halaman HOME, katalog, detail produk, form order
- **Admin Panel** - Membuat interface admin dengan logika CRUD untuk kelola data
- **Sistem Login** - Implementasi autentikasi admin dengan session management
- **API & Backend Logic** - Proses order, validasi input, rate limiting
- **Integrasi Notifikasi** - Koneksi ke WhatsApp dan Telegram untuk notifikasi otomatis
- **Styling & UI** - Design responsif dengan Tailwind CSS

Dengan kata lain: **Saya terlibat dalam pengembangan frontend, backend dasar, serta perancangan database dan alur sistem**

---

## 📚 Apa yang Saya Pahami dari Proyek Ini

### Konsep yang Saya Kuasai
1. **Database Design** - Merancang tabel relasi dan primary key
2. **CRUD Operations** - Implementasi Create, Read, Update, Delete data
3. **Form Validation** - Validasi input dari client dan server
4. **Responsive Design** - Membuat interface yang bagus di desktop dan mobile

### Konsep yang Masih Saya Pelajari
- **Error Handling** yang lebih robust (sekarang dasar)
- **Caching & Performance Optimization**
- **API Integration** - Menghubungkan aplikasi dengan layanan pihak ketiga (reCAPTCHA, WhatsApp, Telegram)
- **Session Management** - Cara kerja login dan otorisasi
- **Prepared Statement vs String Concatenation** - Memahami pentingnya security untuk cegah SQL injection
- **Rate Limiting** - Membatasi jumlah request untuk cegah spam
- **Advanced Security** (CORS, CSRF tokens di semua form)
- **API Design** yang lebih clean dan terstruktur
- **Database Query Optimization** untuk dataset besar

---

## 📝 Catatan Pengembangan

### Bantuan AI dalam Proyek
Saya menggunakan **ChatGPT/Copilot untuk**:
- Memperbaiki syntax dan struktur kode PHP
- Menjelaskan konsep database design
- Debugging ketika ada error (tapi saya yang analisis & fix)
- Best practices dalam keamanan (reCAPTCHA, rate limiting)
- Refactoring kode yang sudah berfungsi

### Keterbatasan & Area Perbaikan
1. **Error Messages** - Masih banyak yang generic, perlu pesan error lebih spesifik
2. **Logging & Monitoring** - Belum ada sistem log untuk tracking masalah
3. **Pagination** - Untuk data besar, perlu implementasi pagination
4. **Email Notification** - Sekarang hanya WhatsApp & Telegram, perlu tambah email
5. **Admin Features** - Belum ada role-based access (semua admin punya akses penuh)
6. **Data Backup** - Belum ada sistem backup otomatis
7. **Testing** - Baru testing manual, belum automation testing

---

## 🚀 Cara Menjalankan Project

### Prasyarat
- PHP 7.4+ (atau versi lebih baru)
- MySQL/MariaDB Server
- Web Server (Apache preferred, bisa gunakan Laragon)
- Browser modern (Chrome, Firefox, Safari)

### Instalasi & Setup

#### 1. **Siapkan Database**
```sql
-- Buat database dengan nama sesuai di config/db.php
CREATE DATABASE penjualan_umkm;

-- Import tabel yang diperlukan
-- (File SQL database ada di folder tertentu atau konsultasi dengan file yang ada)
```

#### 2. **Konfigurasi Database**
Buka `config/db.php` dan sesuaikan dengan setting lokal Anda:
```php
$host = "127.0.0.1";           // Host database
$user = "root";                // Username
$pass = "";                    // Password
$db = "penjualan_umkm";       // Nama database
```

#### 3. **Konfigurasi API Keys**
Di file `config/db.php`, pastikan sudah ada:
- **Google reCAPTCHA Keys** - Daftar di [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
- **WhatsApp API** - Integrasi dengan platform WhatsApp (CloudAPI atau library)
- **Telegram Bot Token** - Buat di [@BotFather](https://t.me/botfather) di Telegram

#### 4. **Jalankan di Server**
```bash
# Jika menggunakan Laragon:
# 1. Taruh folder di C:\laragon\www\
# 2. Buka http://localhost/WEBSITE-RESTORAN-SEDERHANA

# Atau jika manual dengan PHP built-in:
cd c:\laragon\www\WEBSITE-RESTORAN-SEDERHANA
php -S localhost:8000
# Buka http://localhost:8000
```

#### 5. **Login Admin**
- Buka http://localhost/WEBSITE-RESTORAN-SEDERHANA/admin/
- Gunakan username & password yang sudah dibuat di database
- Kelola produk, pesanan, dan testimoni dari sini

#### 6. **Akses Halaman Pelanggan**
- Halaman Home: `/`
- Katalog Menu: `/katalog.php`
- Detail Produk: `/detail.php?id=[product_id]`

---

## 📞 Kontak & Feedback

Jika ada pertanyaan atau feedback tentang kode dan project ini, silakan buat **Issue** di GitHub atau hubungi saya langsung.

---

## 📄 Lisensi

Project ini dibuat untuk tujuan pendidikan sebagai tugas RPL. Silakan gunakan sebagai referensi belajar, tapi jangan copy-paste mentah tanpa memahami kodenya. 

**"Belajar adalah prosesnya, bukan hasilnya!"** 🎓

---

**Dibuat oleh Dannys Martha Favrillia dari banyak coba-coba, debugging, dan belajar dari kesalahan**
