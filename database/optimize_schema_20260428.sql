SET NAMES utf8mb4;

DELETE FROM he_sinh_thai_dong
WHERE section IN ('co-dien-dien-nhe', 'ha-tang-ict', 'smart-solution');

INSERT INTO he_sinh_thai_dong (section, table_name, group_name, label, sort_order) VALUES
('co-dien-dien-nhe', 'he_thong_dien_cong_nghiep_dan_dung', 'Cơ điện', 'Hệ thống điện công nghiệp & dân dụng', 10),
('co-dien-dien-nhe', 'he_thong_cap_thoat_nuoc', 'Cơ điện', 'Hệ thống cấp thoát nước', 20),
('co-dien-dien-nhe', 'he_thong_dieu_hoa_thong_gio', 'Cơ điện', 'Hệ thống điều hòa thông gió', 30),
('co-dien-dien-nhe', 'tu_mang_vat_tu_vien_thong', 'Điện nhẹ', 'Tủ mạng và vật tư viễn thông', 110),
('co-dien-dien-nhe', 'cap_vat_tu_day_tin_hieu', 'Điện nhẹ', 'Cáp, vật tư dây tín hiệu', 120),
('co-dien-dien-nhe', 'thiet_bi_ket_noi_tin_hieu', 'Điện nhẹ', 'Thiết bị kết nối tín hiệu', 130),
('co-dien-dien-nhe', 'he_thong_an_ninh_giam_sat', 'Điện nhẹ', 'Hệ thống an ninh - giám sát điện tử', 140),
('co-dien-dien-nhe', 'he_thong_kiem_soat_truy_cap', 'Điện nhẹ', 'Hệ thống kiểm soát truy cập', 150),
('co-dien-dien-nhe', 'he_thong_thong_tin_lien_lac', 'Điện nhẹ', 'Hệ thống thông tin liên lạc', 160),
('co-dien-dien-nhe', 'he_thong_am_thanh_trinh_chieu', 'Điện nhẹ', 'Hệ thống âm thanh trình chiếu', 170),
('co-dien-dien-nhe', 'he_thong_canh_bao', 'Điện nhẹ', 'Hệ thống cảnh báo', 180),
('co-dien-dien-nhe', 'he_thong_dieu_khien_tu_dong_hoa', 'Điện nhẹ', 'Hệ thống điều khiển - tự động hóa', 190),
('ha-tang-ict', 'kenh_truyen_dan_toc_do_cao', 'Hạ tầng ICT', 'Kênh truyền dẫn mạng viễn thông tốc độ cao', 10),
('ha-tang-ict', 'ha_tang_cntt_thue_ngoai', 'Hạ tầng ICT', 'Hạ tầng CNTT thuê ngoài', 20),
('ha-tang-ict', 'trung_tam_du_lieu', 'Hạ tầng ICT', 'Trung tâm dữ liệu', 30),
('ha-tang-ict', 'thiet_bi_phan_cung', 'Hạ tầng ICT', 'Thiết bị phần cứng', 40),
('ha-tang-ict', 'phan_mem', 'Hạ tầng ICT', 'Phần mềm', 50),
('ha-tang-ict', 'mang', 'Hạ tầng ICT', 'Mạng', 60),
('ha-tang-ict', 'du_lieu_luu_tru', 'Hạ tầng ICT', 'Dữ liệu & Lưu trữ', 70),
('ha-tang-ict', 'bao_mat', 'Hạ tầng ICT', 'Bảo mật', 80),
('ha-tang-ict', 'thiet_bi_tin_hoc_van_phong', 'Hạ tầng ICT', 'Thiết bị tin học văn phòng', 90),
('smart-solution', 'smart_home', 'Smart Solution', 'Smart Home', 10),
('smart-solution', 'smart_office', 'Smart Solution', 'Smart Office', 20);

ALTER TABLE tai_nguyen_san_pham
    ADD COLUMN IF NOT EXISTS ma_tai_nguyen VARCHAR(30) NULL AFTER id,
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

UPDATE tai_nguyen_san_pham tsp
LEFT JOIN tai_nguyen_media tm
    ON tm.chi_tiet_id = tsp.chi_tiet_id
   AND (
        (tm.loai = 'anh' AND tsp.media_type = 'image')
        OR (tm.loai = 'video' AND tsp.media_type = 'video')
        OR (tm.loai = 'pdf' AND tsp.media_type = 'pdf')
   )
SET
    tsp.ma_tai_nguyen = COALESCE(tsp.ma_tai_nguyen, tm.ma_tai_nguyen),
    tsp.title = CASE tsp.media_type
        WHEN 'image' THEN 'Nhập ảnh'
        WHEN 'video' THEN 'Nhập video'
        WHEN 'pdf' THEN 'Nhập file'
        ELSE tsp.title
    END,
    tsp.updated_at = COALESCE(tm.updated_at, tsp.created_at);

INSERT INTO tai_nguyen_san_pham (
    ma_tai_nguyen,
    chi_tiet_id,
    media_type,
    title,
    media_url,
    is_primary,
    sort_order,
    created_at,
    updated_at
)
SELECT
    tm.ma_tai_nguyen,
    tm.chi_tiet_id,
    CASE tm.loai
        WHEN 'anh' THEN 'image'
        WHEN 'video' THEN 'video'
        WHEN 'pdf' THEN 'pdf'
    END AS media_type,
    CASE tm.loai
        WHEN 'anh' THEN 'Nhập ảnh'
        WHEN 'video' THEN 'Nhập video'
        WHEN 'pdf' THEN 'Nhập file'
    END AS title,
    tm.url,
    0 AS is_primary,
    CASE tm.loai
        WHEN 'anh' THEN 0
        WHEN 'video' THEN 1
        WHEN 'pdf' THEN 2
    END AS sort_order,
    tm.created_at,
    tm.updated_at
FROM tai_nguyen_media tm
LEFT JOIN tai_nguyen_san_pham tsp
    ON tsp.chi_tiet_id = tm.chi_tiet_id
   AND tsp.media_type = CASE tm.loai
        WHEN 'anh' THEN 'image'
        WHEN 'video' THEN 'video'
        WHEN 'pdf' THEN 'pdf'
    END
WHERE tsp.id IS NULL;

UPDATE tai_nguyen_san_pham
SET ma_tai_nguyen = CONCAT('TNM-', LPAD(id, 6, '0'))
WHERE ma_tai_nguyen IS NULL OR ma_tai_nguyen = '';

ALTER TABLE tai_nguyen_san_pham
    ADD UNIQUE KEY uq_tai_nguyen_san_pham_ma_tai_nguyen (ma_tai_nguyen),
    ADD UNIQUE KEY uq_tai_nguyen_san_pham_chi_tiet_media_type (chi_tiet_id, media_type),
    ADD KEY idx_tai_nguyen_san_pham_chi_tiet_media_type (chi_tiet_id, media_type);

DROP TABLE IF EXISTS tai_nguyen_media;
DROP TABLE IF EXISTS ncc_lien_ket_chi_tiet;
DROP TABLE IF EXISTS ncc_lien_ket_san_pham;
DROP TABLE IF EXISTS ncc_lien_ket_thuong_hieu;
DROP TABLE IF EXISTS san_pham_lien_ket_chi_tiet;
DROP TABLE IF EXISTS ncc_san_pham_chi_tiet_backup;
DROP TABLE IF EXISTS san_pham_chi_tiet_backup;
