<?php
function sqliScanner($target) {
    echo Colors::YELLOW."[🔎] SQL Injection scan for $target\n".Colors::RESET;
    $payloads = ["'", "\"", " OR 1=1", " OR '1'='1"];
    $found = "";
    foreach ($payloads as $p) {
        $url = "http://$target/?id=".$p;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $resp = curl_exec($ch);
        curl_close($ch);
        if (stripos($resp, "mysql")!==false || stripos($resp, "syntax")!==false) {
            echo Colors::GREEN."   [✅] SQL Injection detected! Payload: $p\n".Colors::RESET;
            $found = $p;
            break;
        }
    }
    if(!$found) echo Colors::RED."[-] No SQL Injection found\n".Colors::RESET;
    logResult("sqli.log", "[SQLI][$target] ".($found ?: "Not detected"));
}
?>