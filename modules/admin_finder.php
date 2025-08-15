<?php
function findAdminPages($target, $wordlist="wordlists/admin_pages.txt") {
    echo Colors::YELLOW."[🔎] Searching admin pages for $target...\n".Colors::RESET;
    if (!file_exists($wordlist)) {
        echo Colors::RED."[-] Admin page wordlist not found!\n".Colors::RESET;
        return;
    }
    $pages = file($wordlist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = "";
    foreach ($pages as $page) {
        $url = "http://$target/$page";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode < 400) {
            echo Colors::GREEN."   [✅] Found admin page: $url\n".Colors::RESET;
            $found .= "$url\n";
        }
    }
    logResult("admin_pages.log", "[ADMIN][$target]\n".$found);
}
?>