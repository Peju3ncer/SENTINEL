<?php
function checkSSL($target) {
    echo Colors::YELLOW."[🔎] Checking SSL certificate for $target\n".Colors::RESET;

    $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
    $read = @stream_socket_client("ssl://".$target.":443", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);

    if (!$read) {
        echo Colors::RED."[-] SSL connection failed: $errstr ($errno)\n".Colors::RESET;
        return;
    }

    $params = stream_context_get_params($read);
    if (isset($params['options']['ssl']['peer_certificate'])) {
        $certInfo = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

        if ($certInfo) {
            echo Colors::GREEN."[+] SSL Certificate Information:\n".Colors::RESET;
            echo "    Issuer: " . ($certInfo['issuer']['CN'] ?? 'Unknown') . "\n";
            echo "    Subject: " . ($certInfo['subject']['CN'] ?? 'Unknown') . "\n";
            echo "    Valid From: " . date('Y-m-d H:i:s', $certInfo['validFrom_time_t']) . "\n";
            echo "    Valid To: " . date('Y-m-d H:i:s', $certInfo['validTo_time_t']) . "\n";
        } else {
            echo Colors::RED."[-] Failed to parse SSL certificate.\n".Colors::RESET;
        }
    } else {
        echo Colors::RED."[-] No SSL certificate found.\n".Colors::RESET;
    }

    fclose($read);
}
