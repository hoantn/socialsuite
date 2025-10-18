<?php
use Illuminate\Contracts\Console\Kernel;
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
$rec = \App\Models\FacebookToken::query()->latest('id')->first();
if (!$rec) { echo "No facebook_tokens found. Login via /auth/facebook/login first.\n"; exit(1); }
$token = $rec->token;
$url = 'https://graph.facebook.com/v19.0/me?fields=id,name&access_token=' . urlencode($token);
$ch = curl_init($url);
curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>20]);
$out=curl_exec($ch);$err=curl_error($ch);$info=curl_getinfo($ch);curl_close($ch);
$errno = curl_errno($ch);

echo "HTTP: " . ($info['http_code'] ?? 0) . PHP_EOL;
echo "CURL ERRNO: " . $errno . PHP_EOL;
echo "CURL ERROR: " . ($err !== '' ? $err : '(none)') . PHP_EOL;
echo "BODY: " . $out . PHP_EOL;

