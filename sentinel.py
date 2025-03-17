import socket
import requests
import ssl
import whois
import json
from colorama import Fore, init
from termcolor import colored

# Inisialisasi colorama untuk warna di terminal
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

# SQL Injection Scanner
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
    subdomains = ["www", "mail", "blog", "dev", "shop", "forum"]
    for sub in subdomains:
        url = f"http://{sub}.{target}"
        try:
            response = requests.get(url, timeout=3)
            if response.status_code < 400:
                print(colored(f"   [✅] Found: {url}", "green"))
        except requests.ConnectionError:
            pass

# Admin Page Finder
def find_admin_page(target):
    print(colored(f"\n[🔎] Looking for admin page on {target}...", "yellow"))
    pages = ["admin", "login", "dashboard", "cpanel", "wp-admin", "admin.php"]
    for page in pages:
        url = f"http://{target}/{page}"
        try:
            response = requests.get(url, timeout=3)
            if response.status_code < 400:
                print(colored(f"   [✅] Found: {url}", "green"))
        except requests.ConnectionError:
            pass

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

# Menu utama
def main():
    print_banner()
    target = input(colored("[+] Enter target domain (without http/https): ", "cyan"))
    
    print("\n[1] WHOIS Lookup")
    print("[2] SQL Injection Scanner")
    print("[3] XSS Scanner")
    print("[4] Port Scanner")
    print("[5] Subdomain Finder")
    print("[6] Admin Page Finder")
    print("[7] CMS Detector")
    print("[8] SSL Certificate Checker")
    print("[9] Exit")
    
    choice = input(colored("[+] Select an option: ", "cyan"))
    
    if choice == "1":
        whois_lookup(target)
    elif choice == "2":
        url = input(colored("[+] Enter URL target: ", "cyan"))
        sqli_scanner(url)
    elif choice == "3":
        url = input(colored("[+] Enter URL target: ", "cyan"))
        xss_scanner(url)
    elif choice == "4":
        port_scan(target)
    elif choice == "5":
        find_subdomains(target)
    elif choice == "6":
        find_admin_page(target)
    elif choice == "7":
        detect_cms(target)
    elif choice == "8":
        check_ssl(target)
    elif choice == "9":
        print(colored("[+] Exiting SENTINEL...", "cyan"))
        exit()
    else:
        print(colored("[❌] Invalid choice!", "red"))

if __name__ == "__main__":
    main()