<?php
$ch = curl_init('https://graph.facebook.com');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 10,
]);
$out = curl_exec($ch);
var_dump($out, curl_errno($ch), curl_error($ch));
curl_close($ch);
