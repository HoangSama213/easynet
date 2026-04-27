-- Bảng combo chính
CREATE TABLE IF NOT EXISTS `bo_san_pham` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `ma_combo`    VARCHAR(30) NOT NULL UNIQUE,
  `ten_combo`   VARCHAR(255) NOT NULL,
  `mo_ta`       TEXT,
  `gia_le`      DECIMAL(15,2) DEFAULT NULL,
  `gia_combo`   DECIMAL(15,2) DEFAULT NULL,
  `trang_thai`  ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BOM: định mức thành phần combo (combo gồm những sản phẩm chi tiết nào, số lượng bao nhiêu)
CREATE TABLE IF NOT EXISTS `chi_tiet_bo_san_pham` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `combo_id`    INT NOT NULL,
  `chi_tiet_id` INT NOT NULL,
  `so_luong`    INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_chi_tiet_bo_san_pham` (`combo_id`, `chi_tiet_id`),
  CONSTRAINT `fk_chi_tiet_bo_san_pham_combo`    FOREIGN KEY (`combo_id`)    REFERENCES `bo_san_pham`(`id`)            ON DELETE CASCADE,
  CONSTRAINT `fk_chi_tiet_bo_san_pham_chitiet`  FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gắn combo vào chiến dịch marketing
CREATE TABLE IF NOT EXISTS `chien_dich_bo_san_pham` (
  `id`             INT NOT NULL AUTO_INCREMENT,
  `combo_id`       INT NOT NULL,
  `ten_chien_dich` VARCHAR(255) NOT NULL,
  `ghi_chu`        TEXT,
  `bat_dau`        DATE DEFAULT NULL,
  `ket_thuc`       DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_chien_dich_bo_san_pham_combo` FOREIGN KEY (`combo_id`) REFERENCES `bo_san_pham`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
