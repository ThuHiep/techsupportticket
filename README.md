# TECH_SUPPORT_TICKET

 fix lỗi:

 - Mở cmd và tới thư mục vừa clone về (vd: C:xampp/htdocs/techsupportticket/)
 - Gõ lệnh: composer install
 - Vào PhPStorm vào file .env.example mở dấu '#' trước các trường và sửa thành như sau
 (phải có csdl trên phpmyadmin)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techsupportticket
DB_USERNAME=root
DB_PASSWORD=

 - Gõ lệnh: copy .env.example .env
 - Gõ lệnh: php artisan key:generate
 - Vào file session.php trong thư mục techsupportticket/config/ tìm tới chỗ SESSION_DRIVER sửa database thành file
 - Gõ lệnh: php artisan config:clear
 - Gõ lệnh: php artisan migrate
 - Gõ lệnh: php artisan config:clear
 - Gõ lệnh: php artisan cache:clear
 - Gõ lệnh: php artisan route:clear
 - Cuối cùng: php artisan serve để chạy server hoặc có thể vào xampp chạy admin của Apache(localhost:/techsupportticket/public/)
