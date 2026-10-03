-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2026 at 11:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hoalu_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('call','sms','email','meeting','note','appointment','surgery','status_change') NOT NULL,
  `content` text DEFAULT NULL,
  `result` enum('success','fail','pending','no_answer') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `customer_id`, `user_id`, `type`, `content`, `result`, `created_at`) VALUES
(1, 1, 3, 'call', 'Gọi điện xác nhận lịch mổ', 'success', '2026-10-03 01:44:06'),
(2, 2, 3, 'appointment', 'Đã đặt lịch khám 9h sáng mai', 'success', '2026-10-02 23:44:06'),
(3, 3, 4, 'note', 'Khách quan tâm gói trả góp', 'pending', '2026-10-02 21:44:06'),
(4, 4, 3, 'sms', 'Gửi SMS nhắc lịch hẹn', 'success', '2026-10-02 02:44:06'),
(5, 5, 4, 'call', 'Khách chưa nghe máy', 'no_answer', '2026-10-01 02:44:06'),
(6, 6, 3, 'surgery', 'Mổ đục thủy tinh thể thành công', 'success', '2026-09-30 02:44:06'),
(7, 7, 4, 'meeting', 'Gặp trực tiếp, đã ký hợp đồng', 'success', '2026-09-29 02:44:06'),
(8, 8, 3, 'email', 'Gửi bảng báo giá chi tiết', 'success', '2026-09-28 02:44:06'),
(9, 1, 3, 'call', 'Gọi điện xác nhận lịch mổ', 'success', '2026-10-03 01:45:38'),
(10, 2, 3, 'appointment', 'Đã đặt lịch khám 9h sáng mai', 'success', '2026-10-02 23:45:38'),
(11, 3, 4, 'note', 'Khách quan tâm gói trả góp', 'pending', '2026-10-02 21:45:38'),
(12, 4, 3, 'sms', 'Gửi SMS nhắc lịch hẹn', 'success', '2026-10-02 02:45:38'),
(13, 5, 4, 'call', 'Khách chưa nghe máy', 'no_answer', '2026-10-01 02:45:38'),
(14, 6, 3, 'surgery', 'Mổ đục thủy tinh thể thành công', 'success', '2026-09-30 02:45:38'),
(15, 7, 4, 'meeting', 'Gặp trực tiếp, đã ký hợp đồng', 'success', '2026-09-29 02:45:38'),
(16, 8, 3, 'email', 'Gửi bảng báo giá chi tiết', 'success', '2026-09-28 02:45:38');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `entity` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `duration_minutes` int(11) DEFAULT 30,
  `doctor` varchar(100) DEFAULT NULL,
  `status` enum('pending','confirmed','done','cancelled','no_show') DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `user_id`, `title`, `scheduled_at`, `duration_minutes`, `doctor`, `status`, `note`, `created_at`) VALUES
(1, 2, 3, 'Khám tư vấn Lasik', '2026-10-04 09:44:06', 30, 'BS. Trần Minh', 'confirmed', NULL, '2026-10-03 02:44:06'),
(2, 7, 4, 'Khám võng mạc', '2026-10-05 09:44:06', 30, 'BS. Lê Hoa', 'pending', NULL, '2026-10-03 02:44:06'),
(3, 4, 3, 'Khám Smile', '2026-10-06 09:44:06', 30, 'BS. Trần Minh', 'pending', NULL, '2026-10-03 02:44:06'),
(4, 2, 3, 'Khám tư vấn Lasik', '2026-10-04 09:45:38', 30, 'BS. Trần Minh', 'confirmed', NULL, '2026-10-03 02:45:38'),
(5, 7, 4, 'Khám võng mạc', '2026-10-05 09:45:38', 30, 'BS. Lê Hoa', 'pending', NULL, '2026-10-03 02:45:38'),
(6, 4, 3, 'Khám Smile', '2026-10-06 09:45:38', 30, 'BS. Trần Minh', 'pending', NULL, '2026-10-03 02:45:38');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `birth_year` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` enum('new','contacted','appointment','consulted','surgery','done','lost') DEFAULT 'new',
  `interest` varchar(255) DEFAULT NULL,
  `estimated_value` decimal(15,0) DEFAULT 0,
  `note` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `last_contact_at` datetime DEFAULT NULL,
  `next_followup_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `full_name`, `phone`, `email`, `gender`, `birth_year`, `address`, `city`, `source_id`, `assigned_to`, `status`, `interest`, `estimated_value`, `note`, `tags`, `last_contact_at`, `next_followup_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'KH240101', 'Nguyễn Văn An', '0901234567', 'an.nguyen@gmail.com', 'male', 1985, NULL, 'Ninh Bình', 1, 3, 'surgery', 'Đục thủy tinh thể', 15000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(2, 'KH240102', 'Trần Thị Bình', '0912345678', 'binh.tran@gmail.com', 'female', 1992, NULL, 'Nam Định', 2, 3, 'appointment', 'Cận thị - Lasik', 25000000, NULL, NULL, NULL, '2026-10-04 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(3, 'KH240103', 'Lê Hoàng Cường', '0923456789', 'cuong.le@yahoo.com', 'male', 1978, NULL, 'Hà Nam', 1, 4, 'consulted', 'Glaucoma', 18000000, NULL, NULL, NULL, '2026-10-05 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(4, 'KH240104', 'Phạm Thu Dung', '0934567890', 'dung.pham@gmail.com', 'female', 1995, NULL, 'Ninh Bình', 3, 3, 'contacted', 'Cận thị - Smile', 22000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(5, 'KH240105', 'Hoàng Minh Đức', '0945678901', 'duc.hoang@gmail.com', 'male', 1988, NULL, 'Thanh Hóa', 4, 4, 'new', 'Khám tổng quát', 500000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(6, 'KH240106', 'Vũ Thị Hoa', '0956789012', 'hoa.vu@gmail.com', 'female', 1965, NULL, 'Ninh Bình', 5, 3, 'surgery', 'Đục thủy tinh thể', 17000000, NULL, NULL, NULL, '2026-10-02 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(7, 'KH240107', 'Đặng Quốc Hùng', '0967890123', 'hung.dang@gmail.com', 'male', 1990, NULL, 'Nam Định', 1, 4, 'appointment', 'Võng mạc', 30000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(8, 'KH240108', 'Bùi Thị Lan', '0978901234', 'lan.bui@gmail.com', 'female', 1982, NULL, 'Hà Nam', 2, 3, 'done', 'Mắt lác', 12000000, NULL, NULL, NULL, '2026-10-01 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(9, 'KH240109', 'Ngô Văn Minh', '0989012345', 'minh.ngo@gmail.com', 'male', 1975, NULL, 'Ninh Bình', 1, 4, 'consulted', 'Cận thị - Lasik', 25000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(10, 'KH240110', 'Dương Thị Ngọc', '0990123456', 'ngoc.duong@gmail.com', 'female', 1998, NULL, 'Thanh Hóa', 3, 3, 'new', 'Cận thị - Smile', 22000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(11, 'KH240111', 'Lý Hoàng Phúc', '0901111111', 'phuc.ly@gmail.com', 'male', 1980, NULL, 'Ninh Bình', 6, 4, 'surgery', 'Đục thủy tinh thể', 16000000, NULL, NULL, NULL, '2026-10-03 09:44:06', 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(12, 'KH240112', 'Trịnh Thu Quỳnh', '0902222222', 'quynh.trinh@gmail.com', 'female', 1993, NULL, 'Nam Định', 1, 3, 'lost', 'Khám tổng quát', 500000, NULL, NULL, NULL, NULL, 1, '2026-10-03 02:44:06', '2026-10-03 02:44:06'),
(25, 'KH250101', 'Nguyễn Văn An', '0901234567', 'an.nguyen@gmail.com', 'male', 1985, NULL, 'Ninh Bình', 1, 3, 'surgery', 'Đục thủy tinh thể', 15000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(26, 'KH250102', 'Trần Thị Bình', '0912345678', 'binh.tran@gmail.com', 'female', 1992, NULL, 'Nam Định', 2, 3, 'appointment', 'Cận thị - Lasik', 25000000, NULL, NULL, NULL, '2026-10-04 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(27, 'KH250103', 'Lê Hoàng Cường', '0923456789', 'cuong.le@yahoo.com', 'male', 1978, NULL, 'Hà Nam', 1, 4, 'consulted', 'Glaucoma', 18000000, NULL, NULL, NULL, '2026-10-05 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(28, 'KH250104', 'Phạm Thu Dung', '0934567890', 'dung.pham@gmail.com', 'female', 1995, NULL, 'Ninh Bình', 3, 3, 'contacted', 'Cận thị - Smile', 22000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(29, 'KH250105', 'Hoàng Minh Đức', '0945678901', 'duc.hoang@gmail.com', 'male', 1988, NULL, 'Thanh Hóa', 4, 4, 'new', 'Khám tổng quát', 500000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(30, 'KH250106', 'Vũ Thị Hoa', '0956789012', 'hoa.vu@gmail.com', 'female', 1965, NULL, 'Ninh Bình', 5, 3, 'surgery', 'Đục thủy tinh thể', 17000000, NULL, NULL, NULL, '2026-10-02 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(31, 'KH250107', 'Đặng Quốc Hùng', '0967890123', 'hung.dang@gmail.com', 'male', 1990, NULL, 'Nam Định', 1, 4, 'appointment', 'Võng mạc', 30000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(32, 'KH250108', 'Bùi Thị Lan', '0978901234', 'lan.bui@gmail.com', 'female', 1982, NULL, 'Hà Nam', 2, 3, 'done', 'Mắt lác', 12000000, NULL, NULL, NULL, '2026-10-01 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(33, 'KH250109', 'Ngô Văn Minh', '0989012345', 'minh.ngo@gmail.com', 'male', 1975, NULL, 'Ninh Bình', 1, 4, 'consulted', 'Cận thị - Lasik', 25000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(34, 'KH250110', 'Dương Thị Ngọc', '0990123456', 'ngoc.duong@gmail.com', 'female', 1998, NULL, 'Thanh Hóa', 3, 3, 'new', 'Cận thị - Smile', 22000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(35, 'KH250111', 'Lý Hoàng Phúc', '0901111111', 'phuc.ly@gmail.com', 'male', 1980, NULL, 'Ninh Bình', 6, 4, 'surgery', 'Đục thủy tinh thể', 16000000, NULL, NULL, NULL, '2026-10-03 09:45:38', 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38'),
(36, 'KH250112', 'Trịnh Thu Quỳnh', '0902222222', 'quynh.trinh@gmail.com', 'female', 1993, NULL, 'Nam Định', 1, 3, 'lost', 'Khám tổng quát', 500000, NULL, NULL, NULL, NULL, 1, '2026-10-03 02:45:38', '2026-10-03 02:45:38');

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `category` enum('refraction','cataract','glaucoma','retina','strabismus','general','other') DEFAULT 'other',
  `icon` varchar(50) DEFAULT 'eye',
  `color` varchar(20) DEFAULT '#06b6d4',
  `description` text DEFAULT NULL,
  `price_from` decimal(15,0) DEFAULT 0,
  `price_to` decimal(15,0) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `code`, `name`, `category`, `icon`, `color`, `description`, `price_from`, `price_to`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'BENH01', 'Cận thị - Lasik', 'refraction', 'glasses', '#06b6d4', 'Phẫu thuật Lasik điều trị cận thị', 15000000, 30000000, 1, 1, '2026-10-03 06:33:12'),
(2, 'BENH02', 'Cận thị - Smile', 'refraction', 'sparkles', '#3b82f6', 'Phẫu thuật Smile không dao kéo', 22000000, 35000000, 1, 2, '2026-10-03 06:33:12'),
(3, 'BENH03', 'Đục thủy tinh thể', 'cataract', 'aperture', '#8b5cf6', 'Thay thủy tinh thể nhân tạo', 12000000, 25000000, 1, 3, '2026-10-03 06:33:12'),
(4, 'BENH04', 'Glaucoma (Cườm nước)', 'glaucoma', 'droplet', '#ec4899', 'Điều trị tăng nhãn áp', 8000000, 20000000, 1, 4, '2026-10-03 06:33:12'),
(5, 'BENH05', 'Bệnh võng mạc', 'retina', 'eye', '#f59e0b', 'Điều trị võng mạc tiểu đường, thoái hóa', 15000000, 40000000, 1, 5, '2026-10-03 06:33:12'),
(6, 'BENH06', 'Mắt lác (Lé)', 'strabismus', 'move', '#10b981', 'Phẫu thuật chỉnh mắt lác', 10000000, 18000000, 1, 6, '2026-10-03 06:33:12'),
(7, 'BENH07', 'Khám tổng quát', 'general', 'clipboard', '#64748b', 'Khám và tư vấn sức khỏe mắt', 300000, 800000, 1, 7, '2026-10-03 06:33:12'),
(8, 'BENH08', 'Khô mắt - Viêm kết mạc', 'other', 'droplets', '#0891b2', 'Điều trị khô mắt, viêm kết mạc', 500000, 3000000, 1, 8, '2026-10-03 06:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `external_id` int(11) DEFAULT NULL,
  `external_source` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT 'globe',
  `color` varchar(20) DEFAULT '#06b6d4',
  `type` enum('facebook','google','tiktok','walkin','referral','hotline','other') DEFAULT 'other',
  `category` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`id`, `external_id`, `external_source`, `name`, `icon`, `color`, `type`, `category`, `start_date`, `end_date`, `description`, `is_active`) VALUES
(1, NULL, NULL, 'Facebook Ads', 'facebook', '#1877f2', 'facebook', NULL, NULL, NULL, 'Khách hàng đến từ Facebook Ads', 1),
(2, NULL, NULL, 'Google Ads', 'search', '#ea4335', 'google', NULL, NULL, NULL, 'Khách hàng đến từ Google Ads', 1),
(3, NULL, NULL, 'TikTok', 'music', '#000000', 'tiktok', NULL, NULL, NULL, 'Khách hàng đến từ TikTok', 1),
(4, NULL, NULL, 'Hotline 1900', 'phone', '#f59e0b', 'hotline', NULL, NULL, NULL, 'Khách hàng đến từ Hotline 1900', 1),
(5, NULL, NULL, 'Khách vãng lai', 'footprints', '#10b981', 'walkin', NULL, NULL, NULL, 'Khách hàng đến từ Khách vãng lai', 1),
(6, NULL, NULL, 'Giới thiệu', 'users', '#8b5cf6', 'referral', NULL, NULL, NULL, 'Khách hàng đến từ Giới thiệu', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `status` enum('open','processing','resolved','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `code`, `customer_id`, `assigned_to`, `subject`, `content`, `priority`, `status`, `created_at`, `resolved_at`) VALUES
(1, 'TK240001', 1, 5, 'Hỏi về chế độ sau mổ', 'Khách hỏi có cần kiêng ăn gì không?', 'normal', 'open', '2026-10-03 02:44:06', NULL),
(2, 'TK240002', 6, 5, 'Đau mắt sau mổ', 'Khách báo đau nhẹ mắt phải sau mổ 2 ngày', 'high', 'processing', '2026-10-03 02:44:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','sales','cskh') DEFAULT 'sales',
  `team` varchar(50) DEFAULT NULL,
  `kpi_target` decimal(15,0) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `team`, `kpi_target`, `is_active`, `last_login`, `created_at`) VALUES
(1, 'Nguyễn Văn Admin', 'admin@hoalu.vn', '0900000001', '$2y$10$WufVBKStTr/socR0hvdlW.AlqjDWoPDPv/RBlR6EimtjRrQsYgMY.', 'admin', 'BOD', 0, 1, '2026-10-03 13:20:13', '2026-10-03 00:58:14'),
(2, 'Trần Quản Lý', 'manager@hoalu.vn', NULL, '$2y$10$WufVBKStTr/socR0hvdlW.AlqjDWoPDPv/RBlR6EimtjRrQsYgMY.', 'manager', 'Sales A', 500000000, 1, NULL, '2026-10-03 01:41:59'),
(3, 'Nguyễn Thị Lan', 'lan@hoalu.vn', NULL, '$2y$10$WufVBKStTr/socR0hvdlW.AlqjDWoPDPv/RBlR6EimtjRrQsYgMY.', 'sales', 'Sales A', 200000000, 1, NULL, '2026-10-03 01:41:59'),
(4, 'Lê Văn Hùng', 'hung@hoalu.vn', NULL, '$2y$10$WufVBKStTr/socR0hvdlW.AlqjDWoPDPv/RBlR6EimtjRrQsYgMY.', 'sales', 'Sales B', 200000000, 1, NULL, '2026-10-03 01:41:59'),
(5, 'Phạm Thu Hà', 'ha@hoalu.vn', NULL, '$2y$10$WufVBKStTr/socR0hvdlW.AlqjDWoPDPv/RBlR6EimtjRrQsYgMY.', 'cskh', 'CSKH', 0, 1, NULL, '2026-10-03 01:41:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_entity` (`entity`,`entity_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_scheduled` (`scheduled_at`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `source_id` (`source_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_assigned` (`assigned_to`),
  ADD KEY `idx_phone` (`phone`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_external` (`external_source`,`external_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `sources` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
