<?php
function whoisLookup($domain) {
    echo Colors::YELLOW."[🔎] WHOIS Lookup for $domain\n".Colors::RESET;
    $output = shell_exec("whois $domain 2>&1");
    if ($output) {
        echo Colors::GREEN.$output.Colors::RESET;
        logResult("whois.log", "[WHOIS][$domain] ".$output);
    } else {
        echo Colors::RED."[-] WHOIS lookup failed!\n".Colors::RESET;
    }
}
?>