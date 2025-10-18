<?php
/**
 * [SOCIALSUITE][GPT][2025-10-18 15:30 +07]
 * CHANGE: Bootstrap CA bundle cho cURL/OpenSSL (Windows) để fix lỗi SSL với Facebook Graph API.
 * WHY: Tránh "SSL certificate problem: unable to get local issuer certificate" khi gọi graph.facebook.com.
 * IMPACT: Toàn app khi bật flag, không chạm vào vendor/facebook/graph-sdk.
 * TEST: php scripts/check_ssl.php → HTTP 200; gọi /me/accounts trả về 200.
 * ROLLBACK: Set SOCIALSUITE_FEATURE_SSL_CA=false hoặc bỏ require file này trong public/index.php.
 */
$enable = getenv('SOCIALSUITE_FEATURE_SSL_CA') ?? 'true'; // bật mặc định, có thể tắt qua .env
if (strtolower($enable) === 'true') {
    $defaultCa = __DIR__ . '/../certs/cacert.pem';
    $ca = getenv('SOCIALSUITE_SSL_CA_PATH') ?: $defaultCa;

    if (is_file($ca)) {
        // Cho cURL (extension) và OpenSSL (streams)
        @ini_set('curl.cainfo', $ca);               // [SOCIALSUITE][GPT] WHY: trỏ cURL tới CA bundle
        @ini_set('openssl.cafile', $ca);            // [SOCIALSUITE][GPT] WHY: trỏ OpenSSL tới CA bundle

        // Một số lib đọc biến môi trường này
        if (!getenv('SSL_CERT_FILE')) {
            putenv("SSL_CERT_FILE=$ca");
        }

        // Cho các stream context (file_get_contents, v.v.)
        stream_context_set_default([ 'ssl' => [
            'cafile' => $ca,
            'verify_peer' => true,
            'verify_peer_name' => true,
        ]]);

        // [SOCIALSUITE][GPT] Optional: xác nhận (log ra error_log)
        error_log('[SOCIALSUITE][GPT] SSL CA in use: ' . $ca);
    } else {
        error_log('[SOCIALSUITE][GPT][WARN] Missing CA file at: ' . $ca);
    }
}
