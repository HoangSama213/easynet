ALTER TABLE san_pham_chi_tiet
    ADD COLUMN hinh_anh_san_pham VARCHAR(500) NULL AFTER thuong_hieu_id;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN bao_hanh VARCHAR(255) NULL AFTER hinh_anh_san_pham;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN mo_ta_ngan TEXT NULL AFTER bao_hanh;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN dac_diem LONGTEXT NULL AFTER mo_ta_ngan;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN thong_so_ky_thuat LONGTEXT NULL AFTER dac_diem;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN tinh_nang LONGTEXT NULL AFTER thong_so_ky_thuat;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN giai_phap_lien_quan VARCHAR(500) NULL AFTER tinh_nang;

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN du_an_lien_quan VARCHAR(500) NULL AFTER giai_phap_lien_quan;
