<?php
// [SOCIALSUITE][GPT][2025-10-18 15:45 +07] TEST: verify TLS + call /me with token

echo "curl.cainfo = " . ini_get('curl.cainfo') . PHP_EOL;
echo "openssl.cafile = " . ini_get('openssl.cafile') . PHP_EOL;

$token = getenv('FB_TEST_ACCESS_TOKEN'); // đặt trong .env
$url = 'https://graph.facebook.com/v19.0/me?fields=id,name&access_token=' . urlencode($token);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
]);
$out  = curl_exec($ch);
$err  = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "HTTP: " . ($info['http_code'] ?? 0) . PHP_EOL;
echo "CURL ERROR: " . ($err ?: '(none)') . PHP_EOL;
echo "BODY: " . $out . PHP_EOL;
