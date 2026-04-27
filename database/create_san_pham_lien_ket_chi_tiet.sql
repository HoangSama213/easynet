CREATE TABLE IF NOT EXISTS san_pham_lien_ket_chi_tiet (
    san_pham_id INT NOT NULL,
    chi_tiet_id INT NOT NULL,
    PRIMARY KEY (san_pham_id, chi_tiet_id),
    UNIQUE KEY uq_san_pham_lien_ket_chi_tiet_chi_tiet_id (chi_tiet_id),
    KEY idx_san_pham_lien_ket_chi_tiet_chi_tiet_id (chi_tiet_id),
    CONSTRAINT fk_splkct_san_pham
        FOREIGN KEY (san_pham_id) REFERENCES san_pham_ncc (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_splkct_chi_tiet
        FOREIGN KEY (chi_tiet_id) REFERENCES san_pham_chi_tiet (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
