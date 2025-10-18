# SSL Fix for SocialSuite (Windows/XAMPP)

## Nguyên nhân
PHP/cURL trên Windows không có CA bundle nên không xác thực được chứng chỉ của Facebook →
`SSL certificate problem: unable to get local issuer certificate`.

## Cách áp dụng (App-level – không đụng vendor/SDK)
1) Tải CA bundle `cacert.pem` từ cURL: https://curl.se/docs/caextract.html
2) Tạo thư mục `{repo}/certs/` và đặt file vào: `certs/cacert.pem`
3) Copy các file trong gói này vào repo của bạn, giữ nguyên cấu trúc:
   - `bootstrap/ssl.php` (NEW)
   - `scripts/check_ssl.php` (NEW)
   - `patches/2025-10-18_ssl-bootstrap.patch`
4) Áp dụng patch (thêm `require_once` và biến môi trường trong `.env.example`):
   ```bash
   git apply patches/2025-10-18_ssl-bootstrap.patch
   ```
5) Mở `.env` và thêm (hoặc giữ mặc định):
   ```dotenv
   SOCIALSUITE_FEATURE_SSL_CA=true
   SOCIALSUITE_SSL_CA_PATH=certs/cacert.pem
   ```
6) Kiểm tra nhanh:
   ```bash
   php scripts/check_ssl.php
   # Kỳ vọng: HTTP: 200 và không có CURL ERROR
   ```

## Rollback
- Tắt flag: `SOCIALSUITE_FEATURE_SSL_CA=false` trong `.env` hoặc
- Gỡ dòng `require_once __DIR__ . '/../bootstrap/ssl.php';` trong `public/index.php`.

## Ghi chú
- Lỗi cảnh báo "Not secure" trên https://localhost là do chứng chỉ **local**; không liên quan lỗi outbound tới graph.facebook.com. Patch này xử lý outbound.
- Nếu muốn sửa tận gốc toàn hệ thống: set `curl.cainfo` và `openssl.cafile` trong `php.ini`.
