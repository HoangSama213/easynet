ALTER TABLE `san_pham_chi_tiet`
    ADD COLUMN IF NOT EXISTS `mau_sac` VARCHAR(150) NULL AFTER `thuong_hieu`,
    ADD COLUMN IF NOT EXISTS `kich_thuoc` VARCHAR(150) NULL AFTER `mau_sac`,
    ADD COLUMN IF NOT EXISTS `cong_dung_chinh` TEXT NULL AFTER `kich_thuoc`,
    ADD COLUMN IF NOT EXISTS `doi_tuong_khach_hang` VARCHAR(255) NULL AFTER `cong_dung_chinh`,
    ADD COLUMN IF NOT EXISTS `gia_von` DECIMAL(15,2) NULL AFTER `doi_tuong_khach_hang`,
    ADD COLUMN IF NOT EXISTS `gia_ban_le` DECIMAL(15,2) NULL AFTER `gia_von`,
    ADD COLUMN IF NOT EXISTS `gia_si` DECIMAL(15,2) NULL AFTER `gia_ban_le`;

RENAME TABLE `catalog_systems` TO `he_sinh_thai_dong`;
RENAME TABLE `combo` TO `bo_san_pham`;
RENAME TABLE `combo_item` TO `chi_tiet_bo_san_pham`;
RENAME TABLE `combo_campaign` TO `chien_dich_bo_san_pham`;
RENAME TABLE `combo_proposal` TO `bao_gia_bo_san_pham`;
RENAME TABLE `product_media` TO `tai_nguyen_san_pham`;
RENAME TABLE `product_content` TO `noi_dung_san_pham`;
RENAME TABLE `product_relation` TO `lien_ket_san_pham`;
