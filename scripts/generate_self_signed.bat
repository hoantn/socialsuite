@echo off
set SSL_DIR=D:\xampp\apache\ssl
if not exist "%SSL_DIR%" mkdir "%SSL_DIR%"
cd /d "%SSL_DIR%"
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout localhost.key -out localhost.crt -subj "/C=VN/ST=Local/L=Local/O=Local/OU=Dev/CN=localhost"
echo Created: %SSL_DIR%\localhost.crt and localhost.key
pause
