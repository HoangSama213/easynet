-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2026 at 09:36 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easynet`
--

-- --------------------------------------------------------

--
-- Table structure for table `bao_mat`
--

CREATE TABLE `bao_mat` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bao_mat`
--

INSERT INTO `bao_mat` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Firewall', 'Fortinet, Cisco', NULL, NULL, NULL),
(2, 'IDS/IPS', 'Cisco, Palo Alto Networks, IBM', NULL, NULL, NULL),
(3, 'IAM (Identity & Access Management)', 'IBM, Microsoft, Oracle, Okta, Ping Identity, SailPoint, OneLogin', NULL, NULL, NULL),
(4, 'Chính sách an toàn thông tin', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cap_vat_tu_day_tin_hieu`
--

CREATE TABLE `cap_vat_tu_day_tin_hieu` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cap_vat_tu_day_tin_hieu`
--

INSERT INTO `cap_vat_tu_day_tin_hieu` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Cáp quang', 'Vinacap, M3 Viettel, Telvina, Saicom, LS', NULL, 'Điện Sài Gòn, Ngọc Minh', NULL),
(2, 'Cáp mạng', NULL, NULL, 'Điện Sài Gòn, Ngọc Minh', NULL),
(3, 'Cáp tín hiệu', 'Belden, Alantek, Altek Kabel, LS', NULL, 'Điện Sài Gòn, Ngọc Minh', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `co_dien`
--

CREATE TABLE `co_dien` (
  `id` int NOT NULL,
  `ten_he_thong` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `co_dien`
--

INSERT INTO `co_dien` (`id`, `ten_he_thong`) VALUES
(1, 'Hệ thống điện công nghiệp & dân dụng'),
(2, 'Hệ thống cấp thoát nước'),
(3, 'Hệ thống điều hòa thông gió');

-- --------------------------------------------------------

--
-- Table structure for table `dien_nhe`
--

CREATE TABLE `dien_nhe` (
  `id` int NOT NULL,
  `ten_he_thong` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dien_nhe`
--

INSERT INTO `dien_nhe` (`id`, `ten_he_thong`) VALUES
(1, 'Tủ mạng và vật tư viễn thông'),
(2, 'Cáp, vật tư dây tín hiệu'),
(3, 'Thiết bị kết nối tín hiệu'),
(4, 'Hệ thống an ninh - giám sát điện tử'),
(5, 'Hệ thống kiểm soát truy cập'),
(6, 'Hệ thống cảnh báo'),
(7, 'Hệ thống thông tin liên lạc'),
(8, 'Hệ thống âm thanh và trình chiếu chuyên dùng'),
(9, 'Hệ thống điều khiển - tự động hoá');

-- --------------------------------------------------------

--
-- Table structure for table `doi_tac`
--

CREATE TABLE `doi_tac` (
  `id` int NOT NULL,
  `linh_vuc` varchar(500) NOT NULL,
  `doi_tac_tieu_bieu` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doi_tac`
--

INSERT INTO `doi_tac` (`id`, `linh_vuc`, `doi_tac_tieu_bieu`, `ghi_chu`) VALUES
(1, 'Tư vấn thiết kế', NULL, NULL),
(2, 'Nội thất', 'Sắc Màu, Huy Bảo Tín', NULL),
(3, 'Xây dựng', 'Thái Dương, Nam Khang', NULL),
(4, 'Cơ điện - MEP', 'Vietlight, Nam Thịnh', NULL),
(5, 'Phần mềm', NULL, NULL),
(6, 'Hệ thống thông tin (Tích hợp hệ thống - System Integrate)', 'FPT IS, Liên Phát, Smac', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `du_lieu_luu_tru`
--

CREATE TABLE `du_lieu_luu_tru` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `du_lieu_luu_tru`
--

INSERT INTO `du_lieu_luu_tru` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Database', 'Oracle (cho Oracle Database, MySQL), Microsoft (cho SQL Server, Access, Azure SQL Database)', NULL, NULL, NULL),
(2, 'SAN', 'HPE, IBM, Dell, Infortrend', NULL, NULL, NULL),
(3, 'NAS', 'Synology, QNAP, Asustor, TerraMaste', NULL, NULL, NULL),
(4, 'Cloud Storage', 'Google (Google Drive), Microsoft (OneDrive)', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hang_muc`
--

CREATE TABLE `hang_muc` (
  `id` int NOT NULL,
  `ten_hang_muc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hang_muc`
--

INSERT INTO `hang_muc` (`id`, `ten_hang_muc`) VALUES
(5, 'Cơ điện MEP'),
(1, 'Điện nhẹ ELV'),
(3, 'Hạ tầng CNTT'),
(8, 'Hệ thống thông tin (Tích hợp hệ thống - System Integrate)'),
(10, 'Nội thất'),
(7, 'Phần mềm'),
(2, 'Thiết bị CNTT và thiết bị văn phòng'),
(6, 'Thiết bị gia dụng'),
(9, 'Tư vấn thiết kế'),
(4, 'Vật tư phụ kiện'),
(11, 'Xây dựng');

-- --------------------------------------------------------

--
-- Table structure for table `ha_tang_cntt_thue_ngoai`
--

CREATE TABLE `ha_tang_cntt_thue_ngoai` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ha_tang_cntt_thue_ngoai`
--

INSERT INTO `ha_tang_cntt_thue_ngoai` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Cloud Server', 'Viettel IDC, CMC Cloud, FPT Cloud,', NULL, NULL, NULL),
(2, 'DC Location', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_am_thanh_trinh_chieu`
--

CREATE TABLE `he_thong_am_thanh_trinh_chieu` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_am_thanh_trinh_chieu`
--

INSERT INTO `he_thong_am_thanh_trinh_chieu` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Phòng họp trực tuyến', 'Logitech, Poly, Cisco, Yealink, Aver', NULL, 'Logico, Hoàng Đạo, Thiên An Minh, VMV, Sinh Minh', NULL),
(2, 'IPTV', 'FPT, VNPT, Viettel', NULL, 'Logico, Hoàng Đạo, Thiên An Minh, VMV, Sinh Minh', NULL),
(3, 'Màn hình ghép', 'Samsung, LG, Hikvision, Panasonic', NULL, 'Logico, Hoàng Đạo, Thiên An Minh, VMV, Sinh Minh', NULL),
(4, 'Âm thanh đa vùng', 'Sonos, Bose, JB, Boch', NULL, 'Logico, Hoàng Đạo, Thiên An Minh, VMV, Sinh Minh', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_an_ninh_giam_sat`
--

CREATE TABLE `he_thong_an_ninh_giam_sat` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_an_ninh_giam_sat`
--

INSERT INTO `he_thong_an_ninh_giam_sat` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Camera giám sát khu vực - mục tiêu', 'Hikvision, Dahua, Kbvision, Ezviz, Imou', NULL, 'Nhà An Toàn, Lê Hoàng, Sinh Minh', NULL),
(2, 'Giám sát bằng RFID', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_canh_bao`
--

CREATE TABLE `he_thong_canh_bao` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_canh_bao`
--

INSERT INTO `he_thong_canh_bao` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Âm thanh thông báo - PA', 'Toa, Bosch,', NULL, 'VMV, Sinh Minh', NULL),
(2, 'Báo cháy - FA', 'Dahua', NULL, 'VMV, Sinh Minh', NULL),
(3, 'Báo động chống đột nhập', NULL, NULL, 'VMV, Sinh Minh', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_cap_thoat_nuoc`
--

CREATE TABLE `he_thong_cap_thoat_nuoc` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_cap_thoat_nuoc`
--

INSERT INTO `he_thong_cap_thoat_nuoc` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Thiết bị nước vệ sinh', NULL, NULL, NULL, NULL),
(2, 'Thiết bị chống sét và tiếp địa', NULL, NULL, NULL, NULL),
(3, 'Vật tư phụ kiện (ống, máng,…)', NULL, NULL, NULL, NULL),
(4, 'Thi công lắp đặt đường ống cấp nước lạnh/nóng', NULL, NULL, NULL, NULL),
(5, 'Lắp đặt bơm & tủ điều khiển', NULL, NULL, NULL, NULL),
(6, 'Lắp đặt bồn, bể chứa', NULL, NULL, NULL, NULL),
(7, 'Kết nối thiết bị sử dụng nước', NULL, NULL, NULL, NULL),
(8, 'Lắp đặt nước thải sinh hoạt', NULL, NULL, NULL, NULL),
(9, 'Lắp đặt ống thóat nước mưa', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_dien_cong_nghiep_dan_dung`
--

CREATE TABLE `he_thong_dien_cong_nghiep_dan_dung` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_dien_cong_nghiep_dan_dung`
--

INSERT INTO `he_thong_dien_cong_nghiep_dan_dung` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Thiết bị điện, cáp điện', NULL, NULL, NULL, NULL),
(2, 'Thiết bị điện mặt trời', NULL, NULL, NULL, NULL),
(3, 'Thi công cơ điện', NULL, NULL, NULL, NULL),
(4, 'Hệ thống chiếu sáng', NULL, NULL, NULL, NULL),
(5, 'Hệ thống ổ cắm - thiết bị sử dụng điện', NULL, NULL, NULL, NULL),
(6, 'Dây dẫn & phụ kiện (hộp nối, ống luồn PVC, ruột gà)', NULL, NULL, NULL, NULL),
(7, 'Nguồn điện trung - hạ thế', NULL, NULL, NULL, NULL),
(8, 'Hệ thống tủ điện (tủ điện tổng, tủ ATS, tủ điều kiển động cơ, tủ điều khiển máy móc, dây chuyền)', NULL, NULL, NULL, NULL),
(9, 'Hệ thống phân phối điện (cáp động lực áp thế, thang máy cáp, khay cáp, busway, ống thép, ông HDPE, ống EMT)', NULL, NULL, NULL, NULL),
(10, 'Hệ thống chiếu sáng công nghiệp (đèn nhà xưởng, đèn văn phòng, đèn sự cố - exit, chiếu sáng ngoài trời, bãi xe)', NULL, NULL, NULL, NULL),
(11, 'Hệ thống điều khiển & tự động hóa', NULL, NULL, NULL, NULL),
(12, 'Hệ thống bảo vệ - an toàn điện', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_dieu_hoa_thong_gio`
--

CREATE TABLE `he_thong_dieu_hoa_thong_gio` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_dieu_hoa_thong_gio`
--

INSERT INTO `he_thong_dieu_hoa_thong_gio` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Cung cấp, thi công và lắp đặt máy lạnh dân dụng & công nghiệp', NULL, NULL, NULL, NULL),
(2, 'Lắp đặt dàn lạnh - dàn nóng', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_dieu_khien_tu_dong_hoa`
--

CREATE TABLE `he_thong_dieu_khien_tu_dong_hoa` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_dieu_khien_tu_dong_hoa`
--

INSERT INTO `he_thong_dieu_khien_tu_dong_hoa` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'BMS', 'Siemens, Schneider Electric, Honeywell,...', NULL, 'Smartliving, FPT', NULL),
(2, 'Smarthome', 'Lumi Smart, FPT, Xiaomi, Fibaro', NULL, 'Smartliving, FPT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_kiem_soat_truy_cap`
--

CREATE TABLE `he_thong_kiem_soat_truy_cap` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_kiem_soat_truy_cap`
--

INSERT INTO `he_thong_kiem_soat_truy_cap` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Quản lý cửa ra vào', 'ZKTeco, Ronald Jack, Hikvision', NULL, 'Vi Khang, Sinh Minh, KBVISION, TID', NULL),
(2, 'Cổng ra vào', 'ZKTeco, Hikvision', NULL, 'Vi Khang, Sinh Minh, KBVISION, TID', NULL),
(3, 'Bãi xe', 'ZKTeco', NULL, 'Vi Khang, Sinh Minh, KBVISION, TID', NULL),
(4, 'Phân tầng thang máy', 'Soyal, Chiyu, Hikvision', NULL, 'Vi Khang, Sinh Minh, KBVISION, TID', NULL),
(5, 'Video Door Phone', 'Hikvision, Dahua, Panasonic', NULL, 'Vi Khang, Sinh Minh, KBVISION, TID', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `he_thong_thong_tin_lien_lac`
--

CREATE TABLE `he_thong_thong_tin_lien_lac` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `he_thong_thong_tin_lien_lac`
--

INSERT INTO `he_thong_thong_tin_lien_lac` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Intercom', 'Hikvision, Panasonic, Samsung', NULL, 'HTK VIỆT NAM', NULL),
(2, 'Tổng đài điện thoại', 'Panasonic, LG', NULL, 'HTK VIỆT NAM', NULL),
(3, 'Hướng dẫn - Thông báo', NULL, NULL, 'HTK VIỆT NAM', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kenh_truyen_dan_toc_do_cao`
--

CREATE TABLE `kenh_truyen_dan_toc_do_cao` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kenh_truyen_dan_toc_do_cao`
--

INSERT INTO `kenh_truyen_dan_toc_do_cao` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'VNPT', NULL, NULL, NULL, NULL),
(2, 'VIETTEL', NULL, NULL, NULL, NULL),
(3, 'FPT', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khach_hang`
--

CREATE TABLE `khach_hang` (
  `id` int NOT NULL,
  `phan_loai` varchar(255) DEFAULT NULL,
  `phan_loai_khach_hang` varchar(255) DEFAULT NULL,
  `khach_hang_tieu_bieu` text,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khach_hang`
--

INSERT INTO `khach_hang` (`id`, `phan_loai`, `phan_loai_khach_hang`, `khach_hang_tieu_bieu`, `ghi_chu`) VALUES
(1, 'Theo năng lực phục vụ', 'Khách hàng thương mại', 'PV Oil, Petronas, Dầu khí Phú Mỹ, Petrosetco, Pvcombank', NULL),
(2, 'Theo năng lực phục vụ', 'Khách hàng dịch vụ', NULL, NULL),
(3, 'Theo năng lực phục vụ', 'Khách hàng dự án', NULL, NULL),
(4, 'Theo nhóm ngành', 'Ngân hàng/Tài chính', 'PV Bank,...', NULL),
(5, 'Theo nhóm ngành', 'Giáo dục', 'Huflit, Global Education, Emasi', NULL),
(6, 'Theo nhóm ngành', 'Dầu khí', 'PV Oil, Petronas, Dầu khí Phú Mỹ, Petrosetco', NULL),
(7, 'Theo nhóm ngành', 'Doanh nghiệp', 'Giải khát Nam Việt, Levents Global, KingSport, Emasi, BiuBiu, LightHouse, Khai Sáng', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mang`
--

CREATE TABLE `mang` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mang`
--

INSERT INTO `mang` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'LAN', NULL, NULL, NULL, NULL),
(2, 'WAN', NULL, NULL, NULL, NULL),
(3, 'MAN', NULL, NULL, NULL, NULL),
(4, 'Hệ thống kết nối giữa các thiết bị', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ncc_lien_ket_chi_tiet`
--

CREATE TABLE `ncc_lien_ket_chi_tiet` (
  `ncc_id` int NOT NULL,
  `chi_tiet_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ncc_lien_ket_chi_tiet`
--

INSERT INTO `ncc_lien_ket_chi_tiet` (`ncc_id`, `chi_tiet_id`) VALUES
(1, 1),
(6, 1),
(8, 1),
(23, 1),
(27, 1),
(37, 1),
(43, 1),
(1, 2),
(3, 2),
(7, 2),
(18, 2),
(29, 2),
(35, 2),
(36, 2),
(44, 2),
(51, 2),
(58, 2),
(3, 3),
(18, 3),
(44, 3),
(1, 4),
(3, 4),
(4, 4),
(7, 4),
(18, 4),
(29, 4),
(36, 4),
(58, 4),
(25, 6),
(28, 6),
(12, 8),
(18, 8),
(54, 8),
(12, 9),
(18, 9),
(39, 9),
(18, 10),
(18, 11),
(1, 12),
(2, 12),
(6, 12),
(8, 12),
(10, 12),
(22, 12),
(23, 12),
(43, 12),
(50, 12),
(2, 13),
(6, 13),
(8, 13),
(10, 13),
(23, 13),
(43, 13),
(50, 13),
(5, 14),
(6, 15),
(8, 15),
(6, 16),
(22, 17),
(1, 18),
(2, 18),
(6, 18),
(8, 18),
(50, 18),
(1, 19),
(33, 19),
(50, 19),
(1, 20),
(33, 20),
(50, 20),
(8, 21),
(10, 21),
(5, 22),
(52, 22),
(5, 23),
(40, 23),
(52, 23),
(60, 23),
(3, 24),
(35, 24),
(36, 24),
(51, 24),
(54, 24),
(60, 24),
(3, 25),
(29, 25),
(35, 25),
(36, 25),
(51, 25),
(3, 26),
(3, 27),
(3, 28),
(51, 28),
(54, 28),
(3, 30),
(7, 30),
(15, 30),
(51, 30),
(54, 30),
(7, 31),
(44, 32),
(1, 33),
(28, 33),
(38, 33),
(38, 34),
(11, 37),
(19, 37),
(24, 37),
(47, 37),
(24, 38),
(24, 39),
(41, 39),
(49, 39),
(59, 39),
(39, 40),
(47, 40),
(20, 43),
(46, 43),
(48, 43),
(20, 45),
(46, 45),
(56, 45),
(25, 53),
(34, 54),
(13, 55),
(45, 55),
(13, 56),
(16, 60),
(42, 60),
(60, 60),
(21, 61),
(42, 62),
(60, 63),
(7, 64),
(8, 64),
(43, 64),
(26, 65),
(7, 68),
(18, 69),
(29, 70),
(9, 71),
(14, 72),
(17, 72),
(28, 72),
(44, 72),
(52, 74),
(8, 76),
(27, 76),
(28, 76),
(35, 76),
(44, 76),
(22, 77),
(58, 77),
(54, 79),
(57, 82);

-- --------------------------------------------------------

--
-- Table structure for table `ncc_lien_ket_san_pham`
--

CREATE TABLE `ncc_lien_ket_san_pham` (
  `ncc_id` int NOT NULL,
  `san_pham_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ncc_lien_ket_san_pham`
--

INSERT INTO `ncc_lien_ket_san_pham` (`ncc_id`, `san_pham_id`) VALUES
(1, 1),
(4, 1),
(7, 1),
(8, 1),
(18, 1),
(27, 1),
(28, 1),
(29, 1),
(37, 1),
(43, 1),
(44, 1),
(51, 1),
(58, 1),
(1, 2),
(2, 2),
(5, 2),
(6, 2),
(8, 2),
(18, 2),
(43, 2),
(50, 2),
(1, 3),
(33, 3),
(50, 3),
(2, 4),
(5, 4),
(8, 4),
(21, 4),
(3, 5),
(9, 5),
(29, 5),
(35, 5),
(36, 5),
(45, 5),
(51, 5),
(60, 5),
(6, 7),
(8, 7),
(18, 7),
(22, 7),
(43, 7),
(50, 7),
(7, 8),
(45, 8),
(8, 9),
(8, 10),
(13, 11),
(29, 11),
(45, 11),
(52, 11),
(13, 12),
(15, 13),
(16, 14),
(42, 14),
(17, 15),
(24, 15),
(56, 15),
(24, 16),
(25, 17),
(27, 17),
(28, 17),
(26, 18),
(27, 18),
(26, 19),
(33, 22),
(60, 23),
(38, 24),
(38, 25),
(39, 26),
(47, 26),
(40, 27),
(52, 27),
(60, 27),
(41, 28),
(48, 28),
(49, 28),
(59, 28),
(46, 31),
(48, 31),
(47, 32),
(47, 33),
(48, 34),
(51, 34),
(45, 35),
(51, 36),
(29, 70),
(44, 71),
(57, 73),
(60, 74),
(60, 75),
(60, 76);

-- --------------------------------------------------------

--
-- Table structure for table `ncc_lien_ket_thuong_hieu`
--

CREATE TABLE `ncc_lien_ket_thuong_hieu` (
  `ncc_id` int NOT NULL,
  `thuong_hieu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ncc_lien_ket_thuong_hieu`
--

INSERT INTO `ncc_lien_ket_thuong_hieu` (`ncc_id`, `thuong_hieu_id`) VALUES
(1, 1),
(8, 1),
(10, 1),
(1, 2),
(4, 2),
(7, 2),
(8, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 8),
(6, 8),
(8, 8),
(10, 8),
(2, 9),
(6, 9),
(2, 10),
(6, 10),
(10, 11),
(3, 12),
(9, 12),
(3, 13),
(3, 14),
(3, 15),
(9, 16),
(4, 18),
(5, 19),
(7, 20),
(8, 21),
(8, 22),
(8, 24),
(8, 25),
(8, 26),
(8, 27),
(13, 28),
(13, 29),
(13, 30),
(13, 31),
(13, 32),
(15, 34),
(15, 35),
(15, 36);

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `id` int NOT NULL,
  `ten_ncc` varchar(255) NOT NULL,
  `id_hang_muc` int DEFAULT NULL,
  `hang_muc` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `nguoi_lien_he` varchar(255) DEFAULT NULL,
  `nhom_zalo` varchar(255) DEFAULT NULL,
  `san_pham_ncc` text,
  `thuong_hieu_phan_phoi` text,
  `san_pham_chi_tiet` text,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`id`, `ten_ncc`, `id_hang_muc`, `hang_muc`, `website`, `nguoi_lien_he`, `nhom_zalo`, `san_pham_ncc`, `thuong_hieu_phan_phoi`, `san_pham_chi_tiet`, `ghi_chu`) VALUES
(1, 'Elite', 1, 'Điện nhẹ ELV', 'https://elite-jsc.com/', 'Mr An (Sale)', 'Elite-Easynet', 'Thiết bị mạng, Máy tính, Máy in', 'HPE, ARUBA, XFUSION, HUAWEI, ZYXEL, EPSON, Liebert', 'Server, Switch, Access Point (AP), Máy tính để bàn (PC), Màn hình, scan, UPS Liebert', NULL),
(2, 'Maxsell', 2, 'Thiết bị CNTT và thiết bị văn phòng', NULL, 'Mr Hiếu (Sale)', 'Easynet-Maxsell', 'Thiết bị văn phòng, Máy tính', 'Dell, HP, Lenovo', 'Laptop, Máy tính để bàn (PC), Màn hình', 'Đại lý bán lẻ'),
(3, 'Nhà An Toàn', 1, 'Điện nhẹ ELV', 'https://nhaantoan.com/', 'Group Sale', 'Mạng Sáng Tạo - NAT', 'Camera & An ninh', 'Hikvision, HiLook, Ruijie, Aolin', 'Camera, Đầu ghi, Thẻ nhớ, Chuông cửa, báo động, Máy chấm công Hikvision, Switch, Router, Access Point', NULL),
(4, 'FPT wifi', 1, 'Điện nhẹ ELV', NULL, 'Ms. Nguyễn Thịnh (Sale)', 'FPT_cty Easynet', 'Thiết bị mạng', 'Unifi, Aruba', 'Access Point (AP)', NULL),
(5, 'Istone', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://istone.vn/', 'Mr. Hải (Giám đốc)', 'IStone - Easynet', 'IT - Apple', 'Apple', 'Điện thoại iPhone, Macbook, Phụ kiện Apple', NULL),
(6, 'Nghĩa Thư', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://laptopxachtay.com.vn/', 'Group Sale', 'Nghĩa Thư - Easynet', 'Laptop, Máy tính', 'Dell, HP, Lenovo', 'Laptop, Máy tính để bàn (PC), Màn hình, Máy tính Workstation, Server, Máy tính mini NUC', 'Chủ yếu hàng xách tay'),
(7, 'TID', 1, 'Điện nhẹ ELV', 'https://www.tid.vn/', 'TID Trụ Ngô', 'TID-EASYNET', 'Access Control, Thiết bị mạng', 'Zkteco, ARUBA', 'Máy kiểm soát cửa, chấm công, Khóa cửa và phụ kiện, Phần mềm chấm công, Switch, Access Point (AP)', NULL),
(8, 'Nguyên Kim', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://vitinhnguyenkim.vn/', 'Mr Sang', 'NGKIM - HQS_Q01_SM_MANGSANGTAO', 'Laptop, Máy tính, Thiết bị mạng, Thiết bị văn phòng, Phần mềm', 'HPE, DELL, ASUS, MSI, INTEL, SAMSUNG, ARUBA, TPLINK, LOGITECH', 'Laptop, Máy tính để bàn (PC), Màn hình, Máy tính Workstation, Server, Phần mềm (MS365, Bảo mật, Tường lửa...), Thiết bị mạng, Linh kiện Lap, PC', NULL),
(9, 'Lê Hoàng - Gò Vấp', 1, 'Điện nhẹ ELV', 'https://lehoangcctv.com/', 'Sale zalo', 'EASYNET - Lê Hoàng GV', 'Camera', 'Hikvision, Ezviz, Hdparon', 'Đại lý Camera, phụ kiện', NULL),
(10, 'USA - Laptop', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://laptopusa.vn/', 'Mr. Thế Anh', 'USALAPTOP', NULL, 'HPE, DELL, THINKPAD', 'Laptop, PC, Linh kiện máy tính', NULL),
(11, 'Điện 18', 4, 'Vật tư phụ kiện', NULL, NULL, 'EASYNET- ĐIỆN 18', NULL, NULL, 'vật tư, thiết bị điện,...', NULL),
(12, 'Kim Phát', 4, 'Vật tư phụ kiện', NULL, 'Ms. Hà', 'KIM PHÁT - MẠNG SÁNG TẠO', NULL, NULL, 'Thiết bị quang, cáp quang', NULL),
(13, 'VMV', 1, 'Điện nhẹ ELV', 'https://thietbiamthanh.vn/', 'Mr Lĩnh 0987 873 090', 'TOA-BOSCH-EASYNET', 'Thiết bị âm thanh, Phụ kiện âm thanh', 'BOSCH, DYNACORD, JBL', 'TOA - BOSCH, còn có Dynacord, JBL, Shure, EV', NULL),
(14, 'QDtek', 4, 'Vật tư phụ kiện', 'https://qdtek.vn/', NULL, 'EasyNet - QDtek', NULL, NULL, 'Dây cáp mạng và phụ kiện Commscope, LS', NULL),
(15, 'Minh Tân ACS', 1, 'Điện nhẹ ELV', 'https://www.mayvanphongminhtan.com/', 'Mr Tân', 'Minh Tân ACS - Easynet', 'Máy chấm công', 'SEIKO, TITA, RONALD,...', 'Máy chấm công thẻ, vân tay, khuôn mặt, thiết bị phân biệt', NULL),
(16, 'Điện máy số 8', 6, 'Thiết bị gia dụng', NULL, 'Ms. Ngân sale', 'Điện Máy Số Tám - EASYNET', 'Thiết bị gia dụng', NULL, 'Tivi', NULL),
(17, 'ADG', 4, 'Vật tư phụ kiện', 'https://adg.vn/', 'Ms Cẩm 0982 461 113', 'ADG-EASYNET', 'Vật tư thi công', NULL, 'Dây cáp mạng và phụ kiện Commscope', NULL),
(18, 'An Phát', 3, 'Hạ tầng CNTT', 'https://www.anphatpc.com.vn/', 'Ms Lê Chi - 0938 360 307', NULL, 'Laptop, PC, Linh kiện, Thiết bị mạng', 'ASUS, TPLINK, HP', 'Access Point - Router - License - VigorACS & Web Content Filter & WiFi Marketing - Switch - DrayTek, Cáp, vật tư quang', NULL),
(19, 'Cát Vạn Lợi', 5, 'Cơ điện MEP', NULL, 'Ms Lan', 'Cát Vạn Lợi-Easynet', NULL, NULL, 'Vật tư thiết bị cơ điện công nghiệp', NULL),
(20, 'Thái Hoàng Long An', 1, 'Điện nhẹ ELV', 'https://thaihoangec.com.vn/', NULL, 'SÁNG TẠO- QUẬN 1_0983 997 986', NULL, NULL, 'Máng cáp và phụ kiện theo yêu cầu', NULL),
(21, 'Phúc Ngọc Anh', 6, 'Thiết bị gia dụng', 'https://dienmayphucngocanh.com/', 'Ms Lan', 'PhucNgocAnh-EasyNet (MayLanh)', 'Thiết bị văn phòng', 'TOSHIBA, LG', 'Máy lạnh', NULL),
(22, 'Digiworld', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://digiworld.com.vn/', 'Mr Hiển 0364 209 218', 'Hiển DGW - Easynet', 'Laptop', NULL, 'Máy tính, máy tính bảng, thiết bị VP', NULL),
(23, 'CNET - FPT', 3, 'Hạ tầng CNTT', NULL, 'Mr Thông 0976 699 232, Mr Nghĩa 0908 184 009', NULL, NULL, NULL, 'Các sp của HP', NULL),
(24, 'Nguyễn Giang', 1, 'Điện nhẹ ELV', 'https://nguyengiang.vn/', 'Mr. Cường', 'NGUYEN GIANG - EASYNET', 'Đèn, thiết bị điện, vật tư', 'PANASONIC, PHILIPS', 'vật tư thiết bị điện, đèn chiếu sáng, ống,...', NULL),
(25, 'TMC - cty Thắng Minh', 3, 'Hạ tầng CNTT', 'https://www.tmcrack.vn/vn/', 'Ms. Cẩm', 'Tủ rack TMC-KNMST', 'Tủ rack', 'TMC', 'Tủ rack, thanh đấu nối, patch panel,...', NULL),
(26, 'KTC', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://ktc.com.vn/', NULL, 'Phân phối Ugreen - EASYNET - KTC', 'Bộ chuyển đổi, HUD USB,...', 'UGREEN', 'UGREEN', NULL),
(27, 'Netsmart', 3, 'Hạ tầng CNTT', 'https://netsmart.vn/', 'Mr. Trung', 'Netsmart-EASYNET', 'Thiết bị mạng, Bộ chuyển đổi, Bộ định tuyến, tủ mạng, cáp', 'Cisco', 'Thiết bị mạng Cisco, Planet, NetGate, server', NULL),
(28, 'ADTek', 3, 'Hạ tầng CNTT', 'https://adtek.vn/', 'Mr. Quý', 'EASYNET - Adtek', 'Thiết bị mạng, Thiết bị hệ thống, Tủ rack', 'Mikrotik, Aruba, HP', 'Dây cáp mạng và phụ kiện - Panduit, Norden. Thiết bị mạng Mikrotik, Aruba, HP... UPS Norden, Tủ rack ADRACK', NULL),
(29, 'Thiên An Minh', 1, 'Điện nhẹ ELV', 'https://tongdai.com.vn/', 'Ms. Chi', 'Thiên An Minh - Easynet', 'Thiết bị mạng, Tổng đài, Thiết bị âm thanh & hội nghị, Camera', 'Grandstream, Mikrotik, Synway, Vbet, Tansonic', 'Grandstream: Wifi, Switch, đầu ghi, tổng đài IP,...', NULL),
(30, 'Vtech', 1, 'Điện nhẹ ELV', 'https://vtechsolutions.vn/', NULL, NULL, 'Thiết bị mạng, Camera, Bộ lưu điện UPS, Hệ thống âm thanh', NULL, 'Cam, thiết bị mạng', NULL),
(31, 'Smartliving', 1, 'Điện nhẹ ELV', 'https://smartliving.vn/', 'Mr. Chính', 'Smartliving - Easynet', 'Hệ thống nhà thông minh', 'Tuya, Dooya, Aqara, Xiaomi, Hunonic', 'Thiết bị smarthome', NULL),
(32, 'Dobo', 5, 'Cơ điện MEP', 'https://dobo.com.vn/', 'Ms. Thảo Phương', 'Dobo - Easynet', 'Thiết bị điện, ổ đóng cắt, tủ điện', 'Dobo', 'Thiết bị điện Dobo Korea', NULL),
(33, 'Siêu nhanh', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://mayphotosieunhanh.com/', NULL, 'Siêu nhanh máy in - Easynet', 'Máy in, Linh kiện máy in', 'FUJIFILM', 'Cung cấp/sửa chữa/cho thuê máy in máy scan', NULL),
(34, 'TechBox', 6, 'Thiết bị gia dụng', 'https://techbox.vn/', NULL, 'TechBox - Easynet', 'Tivi, Màn hình, giá treo, khung treo', 'LG', 'Khung treo, giá treo tivi', NULL),
(35, 'TVT', 1, 'Điện nhẹ ELV', 'https://tvtvietnam.vn/', NULL, 'TVT Camera - Easynet', 'Camera & An ninh', 'TVT', 'Camera, Thiết bị mạng, đầu ghi, Switch...', NULL),
(36, 'Camera 286', 1, 'Điện nhẹ ELV', 'https://camera286.com/', 'Ms. Huệ', 'Camera 286 - Easynet', 'Camera & An ninh', 'HIKVISION, IMOU, ZKTECO', 'Camera HIK, IMOU... Thiết bị đầu ghi, switch, wifi, Ruijie', NULL),
(37, 'CNTT shop - Việt Thái Dương', 1, 'Điện nhẹ ELV', 'https://cnttshop.vn/', 'Ms. Huyền', 'CNTT shop - Easynet - Việt Thái Dương', 'Thiết bị mạng, Thiết bị lưu trữ, Phụ kiện mạng chủ, phụ kiện mạng', 'CISCO, ARUBA, HPE, DELL', 'Máy chủ, server, Aruba, Unifi', NULL),
(38, 'Dakia', 1, 'Điện nhẹ ELV', 'https://dakiatech.com/', NULL, 'Dakia - Easynet', 'Bộ lưu điện, Ắc quy', 'INVT, DELTA, ARES', 'Ắc quy - UPS', NULL),
(39, 'Điện Sài Gòn', 5, 'Cơ điện MEP', NULL, 'Mr. Thịnh', 'Điện Sài Gòn - Easynet', 'Cáp quang, cáp điện, cáp tín hiệu', NULL, 'Cáp quang, cáp điện, cáp tín hiệu...', NULL),
(40, 'Hoàng Đạo', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://zodiac.com.vn/', NULL, 'Hoàng Đạo - Easynet', 'Máy chiếu, sản phẩm trình chiếu, hệ thống đèn, màn hình tương tác', 'Maxell', 'Máy chiếu', NULL),
(41, 'Sam Phú', 1, 'Điện nhẹ ELV', 'https://samphu.vn/', 'Ms. Hiền', 'KẾT NỐI MẠNG SÁNG TẠO - SAM PHÚ', 'Ống nhựa, ống luồn, nẹp ống', 'Sam Phú', 'Ống HDPE và phụ kiện', NULL),
(42, 'Minh Thái', 6, 'Thiết bị gia dụng', 'http://minhthaico.com/', 'Mr. Ninh Thái', 'Minh Thái - Easynet', 'Điện tử, Điện lạnh, Tivi, Phụ kiện', 'LG, SAMSUNG', 'Tivi, tủ lạnh, ...', NULL),
(43, 'Phúc Khang', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://pkcomputer.vn/', 'Mr. Đức', 'Phúc Khang - Easynet', 'PC, Laptop, Linh kiện máy tính, Linh kiện mạng', 'ACER, DELL, LEXAR, INTEL', 'PC, Laptop, Server, Software', NULL),
(44, 'Quang Lâm', 1, 'Điện nhẹ ELV', 'https://www.wintop.com.vn/', NULL, 'Quang Lâm - Easynet', 'Thiết bị truyền dẫn, Thiết bị mạng, Thiết bị đo', NULL, 'Cáp mạng, truyền dẫn. Thiết bị mạng: Switch, Router... Thiết bị triển khai điện nhẹ: Máy đo, máy kéo cáp, Máy in... Thiết bị nguồn và phụ kiện điện nhẹ', NULL),
(45, 'Sinh Minh', 1, 'Điện nhẹ ELV', 'https://sinhminh.com.vn/', 'Ms. Hồng Đức', 'SMT - EASYNET', 'Camera & An ninh, Âm thanh thông báo, Kiểm soát ra vào, Tháp giải nhiệt', 'BOSCH', 'Loa Bosch-TOA', NULL),
(46, 'Ngọc Minh', 1, 'Điện nhẹ ELV', 'https://ngocminhec.com.vn/', NULL, 'Máng cáp Ngọc Minh', 'Máng cáp điện, khay cáp điện, Phụ kiện kèm theo', NULL, 'SX máng cáp và phụ kiện theo yêu cầu', NULL),
(47, 'Thái Sơn Nam', 1, 'Điện nhẹ ELV', 'https://thaisonnam.vn/', 'Mr. Sang zalo', 'ST EASYNET - Thái Sơn Nam', 'Thiết bị hạ thế, Tủ điện hạ thế, Máy biến thế khô, Thiết bị trung thế, Cáp tín hiệu, Cáp viễn thông', 'LS', 'Vật tư điện, cáp hàng LS', NULL),
(48, 'HAEC', 1, 'Điện nhẹ ELV', 'https://www.haecconduit.com/', 'Mr. Thịnh', 'Easynet-HAEC- Mạng cáp', 'Ống luồn, Thang máng cáp, Máng lưới, Đế công tắc, Đầu cos, Thiết bị chống sét', NULL, 'Máng cáp và phụ kiện theo yêu cầu', NULL),
(49, 'T&S VIỆT NAM', 1, 'Điện nhẹ ELV', 'https://www.gspipe.vn/', 'Ms. Nga', 'Easynet - GS', 'Hệ thống ống, Phụ kiện nhựa', NULL, 'Ống luồn dây điện và phụ kiện ống GS', NULL),
(50, 'Nextmall', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://nextmall.vn/', 'Sale Ms. Ali', 'Nextmall - EASYNET', 'Laptop, Màn hình, Linh kiện, Máy in, Scan', 'HP, DELL, ASUS, ACER', 'Laptop, PC bộ: DELL, HP, ASUS... LCD các hãng', NULL),
(51, 'KBVISION', 1, 'Điện nhẹ ELV', 'https://kbvision.vn/', 'Mr. Đạt', 'KBvision - Easynet', 'Camera, phụ kiện camera, chuông cửa màn hình, khóa thông minh, máy chấm công, thiết bị mạng', 'KBVISION', '- Camera, Đầu ghi - Chuông cửa, báo động - Máy chấm công - Switch - Phụ kiện', NULL),
(52, 'HTK VIỆT NAM', 1, 'Điện nhẹ ELV', 'https://htkvietnam.vn/', 'Mr. Long', 'MAP-CTY KẾT NỐI MẠNG SÁNG TẠO', 'Máy chiếu, sản phẩm trình chiếu, màn hình tương tác', 'Polycom, Cisco, Bosch, TOA', '- Thiết bị HNTH Polycom HN - Máy chiếu - Bảng điều khiển - Âm thanh', NULL),
(53, 'Công ty Trường Tiến', 1, 'Điện nhẹ ELV', 'https://www.vivotek.vn/', 'Ms. Nhung sale zalo', 'Cctv Vivotek', 'Camera, phụ kiện camera, máy chấm công, chuông thông báo, chuông báo động, hệ thống POS tính tiền', 'Vivotek', 'Phân phối camera Vivotek', NULL),
(54, 'Vi Khang', 1, 'Điện nhẹ ELV', 'https://vikhang.com/', 'Ngọc Uẩn', 'Vi Khang (ZKTECO) - Easynet', 'Camera, phụ kiện camera, máy chấm công, chuông thông báo, chuông báo động, hệ thống POS tính tiền', NULL, '- Camera quan sát - Máy chấm công, kiểm soát cửa ra vào - Chuông thông báo, Thiết bị báo động - Hệ thống POS tính tiền', NULL),
(55, 'Vidoco', 3, 'Hạ tầng CNTT', 'https://vidoweb.vn/', 'Mr. Vinh, sale Vidoco', NULL, NULL, NULL, 'Phần mềm chatGPT, Canva,...', NULL),
(56, 'Thiên Lộc An', 4, 'Vật tư phụ kiện', NULL, 'Ms. Thúy Quyên, Mr. An sale', 'SÁNG TẠO - THIÊN LỘC AN - VẬT TƯ PHỤ', 'Vật tư phụ', NULL, 'Vật tư phụ thi công, vật tư mảng cáp: tyren, tán, lông đền,...', NULL),
(57, 'Bảo Thắng', 4, 'Vật tư phụ kiện', 'https://baothangme.com/vi/', 'Ms. Thủy, Mr. Bảo', 'Ống gió Bảo Thắng - Easynet', 'Ống gió và phụ kiện', NULL, 'Ống gió tròn, ống gió vuông và phụ kiện, van ống gió', NULL),
(58, 'Khang Yến', 3, 'Hạ tầng CNTT', 'https://khangyentech.com/', 'Mr. Thắng', 'KHANGYEN-EASYNET - CISCO - MÁY IN', 'Thiết bị mạng', 'Cisco', 'Switch, wifi, firewall...', NULL),
(59, 'Ngọc Nguyễn', 4, 'Vật tư phụ kiện', 'https://ngocnguyenhdv.com/', 'Mr. Ngân Nguyễn', 'Ngọc Nguyễn - EASYNET (HDV)', 'Ống và phụ kiện', 'HDV', 'Ống và phụ kiện ống hàng HDV', NULL),
(60, 'Logico', 2, 'Thiết bị CNTT và thiết bị văn phòng', 'https://logico.com.vn/', 'Ms. Yến', 'Sales LGC - Easynet', 'Máy chiếu, camera, máy quay phim, tivi', 'Sony, Epson, Philips', '- Máy chiếu, màn chiếu. Chuyên các dòng thiết bị Sony, Philips - Camera - Máy quay phim - Tivi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phan_mem`
--

CREATE TABLE `phan_mem` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phan_mem`
--

INSERT INTO `phan_mem` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Hệ điều hành (Windows, Linux)', NULL, NULL, NULL, NULL),
(2, 'Phần mềm quản trị cơ sở dữ liệu', 'Oracle, Microsoft', NULL, NULL, NULL),
(3, 'Phần mềm ứng dụng doanh nghiệp', 'MISA, Base.vn....', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham_chi_tiet`
--

CREATE TABLE `san_pham_chi_tiet` (
  `id` int NOT NULL,
  `ten_chi_tiet` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `san_pham_chi_tiet`
--

INSERT INTO `san_pham_chi_tiet` (`id`, `ten_chi_tiet`) VALUES
(34, 'Ắc quy'),
(4, 'Access Point (AP)'),
(74, 'Bảng điều khiển'),
(28, 'Báo động'),
(7, 'Bộ chuyển đổi'),
(24, 'Camera quan sát'),
(41, 'Cáp mạng'),
(9, 'Cáp quang'),
(40, 'Cáp tín hiệu'),
(27, 'Chuông cửa'),
(29, 'Chuông thông báo'),
(71, 'Đại lý Camera, phụ kiện'),
(25, 'Đầu ghi'),
(72, 'Dây cáp mạng và phụ kiện'),
(23, 'Điện thoại iPhone'),
(5, 'Firewall'),
(54, 'Giá treo/Khung treo tivi'),
(57, 'Hệ thống hội nghị truyền hình (HNTH)'),
(79, 'Hệ thống POS'),
(32, 'Hệ thống POS tính tiền'),
(42, 'Hệ thống smarthome'),
(44, 'Khay cáp'),
(68, 'Khóa cửa và phụ kiện'),
(13, 'Laptop'),
(69, 'License phần mềm'),
(21, 'Linh kiện PC/Lap'),
(55, 'Loa Bosch/TOA'),
(56, 'Loa JBL/Dynacord/Shure/EV'),
(48, 'Lông đền'),
(14, 'Macbook'),
(59, 'Màn chiếu'),
(18, 'Màn hình'),
(43, 'Máng cáp'),
(36, 'Máy biến thế khô'),
(30, 'Máy chấm công (Vân tay/Thẻ/Khuôn mặt)'),
(58, 'Máy chiếu'),
(66, 'Máy đo'),
(19, 'Máy in'),
(67, 'Máy kéo cáp'),
(31, 'Máy kiểm soát cửa ra vào'),
(61, 'Máy lạnh'),
(63, 'Máy quay phim'),
(20, 'Máy scan'),
(17, 'Máy tính bảng'),
(12, 'Máy tính để bàn (PC)'),
(16, 'Máy tính mini NUC'),
(39, 'Ống điện'),
(51, 'Ống gió tròn/vuông'),
(82, 'Ống gió và phụ kiện'),
(50, 'Ống HDPE'),
(49, 'Ống luồn dây điện GS'),
(64, 'Phần mềm (MS365, ChatGPT, Canva...)'),
(22, 'Phụ kiện Apple'),
(3, 'Router'),
(1, 'Server'),
(2, 'Switch'),
(47, 'Tán'),
(53, 'Thanh đấu nối (Patch panel)'),
(26, 'Thẻ nhớ'),
(38, 'Thiết bị chiếu sáng'),
(75, 'Thiết bị HNTH Polycom HN'),
(76, 'Thiết bị mạng'),
(8, 'Thiết bị quang'),
(73, 'Thiết bị triển khai điện nhẹ'),
(77, 'Thiết bị văn phòng'),
(60, 'Tivi'),
(70, 'Tổng đài IP'),
(35, 'Tủ điện hạ thế/trung thế'),
(62, 'Tủ lạnh'),
(6, 'Tủ rack'),
(46, 'Tyren'),
(65, 'UGREEN'),
(33, 'UPS (Bộ lưu điện)'),
(52, 'Van ống gió'),
(45, 'Vật tư máng cáp'),
(37, 'Vật tư thiết bị điện'),
(11, 'VigorACS & Web Content Filter'),
(10, 'WiFi Marketing'),
(15, 'Workstation');

-- --------------------------------------------------------

--
-- Table structure for table `san_pham_ncc`
--

CREATE TABLE `san_pham_ncc` (
  `id` int NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `san_pham_ncc`
--

INSERT INTO `san_pham_ncc` (`id`, `ten_san_pham`) VALUES
(25, 'Ắc quy'),
(8, 'Access Control'),
(33, 'Âm thanh thông báo'),
(18, 'Bộ chuyển đổi'),
(24, 'Bộ lưu điện (UPS)'),
(5, 'Camera & An ninh'),
(26, 'Cáp quang, cáp điện, cáp tín hiệu'),
(16, 'Đèn, thiết bị điện'),
(30, 'Điện tử, điện lạnh'),
(76, 'Giá treo - Khung treo'),
(20, 'Hệ thống nhà thông minh'),
(73, 'Hệ thống ống gió'),
(19, 'HUD USB'),
(6, 'IT - Apple'),
(34, 'Kiểm soát ra vào'),
(7, 'Laptop'),
(22, 'Linh kiện máy in'),
(75, 'Màn hình'),
(28, 'Màn hình tương tác'),
(13, 'Máy chấm công'),
(27, 'Máy chiếu'),
(3, 'Máy in'),
(2, 'Máy tính'),
(21, 'Ổ đóng cắt, tủ điện'),
(39, 'Ống gió và phụ kiện'),
(29, 'Ống nhựa, ống luồn, nẹp ống'),
(10, 'Phần mềm'),
(12, 'Phụ kiện âm thanh'),
(36, 'Thang máng cáp'),
(35, 'Tháp giải nhiệt'),
(11, 'Thiết bị âm thanh'),
(32, 'Thiết bị đo'),
(71, 'Thiết bị đo & Nguồn điện nhẹ'),
(14, 'Thiết bị gia dụng'),
(1, 'Thiết bị mạng'),
(74, 'Thiết bị nghe nhìn & Máy quay'),
(9, 'Thiết bị thông minh'),
(37, 'Thiết bị trung thế/hạ thế'),
(31, 'Thiết bị truyền dẫn'),
(4, 'Thiết bị văn phòng'),
(23, 'Tivi'),
(70, 'Tổng đài IP'),
(17, 'Tủ rack'),
(38, 'Vật tư phụ'),
(15, 'Vật tư thi công');

-- --------------------------------------------------------

--
-- Table structure for table `smart_home`
--

CREATE TABLE `smart_home` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `smart_home`
--

INSERT INTO `smart_home` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Điều khiển chiếu sáng thông minh', NULL, NULL, NULL, NULL),
(2, 'Điều khiển rèm cửa, máu che tự động', NULL, NULL, NULL, NULL),
(3, 'Điều khiển điều hòa, sưởi, thông gió', NULL, NULL, NULL, NULL),
(4, 'Điều khiển thiết bị điện (ổ cắm, công tắc)', NULL, NULL, NULL, NULL),
(5, 'Kịch bản sinh hoạt thông minh (scene)', NULL, NULL, NULL, NULL),
(6, 'Điều khiển bằng app, giọng nói, cảm biến', NULL, NULL, NULL, NULL),
(7, 'An ninh thông minh: camera, báo trộm, cảm biến cửa', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `smart_office`
--

CREATE TABLE `smart_office` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `smart_office`
--

INSERT INTO `smart_office` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Quản lý chiếu sáng theo khu vực và thời gian', NULL, NULL, NULL, NULL),
(2, 'Điều hòa - thông gió thông minh (HVAC Control)', NULL, NULL, NULL, NULL),
(3, 'Quản lý phòng họp thông minh', NULL, NULL, NULL, NULL),
(4, 'Hệ thống đặt chỗ (Desk/ Booking Room)', NULL, NULL, NULL, NULL),
(5, 'Kiểm soát ra vào thông minh', NULL, NULL, NULL, NULL),
(6, 'Giám sát & Tối ưu năng lượng văn phòng', NULL, NULL, NULL, NULL),
(7, 'Tích hợp AV (Âm thanh & Hình ảnh), màn hình, Digital', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thiet_bi_ket_noi_tin_hieu`
--

CREATE TABLE `thiet_bi_ket_noi_tin_hieu` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thiet_bi_ket_noi_tin_hieu`
--

INSERT INTO `thiet_bi_ket_noi_tin_hieu` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Swich', 'Cisco, Aruba, TP-Link,...', NULL, 'Elite, FPT wifi, TID, Nguyên Kim, Netsmart, An Phát,...', NULL),
(2, 'Access Point', 'Ubiquiti, TP-Link, Aruba, Ruijie, Cisco,...', NULL, 'Elite, FPT wifi, TID, Nguyên Kim, Netsmart, An Phát,...', NULL),
(3, 'Zigbee hub', 'Bosch, Panasonic, Sony, Philips', NULL, 'Elite, FPT wifi, TID, Nguyên Kim, Netsmart, An Phát,...', NULL),
(4, 'Các thiết bị chuyển đổi tín hiệu', NULL, NULL, 'Elite, FPT wifi, TID, Nguyên Kim, Netsmart, An Phát,...', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thiet_bi_phan_cung`
--

CREATE TABLE `thiet_bi_phan_cung` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thiet_bi_phan_cung`
--

INSERT INTO `thiet_bi_phan_cung` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Máy chủ', 'HPE, CISCO, DELL', NULL, 'Elite, Nghĩa Thư, CNET - FPT', NULL),
(2, 'Thiết bị lưu trữ', 'WD, Seagate, Samsung, Kingston,...', NULL, 'Elite, Nghĩa Thư, CNET - FPT', NULL),
(3, 'Thiết bị mạng (Router, Switch, Firewall)', 'Cisco, Juniper, Fortinet, Dell, DrayTek', NULL, 'Elite, Nghĩa Thư, CNET - FPT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thiet_bi_tin_hoc_van_phong`
--

CREATE TABLE `thiet_bi_tin_hoc_van_phong` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thiet_bi_tin_hoc_van_phong`
--

INSERT INTO `thiet_bi_tin_hoc_van_phong` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Máy tính', 'HP, DELL, ASUS, ACER,...', NULL, 'Maxsell, Nghĩa Thư, Elite, Logico, Nguyên Kim, USA - Laptop...', NULL),
(2, 'Máy in/Scan', 'EPSON, HP,...', NULL, 'Maxsell, Nghĩa Thư, Elite, Logico, Nguyên Kim, USA - Laptop...', NULL),
(3, 'Máy chiếu', 'Sony, Epson, Optoma, View Sonic, Panasonic', NULL, 'Maxsell, Nghĩa Thư, Elite, Logico, Nguyên Kim, USA - Laptop...', NULL),
(4, 'TV', 'Xiaomi, Samsung, LG,...', NULL, 'Maxsell, Nghĩa Thư, Elite, Logico, Nguyên Kim, USA - Laptop...', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `id` int NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`id`, `ten_thuong_hieu`) VALUES
(70, 'Acer'),
(15, 'Aolin'),
(19, 'Apple'),
(57, 'Aqara'),
(74, 'Ares'),
(2, 'ARUBA'),
(21, 'ASUS'),
(28, 'Bosch'),
(43, 'Cisco'),
(49, 'Commscope'),
(8, 'Dell'),
(73, 'Delta'),
(60, 'Dobo'),
(56, 'Dooya'),
(23, 'DrayTek'),
(29, 'Dynacord'),
(6, 'EPSON'),
(33, 'EV'),
(16, 'Ezviz'),
(61, 'Fujifilm'),
(51, 'Grandstream'),
(17, 'Hdparon'),
(67, 'HDV'),
(12, 'Hikvision'),
(13, 'HiLook'),
(9, 'HP'),
(1, 'HPE'),
(4, 'HUAWEI'),
(59, 'Hunonic'),
(63, 'Imou'),
(25, 'Intel'),
(72, 'InVT'),
(30, 'JBL'),
(64, 'KBVision'),
(10, 'Lenovo'),
(71, 'Lexar'),
(38, 'LG'),
(7, 'Liebert'),
(27, 'Logitech'),
(50, 'LS'),
(69, 'Maxell'),
(46, 'Mikrotik'),
(24, 'MSI'),
(45, 'NetGate'),
(47, 'Norden'),
(39, 'Panasonic'),
(48, 'Panduit'),
(40, 'Philips'),
(44, 'Planet'),
(65, 'Polycom'),
(36, 'Ronald Jack'),
(14, 'Ruijie'),
(26, 'Samsung'),
(34, 'Seiko'),
(32, 'Shure'),
(68, 'Sony'),
(52, 'Synway'),
(54, 'Tansonic'),
(11, 'THINKPAD'),
(35, 'Tita'),
(41, 'TMC'),
(31, 'TOA'),
(37, 'Toshiba'),
(22, 'TP-Link'),
(55, 'Tuya'),
(62, 'TVT'),
(42, 'Ugreen'),
(18, 'Unifi'),
(53, 'Vbet'),
(66, 'Vivotek'),
(3, 'XFUSION'),
(58, 'Xiaomi'),
(20, 'Zkteco'),
(5, 'ZYXEL');

-- --------------------------------------------------------

--
-- Table structure for table `trung_tam_du_lieu`
--

CREATE TABLE `trung_tam_du_lieu` (
  `id` int NOT NULL,
  `chi_tiet` varchar(500) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(500) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trung_tam_du_lieu`
--

INSERT INTO `trung_tam_du_lieu` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Data Center', 'Viettel, FPT, CMC', NULL, NULL, NULL),
(2, 'Server Room', 'Dell EMC, HPE', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tu_mang_vat_tu_vien_thong`
--

CREATE TABLE `tu_mang_vat_tu_vien_thong` (
  `id` int NOT NULL,
  `chi_tiet` varchar(255) NOT NULL,
  `hang_noi_bat` varchar(500) DEFAULT NULL,
  `san_pham` varchar(255) DEFAULT NULL,
  `nha_phan_phoi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tu_mang_vat_tu_vien_thong`
--

INSERT INTO `tu_mang_vat_tu_vien_thong` (`id`, `chi_tiet`, `hang_noi_bat`, `san_pham`, `nha_phan_phoi`, `ghi_chu`) VALUES
(1, 'Tủ rack', 'KPrack', NULL, 'Kim Phát', 'KPrack - search chưa ra giá, sp còn mới trên thị trường, chưa public giá trên website'),
(2, 'ODF', 'KPrack', NULL, 'Kim Phát', 'KPrack - search chưa ra giá, sp còn mới trên thị trường, chưa public giá trên website'),
(3, 'MDF', 'Saicom', NULL, NULL, NULL),
(4, 'Tủ mạng', 'comrack (ADG - đa dạng nguồn hàng), ADrack (ADtek)', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bao_mat`
--
ALTER TABLE `bao_mat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cap_vat_tu_day_tin_hieu`
--
ALTER TABLE `cap_vat_tu_day_tin_hieu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `co_dien`
--
ALTER TABLE `co_dien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dien_nhe`
--
ALTER TABLE `dien_nhe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doi_tac`
--
ALTER TABLE `doi_tac`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `du_lieu_luu_tru`
--
ALTER TABLE `du_lieu_luu_tru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hang_muc`
--
ALTER TABLE `hang_muc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_hang_muc` (`ten_hang_muc`);

--
-- Indexes for table `ha_tang_cntt_thue_ngoai`
--
ALTER TABLE `ha_tang_cntt_thue_ngoai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_am_thanh_trinh_chieu`
--
ALTER TABLE `he_thong_am_thanh_trinh_chieu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_an_ninh_giam_sat`
--
ALTER TABLE `he_thong_an_ninh_giam_sat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_canh_bao`
--
ALTER TABLE `he_thong_canh_bao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_cap_thoat_nuoc`
--
ALTER TABLE `he_thong_cap_thoat_nuoc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_dien_cong_nghiep_dan_dung`
--
ALTER TABLE `he_thong_dien_cong_nghiep_dan_dung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_dieu_hoa_thong_gio`
--
ALTER TABLE `he_thong_dieu_hoa_thong_gio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_dieu_khien_tu_dong_hoa`
--
ALTER TABLE `he_thong_dieu_khien_tu_dong_hoa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_kiem_soat_truy_cap`
--
ALTER TABLE `he_thong_kiem_soat_truy_cap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `he_thong_thong_tin_lien_lac`
--
ALTER TABLE `he_thong_thong_tin_lien_lac`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kenh_truyen_dan_toc_do_cao`
--
ALTER TABLE `kenh_truyen_dan_toc_do_cao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mang`
--
ALTER TABLE `mang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ncc_lien_ket_chi_tiet`
--
ALTER TABLE `ncc_lien_ket_chi_tiet`
  ADD PRIMARY KEY (`ncc_id`,`chi_tiet_id`),
  ADD KEY `chi_tiet_id` (`chi_tiet_id`);

--
-- Indexes for table `ncc_lien_ket_san_pham`
--
ALTER TABLE `ncc_lien_ket_san_pham`
  ADD PRIMARY KEY (`ncc_id`,`san_pham_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Indexes for table `ncc_lien_ket_thuong_hieu`
--
ALTER TABLE `ncc_lien_ket_thuong_hieu`
  ADD PRIMARY KEY (`ncc_id`,`thuong_hieu_id`),
  ADD KEY `thuong_hieu_id` (`thuong_hieu_id`);

--
-- Indexes for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ncc_hang_muc` (`id_hang_muc`);

--
-- Indexes for table `phan_mem`
--
ALTER TABLE `phan_mem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `san_pham_chi_tiet`
--
ALTER TABLE `san_pham_chi_tiet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_chi_tiet` (`ten_chi_tiet`);

--
-- Indexes for table `san_pham_ncc`
--
ALTER TABLE `san_pham_ncc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_san_pham` (`ten_san_pham`);

--
-- Indexes for table `smart_home`
--
ALTER TABLE `smart_home`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smart_office`
--
ALTER TABLE `smart_office`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thiet_bi_ket_noi_tin_hieu`
--
ALTER TABLE `thiet_bi_ket_noi_tin_hieu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thiet_bi_phan_cung`
--
ALTER TABLE `thiet_bi_phan_cung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thiet_bi_tin_hoc_van_phong`
--
ALTER TABLE `thiet_bi_tin_hoc_van_phong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_thuong_hieu` (`ten_thuong_hieu`);

--
-- Indexes for table `trung_tam_du_lieu`
--
ALTER TABLE `trung_tam_du_lieu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tu_mang_vat_tu_vien_thong`
--
ALTER TABLE `tu_mang_vat_tu_vien_thong`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bao_mat`
--
ALTER TABLE `bao_mat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cap_vat_tu_day_tin_hieu`
--
ALTER TABLE `cap_vat_tu_day_tin_hieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `co_dien`
--
ALTER TABLE `co_dien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dien_nhe`
--
ALTER TABLE `dien_nhe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doi_tac`
--
ALTER TABLE `doi_tac`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `du_lieu_luu_tru`
--
ALTER TABLE `du_lieu_luu_tru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hang_muc`
--
ALTER TABLE `hang_muc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ha_tang_cntt_thue_ngoai`
--
ALTER TABLE `ha_tang_cntt_thue_ngoai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `he_thong_am_thanh_trinh_chieu`
--
ALTER TABLE `he_thong_am_thanh_trinh_chieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `he_thong_an_ninh_giam_sat`
--
ALTER TABLE `he_thong_an_ninh_giam_sat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `he_thong_canh_bao`
--
ALTER TABLE `he_thong_canh_bao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `he_thong_cap_thoat_nuoc`
--
ALTER TABLE `he_thong_cap_thoat_nuoc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `he_thong_dien_cong_nghiep_dan_dung`
--
ALTER TABLE `he_thong_dien_cong_nghiep_dan_dung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `he_thong_dieu_hoa_thong_gio`
--
ALTER TABLE `he_thong_dieu_hoa_thong_gio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `he_thong_dieu_khien_tu_dong_hoa`
--
ALTER TABLE `he_thong_dieu_khien_tu_dong_hoa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `he_thong_kiem_soat_truy_cap`
--
ALTER TABLE `he_thong_kiem_soat_truy_cap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `he_thong_thong_tin_lien_lac`
--
ALTER TABLE `he_thong_thong_tin_lien_lac`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kenh_truyen_dan_toc_do_cao`
--
ALTER TABLE `kenh_truyen_dan_toc_do_cao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mang`
--
ALTER TABLE `mang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `phan_mem`
--
ALTER TABLE `phan_mem`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `san_pham_chi_tiet`
--
ALTER TABLE `san_pham_chi_tiet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `san_pham_ncc`
--
ALTER TABLE `san_pham_ncc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `smart_home`
--
ALTER TABLE `smart_home`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `smart_office`
--
ALTER TABLE `smart_office`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `thiet_bi_ket_noi_tin_hieu`
--
ALTER TABLE `thiet_bi_ket_noi_tin_hieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thiet_bi_phan_cung`
--
ALTER TABLE `thiet_bi_phan_cung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `thiet_bi_tin_hoc_van_phong`
--
ALTER TABLE `thiet_bi_tin_hoc_van_phong`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `trung_tam_du_lieu`
--
ALTER TABLE `trung_tam_du_lieu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tu_mang_vat_tu_vien_thong`
--
ALTER TABLE `tu_mang_vat_tu_vien_thong`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ncc_lien_ket_chi_tiet`
--
ALTER TABLE `ncc_lien_ket_chi_tiet`
  ADD CONSTRAINT `ncc_lien_ket_chi_tiet_ibfk_1` FOREIGN KEY (`ncc_id`) REFERENCES `nha_cung_cap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ncc_lien_ket_chi_tiet_ibfk_2` FOREIGN KEY (`chi_tiet_id`) REFERENCES `san_pham_chi_tiet` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ncc_lien_ket_san_pham`
--
ALTER TABLE `ncc_lien_ket_san_pham`
  ADD CONSTRAINT `ncc_lien_ket_san_pham_ibfk_1` FOREIGN KEY (`ncc_id`) REFERENCES `nha_cung_cap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ncc_lien_ket_san_pham_ibfk_2` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham_ncc` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ncc_lien_ket_thuong_hieu`
--
ALTER TABLE `ncc_lien_ket_thuong_hieu`
  ADD CONSTRAINT `ncc_lien_ket_thuong_hieu_ibfk_1` FOREIGN KEY (`ncc_id`) REFERENCES `nha_cung_cap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ncc_lien_ket_thuong_hieu_ibfk_2` FOREIGN KEY (`thuong_hieu_id`) REFERENCES `thuong_hieu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD CONSTRAINT `fk_ncc_hang_muc` FOREIGN KEY (`id_hang_muc`) REFERENCES `hang_muc` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
