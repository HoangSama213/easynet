CREATE TABLE IF NOT EXISTS ncc_san_pham_chi_tiet (
    id INT NOT NULL AUTO_INCREMENT,
    ma_san_pham VARCHAR(30) NOT NULL,
    ncc_id INT NOT NULL,
    san_pham_id INT DEFAULT NULL,
    chi_tiet_id INT NOT NULL,
    ma_sku VARCHAR(50) DEFAULT NULL,
    thuong_hieu VARCHAR(255) DEFAULT NULL,
    gia DECIMAL(15,2) DEFAULT NULL,
    trang_thai ENUM('dang_ban','ngung') DEFAULT NULL,
    ton_kho INT DEFAULT NULL,
    ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_ncc_san_pham_chi_tiet_ma_san_pham (ma_san_pham),
    UNIQUE KEY uq_ncc_san_pham_chi_tiet_ncc_chi_tiet (ncc_id, chi_tiet_id),
    KEY idx_ncc_san_pham_chi_tiet_ncc_id (ncc_id),
    KEY idx_ncc_san_pham_chi_tiet_san_pham_id (san_pham_id),
    KEY idx_ncc_san_pham_chi_tiet_chi_tiet_id (chi_tiet_id),
    CONSTRAINT fk_nspct_ncc
        FOREIGN KEY (ncc_id) REFERENCES nha_cung_cap (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_nspct_san_pham
        FOREIGN KEY (san_pham_id) REFERENCES san_pham_ncc (id)
        ON DELETE SET NULL,
    CONSTRAINT fk_nspct_chi_tiet
        FOREIGN KEY (chi_tiet_id) REFERENCES san_pham_chi_tiet (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
