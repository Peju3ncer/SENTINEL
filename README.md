# SENTINEL PHP CLI - Terminal Web Security Scanner v1.0

**SENTINEL** adalah tool scanner keamanan berbasis PHP yang berjalan di terminal (CLI) untuk melakukan berbagai pengecekan pada target website.  
Dapat dijalankan di **Linux**, **Termux (Android)**, maupun **Windows PowerShell**.

---

## 📂 Struktur Folder
```
SENTINEL/
├── sentinel.php        # File utama CLI
├── modules/
│   ├── whois.php              # WHOIS Lookup
│   ├── port_scan.php          # Port Scanner
│   ├── subdomain.php          # Subdomain Finder
│   ├── admin_finder.php       # Admin Page Finder
│   ├── cms_detector.php       # CMS Detector
│   ├── sqli_scanner.php       # SQL Injection Scanner
│   ├── xss_scanner.php        # XSS Scanner
│   └── ssl_checker.php        # SSL Certificate Checker
├── wordlists/
│   ├── subdomains.txt         # Wordlist subdomain
│   └── admin_pages.txt        # Wordlist halaman admin
└── logs/                      # Tempat menyimpan hasil scan
```

---

## 📦 Dependensi

### 1. PHP
Pastikan PHP sudah terinstal minimal versi **7.4** atau lebih baru.

Cek versi PHP:
```bash
php -v
```
### 2. Ekstensi PHP yang dibutuhkan
- php-curl (untuk request HTTP)
- php-openssl (untuk pengecekan SSL)
- php-cli (untuk menjalankan PHP di terminal)
- php-mbstring (opsional, untuk string handling)

Instal di Linux/Termux:
```
sudo apt update && sudo apt install php php-cli php-curl php-openssl php-mbstring whois -y <----(Linux)
pkg update && pkg upgrade -y pkg install php whois -y <----(Termux)
```
Install di Windows:
- [Install PHP di](https://www.php.net/downloads)
- Pastikan php.exe ada di PATH
- Instal tool whois untuk Windows jika ingin memakai fitur WHOIS

---

## 💻 Cara Menjalankan

### 1. Clone atau download repository:
```
git clone https://github.com/Peju3ncer/SENTINEL.git
cd SENTINEL
```
### 2. Pastikan file *sentinel.php* bisa dijalankan:
```
chmod +x sentinel.php
```
### 3. Jalankan tool:
```
php sentinel.php
```
Atau (jika ada shebang *#!/usr/bin/env php* di atas):
```
./sentinel.php
```

---

## 📌 Fitur
```
No	Fitur	Deskripsi

1. WHOIS Lookup	Menampilkan informasi WHOIS domain
2. Port Scanner	Memeriksa port umum yang terbuka pada target
3. Subdomain Finder	Mencari subdomain menggunakan wordlist
4. Admin Page Finder	Mencari halaman login admin
5. CMS Detector	Mendeteksi CMS (WordPress, Joomla, Drupal)
6. SQL Injection Scanner	Mengecek potensi SQLi pada parameter GET
7. XSS Scanner	Mengecek potensi XSS pada parameter GET
8. SSL Certificate Checker	Mengecek informasi sertifikat SSL
```

---

## 🗂 Log Output

Setiap hasil scan akan otomatis disimpan di folder *logs/* dengan format:
- whois.log
- ports.log
- subdomains.log
- admin_pages.log
- cms.log
- sqli.log
- xss.log
- ssl.log

---

## ⚠️ Disclaimer

#### Tool ini dibuat untuk pembelajaran dan pengujian keamanan pada sistem milik sendiri atau yang memiliki izin resmi. Segala bentuk penyalahgunaan bukan tanggung jawab pembuat.

---

## Credit

Made by **Peju3ncer** © 2025
