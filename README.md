<p align="center">
  <a href="https://www.uit.edu.vn/" title="Trường Đại học Công nghệ Thông tin" style="border: none;">
    <img src="https://i.imgur.com/WmMnSRt.png" alt="Trường Đại học Công nghệ Thông tin | University of Information Technology">
  </a>
</p>



## GIỚI THIỆU ĐỒ ÁN

-    **Đề tài:** Website Mắt kính thời trang - LENSART EYEWEAR - Backend
-    **Repository BACKEND:** https://github.com/hungtd412/lensart_eyewear_backend
-    **Repository FRONTEND:** https://github.com/ngbahung/client_lensart

## CÔNG NGHỆ SỬ DỤNG

-    **Backend:** [Laravel PHP](https://laravel.com/), 
-    **Frontend:** [HTML](https://developer.mozilla.org/en-US/docs/Web/HTML), [TailwindCSS](https://tailwindcss.com/docs/installation), [JavaScript](https://www.javascript.com/), [ReactJS](https://reactjs.org/)
-    **Database:** [MySQL](https://www.mysql.com/)

## THÀNH VIÊN NHÓM

| STT | MSSV     | Họ và Tên            | GitHub                            | Email                  |
| :-- | :------- | :------------------- | :-------------------------------- | :--------------------- |
| 1   | 22520512 | Nguyễn Bá Hưng         | https://github.com/ngbahung       | 22520512@gm.uit.edu.vn |
| 2   | 22520525 | Trần Đức Hùng       | https://github.com/hungtd412        | 22520525@gm.uit.edu.vn |
| 3   | 22520430 | Nguyễn Thị Trinh  | https://github.com/trinhtrinh342004    | 22521539@gm.uit.edu.vn |
| 4   | 21522609 | Đào Trung Hiếu       | https://github.com/TrunHiuu   | 22520430@gm.uit.edu.vn |

# HƯỚNG DẪN CÀI ĐẶT

## 1. Hướng dẫn chạy Client:
```bash
npm install
npm run dev
```

## 2. Hướng dẫn chạy Server:

### B1: Đảm bảo đã cài đặt Composer
Nếu chưa có, tải về và cài đặt tại: [https://getcomposer.org/](https://getcomposer.org/)

### B2: Tạo tệp `.env` từ file mẫu `.env.example`
```bash
cp .env.example .env
```

### B3: Mở tệp `.env` và cấu hình thông tin database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### B4: Tạo khóa ứng dụng
```bash
php artisan key:generate
```

### B5: Tạo bảng
```bash
php artisan migrate
```

### B6: Chèn dữ liệu giả (chạy file seeder)
```bash
php artisan db:seed
```

### B7: Bật MySQL Workbench, chạy file SQL `script-seed-data-lensart.sql`

### B8: Cấp quyền thư mục lưu trữ và bộ nhớ cache
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### B9: Chạy ứng dụng
```bash
php artisan serve

