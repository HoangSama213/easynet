USE `easynet`;

DROP TABLE IF EXISTS `san_pham_chi_tiet_backup`;
CREATE TABLE `san_pham_chi_tiet_backup`
AS SELECT * FROM `san_pham_chi_tiet`;

DROP TABLE IF EXISTS `ncc_san_pham_chi_tiet_backup`;
CREATE TABLE `ncc_san_pham_chi_tiet_backup`
AS SELECT * FROM `ncc_san_pham_chi_tiet`;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_von'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `gia_von`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_ban_le'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `gia_ban_le`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_si'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `gia_si`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `gia`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'ton_kho'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `ton_kho`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'trang_thai'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `trang_thai`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'thuong_hieu'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `san_pham_chi_tiet` DROP COLUMN `thuong_hieu`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'thuong_hieu_id'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `san_pham_chi_tiet` ADD COLUMN `thuong_hieu_id` INT DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @fk_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`KEY_COLUMN_USAGE`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'thuong_hieu_id'
      AND `REFERENCED_TABLE_NAME` = 'thuong_hieu'
      AND `REFERENCED_COLUMN_NAME` = 'id'
);
SET @sql := IF(
    @fk_exists = 0,
    'ALTER TABLE `san_pham_chi_tiet` ADD CONSTRAINT `fk_san_pham_chi_tiet_thuong_hieu` FOREIGN KEY (`thuong_hieu_id`) REFERENCES `thuong_hieu`(`id`) ON DELETE SET NULL',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'thuong_hieu'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` DROP COLUMN `thuong_hieu`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'ma_sku'
);
SET @sql := IF(@col_exists > 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` DROP COLUMN `ma_sku`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_von'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` ADD COLUMN `gia_von` DECIMAL(15,2) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_ban_le'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` ADD COLUMN `gia_ban_le` DECIMAL(15,2) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'gia_si'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` ADD COLUMN `gia_si` DECIMAL(15,2) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'ton_kho'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` ADD COLUMN `ton_kho` INT DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = 'easynet'
      AND `TABLE_NAME` = 'ncc_san_pham_chi_tiet'
      AND `COLUMN_NAME` = 'trang_thai'
);
SET @sql := IF(@col_exists = 0, 'ALTER TABLE `ncc_san_pham_chi_tiet` ADD COLUMN `trang_thai` ENUM(''dang_ban'',''ngung'') DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
