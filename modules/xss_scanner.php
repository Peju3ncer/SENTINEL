<?php
function xssScanner($target) {
    echo Colors::YELLOW."[🔎] XSS scan for $target\n".Colors::RESET;
    $payloads = ["<script>alert(1)</script>", "'><script>alert(1)</script>"];
    $found = "";
    foreach ($payloads as $p) {
        $url = "http://$target/?q=".$p;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, $p)!==false) {
            echo Colors::GREEN."   [✅] XSS detected! Payload: $p\n".Colors::RESET;
            $found = $p;
            break;
        }
    }
    if(!$found) echo Colors::RED."[-] No XSS vulnerability found\n".Colors::RESET;
    logResult("xss.log", "[XSS][$target] ".($found ?: "Not detected"));
}
?>