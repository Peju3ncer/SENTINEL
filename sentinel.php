#!/usr/bin/env php
<?php
/**
 * SENTINEL PHP CLI - Terminal Web Security Scanner v1.0
 * Works on Linux, Termux, and Windows PowerShell
 * Made by: Peju 3ncer
 */

// ======= ANSI Colors =======
class Colors {
    const RESET = "\e[0m";
    const RED = "\e[31m";
    const GREEN = "\e[32m";
    const YELLOW = "\e[33m";
    const CYAN = "\e[36m";
    const MAGENTA = "\e[35m";
}

// ======= Banner =======
function printBanner() {
    echo Colors::GREEN . "
  ____  ____  _   _ _____ _      _____ _        
 / ___||  _ \\| \\ | | ____| |    | ____| |       
 \\___ \\| | | |  \\| |  _| | |    |  _| | |       
  ___) | |_| | |\\  | |___| |___ | |___| |___    
 |____/|____/|_| \\_|_____|_____|_____|_____|  
" . Colors::YELLOW . "\n Made by: Peju 3ncer\n" . Colors::RESET;
}

// ======= Helper =======
function prompt($text) {
    echo Colors::CYAN . $text . Colors::RESET;
    return trim(fgets(STDIN));
}

function logResult($filename, $content) {
    if(!is_dir(__DIR__."/logs")) mkdir(__DIR__."/logs");
    file_put_contents(__DIR__."/logs/".$filename, $content.PHP_EOL, FILE_APPEND);
}

// ======= Include Modules =======
require_once __DIR__."/modules/whois.php";
require_once __DIR__."/modules/port_scan.php";
require_once __DIR__."/modules/subdomain.php";
require_once __DIR__."/modules/admin_finder.php";
require_once __DIR__."/modules/cms_detector.php";
require_once __DIR__."/modules/sqli_scanner.php";
require_once __DIR__."/modules/xss_scanner.php";
require_once __DIR__."/modules/ssl_checker.php";

// ======= Main Menu =======
function main() {
    printBanner();
    $target = prompt("[+] Enter target domain (without http/https): ");

    while (true) {
        echo Colors::RED . "
[1] WHOIS Lookup
[2] Port Scanner
[3] Subdomain Finder
[4] Admin Page Finder
[5] CMS Detector
[6] SQLi Scanner
[7] XSS Scanner
[8] SSL Certificate Checker
[9] Exit
" . Colors::RESET;

        $choice = prompt("[+] Select an option: ");

        switch($choice) {
            case "1":
                whoisLookup($target);
                break;
            case "2":
                portScan($target);
                break;
            case "3":
                findSubdomains($target);
                break;
            case "4":
                findAdminPages($target);
                break;
            case "5":
                detectCMS($target);
                break;
            case "6":
                sqliScanner($target);
                break;
            case "7":
                xssScanner($target);
                break;
            case "8":
                checkSSL($target);
                break;
            case "9":
                echo Colors::CYAN."[+] Exiting SENTINEL...\n".Colors::RESET;
                exit;
            default:
                echo Colors::RED."[-] Invalid choice!\n".Colors::RESET;
        }
    }
}

main();
?>