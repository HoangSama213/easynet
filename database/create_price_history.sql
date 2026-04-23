CREATE TABLE IF NOT EXISTS lich_su_gia_san_pham (
    id INT NOT NULL AUTO_INCREMENT,
    ncc_id INT NOT NULL,
    chi_tiet_id INT NOT NULL,
    gia DECIMAL(15,2) NULL,
    ghi_chu VARCHAR(255) NULL,
    thoi_gian_thay_doi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_lich_su_gia_san_pham_ncc_chi_tiet (ncc_id, chi_tiet_id),
    CONSTRAINT fk_lich_su_gia_ncc FOREIGN KEY (ncc_id) REFERENCES nha_cung_cap (id) ON DELETE CASCADE,
    CONSTRAINT fk_lich_su_gia_chi_tiet FOREIGN KEY (chi_tiet_id) REFERENCES san_pham_chi_tiet (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
