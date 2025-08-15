<?php
function portScan($target, $ports = [21,22,23,25,53,80,443,3306,8080]) {
    echo Colors::YELLOW."[🔎] Scanning ports on $target...\n".Colors::RESET;
    $result = "";
    foreach ($ports as $port) {
        $connection = @fsockopen($target, $port, $errno, $errstr, 1);
        if ($connection) {
            echo Colors::GREEN."   [✅] Port $port is open!\n".Colors::RESET;
            $result .= "Port $port: OPEN\n";
            fclose($connection);
        } else {
            echo Colors::RED."   [❌] Port $port closed\n".Colors::RESET;
            $result .= "Port $port: CLOSED\n";
        }
    }
    logResult("ports.log", "[PORTSCAN][$target] ".$result);
}
?>