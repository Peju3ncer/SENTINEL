<?php
function detectCMS($target) {
    echo Colors::YELLOW."[🔎] Detecting CMS for $target...\n".Colors::RESET;
    $cms_patterns = [
        "WordPress" => "/wp-content/",
        "Joomla" => "/administrator/",
        "Drupal" => "/sites/all/"
    ];
    $found = "";
    foreach ($cms_patterns as $cms => $path) {
        $url = "http://$target$path";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode < 400) {
            echo Colors::GREEN."   [✅] CMS Detected: $cms\n".Colors::RESET;
            $found = $cms;
            break;
        }
    }
    if(!$found) echo Colors::RED."[-] CMS not detected\n".Colors::RESET;
    logResult("cms.log", "[CMS][$target] ".($found ?: "Not detected"));
}
?>