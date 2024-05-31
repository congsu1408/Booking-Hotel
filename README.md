

# Booking Hotel

## Giới thiệu

Dự án này chứa mã nguồn của một hệ thống đặt phòng khách sạn. Hệ thống cho phép người dùng duyệt các phòng có sẵn, đặt chỗ và quản lý các đặt phòng.

## Yêu cầu

Trước khi bạn chạy dự án này, hãy đảm bảo rằng bạn đã cài đặt các phần mềm sau trên hệ thống của mình:

- [PHP 7.4+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [Node.js và npm](https://nodejs.org/en/download/)
- [Git](https://git-scm.com/)

## Cài đặt

Thực hiện theo các bước sau để cài đặt và chạy dự án:

### 1. Clone Repository

Đầu tiên, clone repository về máy của bạn bằng cách sử dụng Git.

```bash
git clone https://github.com/congsu1408/Booking-Hotel.git
cd Booking-Hotel
```

### 2. Cài đặt Dependencies

Sử dụng Composer để cài đặt các dependencies của PHP.

```bash
composer install
```

Sau đó, sử dụng npm để cài đặt các dependencies của JavaScript.

```bash
npm install
npm run dev
```

### 3. Tạo File Môi Trường

Tạo file `.env` từ file mẫu `.env.example`.

```bash
cp .env.example .env
```


### 4. Thiết Lập Database

Mở file `.env` và cập nhật các thông tin cấu hình database. Sau đó, chạy các migrations để thiết lập database.

```bash
php artisan migrate
```

### 5. Chạy Server

Cuối cùng, chạy server phát triển của Laravel.

```bash
php artisan serve
```

## Sử dụng

Khi server đang chạy, mở trình duyệt web và truy cập `http://127.0.0.1:8000/` để truy cập hệ thống đặt phòng khách sạn.

## Cấu trúc Dự án

Dưới đây là cấu trúc thư mục của dự án:

```
Booking-Hotel/
│
├── app/                # Thư mục chứa mã nguồn của ứng dụng
├── bootstrap/          # Tập tin bootstrap
├── config/             # Tập tin cấu hình
├── database/           # Migrations và seeder
├── public/             # Thư mục công khai
├── resources/          # Tài nguyên của ứng dụng (views, CSS, JS)
├── routes/             # Tập tin định tuyến
├── storage/            # Thư mục lưu trữ
├── tests/              # Thư mục kiểm thử
├── .env.example        # Tập tin môi trường mẫu
├── artisan             # Tập tin Artisan CLI
├── composer.json       # Tập tin Composer
├── package.json        # Tập tin npm
├── webpack.mix.js      # Tập tin cấu hình Laravel Mix
└── ...
```

## Đóng góp

Nếu bạn muốn đóng góp cho dự án này, vui lòng làm theo các bước sau:

1. Fork repository
2. Tạo một branch mới (`git checkout -b feature-branch`)
3. Thực hiện các thay đổi của bạn
4. Commit các thay đổi (`git commit -am 'Thêm tính năng mới'`)
5. Push lên branch (`git push origin feature-branch`)
6. Tạo một Pull Request mới

## Giấy phép

Dự án này được cấp phép dưới giấy phép MIT. Xem tập tin [LICENSE](LICENSE) để biết thêm chi tiết.

---
