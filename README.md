# EasyNet Data

Hệ thống quản lý dữ liệu viết bằng PHP thuần theo mô hình MVC, phục vụ quản lý dữ liệu sản phẩm, nhà cung cấp và hệ sinh thái ngành.

## Chức năng hiện có

- Đăng nhập và phân quyền `editor`, `viewer`
- Trang tổng quan dữ liệu
- Quản lý sản phẩm, nhà cung cấp, danh mục, đối tác, khách hàng
- Quan hệ sản phẩm `cross-sell` và `up-sell`
- Giao diện tiếng Việt, responsive

## Cấu trúc

- `app/Controllers`: điều hướng nghiệp vụ
- `app/Models`: truy vấn dữ liệu
- `app/Views`: giao diện
- `app/Core`: router, request, response, database
- `config/`: cấu hình ứng dụng và cơ sở dữ liệu
- `database/`: dump và migration SQL còn sử dụng
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
