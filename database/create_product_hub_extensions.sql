CREATE TABLE IF NOT EXISTS `tai_nguyen_san_pham` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `chi_tiet_id` INT NOT NULL,
  `media_type` ENUM('image','video','pdf','link') NOT NULL DEFAULT 'image',
  `title` VARCHAR(255) NOT NULL,
  `media_url` VARCHAR(500) NOT NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tai_nguyen_san_pham_chi_tiet_id` (`chi_tiet_id`),
  CONSTRAINT `fk_tai_nguyen_san_pham_chi_tiet`
    FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `noi_dung_san_pham` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `chi_tiet_id` INT NOT NULL,
  `content_type` ENUM('mo_ta','sales_kit','huong_dan','marketing_note') NOT NULL DEFAULT 'mo_ta',
  `title` VARCHAR(255) NOT NULL,
  `content_body` LONGTEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_noi_dung_san_pham_chi_tiet_id` (`chi_tiet_id`),
  CONSTRAINT `fk_noi_dung_san_pham_chi_tiet`
    FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `lien_ket_san_pham` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `source_chi_tiet_id` INT NOT NULL,
  `target_chi_tiet_id` INT NOT NULL,
  `relation_type` ENUM('cross_sell','up_sell') NOT NULL,
  `relation_score` INT NOT NULL DEFAULT 1,
  `note` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lien_ket_san_pham` (`source_chi_tiet_id`, `target_chi_tiet_id`, `relation_type`),
  KEY `idx_lien_ket_san_pham_target` (`target_chi_tiet_id`),
  CONSTRAINT `fk_lien_ket_san_pham_source`
    FOREIGN KEY (`source_chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_lien_ket_san_pham_target`
    FOREIGN KEY (`target_chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `bao_gia_bo_san_pham` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `combo_id` INT NOT NULL,
  `proposal_title` VARCHAR(255) NOT NULL,
  `proposal_body` LONGTEXT NOT NULL,
  `customer_name` VARCHAR(255) DEFAULT NULL,
  `customer_channel` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bao_gia_bo_san_pham_combo_id` (`combo_id`),
  CONSTRAINT `fk_bao_gia_bo_san_pham_combo`
    FOREIGN KEY (`combo_id`) REFERENCES `bo_san_pham`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
