<?php
function whoisLookup($domain) {
    echo Colors::YELLOW."[🔎] WHOIS Lookup for $domain\n".Colors::RESET;

    // Coba WHOIS lokal
    $output = shell_exec("env LD_PRELOAD= whois $domain 2>&1");

    // Jika gagal, pakai RDAP API
    if (!$output || stripos($output, "No match") !== false || stripos($output, "not found") !== false) {
        echo Colors::YELLOW."[!] Local WHOIS failed, trying online API...\n".Colors::RESET;

        $apiUrl = "https://rdap.org/domain/" . urlencode($domain);
        $apiOutput = @file_get_contents($apiUrl);

        if ($apiOutput) {
            $data = json_decode($apiOutput, true);
            if ($data) {
                // Format RDAP ke WHOIS-like
                $formatted = "";
                $formatted .= "Domain Name: " . ($data['ldhName'] ?? $domain) . "\n";
                if (!empty($data['events'])) {
                    foreach ($data['events'] as $event) {
                        $formatted .= ucfirst($event['eventAction']) . ": " . $event['eventDate'] . "\n";
                    }
                }
                if (!empty($data['nameservers'])) {
                    foreach ($data['nameservers'] as $ns) {
                        $formatted .= "Name Server: " . $ns['ldhName'] . "\n";
                    }
                }
                if (!empty($data['entities'])) {
                    foreach ($data['entities'] as $entity) {
                        $formatted .= "Registrar: " . ($entity['vcardArray'][1][1][3] ?? '') . "\n";
                        foreach ($entity['vcardArray'][1] as $v) {
                            if ($v[0] === "email") {
                                $formatted .= "Registrar Email: " . $v[3] . "\n";
                            }
                            if ($v[0] === "tel") {
                                $formatted .= "Registrar Phone: " . $v[3] . "\n";
                            }
                        }
                    }
                }
                $output = $formatted;
            }
        }
    }

    if ($output) {
        echo Colors::GREEN.$output.Colors::RESET;
        logResult("whois.log", "[WHOIS][$domain] ".$output);
    } else {
        echo Colors::RED."[-] WHOIS lookup failed!\n".Colors::RESET;
    }
}
?>
