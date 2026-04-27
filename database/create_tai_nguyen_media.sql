CREATE TABLE IF NOT EXISTS `tai_nguyen_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ma_tai_nguyen` VARCHAR(30) NOT NULL,
  `chi_tiet_id` INT NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `loai` ENUM('anh','video','pdf') NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tai_nguyen_media_ma` (`ma_tai_nguyen`),
  UNIQUE KEY `uq_tai_nguyen_media_chi_tiet_loai` (`chi_tiet_id`, `loai`),
  KEY `idx_tai_nguyen_media_chi_tiet_id` (`chi_tiet_id`),
  CONSTRAINT `fk_tai_nguyen_media_chi_tiet`
    FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tai_nguyen_media` (`ma_tai_nguyen`, `chi_tiet_id`, `url`, `loai`, `created_at`, `updated_at`)
SELECT
  CONCAT('TNM-', LPAD(CAST(tsp.id AS CHAR), 6, '0')) AS ma_tai_nguyen,
  tsp.chi_tiet_id,
  tsp.media_url,
  CASE tsp.media_type
    WHEN 'image' THEN 'anh'
    WHEN 'video' THEN 'video'
    WHEN 'pdf' THEN 'pdf'
  END AS loai,
  COALESCE(tsp.created_at, NOW()) AS created_at,
  NOW() AS updated_at
FROM `tai_nguyen_san_pham` tsp
WHERE tsp.media_type IN ('image', 'video', 'pdf')
  AND COALESCE(tsp.media_url, '') <> ''
ON DUPLICATE KEY UPDATE
  `url` = VALUES(`url`),
  `updated_at` = NOW();
