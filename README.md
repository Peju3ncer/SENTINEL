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
sudo apt update && sudo apt install php php-cli php-curl php-openssl php-mbstring whois -y
```
Install di Windows:
- [Install PHP di](https://www.php.net/downloads)
- Pastikan php.exe ada di PATH
- Instal tool whois untuk Windows jika ingin memakai fitur WHOIS
