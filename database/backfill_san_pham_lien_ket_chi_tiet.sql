INSERT INTO san_pham_lien_ket_chi_tiet (san_pham_id, chi_tiet_id)
SELECT
    MIN(sp.san_pham_id) AS san_pham_id,
    lk.chi_tiet_id
FROM ncc_lien_ket_chi_tiet lk
INNER JOIN ncc_lien_ket_san_pham sp ON sp.ncc_id = lk.ncc_id
GROUP BY lk.chi_tiet_id
HAVING COUNT(DISTINCT sp.san_pham_id) = 1
ON DUPLICATE KEY UPDATE san_pham_id = VALUES(san_pham_id);
