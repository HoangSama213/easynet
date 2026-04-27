CREATE TABLE IF NOT EXISTS `thong_bao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `chi_tiet_id` INT DEFAULT NULL,
  `loai_thong_bao` VARCHAR(50) NOT NULL,
  `muc_do` ENUM('critical','warning','info','success') NOT NULL DEFAULT 'info',
  `tieu_de` VARCHAR(255) NOT NULL,
  `noi_dung` TEXT NOT NULL,
  `ton_kho_hien_tai` INT DEFAULT NULL,
  `trang_thai` ENUM('chua_xem','da_xem') NOT NULL DEFAULT 'chua_xem',
  `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_thong_bao_trang_thai` (`trang_thai`),
  KEY `idx_thong_bao_ngay_tao` (`ngay_tao`),
  KEY `idx_thong_bao_chi_tiet_id` (`chi_tiet_id`),
  UNIQUE KEY `uq_thong_bao_loai_chi_tiet` (`loai_thong_bao`, `chi_tiet_id`),
  CONSTRAINT `fk_thong_bao_chi_tiet`
    FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
