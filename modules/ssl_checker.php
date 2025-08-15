<?php
function checkSSL($target) {
    echo Colors::YELLOW."[🔎] Checking SSL certificate for $target\n".Colors::RESET;
    
    $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
    $read = @stream_socket_client("ssl://".$target.":443", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);

    if (!$read) {
        echo Colors::RED."[-] SSL connection failed: $errstr ($errno)\n".Colors::RESET;
        return;
    }

    $params = stream_context_get_params($read