ALTER TABLE lien_ket_san_pham
    ADD COLUMN gia_mua_kem DECIMAL(15,2) NULL AFTER note;

ALTER TABLE lien_ket_san_pham
    ADD COLUMN so_luong_toi_da INT NOT NULL DEFAULT 1 AFTER gia_mua_kem;

ALTER TABLE lien_ket_san_pham
    ADD COLUMN chi_kich_hoat_khi_con_hang TINYINT(1) NOT NULL DEFAULT 0 AFTER so_luong_toi_da;
