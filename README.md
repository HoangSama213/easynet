# EasyNet Data

Hệ thống quản lý dữ liệu viết bằng PHP thuần theo mô hình MVC, phục vụ thay thế lưu trữ Excel thủ công bằng một giao diện tập trung, phân loại dữ liệu rõ ràng và hỗ trợ nhiều người dùng.

## Chức năng hiện có

- Đăng nhập và phân quyền `admin`, `editor`, `viewer`
- Trang tổng quan thống kê theo 6 nhóm dữ liệu
- Quản lý danh mục dữ liệu với tìm kiếm, lọc, thêm, sửa, xóa
- Giao diện tiếng Việt, responsive, chạy trên Apache/PHP thông thường

## Cấu trúc

- `app/Controllers`: điều hướng nghiệp vụ
- `app/Models`: truy vấn dữ liệu
- `app/Views`: giao diện
- `app/Core`: router, request, response, database
- `config/`: cấu hình ứng dụng và cơ sở dữ liệu
- `database/schema.sql`: cấu trúc bảng và dữ liệu mẫu
- `public/index.php`: bootstrap ứng dụng

## Cách chạy

1. Tạo database và import file `database/schema.sql`.
2. Sửa kết nối trong `config/database.php` nếu cần.
3. Truy cập `http://localhost/easynet`.

## Tài khoản mẫu

- `admin@easynet.local` / `password`
- `editor@easynet.local` / `password`
- `viewer@easynet.local` / `password`
