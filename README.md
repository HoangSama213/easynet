# EasyNet Data

![PHP Check](https://github.com/HoangSama213/easynet/actions/workflows/php-check.yml/badge.svg)

<!-- Khi bật coverage trong CI, dùng badge này:
![Coverage](https://img.shields.io/badge/coverage-pending-lightgrey)
-->

Hệ thống quản lý dữ liệu viết bằng PHP thuần theo mô hình MVC, phục vụ quản lý sản phẩm, nhà cung cấp và hệ sinh thái ngành.

## Chức năng hiện có

- Đăng nhập và phân quyền `editor`, `viewer`
- Trang tổng quan dữ liệu
- Quản lý sản phẩm, nhà cung cấp, danh mục, đối tác, khách hàng
- Giao diện tiếng Việt, responsive
- Hỗ trợ Light / Dark Mode

## Cấu trúc

- `app/Controllers`: điều hướng nghiệp vụ
- `app/Models`: truy vấn dữ liệu
- `app/Views`: giao diện
- `app/Core`: router, request, response, database
- `app/Services`: service dùng cho logic nghiệp vụ có thể test độc lập
- `config/`: cấu hình ứng dụng và cơ sở dữ liệu
- `database/`: dump và migration SQL còn sử dụng
- `tests/`: test tự động tối thiểu
- `tools/php-lint.php`: script kiểm tra cú pháp toàn bộ file PHP
- `public/index.php`: bootstrap ứng dụng

## Cách dựng database

1. Tạo database `easynet` với charset `utf8mb4`.
2. Import file `database/easynet.sql`.
3. Chạy tiếp các file theo thứ tự:
   - `database/create_combo.sql`
   - `database/create_product_hub_extensions.sql`
   - `database/optimize_schema_20260428.sql`
   - `database/product_relation_feature_20260428.sql`
   - `database/add_product_detail_fields_20260428.sql`
4. Kiểm tra kết nối trong `config/database.php` hoặc `.env`.
5. Truy cập `http://localhost/easynet`.

## Tài khoản mẫu hiện tại

- `admin@easynet.vn` / `Admin@123`

## Bộ khung test tối thiểu

### Cài dependency test

```bash
composer install
```

### Chạy kiểm tra cú pháp toàn bộ file PHP

```bash
composer check:php
```

### Chạy PHPUnit

```bash
composer test
```

### Chạy toàn bộ kiểm tra

```bash
composer check
```

## Test hiện có

- `tests/Unit/Services/AuthServiceTest.php`
  - kiểm tra logic đăng nhập hợp lệ
  - kiểm tra tài khoản bị khóa

- `tests/Unit/Middleware/AuthMiddlewareTest.php`
  - kiểm tra đồng bộ session auth
  - kiểm tra helper `is_editor()` và `is_viewer()`

- `tests/Unit/Helpers/BaseUrlTest.php`
  - kiểm tra `base_url()` theo local và cấu hình chuẩn

- `tests/Unit/Helpers/ProjectUrlTest.php`
  - kiểm tra `project_url()` sinh đúng URL nội bộ

- `tests/Unit/Core/RequestTest.php`
  - kiểm tra `Request::uri()` strip đúng base path

- `tests/Integration/AuthFlowTest.php`
  - kiểm tra redirect helper qua HTTP thật
  - kiểm tra guest bị redirect về `/login`
  - kiểm tra `GET /login` render đúng thông báo lỗi
  - kiểm tra `POST /login` redirect thành công
  - kiểm tra `POST /login` sai mật khẩu trả lỗi đúng
  - kiểm tra tài khoản bị khóa ở flow HTTP
  - kiểm tra `logout` xóa session và route bảo vệ lại redirect
