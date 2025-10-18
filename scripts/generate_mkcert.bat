@echo off
set SSL_DIR=D:\xampp\apache\ssl
if not exist "%SSL_DIR%" mkdir "%SSL_DIR%"
cd /d "%SSL_DIR%"
mkcert -install
mkcert localhost
echo Created: localhost.pem and localhost-key.pem in %SSL_DIR%
pause
