import socket
import requests
import ssl
import whois
import threading
from colorama import Fore, init
from termcolor import colored

# Inisialisasi warna di terminal
init(autoreset=True)

# Banner
def print_banner():
    banner = f"""{Fore.GREEN}
   _________  _____________  ________ 
  / __/ __/ |/ /_  __/  _/ |/ / __/ / 
 _\ \/ _//    / / / _/ //    / _// /__
/___/___/_/|_/ /_/ /___/_/|_/___/____/
                                      
  {Fore.YELLOW} Made by: Peju 3ncer {Fore.RESET}
"""
    print(colored(banner, "green"))

# WHOIS Lookup
def whois_lookup(domain):
    try:
        w = whois.whois(domain)
        print(f"{Fore.GREEN}[+] WHOIS Info for {domain}:{Fore.RESET}")
        print(w)
    except Exception as e:
        print(f"{Fore.RED}[-] Gagal mendapatkan WHOIS info: {e}{Fore.RESET}")

# Port Scanner
def port_scan(target, ports=[21, 22, 23, 25, 53, 80, 443, 3306, 8080]):
    print(colored(f"\n[🔎] Scanning ports on {target}...", "yellow"))
    for port in ports:
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.settimeout(1)
        result = sock.connect_ex((target, port))
        if result == 0:
            print(colored(f"   [✅] Port {port} is open!", "green"))
        sock.close()

# Subdomain Finder
def find_subdomains(target):
    print(colored(f"\n[🔎] Searching for subdomains of {target}...", "yellow"))
    
    try:
        with open("subdomains.txt", "r") as f:
            subdomains = [line.strip() for line in f]
    except FileNotFoundError:
        print(colored("   [❌] Wordlist subdomains.txt tidak ditemukan!", "red"))
        return

    def check_sub(sub):
        url = f"http://{sub}.{target}"
        try:
            response = requests.get(url, timeout=2, headers={"User-Agent": "Mozilla/5.0"})
            if response.status_code < 400:
                print(colored(f"   [✅] Found: {url}", "green"))
        except requests.RequestException:
            pass

    threads = []
    for sub in subdomains:
        t = threading.Thread(target=check_sub, args=(sub,))
        threads.append(t)
        t.start()

    for t in threads:
        t.join()

# Admin Page Finder
def find_admin_page(target):
    print(colored(f"\n[🔎] Looking for admin pages on {target}...", "yellow"))
    
    try:
        with open("admin_pages.txt", "r") as f:
            pages = [line.strip() for line in f]
    except FileNotFoundError:
        print(colored("   [❌] Wordlist admin_pages.txt tidak ditemukan!", "red"))
        return

    headers = {
        "User-Agent": "Mozilla/5.0",
        "Referer": "https://www.google.com/"
    }

    def check_admin(page):
        url = f"http://{target}/{page}"
        try:
            response = requests.get(url, timeout=3, headers=headers)
            if response.status_code < 400:
                print(colored(f"   [✅] Found: {url}", "green"))
        except requests.RequestException:
            pass

    for page in pages:
        check_admin(page)

# CMS Detector
def detect_cms(target):
    print(colored(f"\n[🔎] Detecting CMS used by {target}...", "yellow"))
    cms_patterns = {
        "WordPress": "/wp-content/",
        "Joomla": "/administrator/",
        "Drupal": "/sites/all/",
    }
    for cms, path in cms_patterns.items():
        url = f"http://{target}{path}"
        try:
            response = requests.get(url, timeout=3)
            if response.status_code < 400:
                print(colored(f"   [✅] CMS Detected: {cms}", "green"))
                return
        except requests.ConnectionError:
            pass
    print(colored("   [❌] CMS not detected", "red"))

# SQLi Scanner
def sqli_scanner(url):
    payloads = ["'", "\"", " OR 1=1", " OR '1'='1"]
    print(colored(f"\n[🔎] Scanning SQL Injection on {url}...", "yellow"))
    
    for payload in payloads:
        test_url = url + payload
        response = requests.get(test_url)
        
        if "mysql" in response.text.lower() or "syntax" in response.text.lower():
            print(colored(f"   [✅] SQL Injection ditemukan! Payload: {payload}", "green"))
            return
    
    print(colored("   [❌] Tidak ditemukan SQL Injection.", "red"))

# XSS Scanner
def xss_scanner(url):
    payloads = ["<script>alert(1)</script>", "'><script>alert(1)</script>"]
    print(colored(f"\n[🔎] Scanning XSS on {url}...", "yellow"))
    
    for payload in payloads:
        test_url = url + "?q=" + payload
        response = requests.get(test_url)
        
        if payload in response.text:
            print(colored(f"   [✅] XSS ditemukan! Payload: {payload}", "green"))
            return
    
    print(colored("   [❌] Tidak ditemukan XSS vulnerability.", "red"))

# SSL Checker
def check_ssl(target):
    print(colored(f"\n[🔎] Checking SSL certificate of {target}...", "yellow"))
    try:
        ctx = ssl.create_default_context()
        with ctx.wrap_socket(socket.socket(), server_hostname=target) as s:
            s.connect((target, 443))
            cert = s.getpeercert()
            issuer = dict(x[0] for x in cert["issuer"])
            print(colored(f"   [✅] SSL Issuer: {issuer.get('organizationName', 'Unknown')}", "green"))
    except Exception:
        print(colored("   [❌] No valid SSL certificate detected", "red"))

# Menu utama (Looping agar tidak keluar setelah 1 pilihan)
def main():
    print_banner()
    target = input(colored("[+] Enter target domain (without http/https): ", "cyan"))

    while True:
        print(f"""{Fore.RED}
[1] WHOIS Lookup
[2] Port Scanner
[3] Subdomain Finder
[4] Admin Page Finder
[5] CMS Detector
[6] SQLi Scanner
[7] XSS Scanner
[8] SSL Certificate Checker
[9] Exit
{Fore.RESET}""")

        choice = input(colored("[+] Select an option: ", "cyan"))
        
        if choice == "1":
            whois_lookup(target)
        elif choice == "2":
            port_scan(target)
        elif choice == "3":
            find_subdomains(target)
        elif choice == "4":
            find_admin_page(target)
        elif choice == "5":
            detect_cms(target)
        elif choice == "6":
            url = input(colored("[+] Enter URL target: ", "cyan"))
            sqli_scanner(url)
        elif choice == "7":
            url = input(colored("[+] Enter URL target: ", "cyan"))
            xss_scanner(url)
        elif choice == "8":
            check_ssl(target)
        elif choice == "9":
            print(colored("[+] Exiting SENTINEL...", "cyan"))
            break
        else:
            print(colored("[❌] Invalid choice!", "red"))

if __name__ == "__main__":
    main()