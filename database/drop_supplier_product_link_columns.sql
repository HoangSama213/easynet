ALTER TABLE san_pham_chi_tiet
    DROP FOREIGN KEY fk_spct_ncc,
    DROP FOREIGN KEY fk_spct_san_pham,
    DROP INDEX idx_spct_ncc_id,
    DROP INDEX idx_spct_san_pham_id,
    DROP COLUMN ncc_id,
    DROP COLUMN san_pham_id;
