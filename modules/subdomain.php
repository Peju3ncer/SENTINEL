<?php
function findSubdomains($target, $wordlist="wordlists/subdomains.txt") {
    echo Colors::YELLOW."[🔎] Searching subdomains for $target...\n".Colors::RESET;
    if (!file_exists($wordlist)) {
        echo Colors::RED."[-] Subdomain wordlist not found!\n".Colors::RESET;
        return;
    }
    $subs = file($wordlist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = "";
    foreach ($subs as $sub) {
        $url = "http://$sub.$target";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode < 400) {
            echo Colors::GREEN."   [✅] Found: $url\n".Colors::RESET;
            $found .= "$url\n";
        }
    }
    logResult("subdomains.log", "[SUBDOMAIN][$target]\n".$found);
}
?>