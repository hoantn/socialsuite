// file test.php
<?php
$ch = curl_init('https://graph.facebook.com');
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>1, CURLOPT_TIMEOUT=>10]);
var_dump(curl_exec($ch), curl_error($ch));
