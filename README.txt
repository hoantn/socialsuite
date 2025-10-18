PATCH CHO SOCIALSUITE (XAMPP + MySQL)

1) Giải nén zip này đè vào D:\xampp\htdocs\socialsuite
2) Import 'socialsuite.sql' vào phpMyAdmin (DB: socialsuite)
3) Chạy 'apply_patch.bat' (sẽ loại bỏ Pail/Sail provider nếu còn + dọn cache)
4) Mở http://localhost/socialsuite/public

Đăng nhập admin:
- username: admin
- password: 123456

APP_KEY đã set sẵn trong .env: base64:/W7EcGtZbcpt6USqiQ50O9gBmP0RtWiv6Q84glrA85A=
