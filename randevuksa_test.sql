-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 11, 2026 at 07:30 PM
-- Server version: 8.0.46
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `randevuksa_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date NOT NULL,
  `gender` int NOT NULL DEFAULT '1' COMMENT '1 male , 2 female',
  `type` int NOT NULL DEFAULT '1' COMMENT '1 main admin ,2 supervisor',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone`, `password`, `image`, `dob`, `gender`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Dr Ayman', 'admin@admin.com', '01221274710', '$10$ApjSEBzmqXf.Hr7vStlp/ewJPgBpd.ClQPhYdgULwJP0NuJ5OZ232', NULL, '1991-01-01', 1, 1, 1, '0000-00-00 00:00:00', '2022-10-17 01:28:56', '2022-10-17 01:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `age_categories`
--

CREATE TABLE `age_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `age_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_from_period` enum('days','years') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'years',
  `age_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_to_period` enum('days','years') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'years',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `age_categories`
--

INSERT INTO `age_categories` (`id`, `age_from`, `age_from_period`, `age_to`, `age_to_period`, `status`, `created_at`, `updated_at`) VALUES
(1, '0', 'days', '30', 'days', 1, '2024-05-03 17:14:43', '2024-05-03 17:14:43'),
(2, '30', 'days', '2', 'years', 1, '2024-05-03 17:14:43', '2024-05-03 17:14:43'),
(3, '3', 'years', '12', 'years', 1, '2024-05-03 17:14:43', '2024-05-03 17:14:43'),
(4, '13', 'years', '20', 'years', 1, '2024-05-03 17:14:43', '2024-05-03 17:14:43'),
(5, '21', 'years', '120', 'years', 1, '2024-05-03 17:14:43', '2024-05-03 17:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `all_permissions`
--

CREATE TABLE `all_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `permission` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `all_permissions`
--

INSERT INTO `all_permissions` (`id`, `permission`, `name_en`, `name_ar`, `flag`, `status`, `parent_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'complaints', 'complaints', 'الشكاوى', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(2, 'complaint_view', 'View', 'عرض', 1, 1, 1, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(3, 'complaint_reply', 'Reply', 'الرد', 6, 1, 1, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(4, 'posts', 'Posts', 'المشاركات', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(5, 'post_view', 'View', 'عرض', 1, 1, 4, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(6, 'post_add', 'Add', 'اضافة', 2, 1, 4, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(7, 'post_edit', 'Edit', 'تعديل', 3, 1, 4, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(8, 'post_delete', 'Delete', 'حذف', 4, 1, 4, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(9, 'attendance', 'Attendance', 'الحضور والانصراف', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(10, 'attendance_view', 'manage', 'إدارة الحضور والاذونات', 1, 1, 9, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(11, 'Profile', 'profile', 'الملف الشخصى', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(12, 'profile_view', 'View ', 'عرض', 1, 1, 11, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(13, 'profile_edit', 'Edit', 'تعديل', 3, 1, 11, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(14, 'Departments', 'departments', 'الاقسام', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(15, 'department_view', 'View', 'عرض', 1, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(16, 'department_add', 'Add', 'اضافة', 2, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(17, 'department_edit', 'Edit', 'تعديل', 3, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(18, 'department_status', 'Status', 'تفعيل وعدم تفعيل', 5, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(19, 'department_shift', 'Shifts department', 'ادارة شيفت', 7, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(20, 'department_employees', 'show employees', 'عرض موظفيين', 8, 1, 14, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(21, 'Offers', 'Offers', 'العروض', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(22, 'offers_view', 'View', 'عرض', 1, 1, 21, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(23, 'offers_add', 'Add', 'اضافة', 2, 1, 21, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(24, 'offer_edit', 'Edit', 'تعديل', 3, 1, 21, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(25, 'offers_delete', 'Delete', 'حذف ', 4, 1, 21, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(26, 'specialties', 'specialties', 'التخصصات', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(27, 'specialties_view', 'View', 'عرض', 1, 1, 26, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(28, 'specialties_add', 'Add', 'اضافة', 2, 1, 26, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(29, 'specialties_edit', 'Edit', 'تعديل', 3, 1, 26, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(30, 'specialties_delete', 'Delete', 'حذف', 4, 1, 26, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(31, 'Branches', 'branches', 'فروع العيادة', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(32, 'branches_view', 'View', 'عرض', 1, 1, 31, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(33, 'branches_add', 'Add', 'اضافة', 2, 1, 31, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(34, 'branches_edit', 'Edit', 'تعديل', 3, 1, 31, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(35, 'branches_status', 'Status', 'تفعيل وعدم تفعيل', 5, 1, 31, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(36, 'Supervisors managment', 'supervisors', 'المشرفيين', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(37, 'supervisors_view', 'View', 'عرض', 1, 1, 36, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(38, 'supervisors_add', 'Add', 'اضافة', 2, 1, 36, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(39, 'supervisors_edit', 'Edit', 'تعديل', 3, 1, 36, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(40, 'supervisors_permissions', 'permissions', 'صلاحيات', 9, 1, 36, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(41, 'Points', 'points', 'نقاط العيادة', 0, 1, NULL, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(42, 'points_view', 'View', 'عرض', 1, 1, 41, NULL, '2022-08-19 16:55:34', '2022-08-19 16:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `app_types`
--

CREATE TABLE `app_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint DEFAULT NULL,
  `type` int NOT NULL DEFAULT '2',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_types`
--

INSERT INTO `app_types` (`id`, `name_en`, `name_ar`, `clinic_id`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'clinics', 'عيادات', NULL, 1, 0, NULL, '2022-07-03 03:15:53', '2022-07-03 03:15:53'),
(2, 'Reciption', 'الاستقبال', NULL, 1, 0, NULL, '2022-07-13 01:19:44', '2022-08-05 17:58:27'),
(3, 'Doctors', 'الأطباء', NULL, 1, 0, NULL, '2022-07-13 01:19:44', '2022-08-05 15:37:00'),
(4, 'Labs', 'المعامل', NULL, 1, 0, NULL, '2022-07-13 01:19:44', '2022-07-13 01:19:44'),
(5, 'pharmacy', 'صيدلية', NULL, 1, 0, NULL, '2022-07-13 01:19:44', '2022-08-05 04:28:06'),
(6, 'Admin', 'الادمن', NULL, 0, 0, NULL, '2022-07-14 10:59:50', '2022-08-05 01:00:33'),
(7, 'Branches', 'فروع العياده', NULL, 0, 0, NULL, '2022-08-04 01:38:41', '2022-08-04 14:45:39'),
(8, 'Nursing', 'تمريض', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06'),
(9, 'Accounting', 'الحسابات', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06'),
(10, 'Insurances', 'التامينات', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06'),
(11, 'Admin', 'ادمن العيادة', NULL, 11, 0, NULL, '2022-07-14 10:59:50', '2022-08-05 01:00:33'),
(25, 'Lab', 'معمل', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06'),
(26, 'Rays', 'أشعة', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06'),
(27, 'Employee', 'الموظفيين', NULL, 1, 0, NULL, '2022-07-13 02:19:44', '2022-08-05 05:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `account_type` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `day_id` bigint UNSIGNED NOT NULL,
  `check_in` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `another_employee` bigint UNSIGNED DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings`
--

CREATE TABLE `attendance_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `attendance_period` int NOT NULL COMMENT 'hours',
  `leaving_period` int NOT NULL COMMENT 'hours',
  `extra_time` int NOT NULL COMMENT 'hours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_settings`
--

INSERT INTO `attendance_settings` (`id`, `clinic_id`, `attendance_period`, `leaving_period`, `extra_time`, `created_at`, `updated_at`) VALUES
(2, 1, 4, 4, 8, '2023-08-19 07:41:35', '2023-08-19 07:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `bonds`
--

CREATE TABLE `bonds` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reception_id` bigint UNSIGNED NOT NULL,
  `account_type` enum('register_in_safe','register_with_card') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'register_in_safe',
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `account_id` int UNSIGNED DEFAULT NULL,
  `movement_type` enum('receipt_bond','exchange_bond') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_method` enum('cache','visa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint UNSIGNED DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name_en`, `name_ar`, `country_id`, `status`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Riyadh', 'الرياض', 1, 1, NULL, NULL, '2022-07-03 04:24:32', '2026-05-31 21:00:00'),
(5, 'Eastern', 'الشرقية', 1, 0, NULL, NULL, '2022-07-03 04:24:32', '2026-05-31 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snapchat_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monitor_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monitor_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_type` bigint UNSIGNED NOT NULL DEFAULT '1',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `package_end_date` date DEFAULT NULL,
  `communication_officer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `communication_officer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT '0',
  `lng` double DEFAULT '0',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int DEFAULT '1' COMMENT '1 male, 2 female ,3 other',
  `specialization` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `points_enabled` tinyint NOT NULL DEFAULT '0',
  `points_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `platform` tinyint NOT NULL DEFAULT '1' COMMENT '1 android ,2 ios',
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jwt_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `info_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `degree_id` bigint DEFAULT NULL,
  `ID_Number` int DEFAULT NULL,
  `license_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_commercial_license` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `firebase_token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tax` int NOT NULL DEFAULT '15',
  `is_manager` int NOT NULL DEFAULT '0' COMMENT '1 is manager nursing	',
  `nursing_point_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`id`, `name`, `email`, `phone`, `password`, `image`, `post_number`, `fax`, `website`, `facebook_url`, `instagram_url`, `tiktok_url`, `snapchat_url`, `youtube_url`, `monitor_username`, `monitor_password`, `qr_code`, `app_type`, `parent_id`, `city_id`, `date_created`, `package_end_date`, `communication_officer`, `communication_officer_phone`, `alternative_phone`, `lat`, `lng`, `address`, `gender`, `specialization`, `status`, `points_enabled`, `points_category`, `enabled_modules`, `platform`, `device_token`, `jwt_token`, `info`, `info_ar`, `degree_id`, `ID_Number`, `license_number`, `medical_commercial_license`, `condition`, `firebase_token`, `tax`, `is_manager`, `nursing_point_id`, `notes`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'Al Hayah Hospital', 'clinic@gmail.com', '0522212369', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16850300511957.jpg', NULL, NULL, NULL, 'https://www.facebook.com/Alhayatalgadidahospital/?_rdr', 'https://www.instagram.com/hnhgrooup', NULL, NULL, 'https://www.youtube.com/channel/UCueDME1ckhHWNJOtT41n4XQ', NULL, NULL, '16850262478092.jpg', 1, 1, 5, '1970-01-01', NULL, 'دكتور احمد', NULL, NULL, 24.4412804, 39.6198035, 'مستشفى الحياة الوطني، طريق الهجرة الفرعي، المدينة المنورة السعودية', 1, NULL, 1, 1, NULL, '[\"points\",\"clinic_admin\"]', 1, '2134', 'nVBQ6w8i32ALah9wccEgup2Zlxyj3VHXIxzsvsgf1HEBlzLPMC1673301726', 'مستشفى الحياة طريق الهجره', NULL, NULL, 930230712, NULL, NULL, NULL, '2134', 15, 0, NULL, NULL, NULL, '2022-07-03 04:44:23', '2026-07-18 11:28:47'),
(189, 'مدير المشروع', 'admin@gmail.com', '01221274700', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16850300511957.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16850262478092.jpg', 6, 1, 5, '1970-01-01', NULL, 'دكتور احمد', NULL, NULL, 24.4412804, 39.6198035, 'مستشفى الحياة الوطني، طريق الهجرة الفرعي، المدينة المنورة السعودية', 1, NULL, 1, 0, NULL, NULL, 1, '2134', 'nVBQ6w8i32ALah9wccEgup2Zlxyj3VHXIxzsvsgf1HEBlzLPMC1673301725', 'مستشفى الحياة طريق الهجره', NULL, NULL, 930230712, NULL, NULL, NULL, '2134', 15, 0, NULL, NULL, NULL, '2022-07-03 04:44:23', '2026-05-13 21:17:49'),
(194, 'reception', 'rec@gmail.com', '01221274712', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17659851225359.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'ykqSa2hJMy0KYxHTFjW5V0Tosxj53FOgewtlkxilyqjkDxavNrce2Y8aHvqXwO9r8mhVEWXsG8q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2025-12-17 15:25:22', '2025-12-17 15:25:22'),
(196, 'Rasel', 'recepr60@gmail.com', '0544615283', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17790489788359.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 2, NULL, 1, 0, NULL, NULL, 1, NULL, 'wDtcLZfR3IAZEb5mcHVBCHeGb4Uv4wCRPO3jprjyFOLsmFu3oNfYNMRq2gOChUZmuJgeuFPXjV9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2025-12-22 09:23:01', '2026-05-18 22:32:32'),
(197, 'test', 'test@gmail.com', '0111122223355', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17663965814125.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'Dz2XtsoS16XNtC0tUgHAVWAw4MzIFlVAwgBEY4b3zOnDkP6r0S7KoGxPAq3xGL3sSfus7gVSgEH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2025-12-22 09:43:01', '2026-05-17 13:53:33'),
(200, 'مستشفى المواساة', 'info@mouwasat.com', '+966 920 004 477', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711467056383.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 200, 1, NULL, '2026-06-15', NULL, NULL, NULL, 24.3450512, 54.7460581, 'مدينة الرياض - Abu Dhabi - United Arab Emirates', 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'o7AMWV2IcbHUjnF46upPVYQd1XfveVjD7lJlPjca8n0LMmXsTtdxXUZDzSnMjcm4jdX8Zdjs2sU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 09:11:45', '2026-02-15 09:11:45'),
(201, 'مستشفي السلام', 'Elslam@gmail.com', '01020685285', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711682619617.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 201, 1, '1970-01-01', '2028-02-05', NULL, NULL, NULL, 24.7135517, 46.6752957, 'الرياض Saudi Arabia', 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'VJDRRjW9MHJ1hKe1o9ge1XwLMW7iHehYHWbclGXwfAotvq9CSkoDvpj8ZW18W2JhP2Ev6UNAEMg', 'تفاصيل العيادة', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 15:11:01', '2026-03-25 16:17:25'),
(202, 'احمد علي', 'ahmed1@hospital.com', '01010000001', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711710607276.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'FBrWK3w57nLKvkGerTc4S1UAqKuBdm6VC5c2iL9nekaGmkBiQVWZA7Ya89mhlYi85Ex65XxzMfq', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(203, 'محمود حسن', 'mahmoud2@hospital.com', '01010000002', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711712183876.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, '5N5gzneogRevZTd1QWdYFtbGCb1PpiOqQtjPXCWnON3tdd9ERIQR9HJPMUmmQf0ssURPReK4JYv', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:00:18', '2026-02-15 16:00:18'),
(204, 'كريم محمد', 'karim3@hospital.com', '01010000003', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711714959013.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'WwcvaQ3MTF7tonjNObosxJxoW0P6TXdCQ2a7xQzSuih6dw8BhQQ0cVsYx8HsBDviUBcsx82aZjo', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:04:55', '2026-02-15 16:04:55'),
(205, 'اسلام سيد', 'islam4@hospital.com', '01010000004', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711716507776.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, '3h39hImmi12dWl95RBpOXJCO08Okikep4Kwf3F5J0NQG2d0AHsCQVZanj95CDEj5Sblyz6Yw6zD', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(206, 'يوسف خالد', 'youssef5@hospital.com', '01010000005', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711717349460.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'KtiPv9JeVp0lcY2kefP44zgns3OIbKTMg9Gi0c8SeROzYf0leOcvi5fBW2N8vMfFfJtJ4dLTmOp', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(207, 'محمد فؤاد', 'cardio6@hospital.com', '01010000006', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711718303783.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'jEgp9B3xcw7ww00PVxXuWMHI0KB8DDTGljhxZVlmkIJmohbfUPLd5WLqxBh3rkSJUVVRqJdCQ4S', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:10:30', '2026-02-15 16:10:30'),
(208, 'شريف عادل', 'cardio7@hospital.com', '01010000007', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711718945445.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'rYQKeXC1itwW79ztExNNCmS0EI1jwYlLgejzL5v6neR07CdVZqaOHneluy7TxVSGOTzZgsI9CMA', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(209, 'عمرو سامي', 'cardio8@hospital.com', '01010000008', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711719446553.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'KOdigdHzXW8Uk3gYADTlaa2PEKrQymCboe9qs1xkVvvEieTJD0pk5DZYu6Pr28GBRZRzJtFLyRJ', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(210, 'تامر زكي', 'cardio9@hospital.com', '01010000009', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711719956716.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'lp1t8ONEY2DHxE6i7JQ4Jdud4KwXdfTv0GDBMBOJTxzW0uIbxwvac6ch0NUSpRzDyQXdASzMS5X', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(211, 'حسام لطفي', 'cardio10@hospital.com', '01010000010', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17711720466060.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'tGhwrpArEA2l832x6mCcWPL88AsQxJCMilVkNLNv8F0Ar2UMkGIf2fWCxTEcWC8A4Jb3K8GKby5', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(213, 'test test', 'testtesttest@gmail.com', '01234567802', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17751695233774.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'WROZd8QTrc5usfuUJkZxMGURytWOUbhYDjOfigqt5gRo0ovDuWpFtuodrX1BJRGLyWW74UKcTmS', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-04-02 22:38:43', '2026-04-02 22:38:43'),
(215, 'مريم محمد', 'reem.doctor12@gmail.com', '01221334717', NULL, '17776770535109.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 2, NULL, 1, 0, NULL, NULL, 1, NULL, 'Tst51MUZFdr46sKivcNo3CTM28XVFWwtkh98upWIDnBSrjevFYgyZH6nUm7gqW4HS38r04qNCTB', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-05-01 23:10:53', '2026-05-22 12:47:42'),
(221, 'MD soltan', 'clinic55@gmail.com', '05446623', '$2y$10$ImxHJ8JRezKtyIkt4xMyy.w6A7ZZmu7I25/w4xcX73ZoPSkOVGxnW', '17795306044162.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'TqR59lxZiILIzyp4ZL8z6nv2nVYmoYNG6GwSsCMNTZUg3jFnt3hva1SqTtYGPAb5w2WrySlUlhQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-05-16 15:45:34', '2026-05-23 10:03:24'),
(224, 'عيادات الحياة فرع خريص', 'hyyat-khorass@gmail.com', '011252525', '$2y$10$4gDDwAoNF7AMsp33sQpNMOkqk.IGZyKxrotQEITZubBkWWU7qnnQq', '17792814492447.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'ZES0vqcrgGYgZ23j0FHzCfceNbFPzyQj83gNGG99z6IN8DG66lubyOLyb6YaYRV2cu4EK8DGGyO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-05-20 12:50:49', '2026-07-01 06:18:03'),
(225, 'Ahmed Shahin', 'magdywork961@gmail.com', '01144010937', '$2y$10$9plvARherojS8.aP7JqjaOOYYwFljuiTBly2Ky7DZd.B9mt00Daye', '17792920388994.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'quw9optz52UcBtYlXgVnxRN5Mz3TXKgMTCyDa5ZIORNV1QnP3ub7pRillMNTLpPI2EzFlaKjFh9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-05-20 15:47:18', '2026-05-30 20:11:46'),
(226, 'hanan', 'clinic-manager@gmail.com', '0544515273', '$2y$10$ygde/i8H85YiufCXsSXGve89yKHkoE6BPqOaBqdxFGO4LhPI4TItW', '17795308663162.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 0, 0, NULL, NULL, 1, NULL, 'WMeRkd7zCo7xBpyBi8w4pgbnyFwPURzQ1UQCxegjNWh2GDMhqkxS2yibSuEzR1rTf56USwyxG4h', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-05-23 10:07:46', '2026-06-07 08:38:19'),
(227, 'راكان   الشمرى', 'rakan.shamria@gmail.com', '0544665583', NULL, '17803189629994.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'yF7NdjIKpKqVIpW8IwJeeN1MdsVv815a8TcibwAo2ikRIcDC9mDzRiZyOGN2u7LgapawZjGSemS', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-06-01 13:02:42', '2026-06-02 08:38:48'),
(234, 'احمد علي', 'ahmad@gmail.com', '09925586040', '$2y$10$MTAAgLqFWG0Do4ZLv2cvde5sFEmLaqJcrehd/.j2nFM0O2D0osFEG', '17827384762939.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'Ca2Gp5UYursEgnci5kwOZo1v5TNtjd0SgyCNoKyrKFbzHOtbhJgdvyp3tOUuhxD8JL8LN0FKzIX', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-06-29 13:07:56', '2026-06-29 13:07:56'),
(235, 'اية شعبان', 'ayashaban123@gmail.com', '0096369935886045', NULL, '17827387811303.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 2, NULL, 1, 0, NULL, NULL, 1, NULL, 'EMO8X1oNK6dpGGNq39xeEiMFEIKdF0UOSzYDnRiOsAsg2Lf5COkRKQzjjc3tHMnVLOVAtjQgaD8', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-06-29 13:13:01', '2026-08-10 12:08:09'),
(243, 'مستشفى الامل', 'alamalhospital@gmail.com', '0096675488321', '$2y$10$RCfNiIXp9uCKz7zz5G24ru4lGr0DaKoqmU.Kf7gZi/VDriV5vfDf.', '17829402287125.jpeg', NULL, NULL, NULL, 'https://www.instagram.com/aya_shaban68?igsh=MWp2cHExZThoNTll', 'https://www.instagram.com/aya_shaban68?igsh=MWp2cHExZThoNTll', 'https://www.instagram.com/aya_shaban68?igsh=MWp2cHExZThoNTll', 'https://www.instagram.com/aya_shaban68?igsh=MWp2cHExZThoNTll', 'https://www.instagram.com/aya_shaban68?igsh=MWp2cHExZThoNTll', NULL, NULL, '17830775393578.jpeg', 1, 243, 1, '1970-01-01', '2027-06-27', 'اية الخالد', '09966547812', NULL, 21.485811, 39.192505, 'الرياض', 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'JBuvyFc5c0p5stUYztZwhFCCyd3cA5D1tFtNp6zh6KclMeYYUGbr7X59gaL0dnPSRwQQQGtU5O9', 'منشأة طبية تجمع عدة تخصصات', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-01 21:10:28', '2026-07-03 11:18:59'),
(245, 'نور الحمدان', 'nour1245@gmail.com', '009665471234', '$2y$10$7m0EjkJOVmsp4wbE0kez5esppsi0SfpqUbG6rgnXQsJvKDIimJFWG', '17831703646579.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 243, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 2, NULL, 1, 0, NULL, NULL, 1, NULL, '8zKVzy6aS3JQZpm52KfWILlpWEIqhaFyTSv6XB6riHJ6ZDnrACjDroPnVl8AUaHmVi8JaiYbaY0', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-04 13:06:04', '2026-07-04 13:06:04'),
(246, 'خالد العايدي', 'khaled2245@gmail.com', '009668236518', '$2y$10$BO7Joi9B7O5wf62DyRdZA.RVFFf20MTrTd/Q7rfIuF10krSSQn3eS', '17833477617103.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 243, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'J5OUVohcbU4PeUgppUXGl7aF14KqSgVqi6rMQJZG3pJFdJXttSXIFfoykXMPOe0hBXj3klEXK8x', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-06 14:22:41', '2026-07-06 14:22:41'),
(247, 'مايا الخاني', 'mayaalkhany33@gmail.com', '009966588334', '$2y$10$I6lIV/QiQf1tIHmAZHR7YeN7NoAc.tyYyL8PGQYRhNnQntBsws/.C', '17833487582752.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 243, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 2, NULL, 1, 0, NULL, NULL, 1, NULL, 'MRGMBmeMPpMGNM7vWtsPQeEmSNG0nm5uZ0AY2RKfdbynWrUcesNU0dzMzANs3qavkYK5oSVOir3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-06 14:39:18', '2026-07-06 14:39:18'),
(248, 'مستشفى الامل', 'alamalhospital22@gmail.com', '00996643776455', '$2y$10$bD5S1ggDNDZ8iG04NJnpzOzlj.foVnqpCLi8HQqAMoQQBFXZaHPeO', '17833582979660.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 243, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, 1, 0, NULL, NULL, 1, NULL, 'rOtEbZdNCSDyJBTkWjyQB9i5s5CUHCZqMwfgoi1W6y85t9ZmUAYpEKJYhWJ0I505G32qVA3Ll1n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-06 17:18:17', '2026-07-06 17:18:17'),
(249, 'asssss', 'y@gmail.com', '+201221274986', '$2y$10$sWDkbemR2f8uDgBfbLuSse9YSlh6IuRvlKGIe6P5a.krxgpmZSFii', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 249, NULL, '2026-07-31', '2027-08-26', NULL, NULL, NULL, 0, 0, 'sssssss', 1, NULL, 0, 0, NULL, NULL, 1, NULL, 'UnxPsB9rHCE7Q3u2yBYiTrrssTeYjqRuNifcBzwTi7ysUESirBUHVRiofEPmt9cwunIVknWVAD4', NULL, NULL, NULL, NULL, '422344555433223', '1785520673_GCynJadI.jpg', NULL, NULL, 15, 0, NULL, NULL, NULL, '2026-07-31 17:57:53', '2026-07-31 17:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `clinics_permissions`
--

CREATE TABLE `clinics_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `child_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic_contracts`
--

CREATE TABLE `clinic_contracts` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `contract_model` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash_commission',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `annual_subscription_amount` decimal(10,2) DEFAULT NULL,
  `annual_subscription_starts_at` date DEFAULT NULL,
  `annual_subscription_ends_at` date DEFAULT NULL,
  `rendezvous_badge_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_contracts`
--

INSERT INTO `clinic_contracts` (`id`, `clinic_id`, `contract_model`, `commission_rate`, `annual_subscription_amount`, `annual_subscription_starts_at`, `annual_subscription_ends_at`, `rendezvous_badge_enabled`, `created_at`, `updated_at`) VALUES
(1, 1, 'cash_commission', 0.00, NULL, NULL, NULL, 0, '2026-08-03 11:05:23', '2026-08-03 11:05:23'),
(2, 194, 'cash_commission', 0.00, NULL, NULL, NULL, 0, '2026-08-03 11:05:23', '2026-08-03 11:05:23'),
(3, 197, 'cash_commission', 0.00, NULL, NULL, NULL, 0, '2026-08-03 11:05:23', '2026-08-03 11:05:23'),
(4, 196, 'cash_commission', 0.00, NULL, NULL, NULL, 0, '2026-08-03 11:05:23', '2026-08-03 11:05:23'),
(8, 201, 'annual_subscription', 0.00, 5000.00, '2026-08-01', '2027-08-01', 1, '2026-08-10 17:44:11', '2026-08-10 17:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_device_tokens`
--

CREATE TABLE `clinic_device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `device_token` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firebase_token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic_offers`
--

CREATE TABLE `clinic_offers` (
  `id` bigint UNSIGNED NOT NULL,
  `title_en` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `specialty_id` bigint UNSIGNED DEFAULT NULL,
  `discount` int NOT NULL DEFAULT '1' COMMENT 'discount per percentage',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_offers`
--

INSERT INTO `clinic_offers` (`id`, `title_en`, `title_ar`, `clinic_id`, `specialty_id`, `discount`, `start_date`, `end_date`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'public offer', 'خصومات عامة', 1, NULL, 5, NULL, NULL, 1, '2022-08-04 15:16:41', '2022-07-25 23:52:02', '2022-08-04 15:16:41'),
(2, 'Eid discount 222', 'ssss ssss', 1, NULL, 20, NULL, NULL, 1, '2022-08-05 01:12:06', '2022-07-25 23:53:53', '2022-08-05 01:12:06'),
(3, 'The New Doctor', 'الاطباء الجدد', 1, NULL, 10, NULL, NULL, 1, '2022-07-25 23:59:03', '2022-07-25 23:58:38', '2022-07-25 23:59:03'),
(4, 'The New Doctor', 'الاطباء الجدد', 1, NULL, 10, NULL, NULL, 1, '2022-12-19 05:21:40', '2022-08-04 13:27:18', '2022-12-19 05:21:40'),
(5, 'The New Doctor', 'الاطباء الجدد', 1, NULL, 10, NULL, NULL, 1, NULL, '2022-08-04 13:27:24', '2022-08-04 13:27:24'),
(6, 'new offer', 'aa', 1, NULL, 99, NULL, NULL, 1, '2023-04-12 17:12:58', '2022-08-04 14:44:44', '2023-04-12 17:12:58'),
(7, 'treetment', 'الادوية', 1, 17, 20, NULL, NULL, 1, NULL, '2022-08-04 14:49:53', '2026-04-07 06:16:30'),
(8, 'ddddd', 'sss', 1, NULL, 22, NULL, NULL, 1, '2022-08-05 16:17:49', '2022-08-05 01:11:01', '2022-08-05 16:17:49'),
(9, 'optecs', 'البصريات', 1, 18, 60, NULL, NULL, 1, NULL, '2022-08-05 16:17:36', '2026-04-07 06:16:22'),
(10, 'test', 'tesr', 1, NULL, 20, NULL, NULL, 1, '2022-08-25 01:03:47', '2022-08-25 01:03:36', '2022-08-25 01:03:47'),
(12, 'The New Doctor', 'الاطباء الجدد', 1, 24, 10, NULL, NULL, 1, NULL, '2022-09-05 05:02:09', '2026-04-07 06:16:09'),
(15, 'new', 'عرض جديد', 1, NULL, 63, NULL, NULL, 1, '2023-03-12 06:30:47', '2023-03-12 06:29:56', '2023-03-12 06:30:47'),
(16, 'Scaling & polishing', 'ازالة الجير', 1, 25, 20, NULL, NULL, 1, NULL, '2023-03-13 07:29:15', '2026-04-07 06:15:55'),
(17, 'One day surgery', 'اليوم الواحد', 1, NULL, 15, NULL, NULL, 1, '2023-05-24 14:33:20', '2023-04-12 17:13:45', '2023-05-24 14:33:20'),
(18, 'rrrrr', 'rrr', 1, NULL, 34, NULL, NULL, 1, '2023-05-25 06:31:20', '2023-05-25 06:31:08', '2023-05-25 06:31:20'),
(21, 'skin', 'الجلديه', 1, 19, 10, NULL, NULL, 1, NULL, '2025-12-22 09:41:57', '2026-04-28 21:23:08'),
(22, 'new pat', 'مرضي الجدد', 1, 25, 50, NULL, NULL, 1, '2026-04-28 21:08:56', '2026-04-28 21:08:45', '2026-04-28 21:08:56'),
(23, 'test', 'تيست', 1, 22, 20, '2026-05-10', '2026-05-30', 1, '2026-05-13 21:26:18', '2026-05-13 21:24:52', '2026-05-13 21:26:18'),
(24, 'test', 'تيست', 1, 25, 20, '2026-05-17', '2026-05-31', 1, '2026-05-17 13:52:37', '2026-05-17 13:52:06', '2026-05-17 13:52:37'),
(25, 'Al Ragihi bank', 'خصم منسوبي بنك الراجحي', 1, 17, 5, '2026-05-20', '2026-06-20', 1, NULL, '2026-05-20 12:42:53', '2026-05-20 12:43:08'),
(26, 'kkkk', 'lll', 1, 15, 50, '2026-05-20', '2026-05-28', 1, NULL, '2026-05-20 15:51:26', '2026-05-20 15:51:26'),
(27, 'Al Ragihi bank', 'خصم منسوبي بنك الراجحي', 1, 15, 5, '2026-05-20', NULL, 1, NULL, '2026-05-20 17:02:42', '2026-05-20 17:02:42'),
(28, 'Al Ragihi bank', 'بنك الراجى', 1, 22, 5, '2026-06-22', '2026-06-25', 1, NULL, '2026-05-20 17:02:42', '2026-07-01 08:37:37');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_points`
--

CREATE TABLE `clinic_points` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `content_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_points`
--

INSERT INTO `clinic_points` (`id`, `clinic_id`, `content_ar`, `content_en`, `point`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'رد على شكوى خاصه بعميل ', 'Answer question reply on user', 5, 1, '2022-08-10 13:54:15', NULL),
(8, 1, 'thanks', 'thanks', 5, 1, '2024-12-18 13:50:59', '2024-12-18 13:50:59'),
(9, 1, 'ddddddddd', 'ddddddddd', 5, 1, '2025-07-05 08:50:40', '2025-07-05 08:50:40'),
(10, 1, 'ok', 'ok', 5, 1, '2025-12-22 09:45:07', '2025-12-22 09:45:07'),
(11, 1, 'test', 'test', 5, 1, '2026-02-05 22:44:07', '2026-02-05 22:44:07'),
(12, 1, 'تمام', 'تمام', 5, 1, '2026-03-26 22:11:15', '2026-03-26 22:11:15'),
(13, 1, 'test', 'test', 5, 1, '2026-03-27 19:53:40', '2026-03-27 19:53:40'),
(14, 1, 'تم النظر في التعليق \r\n\r\nالكلام لا يصل منسق ولكن كامل', 'تم النظر في التعليق \r\n\r\nالكلام لا يصل منسق ولكن كامل', 5, 1, '2026-03-31 08:31:58', '2026-03-31 08:31:58'),
(15, 1, 'تمام\r\nتمام\r\nتمام', 'تمام\r\nتمام\r\nتمام', 5, 1, '2026-03-31 21:22:17', '2026-03-31 21:22:17'),
(16, 1, 'تمام \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 'تمام \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 5, 1, '2026-03-31 21:24:54', '2026-03-31 21:24:54'),
(17, 204, 'تيست ادمن \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتيست كريم ادمن', 'تيست ادمن \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتيست كريم ادمن', 5, 1, '2026-03-31 21:30:53', '2026-03-31 21:30:53'),
(18, 1, 'تمام \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 'تمام \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 5, 1, '2026-04-01 22:43:14', '2026-04-01 22:43:14'),
(19, 1, 'تمام\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 'تمام\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتمام', 5, 1, '2026-04-03 22:31:39', '2026-04-03 22:31:39'),
(20, NULL, 'test', 'test', 5, 1, '2026-04-15 14:22:15', '2026-04-15 14:22:15'),
(21, 1, 'تمام\r\n\r\n\r\n\r\n\r\n\r\nتمام', 'تمام\r\n\r\n\r\n\r\n\r\n\r\nتمام', 5, 1, '2026-04-15 22:22:59', '2026-04-15 22:22:59'),
(22, NULL, 'okkkk okkkk\r\n\r\n\r\n\r\n\r\n\r\n\r\nok ok', 'okkkk okkkk\r\n\r\n\r\n\r\n\r\n\r\n\r\nok ok', 5, 1, '2026-04-15 22:29:51', '2026-04-15 22:29:51'),
(23, 1, 'هيتم التواصل مع حضرتك في اقرب وقت', 'هيتم التواصل مع حضرتك في اقرب وقت', 5, 1, '2026-04-28 21:18:17', '2026-04-28 21:18:17'),
(24, NULL, 'تماممممم', 'تماممممم', 5, 1, '2026-05-03 15:50:58', '2026-05-03 15:50:58'),
(25, 1, 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\nممكن توضيح', 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\nممكن توضيح', 5, 1, '2026-05-03 16:01:55', '2026-05-03 16:01:55'),
(26, 1, 'تم الرد', 'تم الرد', 5, 1, '2026-05-13 21:17:21', '2026-05-13 21:17:21'),
(27, NULL, 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nاوك', 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nاوك', 5, 1, '2026-05-17 14:56:09', '2026-05-17 14:56:09'),
(28, NULL, 'ماهي الشكوي \r\n\r\n\r\n\r\n\r\n\r\nجديد', 'ماهي الشكوي \r\n\r\n\r\n\r\n\r\n\r\nجديد', 5, 1, '2026-05-17 14:59:43', '2026-05-17 14:59:43');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_point_nursings`
--

CREATE TABLE `clinic_point_nursings` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `clinic_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_point_nursings`
--

INSERT INTO `clinic_point_nursings` (`id`, `name_en`, `name_ar`, `status`, `clinic_id`, `created_at`, `updated_at`) VALUES
(3, 'point A', 'نقطه أ', 1, 1, NULL, NULL),
(4, 'point B', 'نقطه ب', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clinic_posts_counts`
--

CREATE TABLE `clinic_posts_counts` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_count_from` int NOT NULL DEFAULT '1',
  `doctor_count_to` int NOT NULL DEFAULT '1',
  `post_count` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_posts_counts`
--

INSERT INTO `clinic_posts_counts` (`id`, `doctor_count_from`, `doctor_count_to`, `post_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 1, '2023-04-30 22:09:53', '2023-04-30 22:09:53'),
(2, 4, 10, 3, 1, '2023-04-30 22:09:53', '2023-04-30 22:09:53'),
(3, 11, 1000, 7, 1, '2023-04-30 22:09:53', '2023-04-30 22:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_ratings`
--

CREATE TABLE `clinic_ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating_id` bigint UNSIGNED NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_ratings`
--

INSERT INTO `clinic_ratings` (`id`, `clinic_id`, `user_id`, `rating_id`, `comment`, `rate_value`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(16, 1, 28, 1, 'جيد جدا \n\n\n\n\n\n\nجيد جدا', '4', 1, NULL, '2026-03-29 15:25:31', '2026-04-01 22:40:57'),
(17, 1, 28, 2, 'جيد جدا \n\n\n\n\n\n\nجيد جدا', '4', 1, NULL, '2026-03-29 15:25:31', '2026-04-01 22:40:57'),
(18, 1, 4, 1, '', '5', 1, NULL, '2026-05-03 16:02:36', '2026-05-30 20:54:30'),
(19, 1, 4, 2, '', '5', 1, NULL, '2026-05-03 16:02:36', '2026-06-26 00:39:55'),
(20, 201, 73, 1, 'This is a detailed visit review with enough words to trigger the loyalty rating rule for testing purposes only.', '5', 1, NULL, '2026-07-03 16:35:45', '2026-07-03 16:35:45'),
(21, 201, 76, 1, 'This is a detailed visit review with enough words to trigger the loyalty rating rule for testing purposes only.', '5', 1, NULL, '2026-07-19 11:27:30', '2026-07-19 11:27:30'),
(22, 201, 54, 1, 'This is a detailed visit review with enough words to trigger the loyalty rating rule for testing purposes only.', '5', 1, NULL, '2026-07-29 10:44:39', '2026-07-29 10:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_services`
--

CREATE TABLE `clinic_services` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL DEFAULT '3' COMMENT '1 labs, 2 rays, 3 services',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 active, 0 de_active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic_specialists`
--

CREATE TABLE `clinic_specialists` (
  `id` bigint UNSIGNED NOT NULL,
  `specialty_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 main specialists, 2 sub specialists',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_specialists`
--

INSERT INTO `clinic_specialists` (`id`, `specialty_id`, `clinic_id`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(156, 4, 200, 1, 1, NULL, '2026-02-15 09:11:45', '2026-02-15 09:11:45'),
(157, 3, 200, 1, 1, NULL, '2026-02-15 09:11:45', '2026-02-15 09:11:45'),
(158, 2, 200, 1, 1, NULL, '2026-02-15 09:11:45', '2026-02-15 09:11:45'),
(171, 25, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(172, 24, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(173, 23, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(174, 22, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(175, 21, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(176, 20, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(177, 19, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(178, 18, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(179, 17, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(180, 15, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(181, 4, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(182, 3, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(183, 2, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(184, 1, 201, 1, 1, NULL, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(186, 25, 202, 1, 1, NULL, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(187, 30, 202, 2, 1, NULL, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(188, 28, 202, 2, 1, NULL, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(189, 26, 202, 2, 1, NULL, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(190, 25, 203, 1, 1, NULL, '2026-02-15 16:00:18', '2026-02-15 16:00:18'),
(191, 29, 203, 2, 1, NULL, '2026-02-15 16:00:18', '2026-02-15 16:00:18'),
(192, 27, 203, 2, 1, NULL, '2026-02-15 16:00:18', '2026-02-15 16:00:18'),
(193, 25, 204, 1, 1, NULL, '2026-02-15 16:04:55', '2026-02-15 16:04:55'),
(194, 31, 204, 2, 1, NULL, '2026-02-15 16:04:55', '2026-02-15 16:04:55'),
(195, 28, 204, 2, 1, NULL, '2026-02-15 16:04:55', '2026-02-15 16:04:55'),
(196, 25, 205, 1, 1, NULL, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(197, 31, 205, 2, 1, NULL, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(198, 30, 205, 2, 1, NULL, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(199, 29, 205, 2, 1, NULL, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(200, 25, 206, 1, 1, NULL, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(201, 29, 206, 2, 1, NULL, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(202, 28, 206, 2, 1, NULL, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(203, 26, 206, 2, 1, NULL, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(204, 24, 207, 1, 1, NULL, '2026-02-15 16:10:30', '2026-02-15 16:10:30'),
(205, 34, 207, 2, 1, NULL, '2026-02-15 16:10:30', '2026-02-15 16:10:30'),
(206, 32, 207, 2, 1, NULL, '2026-02-15 16:10:30', '2026-02-15 16:10:30'),
(207, 24, 208, 1, 1, NULL, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(208, 37, 208, 2, 1, NULL, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(209, 35, 208, 2, 1, NULL, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(210, 32, 208, 2, 1, NULL, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(211, 24, 209, 1, 1, NULL, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(212, 42, 209, 2, 1, NULL, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(213, 41, 209, 2, 1, NULL, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(214, 40, 209, 2, 1, NULL, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(215, 24, 210, 1, 1, NULL, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(216, 39, 210, 2, 1, NULL, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(217, 38, 210, 2, 1, NULL, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(218, 36, 210, 2, 1, NULL, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(219, 24, 211, 1, 1, NULL, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(220, 35, 211, 2, 1, NULL, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(221, 33, 211, 2, 1, NULL, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(222, 32, 211, 2, 1, NULL, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(223, 25, 213, 1, 1, NULL, '2026-04-02 22:38:43', '2026-04-02 22:38:43'),
(224, 30, 213, 2, 1, NULL, '2026-04-02 22:38:43', '2026-04-02 22:38:43'),
(225, 25, 215, 1, 1, NULL, '2026-05-01 23:10:53', '2026-05-01 23:10:53'),
(226, 30, 215, 2, 1, NULL, '2026-05-01 23:10:53', '2026-05-01 23:10:53'),
(227, 26, 215, 2, 1, NULL, '2026-05-01 23:10:53', '2026-05-01 23:10:53'),
(231, 3, 227, 1, 1, NULL, '2026-06-01 13:02:42', '2026-06-01 13:02:42'),
(232, 7, 227, 2, 1, NULL, '2026-06-01 13:02:42', '2026-06-01 13:02:42'),
(251, 21, 234, 1, 1, NULL, '2026-06-29 13:07:56', '2026-06-29 13:07:56'),
(252, 136, 234, 2, 1, NULL, '2026-06-29 13:07:56', '2026-06-29 13:07:56'),
(253, 21, 1, 1, 1, NULL, '2026-06-29 13:12:31', '2026-06-29 13:12:31'),
(254, 21, 235, 1, 1, NULL, '2026-06-29 13:13:01', '2026-06-29 13:13:01'),
(255, 136, 235, 2, 1, NULL, '2026-06-29 13:13:01', '2026-06-29 13:13:01'),
(270, 160, 243, 1, 1, NULL, '2026-07-01 21:10:28', '2026-07-01 21:10:28'),
(273, 20, 243, 1, 1, NULL, '2026-07-04 13:04:12', '2026-07-04 13:04:12'),
(275, 20, 245, 1, 1, NULL, '2026-07-04 13:06:04', '2026-07-04 13:06:04'),
(276, 139, 245, 2, 1, NULL, '2026-07-04 13:06:04', '2026-07-04 13:06:04'),
(277, 23, 243, 1, 1, NULL, '2026-07-06 14:19:08', '2026-07-06 14:19:08'),
(279, 23, 246, 1, 1, NULL, '2026-07-06 14:22:41', '2026-07-06 14:22:41'),
(280, 47, 246, 2, 1, NULL, '2026-07-06 14:22:41', '2026-07-06 14:22:41'),
(281, 4, 243, 1, 1, NULL, '2026-07-07 09:37:29', '2026-07-07 09:37:29'),
(282, 22, 1, 1, 1, NULL, '2026-07-07 09:52:16', '2026-07-07 09:52:16'),
(283, 20, 1, 1, 1, NULL, '2026-07-07 09:52:16', '2026-07-07 09:52:16'),
(284, 18, 1, 1, 1, NULL, '2026-07-07 09:52:16', '2026-07-07 09:52:16'),
(285, 2, 249, 1, 1, NULL, '2026-07-31 17:57:53', '2026-07-31 17:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `cms_items`
--

CREATE TABLE `cms_items` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_section_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_items`
--

INSERT INTO `cms_items` (`id`, `cms_section_id`, `type`, `slug`, `settings`, `order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'text', 'hero-text', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(2, 1, 'text', 'hero-text-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(3, 1, 'text', 'hero-text-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(4, 2, 'feature', 'feature-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(5, 2, 'feature', 'feature-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(6, 2, 'feature', 'feature-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(7, 4, 'service', 'service-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(8, 4, 'service', 'service-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(9, 4, 'service', 'service-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(10, 4, 'service', 'service-4', NULL, 4, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(11, 4, 'service', 'service-5', NULL, 5, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(12, 4, 'service', 'service-6', NULL, 6, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(13, 5, 'why-choose-us', 'why-choose-us-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(14, 5, 'why-choose-us', 'why-choose-us-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(15, 5, 'why-choose-us', 'why-choose-us-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(16, 5, 'why-choose-us', 'why-choose-us-4', NULL, 4, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(17, 5, 'why-choose-us', 'why-choose-us-5', NULL, 5, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(18, 9, 'feature', 'feature-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(19, 9, 'feature', 'feature-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(20, 9, 'feature', 'feature-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(21, 10, 'value', 'value-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(22, 10, 'value', 'value-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(23, 10, 'value', 'value-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(24, 10, 'value', 'value-4', NULL, 4, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(25, 10, 'value', 'value-5', NULL, 5, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(26, 10, 'value', 'value-6', NULL, 6, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(27, 11, 'service', 'service-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(28, 11, 'service', 'service-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(29, 11, 'service', 'service-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(30, 11, 'service', 'service-4', NULL, 4, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(31, 11, 'service', 'service-5', NULL, 5, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(32, 11, 'service', 'service-6', NULL, 6, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(33, 11, 'service', 'service-7', NULL, 7, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(34, 11, 'service', 'service-8', NULL, 8, 1, '2026-05-22 23:00:03', '2026-05-24 20:55:31', NULL),
(35, 12, 'faqs', 'faq-1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:03', NULL),
(36, 12, 'faqs', 'faq-2', NULL, 2, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:03', NULL),
(37, 12, 'faqs', 'faq-3', NULL, 3, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:03', NULL),
(38, 12, 'faqs', 'faq-4', NULL, 4, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:04', NULL),
(39, 12, 'faqs', 'faq-5', NULL, 5, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:04', NULL),
(40, 12, 'faqs', 'faq-6', NULL, 6, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:04', NULL),
(41, 12, 'faqs', 'faq-7', NULL, 7, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:04', NULL),
(42, 13, 'faqs', 'faq-8', NULL, 1, 1, '2026-05-22 23:00:03', '2026-05-24 20:44:04', NULL),
(43, 13, 'faqs', 'faq-9', NULL, 2, 1, '2026-05-22 23:00:04', '2026-05-24 20:44:04', NULL),
(44, 13, 'faqs', 'faq-10', NULL, 3, 1, '2026-05-22 23:00:04', '2026-05-24 20:44:04', NULL),
(45, 13, 'faqs', 'faq-11', NULL, 4, 1, '2026-05-22 23:00:04', '2026-05-24 20:44:04', NULL),
(46, 15, 'contact', 'contact-1', '[]', 1, 1, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL),
(47, 15, 'contact', 'contact-2', '[]', 2, 1, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL),
(48, 15, 'contact', 'contact-3', '[]', 3, 1, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL),
(49, 6, 'default', 'google', NULL, 0, 1, '2026-07-13 14:19:22', '2026-07-13 14:19:22', NULL),
(50, 6, 'default', 'apple', NULL, 0, 1, '2026-07-13 14:23:06', '2026-07-13 14:23:06', NULL),
(51, 16, 'default', 'item-1', NULL, 0, 1, '2026-07-16 15:23:18', '2026-07-16 15:23:18', NULL),
(52, 17, 'default', 'HealthـCompanion', NULL, 1, 1, '2026-08-05 19:30:20', '2026-08-05 19:30:20', NULL),
(53, 17, 'default', 'ManagementـDashboard', NULL, 2, 1, '2026-08-05 19:33:46', '2026-08-05 19:33:46', NULL),
(54, 17, 'default', 'CommittedـQuality', NULL, 3, 1, '2026-08-05 19:33:46', '2026-08-05 19:33:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_item_translations`
--

CREATE TABLE `cms_item_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_item_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_item_translations`
--

INSERT INTO `cms_item_translations` (`id`, `cms_item_id`, `locale`, `title`, `sub_title`, `content`, `icon`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Master Your Time. Secure Your Health', NULL, '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Randivo is an advanced ecosystem designed to digitize the entire patient journey, ensuring administrative excellence for doctors and a seamless experience for patients through:</span></p><p><br></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• End-to-End Patient Digitization:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Seamless management from doctor discovery to final service evaluation.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Real-time Communication:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Direct instant link between patients and front-desk staff to minimize wait times.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Smart Clinic Tools:</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;Professional dashboard for organizing appointments, shifts, and medical staff efficiently.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Quality Oversight:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Integrated system to monitor overall performance and resolve complaints instantly.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Verified Ratings:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Distinct evaluation system for doctors based on authentic patient experiences to build trust.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Why Randivo?</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">We bridge the gap between Operational Efficiency and Patient Comfort in one platform, empowering your medical practice to lead in the digital health era.</span></p><p><br></p><p><br></p>', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-08-05 19:38:36'),
(2, 1, 'ar', 'رانديفو: المنصة المتكاملة لإدارة العيادات ورقمنة الخدمات الطبية', NULL, '<p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام رانديفو هو حل تقني متطور يهدف إلى أتمتة رحلة المريض بالكامل، مما يضمن كفاءة إدارية للأطباء وتجربة سلسة للمرضى من خلال:</span></p><p><br></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقمنة رحلة المريض:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;إدارة متكاملة تبدأ من البحث عن الطبيب حتى تقييم الخدمة.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تواصل لحظي:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">ربط مباشر بين المريض والاستقبال لسرعة التنسيق وتقليل وقت الانتظار.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• إدارة ذكية للعيادات:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;لوحة تحكم احترافية لتنظيم المواعيد، الورديات، والكوادر الطبية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقابة وجودة:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;نظام لمراقبة الأداء، معالجة الشكاوى فوراً، وضمان الشفافية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تقييم موثوق:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام تقييم منفصل للأطباء يعتمد على تجارب حقيقية لتعزيز المصداقية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">لماذا رانديفو؟</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نجمع بين كفاءة الإدارة و راحة المريض في منصة واحدة تضمن لك الريادة في القطاع الصحي الرقمي.</span></p><p><br></p>', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-08-05 19:38:36'),
(3, 2, 'en', 'Hero Text 2', NULL, 'Welcome to our website 2', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(4, 2, 'ar', 'النص الرئيسي 2', NULL, 'مرحبا بك في موقعنا 2', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(5, 3, 'en', 'Hero Text 3', NULL, 'Welcome to our website 3', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(6, 3, 'ar', 'النص الرئيسي 3', NULL, 'مرحبا بك في موقعنا 3', 'fa-solid fa-home', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(7, 4, 'en', 'The Patient Experience (iOS & Android App)', 'Your Health Companion, Right in Your Pocket.', 'Feature 1 descriptionWe designed the Randivo app to be your gateway to a life without waiting. Search for your doctor, book your appointment with precision, chat with your clinic, and share your verified feedback  all at the touch of a button.', NULL, '2026-05-22 23:00:03', '2026-06-02 22:35:15'),
(8, 4, 'ar', 'تجربة المريض (تطبيق iOS & Android)', 'رفيقك الصحي.. في جيبك.', 'صممنا تطبيق رنديفو ليكون بوابتك لحياة بلا انتظار. ابحث عن طبيبك، احجز موعدك بدقة، دردش مع عيادتك، وشاركنا تقييمك الحقيقي.. كل هذا بضغطة زر.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:43:50'),
(9, 5, 'en', 'Clinic Management Dashboard', 'Manage Your Clinic Smarter, From One Place.', '<p>Transform your clinic’s administration into a fully digital experience. Take total control over doctor schedules, organize shifts, monitor performance, and resolve patient inquiries in real-time to ensure peak operational efficiency.</p>', NULL, '2026-05-22 23:00:03', '2026-07-23 20:47:33'),
(10, 5, 'ar', 'لوحة تحكم العيادة (Clinic Dashboard)', 'أدر عيادتك بذكاء.. من مكان واحد.', '<p>حوّل إدارة عيادتك إلى تجربة رقمية بالكامل. تحكّم في جداول الأطباء، نظّم الورديات، وراقب أداء العيادة ومعالجة الملاحظات لحظة بلحظة لضمان أعلى مستويات الكفاءة.</p>', NULL, '2026-05-22 23:00:03', '2026-07-23 20:47:34'),
(11, 6, 'en', 'Quality Assurance & Oversight', 'Committed to Quality, Dedicated to Excellence.', '<p>Behind every successful booking is a technical team ensuring quality. Our system administrators monitor operations, resolve technical challenges, and guarantee immediate responses to all inquiries to maintain a seamless ecosystem.</p>', NULL, '2026-05-22 23:00:03', '2026-07-23 20:39:45'),
(12, 6, 'ar', 'مركز الإدارة والرقابة', 'ضمان الجودة.. والتزام بالتميز.', '<p>خلف كل حجز ناجح فريق تقني يضمن لك الجودة. يعمل مديرو النظام على مراقبة سلاسة العمليات، معالجة أي تحديات تقنية، وضمان استجابة فورية لكافة الاستفسارات.</p>', NULL, '2026-05-22 23:00:03', '2026-07-23 20:39:45'),
(13, 7, 'en', 'Smart Appointment Management', NULL, 'A flexible system that allows patients to select the right doctor and time based on pre-defined shifts, putting an end to scheduling chaos and overlapping bookings.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(14, 7, 'ar', 'إدارة الحجوزات الذكية', NULL, 'نظام مرن يتيح للمرضى اختيار الطبيب والموعد بدقة بناءً على جداول ورديات مُحددة مسبقاً، مما ينهي فوضى المواعيد.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(15, 8, 'en', 'Dependents & Minor Management System', NULL, 'A unique feature allowing patients to add up to 3 children (under 18) to a single account, making it easier to manage and book medical appointments for the entire family from one place.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(16, 8, 'ar', 'نظام إدارة المرافقين والصغار', NULL, 'ميزة فريدة تتيح إضافة حتى 3 أبناء (تحت 18 عاماً) لكل حساب مريض، لتسهيل إدارة وحجز المواعيد لكافة أفراد العائلة من مكان واحد.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(17, 9, 'en', 'Instant Communication Channels', NULL, 'Integrated Live Chat with front-desk staff for quick inquiries and seamless booking coordination, reducing the load on traditional phone lines.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(18, 9, 'ar', 'قنوات التواصل الفوري', NULL, 'دردشة مباشرة (Live Chat) مع موظفي الاستقبال لسرعة الاستفسار، تنسيق الحجوزات، وتقليل الضغط على الاتصالات الهاتفية.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(19, 10, 'en', 'Advanced Complaint Resolution System', NULL, 'Specialized channels that ensure the patient\'s voice is heard. Direct tickets can be sent to the Clinic Manager (for medical concerns) or the App Admin (for technical issues), ensuring a prompt response.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(20, 10, 'ar', 'نظام متطور لمعالجة الشكاوى', NULL, 'قنوات مخصصة تضمن إيصال صوت المريض؛ سواء لمدير العيادة (للخدمات الطبية) أو لمدارة التطبيق (للمشاكل التقنية)، لضمان استجابة فورية', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(21, 11, 'en', 'Comprehensive Rating Ecosystem', NULL, 'A dual, independent evaluation system for both doctors (medical quality) and clinics (organizational quality), enhancing transparency and building long-term trust.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(22, 11, 'ar', 'منظومة التقييم الشاملة', NULL, 'تقييم مزدوج ومنفصل للأطباء (لقياس الجودة الطبية) وللعيادات (لقياس جودة التنظيم)، مما يعزز الشفافية والموثوقية.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(23, 12, 'en', 'Role-Specific Dashboards', NULL, 'Custom-tailored interfaces for every professional role (Doctors, Receptionists, and Managers) to ensure high-speed performance, ease of use, and data accuracy.', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(24, 12, 'ar', 'لوحات تحكم تخصصية (Dashboards)', NULL, 'واجهات مخصصة لكل دور وظيفي (طبيب، موظف استقبال، مدير) لضمان سرعة الإنجاز، سهولة الاستخدام، ودقة البيانات', NULL, '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(25, 13, 'en', 'Chaos-Free Organization', NULL, 'Say goodbye to paper logs. From doctor schedules to patient records, everything is accessible at your fingertips, boosting operational efficiency.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(26, 13, 'ar', 'تنظيم ذكي.. بلا فوضى', NULL, 'ودّع زمن السجلات الورقية والملفات الضائعة. مع رنديفو، كل شيء من جداول الأطباء إلى سجلات المرضى متاح بين يديك بضغطة زر، مما يرفع كفاءة العمل بنسبة 100%.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:43:50'),
(27, 14, 'en', 'Enhanced Trust & Credibility', NULL, 'Through our transparent rating and complaint systems, we build a bridge of trust between you and your patients, cementing your professional reputation.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(28, 14, 'ar', 'بناء جسور الثقة والمصداقية', NULL, 'نظام التقييمات الشفاف ومعالجة الشكاوى الفورية يحول مريضك إلى \"شريك\"، ويبني لعيادتك سمعة رقمية قوية تجذب المزيد من العملاء.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(29, 15, 'en', 'Seamless Communication', NULL, 'Eliminate communication barriers with integrated Live Chat, significantly reducing appointment cancellations and \"no-shows.\"', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(30, 15, 'ar', 'تواصل فعال يُنهي المواعيد الضائعة', NULL, 'نلغي حواجز الاتصال التقليدية عبر الشات المباشر، مما يقلل من نسب إلغاء المواعيد (No-shows) ويوفر وقت موظفي الاستقبال.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(31, 16, 'en', 'Comprehensive Family Management', NULL, 'Our unique \"Dependent Management\" feature makes Randivo the top choice for parents, allowing them to manage their children’s health easily.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(32, 16, 'ar', 'الخيار الأول للعائلات', NULL, 'ميزة إضافة \"المرافقين القُصّر\" تجعل رنديفو التطبيق المفضل للأهالي، مما يوسع قاعدة عملائك لتشمل العائلة بالكامل بسهولة ويسر.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:43:50'),
(33, 17, 'en', 'Performance Analytics', NULL, 'Advanced dashboards provide clinic managers with clear insights into staff efficiency and patient satisfaction levels, enabling data-driven growth.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(34, 17, 'ar', 'رؤية تحليلية شاملة (Insights)', NULL, 'لوحات تحكم متقدمة تمنح مدير العيادة رؤية كاملة حول كفاءة الأطباء، مستوى الرضا، ونمو العيادة لاتخاذ قرارات مبنية على بيانات دقيقة.', NULL, '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(35, 18, 'en', 'Randivo Patient App (iOS & Android)', NULL, 'Your digital gateway to finding top-rated doctors and booking appointments with precision. Experience a seamless journey with live chat, real-time queue tracking, and verified reviews for better healthcare.', NULL, '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(36, 18, 'ar', 'تطبيق رنديفو للمرضى (iOS & Android)', NULL, 'بوابتك الذكية للبحث عن أفضل الأطباء وحجز موعدك بالدقيقة. استمتع بتجربة مستخدم سلسة تشمل المحادثة الفورية، تتبع الدور، وتقييم الخدمة لضمان أفضل رعاية صحية.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:30:43'),
(37, 19, 'en', 'Clinic Management Dashboard', NULL, 'The ultimate tool for clinic automation. Manage doctor schedules, organize shifts, and handle patient feedback from a single, intuitive interface designed to boost operational efficiency.', NULL, '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(38, 19, 'ar', 'لوحة تحكم إدارة العيادات (Clinic Dashboard)', NULL, 'الحل الأمثل لأتمتة عيادتك. أدر جداول الأطباء، نظّم الورديات، وتابع شكاوى المرضى من واجهة ذكية واحدة تضمن لك كفاءة التشغيل وزيادة الإنتاجية.', NULL, '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(39, 20, 'en', 'System Administration & Support', NULL, 'Ensuring excellence and system integrity. Our dedicated admin layer monitors global operations, resolves technical issues, and provides immediate support to maintain a flawless experience.', NULL, '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(40, 20, 'ar', 'نظام الإدارة والرقابة', NULL, 'نضمن لك أعلى معايير الجودة والأمان. يعمل فريقنا على المراقبة العامة للنظام، معالجة التحديات التقنية، والرد على الاستفسارات لضمان تجربة مستخدم خالية من العوائق.', NULL, '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(41, 21, 'en', 'Full Administrative Automation', NULL, 'We digitize appointment management to eliminate human error and paper-based logs, ensuring perfectly synchronized doctor shifts and organized clinic flow.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(42, 21, 'ar', 'أتمتة العمليات الإدارية (Zero-Error Operations)', NULL, 'نستهدف رقمنة إدارة المواعيد بالكامل لإنهاء عصر السجلات الورقية، مما يضمن تنظيم ورديات الأطباء بدقة ويمنع تداخل المواعيد أو ازدحام العيادات.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(43, 22, 'en', 'Patient-Centric Experience', NULL, 'Streamlining the patient journey with a 3-click booking process. Our \"Dependent Management\" feature makes Randivo the go-to family app for managing children\'s appointments from a single account.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(44, 22, 'ar', 'تجربة مريض محورها الراحة (Patient-Centric Approach)', NULL, 'نسهل رحلة المريض عبر منصة موحدة للبحث والحجز في ثوانٍ. وبفضل ميزة \"إدارة المرافقين\"، أصبح رنديفو التطبيق العائلي الأول لإدارة مواعيد الأطفال من حساب واحد.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:30:43'),
(45, 23, 'en', 'Real-Time Communication Excellence', NULL, 'Closing the gap between patients and clinics via \"Live Chat,\" reducing phone call congestion and providing instant answers that enhance patient engagement.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(46, 23, 'ar', 'تواصل لحظي وفعّال (Instant Connectivity)', NULL, 'سد الفجوة بين المريض والعيادة عبر \"الشات المباشر\"، مما يقلل الضغط على المكالمات الهاتفية ويوفر استجابة فورية ترفع من ولاء المريض.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(47, 24, 'en', 'Transparency & Quality Control', NULL, 'Creating a competitive healthcare environment through a dual-rating system (Doctor & Clinic), providing real-time data to optimize service quality and patient satisfaction.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(48, 24, 'ar', 'تعزيز الشفافية وضمان الجودة:', NULL, 'من خلال نظام التقييم المزدوج (للطبيب والعيادة) ونظام الشكاوى المباشر. يهدف هذا إلى خلق بيئة تنافسية بين العيادات لتقديم أفضل خدمة، وتزويد مديري النظام والعيادات ببيانات حقيقية حول نقاط القوة والضعف لاتخاذ قرارات تطويرية.', NULL, '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(49, 25, 'en', 'Empowering Clinic Management', NULL, 'Providing a comprehensive dashboard that acts as a \"Smart Administrative Assistant,\" allowing managers to monitor staff performance and resolve complaints professionally.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(50, 25, 'ar', 'تمكين الإدارة الطبية تقنياً (Smart Management)', NULL, 'نقدم لوحة تحكم تعمل كـ \"مساعد إداري ذكي\" لمراقبة الأداء، إدارة الجداول، ومعالجة الشكاوى بأسلوب مؤسسي احترافي.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(51, 26, 'en', 'Accelerating Growth & Visibility', NULL, 'Acting as a \"Medical Marketplace\" that boosts clinic visibility, helping small and large practices reach a wider patient base and increase overall revenue.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:30:14'),
(52, 26, 'ar', 'دفع عجلة النمو والانتشار (Market Expansion)', NULL, 'رنديفو ليس مجرد نظام، بل هو \"سوق طبي\" يزيد من فرص ظهور العيادات ووصولها لشريحة أكبر من المرضى، مما يضمن زيادة الحجوزات ونمو العوائد.', NULL, '2026-05-22 23:00:03', '2026-08-05 13:30:43'),
(53, 27, 'en', 'Smart Appointment Management', NULL, 'A flexible system that allows patients to select the perfect doctor and time slot based on pre-defined shifts, eliminating scheduling chaos.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(54, 27, 'ar', 'إدارة الحجز الذكي (Smart Booking)', NULL, 'نظام مرن يتيح للمريض اختيار الطبيب والموعد بدقة بناءً على ورديات مُحددة مسبقاً، مما ينهي فوضى المواعيد ويضمن كفاءة التشغيل.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(55, 28, 'en', 'Patient-Centric Experience', NULL, 'Streamlining the healthcare journey via a unified platform to search clinics, compare doctors through verified reviews, and secure bookings in seconds.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(56, 28, 'ar', 'تجربة مريض محورها الراحة (Patient Centricity)', NULL, 'تسهيل رحلة المريض عبر منصة موحدة للبحث عن العيادات، المفاضلة بين الأطباء بناءً على تقييمات حقيقية، وإتمام الحجز في ثوانٍ معدودة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(57, 29, 'en', 'Dependent & Minor Management', NULL, '<p>A unique feature allowing patients to add up to 3 children (under 18) per account, making family healthcare coordination simpler than ever.</p>', NULL, '2026-05-22 23:00:03', '2026-07-25 16:43:37'),
(58, 29, 'ar', 'نظام إدارة المرافقين والقُصّر', NULL, '<p>ميزة حصرية تسمح بإضافة حتى 3 أبناء (تحت 18 عاماً) لكل حساب مريض، مما يجعل \"رنديفو\" التطبيق المثالي لإدارة حجوزات العائلة من مكان واحد.</p>', NULL, '2026-05-22 23:00:03', '2026-08-05 13:36:23'),
(59, 30, 'en', 'Instant Communication (Live Chat)', NULL, 'Direct messaging with clinic receptionists for quick inquiries and booking coordination, reducing phone call wait times and enhancing engagement.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(60, 30, 'ar', 'التواصل اللحظي (Live Chat', NULL, 'قنوات دردشة مباشرة مع موظفي الاستقبال للاستفسارات السريعة وتنسيق الحجوزات، مما يقلل الضغط على الهاتف ويزيد سرعة الاستجابة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(61, 31, 'en', 'Multi-Channel Complaint Management', NULL, 'Dedicated feedback loops that ensure patient concerns reach the right person—Clinic Managers for medical issues or App Admins for technical support.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(62, 31, 'ar', 'نظام معالجة الشكاوى المتعددة', NULL, 'قنوات مخصصة لضمان إيصال صوت المريض؛ سواء لمدير العيادة (للخدمات الطبية) أو لمدير التطبيق (للمشاكل التقنية)، لضمان جودة الخدمة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(63, 32, 'en', 'Comprehensive Rating System', NULL, 'Independent evaluations for both doctors (clinical quality) and clinics (operational quality) to build transparency and patient trust.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(64, 32, 'ar', 'منظومة التقييم الشاملة', NULL, 'تقييم مزدوج ومنفصل للأطباء (الجانب الطبي) وللعيادات (الجانب التنظيمي)، مما يعزز الشفافية والموثوقية أمام المرضى.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(65, 33, 'en', 'Role-Specific Dashboards', NULL, 'Customized interfaces tailored to each professional role (Doctors, Receptionists, Managers) to ensure maximum efficiency and ease of use.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(66, 33, 'ar', 'لوحات تحكم تخصصية (Custom Dashboards)', NULL, 'واجهات ذكية مخصصة لكل دور وظيفي (طبيب، استقبال، مدير) لضمان سرعة الإنجاز، سهولة الاستخدام، ودقة البيانات الإدارية.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(67, 34, 'en', 'Market Growth & Visibility', NULL, 'A digital marketplace that boosts visibility for new and growing clinics, connecting them with a wider patient base and driving increased revenue.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(68, 34, 'ar', 'دفع عجلة النمو والانتشار', NULL, 'محرك بحث طبي يزيد من فرص ظهور العيادات الجديدة والناشئة أمام شريحة واسعة من المرضى، مما يضاعف عدد الحجوزات ونمو العوائد.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(69, 35, 'en', 'How can I book an appointment with a specific doctor?', NULL, 'It’s easy: open the app, select your clinic, and browse the list of doctors. Once you choose a doctor, you’ll see the available time slots (shifts). Select your preferred time and click \"Confirm Booking.\"', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:07'),
(70, 35, 'ar', 'كيف يمكنني حجز موعد مع طبيب محدد؟', NULL, 'الأمر بسيط؛ قم بفتح التطبيق، اختر العيادة، ثم تصفح قائمة الأطباء المتاحين. بعد اختيار الطبيب، ستظهر لك المواعيد المتاحة (الورديات)، اختر الموعد الذي يناسبك ثم اضغط على \"تأكيد الحجز\".', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:07'),
(71, 36, 'en', 'Can I book appointments for family members?', NULL, 'Yes. Rundevo allows you to add up to 3 dependents (children or minors under 18) to your profile, enabling you to manage and book their medical appointments directly from your account.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:49:55'),
(72, 36, 'ar', 'هل يمكنني حجز موعد لأفراد عائلتي؟', NULL, 'نعم، يتيح لك رنديفو إضافة حتى 3 مرافقين (أطفال أو قُصّر تحت 18 عاماً) إلى ملفك الشخصي، مما يمكنك من إدارة وحجز مواعيدهم الطبية بسهولة من حسابك.', NULL, '2026-05-22 23:00:03', '2026-08-06 16:20:53'),
(73, 37, 'en', 'How do I contact the clinic for booking inquiries?', NULL, 'Once your booking is confirmed, you can use the \"Live Chat\" feature within the app to talk directly to the clinic\'s receptionist. You can also send an inquiry via the clinic’s page, and their staff will respond promptly.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:07'),
(74, 37, 'ar', 'كيف أتواصل مع العيادة للاستفسار عن تفاصيل الحجز؟', NULL, 'بمجرد إتمام الحجز، تتوفر لك ميزة \"الدردشة الفورية\" داخل التطبيق للتحدث مباشرة مع موظف الاستقبال. كما يمكنك إرسال استفسارك من خلال صفحة العيادة وسيقوم الفريق بالرد عليك في أقرب وقت.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(75, 38, 'en', 'What should I do if I encounter a technical issue with the app?', NULL, 'Our technical team is here to help. You can submit a report directly to the \"App Admin\" via the Technical Support section, and we will resolve the issue immediately.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(76, 38, 'ar', 'ماذا أفعل إذا واجهت مشكلة تقنية في التطبيق؟', NULL, 'فريقنا التقني في خدمتك؛ يمكنك إرسال شكوى مباشرة إلى \"مدير التطبيق\" عبر قسم الدعم الفني، وسنتولى معالجة الأمر فوراً لضمان تجربة سلسة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(77, 39, 'en', 'Can I cancel my appointment?', NULL, 'Yes, you can manage or cancel your appointments through the \"My Bookings\" menu in the app, subject to the specific cancellation policy of each clinic.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:49:55'),
(78, 39, 'ar', 'هل يمكنني إلغاء موعد الحجز؟', NULL, 'نعم، يمكنك إدارة حجوزاتك وإلغاؤها بسهولة من خلال قائمة \"حجوزاتي\" داخل التطبيق، مع مراعاة سياسة الإلغاء والمواعيد المحددة من قِبل كل عيادة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(79, 40, 'en', 'When can I rate the doctor or the clinic?', NULL, 'The rating option becomes available immediately after your scheduled appointment ends. This ensures all reviews are authentic and based on real experiences.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(80, 40, 'ar', 'متى يمكنني تقييم الطبيب أو العيادة؟', NULL, 'يتاح خيار التقييم تلقائياً فور انتهاء الموعد المسجل في النظام؛ لضمان أن تكون كافة التقييمات موثوقة ومبنية على تجربة فعلية.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:56:07'),
(81, 41, 'en', 're my medical information and chats secure?', NULL, 'Absolutely. We prioritize your privacy by using advanced encryption protocols to safeguard your personal data and ensure all communications between you and the clinic remain strictly confidential.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(82, 41, 'ar', 'هل معلوماتي الطبية ومحادثاتي آمنة؟', NULL, 'بالتأكيد. نضع الخصوصية على رأس أولوياتنا، حيث نستخدم بروتوكولات تشفير متقدمة لحماية بياناتك الشخصية وضمان سرية المحادثات بينك وبين العيادة.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:56:07'),
(83, 42, 'en', 'How can my clinic join the Rundevo platform?', NULL, 'You can register directly through our website by entering your basic information and required documentation. Once our team verifies your details, your account will be activated, and you can start receiving bookings immediately.', NULL, '2026-05-22 23:00:03', '2026-06-03 16:53:36'),
(84, 42, 'ar', 'كيف يمكن لعيادتي الانضمام إلى منصة رانديفو؟', NULL, 'يمكنك البدء فوراً عبر التسجيل من خلال موقعنا الإلكتروني وإدخال البيانات الأساسية والوثائق المطلوبة. بمجرد مراجعة فريقنا للبيانات، يتم تفعيل حساب عيادتك لتبدأ في استقبال الحجوزات.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(85, 43, 'en', 'How are doctor schedules and appointments managed?', NULL, 'Through the \"Clinic Manager Dashboard,\" you can add your medical staff and define their specific shifts. The system automatically displays these available slots to patients in real-time, ensuring a conflict-free scheduling process.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(86, 43, 'ar', 'كيف يتم تنظيم وإدارة مواعيد الأطباء؟', NULL, 'من خلال \"لوحة تحكم مدير العيادة\" الذكية، يمكنك إضافة الكادر الطبي وتحديد فترات عملهم (الورديات) بدقة. يقوم النظام تلقائياً بتحديث المواعيد المتاحة للمرضى بناءً على هذه الإعدادات لمنع أي تداخل.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(87, 44, 'en', 'Who is responsible for handling patient complaints?', NULL, 'Our system intelligently routes feedback: clinical or service-related complaints are sent directly to the \"Clinic Manager.\" Technical issues regarding the app are handled by the \"Rundevo Platform Admin\" to ensure a seamless experience.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(88, 44, 'ar', 'كيف يتم التعامل مع شكاوى المرضى ومن المسؤول عنها؟', NULL, 'يوفر النظام تصنيفاً ذكياً للشكاوى؛ فالملحوظات المتعلقة بالخدمة الطبية أو التنظيم الداخلي تصل مباشرة إلى \"مدير العيادة\". أما المشكلات التقنية المتعلقة بالتطبيق، فيتولاها فريق \"إدارة رانديفو\" لضمان أعلى مستويات الجودة.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(89, 45, 'en', 'What features are available for the clinic’s receptionist?', NULL, 'Receptionists have a dedicated dashboard designed for high efficiency. They can monitor daily appointments, respond to patient inquiries via Live Chat, and manage the patient flow within the clinic smoothly.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(90, 45, 'ar', 'ما هي الصلاحيات المتاحة لموظف الاستقبال؟', NULL, 'يمتلك موظف الاستقبال واجهة مخصصة تمنحه الأدوات اللازمة لمتابعة جدول الحجوزات اليومي، الرد على استفسارات المرضى عبر الشات الفوري، وتنظيم تدفق الحالات داخل العيادة بكل سهولة.', NULL, '2026-05-22 23:00:04', '2026-06-03 16:53:36'),
(91, 46, 'en', 'Our Current Location', NULL, 'Corniche Wadi Al-Aqiq, Harrat Al-Wabra District, Madinah Al-Munawwarah, Kingdom of Saudi Arabia', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(92, 46, 'ar', 'موقعنا الحالي', NULL, 'ﻛورﻧﯾش وادي اﻟﻌﻘﯾق ﺣﻲ ﺣرة اﻟوﺑرة اﻟﻣدﯾﻧﺔ اﻟﻣﻧورة اﻟﻣﻣﻠﻛﺔ اﻟﻌرﺑﯾﺔ اﻟﺳﻌودﯾﺔ', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(93, 47, 'en', 'Phone Number', NULL, '0530377588', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(94, 47, 'ar', 'رقم الهاتف', NULL, '0530377588', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(95, 48, 'en', 'Email Address', NULL, 'support@rundevo.net', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(96, 48, 'ar', 'البريد الإلكتروني', NULL, 'support@rundevo.net', NULL, '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(97, 49, 'en', 'google', NULL, 'https://play.google.com/store/apps/details?id=com.takaful.rendezvous', NULL, '2026-07-13 14:19:22', '2026-07-13 14:21:05'),
(98, 49, 'ar', 'google', NULL, 'https://play.google.com/store/apps/details?id=com.takaful.rendezvous', NULL, '2026-07-13 14:19:22', '2026-07-13 14:21:05'),
(99, 50, 'en', 'apple', NULL, 'https://apps.apple.com/us/app/randevu-%D8%B1%D8%A7%D9%86%D8%AF%D9%8A%D9%81%D9%88/id6761128352', NULL, '2026-07-13 14:23:06', '2026-07-13 14:23:06'),
(100, 50, 'ar', 'apple', NULL, 'https://apps.apple.com/us/app/randevu-%D8%B1%D8%A7%D9%86%D8%AF%D9%8A%D9%81%D9%88/id6761128352', NULL, '2026-07-13 14:23:06', '2026-07-13 14:23:06'),
(101, 51, 'en', 'item 1', 'item 1', 'item 1', NULL, '2026-07-16 15:23:18', '2026-07-16 15:23:18'),
(102, 51, 'ar', 'عنصر 1', 'عنصر 1', 'عنصر 1', NULL, '2026-07-16 15:23:18', '2026-07-16 15:23:18'),
(103, 52, 'en', 'Your Health Companion, Right in Your Pocket.', NULL, 'We designed the Randivo app to be your gateway to a life without waiting. Search for your doctor, book your appointment with precision, chat with your clinic, and share your verified feedback  all at the touch of a button.', NULL, '2026-08-05 19:30:20', '2026-08-05 19:30:20'),
(104, 52, 'ar', 'رفيقك الصحي.. في جيبك.', NULL, 'صممنا تطبيق رانديفو ليكون بوابتك لحياة بلا انتظار. ابحث عن طبيبك، احجز موعدك بدقة، دردش مع عيادتك، وشاركنا تقييمك الحقيقي.. كل هذا بضغطة زر.', NULL, '2026-08-05 19:30:20', '2026-08-05 19:30:20'),
(105, 53, 'en', 'Manage Your Clinic Smarter, From One Place', NULL, 'Transform your clinic’s administration into a fully digital experience. Take total control over doctor schedules, organize shifts, monitor performance, and resolve patient inquiries in real-time to ensure peak operational efficiency.', NULL, '2026-08-05 19:33:46', '2026-08-05 19:33:46'),
(106, 53, 'ar', 'أدر عيادتك بذكاء.. من مكان واحد.', NULL, 'حوّل إدارة عيادتك إلى تجربة رقمية بالكامل. تحكّم في جداول الأطباء، نظّم الورديات، وراقب أداء العيادة ومعالجة الملاحظات لحظة بلحظة لضمان أعلى مستويات الكفاءة.', NULL, '2026-08-05 19:33:46', '2026-08-05 19:33:46'),
(107, 54, 'en', 'Committed to Quality, Dedicated to Excellence.', NULL, 'Behind every successful booking is a technical team ensuring quality. Our system administrators monitor operations, resolve technical challenges, and guarantee immediate responses to all inquiries to maintain a seamless ecosystem.', NULL, '2026-08-05 19:33:46', '2026-08-05 19:33:46'),
(108, 54, 'ar', 'ضمان الجودة.. والتزام بالتميز.', NULL, 'خلف كل حجز ناجح فريق تقني يضمن لك الجودة. يعمل مديرو النظام على مراقبة سلاسة العمليات، معالجة أي تحديات تقنية، وضمان استجابة فورية لكافة الاستفسارات.', NULL, '2026-08-05 19:33:46', '2026-08-05 19:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `cms_languages`
--

CREATE TABLE `cms_languages` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `flag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_languages`
--

INSERT INTO `cms_languages` (`id`, `code`, `name`, `native_name`, `direction`, `flag`, `is_default`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'English', 'ltr', '🇺🇸', 0, 1, 1, '2026-05-20 23:20:39', '2026-05-20 23:21:56'),
(2, 'ar', 'Arabic', 'العربية', 'rtl', '🇸🇦', 1, 1, 2, '2026-05-20 23:20:39', '2026-05-20 23:21:56');

-- --------------------------------------------------------

--
-- Table structure for table `cms_links`
--

CREATE TABLE `cms_links` (
  `id` bigint UNSIGNED NOT NULL,
  `linkable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `linkable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_link_translations`
--

CREATE TABLE `cms_link_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_link_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `slug`, `name`, `is_active`, `order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'home', 'Home', 1, 1, '2026-05-22 23:00:03', '2026-05-22 23:00:03', NULL),
(2, 'about', 'About Us', 1, 2, '2026-05-22 23:00:03', '2026-05-22 23:00:03', NULL),
(3, 'services', 'Services', 1, 3, '2026-05-22 23:00:03', '2026-05-22 23:00:03', NULL),
(4, 'faq', 'Faq', 1, 4, '2026-05-22 23:00:03', '2026-05-22 23:00:03', NULL),
(5, 'subscription', 'Subscription', 1, 5, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL),
(6, 'contact', 'Contact', 1, 6, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_page_translations`
--

CREATE TABLE `cms_page_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_page_translations`
--

INSERT INTO `cms_page_translations` (`id`, `cms_page_id`, `locale`, `title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Home', 'Home page', 'home, page', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(2, 1, 'ar', 'الصفحة الرئيسية', 'الصفحة الرئيسية', 'الصفحة الرئيسية', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(3, 2, 'en', 'About Us', 'About Us description', 'About Us description', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(4, 2, 'ar', 'عن النظام', 'عن النظام', 'عن النظام, النظام', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(5, 3, 'en', 'Services', 'Services description', 'Services keywords', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(6, 3, 'ar', 'الخدمات', 'الخدمات الوصف', 'الخدمات الكلمات الدلالية', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(7, 4, 'en', 'Faq', 'Faq page', 'faq, page', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(8, 4, 'ar', 'الأسئلة الشائعة', 'الأسئلة الشائعة', 'الأسئلة الشائعة', '2026-05-22 23:00:03', '2026-05-22 23:00:03'),
(9, 5, 'en', 'Subscription', 'Subscription page', 'subscription, page', '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(10, 5, 'ar', 'الاشتراك', 'الاشتراك', 'الاشتراك', '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(11, 6, 'en', 'Contact', 'Contact page', 'contact, page', '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(12, 6, 'ar', 'اتصل بنا', 'اتصل بنا', 'اتصل بنا', '2026-05-22 23:00:04', '2026-05-22 23:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `cms_sections`
--

CREATE TABLE `cms_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_layout` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_sections`
--

INSERT INTO `cms_sections` (`id`, `cms_page_id`, `name`, `type`, `template`, `section_layout`, `settings`, `order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'عنا', 'about-us', NULL, 'style_1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-08-06 08:25:06', NULL),
(2, 1, 'الخدمات', 'default', NULL, 'default', NULL, 2, 0, '2026-05-22 23:00:03', '2026-08-06 08:14:38', NULL),
(3, 1, 'About Us', 'default', NULL, 'default', NULL, 4, 1, '2026-05-22 23:00:03', '2026-08-06 05:55:11', '2026-08-06 05:55:11'),
(4, 1, 'Services', 'services', NULL, 'style_1', NULL, 5, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(5, 1, 'Why Choose Us', 'why-choose-us', NULL, 'style_1', NULL, 7, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(6, 1, 'Download App', 'download-app', NULL, 'style_1', NULL, 8, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(7, 1, 'Plans', 'plans', NULL, 'style_1', NULL, 9, 1, '2026-05-22 23:00:03', '2026-06-02 22:29:06', NULL),
(8, 2, 'About Us', 'about-us', NULL, 'style_1', NULL, 4, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(9, 2, 'Features', 'features', NULL, 'style_1', NULL, 5, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(10, 2, 'Values', 'values', NULL, 'style_1', NULL, 6, 1, '2026-05-22 23:00:03', '2026-06-03 15:17:11', NULL),
(11, 3, 'Services', 'services', NULL, 'style_1', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-03 16:40:36', NULL),
(12, 4, 'Faq', 'faqs', NULL, 'default', NULL, 1, 1, '2026-05-22 23:00:03', '2026-06-03 16:48:07', NULL),
(13, 4, 'Faq', 'faqs', NULL, 'default', NULL, 2, 1, '2026-05-22 23:00:03', '2026-06-03 16:48:08', NULL),
(14, 5, 'Subscription', 'subscription', NULL, 'default', NULL, 4, 1, '2026-05-22 23:00:04', '2026-06-03 16:59:00', NULL),
(15, 6, 'Contact', 'contact', 'contact', NULL, '[]', 1, 1, '2026-05-22 23:00:04', '2026-05-22 23:00:04', NULL),
(16, 1, 'الصفحة الرئيسية                                     8', 'hero', NULL, 'style_1', NULL, 0, 1, '2026-07-16 14:55:13', '2026-07-16 14:55:13', NULL),
(17, 1, 'المميزات', 'features', NULL, 'style_1', NULL, 0, 1, '2026-07-16 14:55:13', '2026-07-16 15:19:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_section_translations`
--

CREATE TABLE `cms_section_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `cms_section_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_section_translations`
--

INSERT INTO `cms_section_translations` (`id`, `cms_section_id`, `locale`, `title`, `subtitle`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Rundevu: The All-in-One Digital Platform for Clinic Management', 'About Us', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Runevu is an advanced ecosystem designed to digitize the entire patient journey, ensuring administrative excellence for doctors and a seamless experience for patients through:</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• End-to-End Patient Digitization:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Seamless management from doctor discovery to final service evaluation.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Real-time Communication:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Direct instant link between patients and front-desk staff to minimize wait times.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Smart Clinic Tools:</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;Professional dashboard for organizing appointments, shifts, and medical staff efficiently.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Quality Oversight:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Integrated system to monitor overall performance and resolve complaints instantly.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Verified Ratings:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Distinct evaluation system for doctors based on authentic patient experiences to build trust.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Why Rundevu?</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">We bridge the gap between Operational Efficiency and Patient Comfort in one platform, empowering your medical practice to lead in the digital health era.</span></p>', '2026-05-22 23:00:03', '2026-08-06 08:18:25'),
(2, 1, 'ar', 'رنديفو: المنصة المتكاملة لإدارة العيادات ورقمنة الخدمات الطبية', 'من نحن', '<p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام رنديفو هو حل تقني متطور يهدف إلى أتمتة رحلة المريض بالكامل، مما يضمن كفاءة إدارية للأطباء وتجربة سلسة للمرضى من خلال:</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقمنة رحلة المريض:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;إدارة متكاملة تبدأ من البحث عن الطبيب حتى تقييم الخدمة.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تواصل لحظي:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">ربط مباشر بين المريض والاستقبال لسرعة التنسيق وتقليل وقت الانتظار.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• إدارة ذكية للعيادات:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;لوحة تحكم احترافية لتنظيم المواعيد، الورديات، والكوادر الطبية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقابة وجودة:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;نظام لمراقبة الأداء، معالجة الشكاوى فوراً، وضمان الشفافية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تقييم موثوق:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام تقييم منفصل للأطباء يعتمد على تجارب حقيقية لتعزيز المصداقية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">لماذا رانديفو؟</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نجمع بين كفاءة الإدارة و راحة المريض في منصة واحدة تضمن لك الريادة في القطاع الصحي الرقمي.</span></p>', '2026-05-22 23:00:03', '2026-08-06 16:25:30'),
(3, 2, 'en', 'The Randivo Intelligent Ecosystem', NULL, '<p>(Integrated Solutions)</p>', '2026-05-22 23:00:03', '2026-06-03 15:59:14'),
(4, 2, 'ar', 'بوابة رنديفو الذكية', NULL, '<p>&nbsp;(الأنظمة المتكاملة)</p>', '2026-05-22 23:00:03', '2026-08-05 13:46:51'),
(5, 3, 'en', 'Rundevo: The All-in-One Digital Platform for Smart Clinic Management', 'ِAbout Rundevo', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Rundevu is an advanced ecosystem designed to digitize the entire patient journey, ensuring administrative excellence for doctors and a seamless experience for patients through:</span></p><p><br></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• End-to-End Patient Digitization:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Seamless management from doctor discovery to final service evaluation.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Real-time Communication:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Direct instant link between patients and front-desk staff to minimize wait times.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Smart Clinic Tools:</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;Professional dashboard for organizing appointments, shifts, and medical staff efficiently.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Quality Oversight:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Integrated system to monitor overall performance and resolve complaints instantly.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Verified Ratings:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Distinct evaluation system for doctors based on authentic patient experiences to build trust.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">Why Randivo?</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">We bridge the gap between Operational Efficiency and Patient Comfort in one platform, empowering your medical practice to lead in the digital health era.</span></p>', '2026-05-22 23:00:03', '2026-08-05 19:42:17'),
(6, 3, 'ar', 'رنديفو.. المنصة المتكاملة لإدارة العيادات والتحول الرقمي الطبي', 'عن رنديفو', '<p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام رانديفو هو حل تقني متطور يهدف إلى أتمتة رحلة المريض بالكامل، مما يضمن كفاءة إدارية للأطباء وتجربة سلسة للمرضى من خلال:</span></p><p><br></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقمنة رحلة المريض:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;إدارة متكاملة تبدأ من البحث عن الطبيب حتى تقييم الخدمة.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تواصل لحظي:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">ربط مباشر بين المريض والاستقبال لسرعة التنسيق وتقليل وقت الانتظار.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• إدارة ذكية للعيادات:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;لوحة تحكم احترافية لتنظيم المواعيد، الورديات، والكوادر الطبية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقابة وجودة:</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;نظام لمراقبة الأداء، معالجة الشكاوى فوراً، وضمان الشفافية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• تقييم موثوق:&nbsp;</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام تقييم منفصل للأطباء يعتمد على تجارب حقيقية لتعزيز المصداقية.</span></p><p class=\"ql-direction-rtl\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">لماذا رانديفو؟</strong></p><p class=\"ql-direction-rtl\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نجمع بين كفاءة الإدارة و راحة المريض في منصة واحدة تضمن لك الريادة في القطاع الصحي الرقمي.</span></p>', '2026-05-22 23:00:03', '2026-08-05 19:42:17'),
(7, 4, 'en', 'Smart & Integrated Solutions for Digital Healthcare', 'Rundevo Services', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">The Rundevo ecosystem offers a suite of innovative technical services designed to guarantee operational efficiency and patient comfort simultaneously:</span></p>', '2026-05-22 23:00:03', '2026-06-02 23:02:09'),
(8, 4, 'ar', 'حلول ذكية ومتكاملة للرعاية الصحية الرقمية', 'خدمات رنديفو', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">يقدم نظام رنديفو مجموعة من الخدمات التقنية المبتكرة التي تضمن كفاءة التشغيل وراحة المريض في آن واحد</span></p>', '2026-05-22 23:00:03', '2026-08-05 13:43:50'),
(9, 5, 'en', 'Why Choose Us', 'Why Rundevo', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">We don’t just provide a booking app; we are your strategic tech partner in driving your clinic’s success.</span></p>', '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(10, 5, 'ar', 'لماذا تختارنا', 'لماذا رنديفو', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نحن لا نقدم مجرد تطبيق للحجز، بل نكون شريكك التقني لضمان نجاح عيادتك ونمو أعمالك.</span></p>', '2026-05-22 23:00:03', '2026-06-03 14:23:47'),
(11, 6, 'en', 'Join the Future of Healthcare Today!', 'Download App', '<p>Whether you\'re looking for the best doctors or want to manage your family\'s appointments intelligently, our app provides everything you need in one place.</p>', '2026-05-22 23:00:03', '2026-06-03 14:47:20'),
(12, 6, 'ar', 'انضم إلى مستقبل الرعاية الطبية الآن!', 'تحميل التطبيق', '<p>سواء كنت تبحث عن أفضل الأطباء أو ترغب في إدارة مواعيد عائلتك بذكاء، تطبيقنا يوفر لك كل ما تحتاجه في مكان واحد</p>', '2026-05-22 23:00:03', '2026-06-03 14:47:20'),
(13, 7, 'en', 'Rundevo Subscription Packages', 'Packages', '<p>Choose the plan that best fits your business needs and join the Rendezvous Smart Healthcare Network.</p>', '2026-05-22 23:00:03', '2026-06-03 14:47:20'),
(14, 7, 'ar', 'باقات رنديفو: استثمر في مستقبل عيادتك الرقمي', 'الخطط', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">اختر الخطة المناسبة لحجم أعمالك وانضم إلى شبكة رنديفو الطبية الذكية.</span></p>', '2026-05-22 23:00:03', '2026-08-05 13:43:50'),
(15, 8, 'en', 'About Rundevo', 'About Us', '<p><strong style=\"color: rgb(0, 0, 0); background-color: transparent;\">Rundevo: The All-in-One Digital Platform for Smart Clinic Management</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: transparent;\">Randivo is a comprehensive digital ecosystem built to digitize the patient journey. We empower medical centers to automate operations from the initial doctor search to the final service review, utilizing high-end features:</span></p><p><br></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Instant Patient-Clinic Connection:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Real-time communication channels between patients and receptionists to streamline booking and inquiries.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Smart Administrative Tools:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Advanced dashboards giving clinic managers full control over medical staff scheduling and appointment flow.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Seamless &amp; Transparent Experience:</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;A structured medical journey that enhances operational efficiency and builds patient trust.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Performance Monitoring &amp; Quality Control:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Integrated tools to track overall performance and resolve complaints instantly to maintain excellence.</span></p><p><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• Verified Doctor Ratings:&nbsp;</strong></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">A specialized system for independent physician evaluations, ensuring transparency based on authentic patient feedback.</span></p>', '2026-05-22 23:00:03', '2026-06-03 15:17:54'),
(16, 8, 'ar', 'عن رنديفو', 'عنا', '<p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">العنوان: رنديفو.. المنصة المتكاملة لإدارة العيادات والتحول الرقمي الطبي</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">نظام رانديفو هو حل تقني شامل صُمم خصيصاً للمراكز الطبية والعيادات الذكية، بهدف رقمنة رحلة المريض بالكامل وتحويلها إلى تجربة رقمية تفاعلية تبدأ من البحث عن الطبيب وتنتهي بتقييم الخدمة، من خلال الميزات التالية:</span></p><p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• ربط فوري وفعّال:&nbsp;</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">قنوات تواصل لحظية بين المريض وموظفي الاستقبال لتقليل وقت الانتظار وتنسيق الحجوزات بمرونة.</span></p><p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• إدارة ذكية للموارد:</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;لوحات تحكم متطورة تمنح مديري العيادات القدرة على إدارة الكوادر الطبية والمواعيد بدقة متناهية.</span></p><p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• كفاءة تشغيلية وشفافية:&nbsp;</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">يضمن نظامنا تجربة طبية منظمة ترفع من مستوى رضا المرضى وتزيد من إنتاجية الفريق الطبي.</span></p><p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• رقابة وتحليل الأداء:</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;أدوات لمراقبة الأداء العام للمنشأة ومعالجة الشكاوى فوراً لضمان أعلى معايير الجودة.</span></p><p class=\"ql-direction-rtl ql-align-right\"><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\">• نظام تقييم دقيق:&nbsp;</strong></p><p class=\"ql-direction-rtl ql-align-right\"><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">توفير تقييمات منفصلة وموثوقة للأطباء بناءً على تجارب المرضى الحقيقية لتعزيز&nbsp;</span></p>', '2026-05-22 23:00:03', '2026-08-05 13:30:43'),
(17, 9, 'en', 'Rundevo Smart Solutions', NULL, '<p>A Comprehensive Ecosystem for Healthcare</p>', '2026-05-22 23:00:03', '2026-06-03 15:28:01'),
(18, 9, 'ar', 'حلول رنديفو الذكية', NULL, '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">منظومة متكاملة لخدمة القطاع الطبي</span></p>', '2026-05-22 23:00:03', '2026-08-05 13:30:43'),
(19, 10, 'en', 'Shaping the Future of Digital Healthcare', 'Our Vision', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">\"Our goal is to transform medical booking from an administrative burden into a seamless, transparent, and professional digital experience for everyone.\"</span></p>', '2026-05-22 23:00:03', '2026-06-03 16:01:14'),
(20, 10, 'ar', 'صياغة مستقبل الرعاية الصحية الرقمية', 'رؤيتنا', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">\"هدفنا هو تحويل الحجز الطبي من عبء إداري إلى تجربة رقمية تتسم بالسهولة، الشفافية، والاحترافية لجميع الأطراف.\"</span></p>', '2026-05-22 23:00:03', '2026-06-03 16:01:14'),
(21, 11, 'en', 'Rundevo Features', 'Our Services', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">&nbsp;Smart Solutions for Integrated Clinic Management</span></p>', '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(22, 11, 'ar', 'مميزات رنديفو', 'خدماتنا', '<p>حلول ذكية لإدارة العيادات المتكاملة</p>', '2026-05-22 23:00:03', '2026-06-03 16:40:36'),
(23, 12, 'en', 'Frequently Ask Questions', 'Patient Frequently Ask Questions', '<p><br></p>', '2026-05-22 23:00:03', '2026-06-03 16:48:07'),
(24, 12, 'ar', 'الأسئلة الشائعة', 'الأسئلة الشائعة للمرضى', '<p><br></p>', '2026-05-22 23:00:03', '2026-08-06 16:20:53'),
(25, 13, 'en', 'Frequently Ask Questions', 'Clinic Frequently Ask Questions', '<p><br></p>', '2026-05-22 23:00:03', '2026-06-03 16:48:08'),
(26, 13, 'ar', 'الأسئلة الشائعة', 'الأسئلة الشائعة للعيادات', '<p><br></p>', '2026-05-22 23:00:03', '2026-06-03 16:56:07'),
(27, 14, 'en', 'Your First Step Toward Digital Excellence', 'Join Now', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Your First Step Toward Digital Excellence.</span></p><p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">Welcome as our Success Partner. You are moments away from providing your patients with an exceptional booking experience. One step separates you from fully automating your clinic and joining the elite league of smart medical centers.. Let’s get started.</span></p><p><br></p><p><br></p>', '2026-05-22 23:00:04', '2026-06-03 16:59:00'),
(28, 14, 'ar', 'خطوتك الأولى نحو الريادة الرقمية', 'اشترك الآن', '<p><span style=\"background-color: transparent; color: rgb(0, 0, 0);\">أهلاً بك شريكاً للنجاح. أنت على بُعد لحظات من منح مرضاك تجربة حجز استثنائية، وخطوة واحدة تفصلك عن أتمتة عيادتك بالكامل والانضمام لنخبة المراكز الطبية الذكية.. دعنا نبدأ.</span>\r\n		</p><p><br></p>', '2026-05-22 23:00:04', '2026-06-03 16:59:00'),
(29, 15, 'en', 'Contact', 'Contact', 'Contact description', '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(30, 15, 'ar', 'اتصل بنا', 'اتصل بنا', 'اتصل بنا', '2026-05-22 23:00:04', '2026-05-22 23:00:04'),
(31, 16, 'en', NULL, NULL, '<p><br></p>', '2026-07-16 14:55:13', '2026-07-16 14:55:13'),
(32, 16, 'ar', NULL, NULL, '<p><br></p>', '2026-07-16 14:55:13', '2026-08-05 13:43:50'),
(33, 17, 'en', 'Features', NULL, '<p><br></p>', '2026-07-16 14:55:13', '2026-08-05 19:30:20'),
(34, 17, 'ar', 'المميزات', NULL, '<p><br></p>', '2026-07-16 14:55:13', '2026-08-05 19:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_boxes`
--

CREATE TABLE `complaint_boxes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complain` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` int NOT NULL DEFAULT '1' COMMENT 'type 1 send complain to clinic type 2 send complain to admin type 3 send complain to doctors type 4 chat',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_boxes`
--

INSERT INTO `complaint_boxes` (`id`, `user_id`, `clinic_id`, `image`, `complain`, `reply`, `type`, `created_at`, `updated_at`) VALUES
(8, 11, NULL, NULL, 'send complain to admin', NULL, 1, '2022-10-16 23:40:30', '2022-10-16 23:40:30'),
(33, 4, NULL, NULL, 'test', NULL, 1, '2023-03-19 16:54:23', '2023-03-19 16:54:23'),
(47, 4, NULL, NULL, 'welcome 🤗🤗', NULL, 1, '2023-12-29 14:44:42', '2023-12-29 14:44:42'),
(48, 4, NULL, NULL, 'qas', NULL, 1, '2023-12-29 14:46:10', '2023-12-29 14:46:10'),
(51, 4, NULL, NULL, 'twgg', NULL, 1, '2023-12-29 14:53:23', '2023-12-29 14:53:23'),
(71, 28, 210, NULL, 'تمام', NULL, 1, '2026-03-29 15:24:09', '2026-03-29 15:24:09'),
(73, 28, 1, '17749458554544.jpg', 'اختبار لوصول الرسائل \n\nوللتنسيق \n\n\nand languages\n\n\n\n\n\n\n\nthank you', 'تم النظر في التعليق \r\n\r\nالكلام لا يصل منسق ولكن كامل \r\n\r\nالتنسيق ممتاز في التطبيق ولكن في الداش بورد  يراكم الكلام على بعض \r\n\r\n\r\nب استثناء في التطبيق الرساله الطويله لا تظهر للاخر  لعدم وجود سكرول', 1, '2026-03-31 08:30:55', '2026-03-31 08:34:27'),
(77, 28, 204, NULL, 'تيست كريم', 'تيست ادمن \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nتيست كريم ادمن', 1, '2026-03-31 21:30:06', '2026-03-31 21:30:53'),
(78, 28, 204, NULL, 'تيست \n\n\n\n\n\n\n\n\n\n\n\n\n\nتيست', NULL, 1, '2026-03-31 21:31:42', '2026-03-31 21:31:42'),
(79, 28, NULL, NULL, 'تيست \n\n\n\n\n\n\n\n\n\n\nتيست', NULL, 1, '2026-04-01 22:25:02', '2026-04-01 22:25:02'),
(82, 28, NULL, NULL, 'نص سكوي\n\n\n\n\n\n\n\n\n\nنص شكوي', NULL, 1, '2026-04-01 22:44:30', '2026-04-01 22:44:30'),
(83, 28, NULL, NULL, 'نص شكوي \n\n\n\n\n\n\n\n\n\n\n\nنص شكوي', NULL, 1, '2026-04-01 22:45:10', '2026-04-01 22:45:10'),
(85, 4, NULL, NULL, 'test complain description', NULL, 1, '2026-04-03 18:01:00', '2026-04-03 18:01:00'),
(87, 4, 211, NULL, 'test for doctor hossam', NULL, 1, '2026-04-03 18:09:51', '2026-04-03 18:09:51'),
(102, 4, 211, '17756485606498.jpg', 'تجربة ٧', NULL, 1, '2026-04-08 11:42:40', '2026-04-08 11:42:40'),
(104, 3, NULL, NULL, 'test message to clinic', NULL, 1, '2026-04-08 20:37:16', '2026-04-08 20:37:16'),
(105, 3, NULL, NULL, 'gfgjff', NULL, 1, '2026-04-08 20:37:56', '2026-04-08 20:37:56'),
(112, 4, NULL, '17778233967844.jpg', 'تيست \n\n\n\n\n\n\n\n\n\n\n\n\nتيست', 'تماممممم', 1, '2026-05-03 15:49:56', '2026-05-03 15:50:58'),
(113, 4, 1, '17778240812817.jpg', 'شكوي كبيره', 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\nممكن توضيح', 1, '2026-05-03 16:01:21', '2026-05-03 16:01:55'),
(114, 4, NULL, NULL, 'تيست', 'ما هي الشكوي\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nاوك', 1, '2026-05-17 14:54:05', '2026-05-17 14:56:09'),
(115, 4, NULL, '17790299247514.jpg', 'شكوي جديده', 'ماهي الشكوي \r\n\r\n\r\n\r\n\r\n\r\nجديد', 1, '2026-05-17 14:58:44', '2026-05-17 14:59:43'),
(116, 65, NULL, NULL, 'مرحبا مستشفي الحياة', NULL, 1, '2026-06-17 12:26:41', '2026-06-17 12:26:41'),
(117, 65, NULL, NULL, 'مرحبا مستشفي الحياة', NULL, 1, '2026-06-17 12:27:42', '2026-06-17 12:27:42'),
(118, 65, NULL, NULL, 'مرحبا مستشفي الحياة', NULL, 1, '2026-06-17 12:28:06', '2026-06-17 12:28:06'),
(120, 4, NULL, NULL, 'لم يتم الرد علي بسرعة', NULL, 1, '2026-06-25 22:04:24', '2026-06-25 22:04:24'),
(121, 4, NULL, '17824251766529.png', 'مو موجود دكاترة', NULL, 1, '2026-06-25 22:06:16', '2026-06-25 22:06:16'),
(122, 4, NULL, NULL, 'ما عم يتم ارفاق صورة', NULL, 1, '2026-06-25 22:06:42', '2026-06-25 22:06:42'),
(123, 10, NULL, NULL, 'شكوى تيست', NULL, 1, '2026-06-26 13:15:13', '2026-06-26 13:15:13'),
(124, 10, NULL, NULL, 'مرحبا', NULL, 1, '2026-06-29 14:41:33', '2026-06-29 14:41:33'),
(125, 10, NULL, NULL, 'هل يوجد مواعيد؟', NULL, 1, '2026-06-29 14:41:50', '2026-06-29 14:41:50'),
(127, 10, 1, '17831680431989.jpg', 'تيست', NULL, 1, '2026-07-04 12:27:23', '2026-07-04 12:27:23'),
(128, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-04 12:27:40', '2026-07-04 12:27:40'),
(129, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-04 12:29:03', '2026-07-04 12:29:03'),
(130, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-04 12:29:19', '2026-07-04 12:29:19'),
(131, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-04 12:29:40', '2026-07-04 12:29:40'),
(132, 10, NULL, NULL, 'مشكلة تيست', NULL, 1, '2026-07-04 12:41:39', '2026-07-04 12:41:39'),
(133, 10, NULL, NULL, 'تواصل', NULL, 1, '2026-07-08 13:24:16', '2026-07-08 13:24:16'),
(134, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-09 03:18:49', '2026-07-09 03:18:49'),
(135, 10, NULL, NULL, 'تم عمل شكوى لمعرفة متابعة الشكاوى \nيوم الجمعه ٨ مساء\n\nعند قراءة الشكوى يتم التواصل معي', NULL, 1, '2026-07-10 16:43:39', '2026-07-10 16:43:39'),
(136, 10, NULL, NULL, 'تيست', NULL, 1, '2026-07-18 04:53:42', '2026-07-18 04:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `phone`, `message`, `is_read`, `read_by`, `created_at`, `updated_at`) VALUES
(1, 'iiiiiiii', 'uu@w', 'uuuuuuuuuu', 'iiiii', 0, NULL, '2026-05-23 01:11:34', '2026-05-23 01:11:34'),
(2, 'Noemi Hux', 'domains@search-rundevo.net', '7848928227', 'Hey\r\n\r\nRegister rundevo.net in GoogleSearchIndex to be visible in online search results!\r\n\r\nEnlist rundevo.net now: https://searchregister.info', 0, NULL, '2026-05-26 17:22:00', '2026-05-26 17:22:00'),
(3, 'Nichol Meekin', 'domains@search-rundevo.net', '695677606', 'Hi\r\n\r\nFeature rundevo.net in GoogleSearchIndex and have it be visible in google search results!\r\n\r\nEnlist rundevo.net now: https://searchregister.net', 0, NULL, '2026-05-27 18:34:04', '2026-05-27 18:34:04'),
(4, 'Clark Hoy', 'domains@search-rundevo.net', '3405338132', 'Dear Sir/Madam\r\n\r\nInsert rundevo.net in GoogleSearchIndex so it can show up in google search results!\r\n\r\nAdd rundevo.net now: https://searchregister.org', 0, NULL, '2026-05-28 16:18:50', '2026-05-28 16:18:50'),
(5, 'Ashlee Muecke', 'domains@search-randevuksa.com', '2604758683', 'Hi\r\n\r\nInclude randevuksa.com in GoogleSearchIndex so it can be visible in web search results!\r\n\r\nFeature randevuksa.com now: https://searchregister.live', 1, 189, '2026-05-29 15:10:08', '2026-06-01 21:42:31'),
(6, 'Rickie Swanton', 'domains@search-rundevo.net', '7804745564', 'Hi\r\n\r\nInclude rundevo.net in GoogleSearchIndex so it can show up in google search results!\r\n\r\nEnlist rundevo.net now: https://searchregister.info', 0, NULL, '2026-06-08 17:25:44', '2026-06-08 17:25:44'),
(7, 'Mishra', 'anaya.dgtlsolution@gmail.com', '7072666014', 'Hi,\r\n\r\nI noticed your website http://randevuksa.com is newly launched—congratulations on getting it live!\r\n\r\nAt this stage, setting up a strong SEO foundation is crucial so search engines can properly crawl, index, and understand your site.\r\n\r\nI help new websites with essential SEO setup including keyword structure, meta tags, technical SEO, sitemap indexing, and Google Search Console configuration.\r\n\r\nWould you like me to share a few quick recommendations to improve your website’s early visibility?\r\n\r\nBest regards,\r\nAnaya', 0, NULL, '2026-06-17 08:24:41', '2026-06-17 08:24:41'),
(8, 'Ginger Dowling', 'domains@search-rundevo.net', '327926310', 'Greetings\r\n\r\nInclude rundevo.net in GoogleSearchIndex to appear in web search results!\r\n\r\nAdd rundevo.net now: https://searchregister.pro', 0, NULL, '2026-06-18 15:07:20', '2026-06-18 15:07:20'),
(9, 'Mishra', 'anaya.dgtlsolution@gmail.com', '353596302', 'Hi there, \r\n\r\nI recently came across http://randevuksa.com and wanted to get in touch. Your website has good potential, and with the right SEO strategy, it could reach more customers through Google.\r\n\r\nOur SEO services are customized to improve rankings, increase organic traffic, and help businesses generate consistent leads.\r\n\r\nIf this is something you\'d be interested in, let me know, and I\'ll send our SEO strategies along with our package details.\r\n\r\nBest regards,\r\nAnaya', 0, NULL, '2026-07-24 12:40:12', '2026-07-24 12:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name_en`, `name_ar`, `code`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Saudi Arabia', 'المملكة العربية السعودية', 'sa', 1, NULL, '2022-07-03 04:15:17', '2022-07-03 04:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `user_status` int NOT NULL DEFAULT '1' COMMENT '1 user subscribe, 2 not subscribe',
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `valid_from` date NOT NULL,
  `expired_date` date NOT NULL,
  `valid_times` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_number`, `title_en`, `title_ar`, `desc_en`, `desc_ar`, `city_id`, `user_status`, `clinic_id`, `valid_from`, `expired_date`, `valid_times`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '324615378', 'Scan this qr code to get 50% discount during checkout', 'Scan this qr code to get 50% discount during checkout', 'Scan this qr code to get 50% discount during checkout', 'Scan this qr code to get 50% discount during checkout', NULL, 1, NULL, '2022-11-05', '2022-11-24', 9, NULL, '2022-11-06 19:31:50', '2022-11-06 19:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `fax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `name_en`, `name_ar`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Saturday', 'السبت', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(2, 'Sunday', 'الأحد', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(3, 'Monday', 'الاثنين', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(4, 'Tuesday', 'الثلاثاء', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(5, 'Wednesday', 'الأربعاء', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(6, 'Thursday', 'الخميس', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57'),
(7, 'Friday', 'الجمعة', 1, NULL, '2022-08-10 11:40:57', '2022-08-10 11:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `demo_requests`
--

CREATE TABLE `demo_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_requests`
--

INSERT INTO `demo_requests` (`id`, `name`, `clinic_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Ammar Alrefaie', 'Smile horizon Poly clinic', 'ammaryabrod@yahoo.com', '0500723886', '2026-07-02 21:44:39', '2026-07-02 21:44:39'),
(2, 'Ammar Alrefaie', 'Smile horizon Poly clinic', 'ammaryabrod@yahoo.com', '0500723886', '2026-07-02 21:44:54', '2026-07-02 21:44:54'),
(3, 'Ammar Alrefaie', 'Smile horizon Poly clinic', 'ammaryabrod@yahoo.com', '0500723886', '2026-07-02 21:45:55', '2026-07-02 21:45:55'),
(4, 'Ammar Alrefaie', 'Smile horizon Poly clinic', 'ammaryabrod@yahoo.com', '0500723886', '2026-07-04 05:57:48', '2026-07-04 05:57:48'),
(5, 'AYMAN K ABOUFOUL', 'المهيدب', 'dr_aymankmk@hotmail.com', '547872256+966', '2026-07-09 22:26:22', '2026-07-09 22:26:22'),
(6, 'آية شعبان', 'الأمل', 'aya.shaban.23.6.1998@gmail.com', '00963996057001', '2026-07-11 17:43:13', '2026-07-11 17:43:13'),
(7, 'آية شعبان', 'الأمل', 'aya.shaban.23.6.1998@gmail.com', '00963996057001', '2026-07-11 17:43:32', '2026-07-11 17:43:32'),
(8, 'عمار الرفاعي', 'الدولي الطبي', 'dr_aymankmk@hotmail.com', '+966547872256', '2026-07-31 17:03:07', '2026-07-31 17:03:07'),
(9, 'عمار الرفاعي', 'الدولي الطبي', 'dr_aymankmk@hotmail.com', '+966547872256', '2026-07-31 17:11:23', '2026-07-31 17:11:23'),
(10, 'AYMAN K ABOUFOUL', 'المهيدب', 'dr_aymankmk@hotmail.com', '547872256+966', '2026-07-31 17:33:03', '2026-07-31 17:33:03'),
(11, 'eeeee', 'eeeeee', 'tt@gmail.com', '01221274710', '2026-07-31 17:58:41', '2026-07-31 17:58:41'),
(12, 'eeeee', 'eeeeee', 'tt@gmail.com', '01221274710', '2026-07-31 18:01:05', '2026-07-31 18:01:05'),
(13, 'AYMAN K ABOUFOUL', 'المهيدب', 'dr_aymankmk@hotmail.com', '547872256+966', '2026-07-31 18:05:54', '2026-07-31 18:05:54'),
(14, 'Kareem Shaban Abdelmonam', 'kareem clinic', 'shabankareem919@gmail.com', '01090537394', '2026-07-31 19:26:01', '2026-07-31 19:26:01'),
(15, 'عمار الرفاعي', 'الدولي الطبي', 'dr_aymankmk@hotmail.com', '+966547872256', '2026-08-03 09:46:54', '2026-08-03 09:46:54'),
(16, 'عمار الرفاعي', 'الدولي الطبي', 'dr_aymankmk@hotmail.com', '+966547872256', '2026-08-03 09:56:17', '2026-08-03 09:56:17'),
(17, 'مستشفى الامل', 'الأمل', 'alamalhospital@gmail.com', '00963996057001', '2026-08-04 13:57:51', '2026-08-04 13:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_appointments`
--

CREATE TABLE `doctor_appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `day_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date_from` time NOT NULL,
  `date_to` time NOT NULL,
  `period` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `type` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_conditions`
--

CREATE TABLE `doctor_conditions` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `appointments_online` int NOT NULL,
  `appointments_reception` int NOT NULL,
  `number_patients` int NOT NULL DEFAULT '0',
  `condition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `consultation_duration` int DEFAULT NULL COMMENT 'with minutes',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_conditions`
--

INSERT INTO `doctor_conditions` (`id`, `doctor_id`, `appointments_online`, `appointments_reception`, `number_patients`, `condition`, `consultation_duration`, `status`, `created_at`, `updated_at`) VALUES
(6, 202, 30, 30, 30, '', 30, 1, '2026-02-15 15:57:40', '2026-02-15 15:57:40'),
(7, 203, 30, 30, 30, '', 30, 1, '2026-02-15 16:00:18', '2026-02-15 16:00:18'),
(8, 204, 50, 50, 30, '', 30, 1, '2026-02-15 16:04:55', '2026-02-15 16:04:55'),
(9, 205, 50, 50, 30, '', 30, 1, '2026-02-15 16:07:30', '2026-02-15 16:07:30'),
(10, 206, 30, 30, 30, '', 30, 1, '2026-02-15 16:08:54', '2026-02-15 16:08:54'),
(11, 207, 30, 30, 30, '', 30, 1, '2026-02-15 16:10:30', '2026-02-15 16:10:30'),
(12, 208, 30, 30, 30, '', 30, 1, '2026-02-15 16:11:34', '2026-02-15 16:11:34'),
(13, 209, 30, 30, 30, '', 30, 1, '2026-02-15 16:12:24', '2026-02-15 16:12:24'),
(14, 210, 30, 30, 30, '', 30, 1, '2026-02-15 16:13:15', '2026-02-15 16:13:15'),
(15, 211, 30, 30, 30, '', 30, 1, '2026-02-15 16:14:06', '2026-02-15 16:14:06'),
(16, 213, 60, 20, 0, '', 15, 1, '2026-04-02 22:38:43', '2026-04-02 22:38:43'),
(17, 215, 15, 15, 0, '', 30, 1, '2026-05-01 23:10:53', '2026-05-01 23:10:53'),
(19, 227, 50, 50, 0, '', 15, 1, '2026-06-01 13:02:42', '2026-06-01 13:02:42'),
(20, 234, 50, 50, 0, '', 15, 1, '2026-06-29 13:07:56', '2026-06-29 13:07:56'),
(21, 235, 50, 50, 0, '', 15, 1, '2026-06-29 13:13:01', '2026-06-29 13:13:01'),
(24, 245, 50, 50, 0, '', 15, 1, '2026-07-04 13:06:04', '2026-07-04 13:06:04'),
(25, 246, 50, 50, 0, '', 15, 1, '2026-07-06 14:22:41', '2026-07-06 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_degrees`
--

CREATE TABLE `doctor_degrees` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_degrees`
--

INSERT INTO `doctor_degrees` (`id`, `name_en`, `name_ar`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'general doctor ', 'طبيب عام', 1, 1, '2022-08-10 10:30:13', '2022-08-10 10:30:13'),
(2, 'Professor', 'استاذ جامعى', 1, 1, '2022-08-10 10:30:13', '2022-08-10 10:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_emergencies`
--

CREATE TABLE `doctor_emergencies` (
  `id` bigint UNSIGNED NOT NULL,
  `emergency_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE `drugs` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scientific_name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scientific_name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('admin','pharmacy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `medicine_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concentration_ratio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concentration_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Mg' COMMENT 'Mg,g',
  `concentration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usage_or_form` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `international_barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `expiration_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drugs`
--

INSERT INTO `drugs` (`id`, `name_en`, `name_ar`, `scientific_name_ar`, `scientific_name_en`, `short_code`, `type`, `alternative_id`, `clinic_id`, `pharmacy_id`, `doctor_id`, `parent_id`, `medicine_type`, `concentration_ratio`, `concentration_type`, `concentration`, `usage_or_form`, `note`, `international_barcode`, `status`, `expiration_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Paracetamol 500 Mg CAP', 'Paracetamol 500 Mg CAP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mg', NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-11-06 17:47:09', '2022-11-06 17:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `drugs_emergencies`
--

CREATE TABLE `drugs_emergencies` (
  `id` bigint UNSIGNED NOT NULL,
  `emergency_id` bigint UNSIGNED DEFAULT NULL,
  `drugs_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_clinics_taxes`
--

CREATE TABLE `drug_clinics_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `tax` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_sections`
--

CREATE TABLE `drug_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `grand_unit_id` bigint UNSIGNED NOT NULL,
  `micro_unit_id` bigint UNSIGNED NOT NULL,
  `content_of_grand_unit` int NOT NULL,
  `price_of_grand_unit` int UNSIGNED NOT NULL,
  `discount` int UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `returns` tinyint(1) NOT NULL,
  `limit_of_deficiencies` int UNSIGNED NOT NULL,
  `recession_limit` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `electronic_payments`
--

CREATE TABLE `electronic_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `content_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `type` enum('add','minus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'add',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergencies`
--

CREATE TABLE `emergencies` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nurse_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `enter_date` date NOT NULL,
  `exit_date` date NOT NULL,
  `type` int DEFAULT NULL COMMENT '1 Normal exit , 2 Exit by ambulance',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_hospitals`
--

CREATE TABLE `emergency_hospitals` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint UNSIGNED NOT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT '0',
  `lng` double DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `emergency_hospitals`
--

INSERT INTO `emergency_hospitals` (`id`, `name_ar`, `name_en`, `phone`, `image`, `city_id`, `region_id`, `address`, `lat`, `lng`, `status`, `created_at`, `updated_at`) VALUES
(1, 'مستشفى التجمع', 'tagamo3', '01221274710', '17816023361443.png', 1, 14, 'مصر الجديدة، Al Matar, El Nozha, Egypt', 30.112315, 31.3438507, 1, '2026-06-16 09:32:16', '2026-06-16 09:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurances_companies_services`
--

CREATE TABLE `insurances_companies_services` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `clinic_service_id` bigint UNSIGNED DEFAULT NULL,
  `drug_section_id` bigint UNSIGNED DEFAULT NULL,
  `insurance_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED DEFAULT NULL,
  `percentage_discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval` int NOT NULL DEFAULT '0' COMMENT '1 yes, 0 no',
  `type` int NOT NULL DEFAULT '3' COMMENT '1 labs, 2 rays, 3 services',
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 active, 0 de_active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_classes`
--

CREATE TABLE `insurance_classes` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `insurance_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `insurance_classes`
--

INSERT INTO `insurance_classes` (`id`, `company_id`, `insurance_id`, `clinic_id`, `name_en`, `name_ar`, `discount`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 2, NULL, 1, 'CC', 'CC+', 0, 1, NULL, '2024-02-14 23:46:29', '2024-02-14 23:46:29'),
(4, 2, NULL, 1, 'DD', 'DD+', 0, 1, NULL, '2024-02-14 23:46:29', '2024-02-14 23:46:29');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_companies`
--

CREATE TABLE `insurance_companies` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurance_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` int DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 business companies, 2 insurance companies',
  `amount` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `insurance_company_id` bigint UNSIGNED DEFAULT NULL,
  `claims_management_company` enum('waseel','dhs') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` int DEFAULT '1' COMMENT '1 yes pay tax on users, 0 no pay tax on users',
  `policy_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `insurance_companies`
--

INSERT INTO `insurance_companies` (`id`, `name_en`, `name_ar`, `insurance_id`, `clinic_id`, `phone`, `fax`, `email`, `website`, `code`, `provider_id`, `type`, `amount`, `status`, `insurance_company_id`, `claims_management_company`, `tax`, `policy_number`, `date_from`, `date_to`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'Bubba', 'بوبا', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 50, 1, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2024-02-14 23:43:45', '2024-02-14 23:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_policies`
--

CREATE TABLE `insurance_policies` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `deductible` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'نسبة تحمل المريض',
  `ded_max` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'الحد الأقصى لتحمل المريض',
  `approval` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'حد الموافقة',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 active, 0 de_active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `date` date NOT NULL,
  `is_stored_in_inventory_records` tinyint NOT NULL DEFAULT '0',
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_records`
--

CREATE TABLE `inventory_records` (
  `id` bigint UNSIGNED NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_number` int DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_record_items`
--

CREATE TABLE `inventory_record_items` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_record_id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_number` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reception_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `payment_status` enum('paid','un_paid','partially_paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'un_paid',
  `total_price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_tax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `company_tax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `company_total_deductible` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'اجمالى تحمل الشركة %',
  `total_amount_paid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT 'المبلغ المدفوع',
  `other_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `payment_number`, `user_id`, `reception_id`, `doctor_id`, `reservation_id`, `payment_method`, `company_id`, `payment_status`, `total_price`, `discount`, `patient_tax`, `company_tax`, `company_total_deductible`, `total_amount_paid`, `other_info`, `status`, `created_at`, `updated_at`) VALUES
(5, 'INV-00005', NULL, 28, 196, 210, 48, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-25 15:16:41', '2026-03-25 15:16:41'),
(6, 'INV-00006', NULL, 28, 196, 209, 56, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-26 21:53:04', '2026-03-26 21:53:04'),
(7, 'INV-00007', NULL, 28, 196, 205, 60, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-27 21:42:28', '2026-03-27 21:42:28'),
(8, 'INV-00008', NULL, 28, 196, 202, 65, NULL, NULL, 'paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-28 21:31:24', '2026-03-28 21:31:24'),
(9, 'INV-00009', NULL, 28, 196, 202, 66, NULL, NULL, 'paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-28 21:40:22', '2026-03-28 21:40:22'),
(10, 'INV-000010', NULL, 28, 196, 203, 69, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-29 15:04:08', '2026-03-29 15:04:08'),
(11, 'INV-000011', NULL, 28, 196, 204, 70, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-03-29 15:08:10', '2026-03-29 15:08:10'),
(12, 'INV-000012', NULL, 53, 196, 202, 84, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-04-01 22:55:18', '2026-04-01 22:55:18'),
(13, 'INV-000013', NULL, 28, 196, 202, 97, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-04-02 21:37:06', '2026-04-02 21:37:06'),
(14, 'INV-000014', NULL, 28, 196, 209, 98, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-04-02 21:47:09', '2026-04-02 21:47:09'),
(15, 'INV-000015', NULL, 28, 196, 204, 100, NULL, NULL, 'un_paid', '200', '', '0', '0', '0', '100', NULL, 0, '2026-04-02 22:43:41', '2026-04-02 22:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `item_units`
--

CREATE TABLE `item_units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name_en`, `name_ar`, `code`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Arabic', 'العربيه', 'ar', '', 1, '2023-04-03 16:13:07', '2023-04-03 16:13:07'),
(2, 'English', 'الانجليزية', 'en', '', 1, '2023-04-03 16:13:07', '2023-04-03 16:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_coupon_redemptions`
--

CREATE TABLE `loyalty_coupon_redemptions` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points_spent` int UNSIGNED NOT NULL,
  `status` enum('pending','otp_sent','used','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_coupon_redemptions`
--

INSERT INTO `loyalty_coupon_redemptions` (`id`, `coupon_id`, `user_id`, `clinic_id`, `code`, `otp_code`, `points_spent`, `status`, `otp_expires_at`, `used_at`, `confirmed_by`, `created_at`, `updated_at`) VALUES
(2, 1, 76, 201, '6CUFGNK2GA', NULL, 19, 'pending', NULL, NULL, NULL, '2026-07-19 11:26:35', '2026-07-19 11:26:35'),
(3, 1, 77, 201, 'S2EG3I4PIQ', NULL, 19, 'pending', NULL, NULL, NULL, '2026-07-19 12:01:50', '2026-07-19 12:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_point_rules`
--

CREATE TABLE `loyalty_point_rules` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `max_per_day` int UNSIGNED DEFAULT NULL,
  `min_words` int UNSIGNED DEFAULT NULL,
  `expires_after_months` int UNSIGNED NOT NULL DEFAULT '12',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_point_rules`
--

INSERT INTO `loyalty_point_rules` (`id`, `key`, `name_ar`, `name_en`, `points`, `max_per_day`, `min_words`, `expires_after_months`, `status`, `created_at`, `updated_at`) VALUES
(1, 'welcome', 'هدية الترحيب', 'Welcome gift', 50, NULL, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(2, 'completed_visit', 'إتمام الكشف', 'Completed visit', 20, NULL, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(3, 'clinic_cancel_compensation', 'تعويض إلغاء العيادة', 'Clinic cancellation compensation', 5, NULL, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(4, 'share', 'مشاركة من التطبيق', 'In-app share', 5, 2, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(5, 'referral', 'مكافأة دعوة مستخدم', 'Referral bonus', 20, NULL, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(6, 'rating', 'تقييم بعد الزيارة', 'Visit rating', 10, NULL, 20, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14'),
(7, 'electronic_payment', 'الدفع الإلكتروني', 'Electronic payment', 10, NULL, NULL, 12, 1, '2026-07-03 09:43:14', '2026-07-03 09:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_point_transactions`
--

CREATE TABLE `loyalty_point_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` bigint UNSIGNED DEFAULT NULL,
  `rule_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('earn','spend','reversal','expire','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'earn',
  `points` int NOT NULL,
  `description_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_point_transactions`
--

INSERT INTO `loyalty_point_transactions` (`id`, `user_id`, `clinic_id`, `reservation_id`, `source_type`, `source_id`, `rule_key`, `type`, `points`, `description_ar`, `description_en`, `expires_at`, `expired_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 73, 201, NULL, 'loyalty_demo_seed', 1, 'demo_balance', 'adjustment', 200, 'رصيد تجريبي لاختبار دورة نظام النقاط', 'Demo balance for loyalty cycle testing', '2027-07-03 10:25:36', NULL, 1, '2026-07-03 10:25:36', '2026-07-03 10:25:36'),
(2, 73, 201, NULL, 'App\\Models\\LoyaltyCouponRedemption', 1, NULL, 'spend', -80, 'استبدال كوبون نقاط', 'Reward coupon redemption', NULL, NULL, 1, '2026-07-03 10:25:36', '2026-07-03 10:25:36'),
(3, 73, 201, NULL, 'App\\Models\\LoyaltyCouponRedemption', 0, NULL, 'spend', -80, 'استبدال كوبون نقاط', 'Reward coupon redemption', NULL, NULL, 1, '2026-07-03 16:34:26', '2026-07-03 16:34:26'),
(4, 76, NULL, NULL, 'App\\Models\\User', 76, 'welcome', 'earn', 50, 'هدية الترحيب', 'Welcome gift', '2027-07-19 11:08:01', NULL, 1, '2026-07-19 11:08:01', '2026-07-19 11:08:01'),
(5, 76, 1, NULL, 'App\\Models\\LoyaltyCouponRedemption', 1, NULL, 'spend', -19, 'استبدال كوبون نقاط', 'Reward coupon redemption', NULL, NULL, 1, '2026-07-19 11:24:05', '2026-07-19 11:24:05'),
(6, 76, 201, NULL, 'App\\Models\\LoyaltyCouponRedemption', 2, NULL, 'spend', -19, 'استبدال كوبون نقاط', 'Reward coupon redemption', NULL, NULL, 1, '2026-07-19 11:26:35', '2026-07-19 11:26:35'),
(7, 77, NULL, NULL, 'App\\Models\\User', 77, 'welcome', 'earn', 50, 'هدية الترحيب', 'Welcome gift', '2027-07-19 11:56:21', NULL, 1, '2026-07-19 11:56:21', '2026-07-19 11:56:21'),
(8, 77, 201, NULL, 'App\\Models\\LoyaltyCouponRedemption', 3, NULL, 'spend', -19, 'استبدال كوبون نقاط', 'Reward coupon redemption', NULL, NULL, 1, '2026-07-19 12:01:50', '2026-07-19 12:01:50'),
(9, 10, 1, 206, 'App\\Models\\Reservations', 206, 'completed_visit', 'earn', 20, 'إتمام الكشف', 'Completed visit', '2027-07-30 13:39:14', NULL, 1, '2026-07-30 13:39:14', '2026-07-30 13:39:14'),
(10, 78, NULL, NULL, 'App\\Models\\User', 78, 'welcome', 'earn', 50, 'هدية الترحيب', 'Welcome gift', '2027-08-01 14:39:43', NULL, 1, '2026-08-01 14:39:43', '2026-08-01 14:39:43'),
(11, 1, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 2, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-03 13:39:00', NULL, 1, '2026-08-03 13:39:00', '2026-08-03 13:39:00'),
(12, 1, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 3, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-03 13:39:04', NULL, 1, '2026-08-03 13:39:04', '2026-08-03 13:39:04'),
(13, 1, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 4, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 14:30:43', NULL, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43'),
(14, 4, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 5, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 14:55:08', NULL, 1, '2026-08-04 14:55:08', '2026-08-04 14:55:08'),
(15, 4, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 6, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 14:58:23', NULL, 1, '2026-08-04 14:58:23', '2026-08-04 14:58:23'),
(16, 1, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 9, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 15:03:28', NULL, 1, '2026-08-04 15:03:28', '2026-08-04 15:03:28'),
(17, 4, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 15, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 21:14:50', NULL, 1, '2026-08-04 21:14:50', '2026-08-04 21:14:50'),
(18, 4, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 16, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 21:14:58', NULL, 1, '2026-08-04 21:14:58', '2026-08-04 21:14:58'),
(19, 10, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 17, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 21:20:00', NULL, 1, '2026-08-04 21:20:00', '2026-08-04 21:20:00'),
(20, 10, NULL, NULL, 'App\\Models\\LoyaltyShareLog', 18, 'share', 'earn', 5, 'مشاركة التطبيق', 'App share', '2027-08-04 21:20:43', NULL, 1, '2026-08-04 21:20:43', '2026-08-04 21:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_reward_coupons`
--

CREATE TABLE `loyalty_reward_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `service_name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price_before_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_after_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `points_required` int UNSIGNED NOT NULL,
  `usage_limit` int NOT NULL DEFAULT '0',
  `expires_at` date NOT NULL,
  `branch_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_reward_coupons`
--

INSERT INTO `loyalty_reward_coupons` (`id`, `clinic_id`, `service_name_ar`, `service_name_en`, `details_ar`, `details_en`, `price_before_discount`, `discount_type`, `discount_value`, `price_after_discount`, `points_required`, `usage_limit`, `expires_at`, `branch_ids`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 201, 'كشف تجريبي بنظام النقاط', 'Demo loyalty consultation', 'كوبون خصم تجريبي لاختبار الاستبدال والتأكيد بالـ OTP', 'Demo coupon to test redemption and OTP confirmation', 500.00, 'percentage', 25.00, 450.00, 60, 10, '2026-08-03', '[]', 1, '2026-07-03 10:25:36', '2026-07-03 10:25:36', NULL),
(2, 201, 'تحليل CBC تجريبي', 'Demo CBC analysis', 'كوبون ثاني للتأكد من ظهور متجر المكافآت', 'Second demo coupon for rewards store testing', 0.00, 'fixed', 100.00, 0.00, 50, 10, '2026-08-03', '[]', 1, '2026-07-03 10:25:36', '2026-07-03 10:25:36', NULL),
(3, 1, 'تيست', 'test', 'jdsj', 'test', 500.00, 'percentage', 10.00, 450.00, 30, 10, '2026-07-31', '[]', 1, '2026-07-18 11:29:39', '2026-07-18 11:29:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_share_logs`
--

CREATE TABLE `loyalty_share_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `shareable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shareable_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_share_logs`
--

INSERT INTO `loyalty_share_logs` (`id`, `user_id`, `clinic_id`, `shareable_type`, `shareable_id`, `created_at`, `updated_at`) VALUES
(1, 76, 201, 'clinic', 201, '2026-07-19 11:27:12', '2026-07-19 11:27:12'),
(2, 1, NULL, 'app', NULL, '2026-08-03 13:39:00', '2026-08-03 13:39:00'),
(3, 1, NULL, 'app', NULL, '2026-08-03 13:39:04', '2026-08-03 13:39:04'),
(4, 1, NULL, 'app', NULL, '2026-08-04 14:30:43', '2026-08-04 14:30:43'),
(5, 4, NULL, 'app', NULL, '2026-08-04 14:55:08', '2026-08-04 14:55:08'),
(6, 4, NULL, 'app', NULL, '2026-08-04 14:58:23', '2026-08-04 14:58:23'),
(7, 4, NULL, 'app', NULL, '2026-08-04 14:58:50', '2026-08-04 14:58:50'),
(8, 4, NULL, 'app', NULL, '2026-08-04 15:02:03', '2026-08-04 15:02:03'),
(9, 1, NULL, 'app', NULL, '2026-08-04 15:03:28', '2026-08-04 15:03:28'),
(10, 1, NULL, 'app', NULL, '2026-08-04 15:03:37', '2026-08-04 15:03:37'),
(11, 4, NULL, 'app', NULL, '2026-08-04 15:07:40', '2026-08-04 15:07:40'),
(12, 4, NULL, 'app', NULL, '2026-08-04 15:07:46', '2026-08-04 15:07:46'),
(13, 4, 1, 'clinic', 1, '2026-08-04 15:16:38', '2026-08-04 15:16:38'),
(14, 4, NULL, 'app', NULL, '2026-08-04 20:07:05', '2026-08-04 20:07:05'),
(15, 4, NULL, 'app', NULL, '2026-08-04 21:14:50', '2026-08-04 21:14:50'),
(16, 4, NULL, 'app', NULL, '2026-08-04 21:14:58', '2026-08-04 21:14:58'),
(17, 10, NULL, 'app', NULL, '2026-08-04 21:20:00', '2026-08-04 21:20:00'),
(18, 10, NULL, 'app', NULL, '2026-08-04 21:20:43', '2026-08-04 21:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\CmsItem', 7, '6d8764ab-e6b1-42e3-a0ba-79df5c9c5eeb', 'icons_en', 'images - 2026-06-03T015329.477', 'images---2026-06-03T015329.477.jpeg', 'image/jpeg', 'public', 'public', 7673, '[]', '[]', '[]', '[]', 1, '2026-06-02 23:02:09', '2026-06-02 23:02:09'),
(2, 'App\\Models\\CmsItem', 8, '2753d7a0-eb3b-4c60-9e18-c02fcbc1dc93', 'icons_en', 'Screen Shot 2026-06-03 at 1.55.01 AM', 'Screen-Shot-2026-06-03-at-1.55.01-AM.png', 'image/png', 'public', 'public', 284166, '[]', '[]', '[]', '[]', 2, '2026-06-02 23:02:09', '2026-06-02 23:02:09'),
(3, 'App\\Models\\CmsItem', 9, 'b7661d7b-21cd-46d1-b2b1-ff664b2029d7', 'icons_en', '9186538', '9186538.png', 'image/png', 'public', 'public', 34215, '[]', '[]', '[]', '[]', 3, '2026-06-02 23:02:09', '2026-06-02 23:02:09'),
(4, 'App\\Models\\CmsItem', 10, 'b0de986c-3690-438a-90e6-0baab066b59f', 'icons_en', 'Screen Shot 2026-06-03 at 1.56.57 AM', 'Screen-Shot-2026-06-03-at-1.56.57-AM.png', 'image/png', 'public', 'public', 344576, '[]', '[]', '[]', '[]', 4, '2026-06-02 23:02:09', '2026-06-02 23:02:09'),
(5, 'App\\Models\\CmsItem', 11, '80a3c4c9-dcda-4f46-87b9-6186c8f18719', 'icons_en', 'Screen Shot 2026-06-03 at 1.57.34 AM', 'Screen-Shot-2026-06-03-at-1.57.34-AM.png', 'image/png', 'public', 'public', 363103, '[]', '[]', '[]', '[]', 5, '2026-06-02 23:02:09', '2026-06-02 23:02:09'),
(7, 'App\\Models\\CmsItem', 12, 'ba267980-f094-4250-b872-a4ccae574445', 'icons_en', '2782689', '2782689.png', 'image/png', 'public', 'public', 19333, '[]', '[]', '[]', '[]', 6, '2026-06-02 23:03:40', '2026-06-02 23:03:40'),
(8, 'App\\Models\\CmsItem', 12, '7b84f31e-7e57-4b64-aa75-317dafbae549', 'images_en', 'Screen Shot 2026-06-03 at 5.16.06 PM', 'Screen-Shot-2026-06-03-at-5.16.06-PM.png', 'image/png', 'public', 'public', 349371, '[]', '[]', '[]', '[]', 7, '2026-06-03 14:17:06', '2026-06-03 14:17:06'),
(16, 'App\\Models\\CmsItem', 3, '2039a111-bb76-4818-b42f-2db90c5fefda', 'images_en', 'WhatsApp Image 2026-07-13 at 2.42.50 PM', 'WhatsApp-Image-2026-07-13-at-2.42.50-PM.jpeg', 'image/jpeg', 'public', 'public', 86347, '[]', '[]', '[]', '[]', 11, '2026-07-13 12:43:53', '2026-07-13 12:43:53'),
(24, 'App\\Models\\CmsItem', 27, '49a725fa-1677-4139-b331-deaafbf6aa86', 'images_en', 'WhatsApp Image 2026-07-13 at 2.42.50 PM', 'WhatsApp-Image-2026-07-13-at-2.42.50-PM.jpeg', 'image/jpeg', 'public', 'public', 86347, '[]', '[]', '[]', '[]', 16, '2026-07-13 12:56:42', '2026-07-13 12:56:42'),
(26, 'App\\Models\\CmsItem', 35, 'e12ad0b5-4fe8-45f1-ac8f-0e83f82d539a', 'images_en', 'WhatsApp Image 2026-07-13 at 2.46.54 PM', 'WhatsApp-Image-2026-07-13-at-2.46.54-PM.jpeg', 'image/jpeg', 'public', 'public', 68234, '[]', '[]', '[]', '[]', 17, '2026-07-13 12:57:05', '2026-07-13 12:57:05'),
(34, 'App\\Models\\CmsItem', 1, '99d500c7-97d4-47ba-bdb3-657b18e4db64', 'images_en', 'WhatsApp Image 2026-07-13 at 2.42.06 PM', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM.jpeg', 'image/jpeg', 'public', 'public', 43714, '[]', '[]', '[]', '[]', 20, '2026-07-16 14:36:03', '2026-07-16 14:36:03'),
(35, 'App\\Models\\CmsSection', 8, 'b57615db-e6dc-4455-9eb2-7ebb131526e6', 'gallery', 'WhatsApp Image 2026-07-13 at 2.42.06 PM', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM.jpeg', 'image/jpeg', 'public', 'public', 43714, '[]', '[]', '{\"thumb\":true,\"preview\":true}', '[]', 21, '2026-07-16 14:38:01', '2026-07-16 14:38:01'),
(36, 'App\\Models\\CmsSection', 8, '2963e9ab-bd9f-4048-8eaf-d34be734886c', 'images', 'WhatsApp Image 2026-07-13 at 2.42.06 PM', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM.jpeg', 'image/jpeg', 'public', 'public', 43714, '[]', '[]', '{\"thumb\":true,\"preview\":true}', '[]', 21, '2026-07-16 14:38:01', '2026-07-16 14:38:01'),
(37, 'App\\Models\\CmsItem', 1, '34200411-d4e0-4954-a785-a8df4007d48d', 'images_ar', 'WhatsApp Image 2026-07-13 at 2.42.06 PM', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM.jpeg', 'image/jpeg', 'public', 'public', 43714, '[]', '[]', '[]', '[]', 22, '2026-07-16 14:55:13', '2026-07-16 14:55:13'),
(38, 'App\\Models\\CmsItem', 2, '1d4e3a1f-4c76-4251-b441-0b22caea7557', 'images_en', 'WhatsApp Image 2026-07-13 at 2.46.54 PM', 'WhatsApp-Image-2026-07-13-at-2.46.54-PM.jpeg', 'image/jpeg', 'public', 'public', 68234, '[]', '[]', '[]', '[]', 23, '2026-07-16 14:55:13', '2026-07-16 14:55:13'),
(42, 'App\\Models\\CmsItem', 51, '5c6178de-e730-4b62-8d4e-e1c99f5cef0a', 'images_en', 'WhatsApp-Image-2026-07-12-at-7.11.44-PM-thumb', 'WhatsApp-Image-2026-07-12-at-7.11.44-PM-thumb.jpg', 'image/jpeg', 'public', 'public', 24433, '[]', '[]', '[]', '[]', 26, '2026-07-16 15:23:18', '2026-07-16 15:23:18'),
(43, 'App\\Models\\CmsItem', 51, '7c83e776-d87d-4548-adc6-90a675301dcf', 'images_ar', 'WhatsApp-Image-2026-07-12-at-7.11.44-PM-thumb', 'WhatsApp-Image-2026-07-12-at-7.11.44-PM-thumb.jpg', 'image/jpeg', 'public', 'public', 24433, '[]', '[]', '[]', '[]', 27, '2026-07-16 15:23:18', '2026-07-16 15:23:18'),
(44, 'App\\Models\\CmsItem', 6, 'f744167a-f9aa-4be3-98b0-cca0d4e0801a', 'images_en', '300x336 EN', '300x336-EN.jpg', 'image/jpeg', 'public', 'public', 366173, '[]', '[]', '[]', '[]', 28, '2026-07-23 20:39:45', '2026-07-23 20:39:45'),
(45, 'App\\Models\\CmsItem', 6, 'be97def7-9bf0-4e95-8c02-71a4847979fc', 'images_ar', '300x336ar', '300x336ar.jpg', 'image/jpeg', 'public', 'public', 349351, '[]', '[]', '[]', '[]', 29, '2026-07-23 20:39:45', '2026-07-23 20:39:45'),
(47, 'App\\Models\\CmsItem', 5, '94f0217a-6012-4ca2-8d2a-97241af4e6c7', 'images_ar', '315×132 ar -3 ', '315×132-ar--3-.jpg', 'image/jpeg', 'public', 'public', 1100618, '[]', '[]', '[]', '[]', 31, '2026-07-23 20:47:34', '2026-07-23 20:47:34'),
(48, 'App\\Models\\CmsItem', 29, '3f978ab7-45f7-4557-9678-cc51e337fcb9', 'images_en', '132x315 en - 5', '132x315-en---5.jpg', 'image/jpeg', 'public', 'public', 172253, '[]', '[]', '[]', '[]', 32, '2026-07-25 16:43:37', '2026-07-25 16:43:37'),
(49, 'App\\Models\\CmsItem', 29, '11c92267-3afc-42db-92ab-d77e3673e333', 'images_ar', '132x315 ar - 5', '132x315-ar---5.jpg', 'image/jpeg', 'public', 'public', 166018, '[]', '[]', '[]', '[]', 33, '2026-07-25 16:43:37', '2026-07-25 16:43:37'),
(50, 'App\\Models\\CmsItem', 5, 'bea06ad7-5989-4cfc-af1a-f7dbbb05f041', 'images_en', '315X132 en - 4', '315X132-en---4.jpg', 'image/jpeg', 'public', 'public', 162135, '[]', '[]', '[]', '[]', 34, '2026-07-27 08:33:29', '2026-07-27 08:33:29'),
(51, 'App\\Models\\CmsItem', 4, 'f8d6c8e2-315c-4dc9-988c-b1df9c217369', 'images_ar', 'WhatsApp-Image-2026-07-13-at-2.42.50-PM', 'WhatsApp-Image-2026-07-13-at-2.42.50-PM.jpeg', 'image/jpeg', 'public', 'public', 8815, '[]', '[]', '[]', '[]', 35, '2026-07-27 11:46:24', '2026-07-27 11:46:24'),
(52, 'App\\Models\\CmsItem', 7, 'f819bd90-3bf7-48cb-8749-b7ef4bef543d', 'images_en', '315×132 EN - 1.jpg', '315×132-EN---1.jpg.jpeg', 'image/jpeg', 'public', 'public', 1401838, '[]', '[]', '[]', '[]', 36, '2026-08-03 11:32:53', '2026-08-03 11:32:53'),
(53, 'App\\Models\\CmsItem', 7, 'e9d692f0-3314-475a-a62a-8154a85953a6', 'images_ar', '315×132 ar -1.jpg', '315×132-ar--1.jpg.jpeg', 'image/jpeg', 'public', 'public', 1364707, '[]', '[]', '[]', '[]', 37, '2026-08-03 11:32:54', '2026-08-03 11:32:54'),
(54, 'App\\Models\\CmsItem', 9, 'e16f8e9d-86b0-4979-9559-01e056340163', 'images_en', '315×132 EN-3.jpg', '315×132-EN-3.jpg.jpeg', 'image/jpeg', 'public', 'public', 1227972, '[]', '[]', '[]', '[]', 38, '2026-08-03 11:32:55', '2026-08-03 11:32:55'),
(55, 'App\\Models\\CmsItem', 9, 'f3f7aa71-b18f-4a70-8c88-9068c857f85d', 'images_ar', '315×132 ar -3 .jpg', '315×132-ar--3-.jpg.jpeg', 'image/jpeg', 'public', 'public', 1100618, '[]', '[]', '[]', '[]', 39, '2026-08-03 11:32:56', '2026-08-03 11:32:56'),
(56, 'App\\Models\\CmsItem', 8, '2d1d3695-a880-4985-b5b3-66e604d602c7', 'images_en', 'WhatsApp Image 2026-08-01 at 4.32.24 PM', 'WhatsApp-Image-2026-08-01-at-4.32.24-PM.jpeg', 'image/jpeg', 'public', 'public', 52820, '[]', '[]', '[]', '[]', 40, '2026-08-03 11:48:06', '2026-08-03 11:48:06'),
(57, 'App\\Models\\CmsItem', 8, '69d61e9a-3b35-4d48-9c67-3bd2db36e770', 'images_ar', 'WhatsApp Image 2026-08-01 at 4.31.36 PM', 'WhatsApp-Image-2026-08-01-at-4.31.36-PM.jpeg', 'image/jpeg', 'public', 'public', 50645, '[]', '[]', '[]', '[]', 41, '2026-08-03 11:48:06', '2026-08-03 11:48:06'),
(58, 'App\\Models\\CmsItem', 11, 'd5817da5-05ad-4e10-bcfa-cc6f364357cd', 'images_en', 'WhatsApp Image 2026-08-01 at 4.33.21 PM', 'WhatsApp-Image-2026-08-01-at-4.33.21-PM.jpeg', 'image/jpeg', 'public', 'public', 50017, '[]', '[]', '[]', '[]', 42, '2026-08-03 11:52:09', '2026-08-03 11:52:09'),
(59, 'App\\Models\\CmsItem', 11, 'ca98553d-3fdc-4cdd-85bf-7a5c25b43aef', 'images_ar', 'WhatsApp Image 2026-08-01 at 4.32.59 PM', 'WhatsApp-Image-2026-08-01-at-4.32.59-PM.jpeg', 'image/jpeg', 'public', 'public', 46908, '[]', '[]', '[]', '[]', 43, '2026-08-03 11:52:09', '2026-08-03 11:52:09'),
(60, 'App\\Models\\CmsSection', 3, '5425d6d7-0459-4673-b2ec-4e18e628dc78', 'images_en', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM', 'WhatsApp-Image-2026-07-13-at-2.42.06-PM.jpeg', 'image/jpeg', 'public', 'public', 43714, '[]', '[]', '[]', '[]', 44, '2026-08-05 19:43:48', '2026-08-05 19:43:48'),
(61, 'App\\Models\\CmsItem', 10, '21d2857b-5b13-4009-b422-c2e093e69b43', 'images_en', '315×132 - 8 EN.jpg', '315×132---8-EN.jpg.jpeg', 'image/jpeg', 'public', 'public', 70979, '[]', '[]', '[]', '[]', 45, '2026-08-08 08:55:42', '2026-08-08 08:55:42'),
(62, 'App\\Models\\CmsItem', 10, 'bc6920f4-974c-410e-8df6-20f10bbe0234', 'images_ar', '315×132 - 8 AR.jpg', '315×132---8-AR.jpg.jpeg', 'image/jpeg', 'public', 'public', 68279, '[]', '[]', '[]', '[]', 46, '2026-08-08 08:55:42', '2026-08-08 08:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `medical_reports`
--

CREATE TABLE `medical_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `question_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 suffers , 2 question with reason',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_reports`
--

INSERT INTO `medical_reports` (`id`, `question_en`, `question_ar`, `parent_id`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Do You suffer from the following diseases :', 'هل تعانى من الامراض التالية', NULL, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(2, 'Hypertension', 'ضغط الدم', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(3, 'Diabetes', 'امراض السكرى', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(4, 'Heart trouble', 'امراض القلب', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(5, 'Kidney Diseases', 'امراض الكلى', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(6, 'Haemophilia', 'امراض الدم', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(7, 'Thyroid', 'الغدة الدرقية', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(8, 'Asthma', 'الربو', 1, 1, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(9, 'Is There an allergy ? (Mention it, if any)', 'هل يوجد حساسية (ذكرها ان وجد)', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(10, 'Are there genetic diseases ?', 'هل يوجد امراض وراثية (ذكرها ان وجد)', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(11, 'Convulsions , fainting spells, or other mental disorders', 'تشنجات و نوبات اغماء او اضطرابات عقليه اخرى', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(12, 'Have you ever had surgery', 'هل اجريت عمليات جراحية سابقا', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(13, 'For women are you currently pregnant or nursing ?', 'للنساء هل يوجد حمل او رضاعة حاليا', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(14, 'Are you currently receiving medical treatment ?', 'هل تتلقى اى علاج طبى حاليا (ذكرها ان وجد)', NULL, 2, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05'),
(15, 'Are there other diseases :', 'هل يوجد امراض اخرى :', NULL, 3, 1, NULL, '2022-11-05 10:26:05', '2022-11-05 10:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2022_06_27_203600_create_countries_table', 1),
(5, '2022_06_27_204224_create_languages_table', 1),
(6, '2022_06_27_204309_create_cities_table', 1),
(7, '2022_06_27_204310_create_users_table', 1),
(8, '2022_06_27_210031_create_specialties_table', 1),
(9, '2022_06_27_210537_create_app_types_table', 1),
(10, '2022_06_27_210947_create_admins_table', 1),
(11, '2022_06_27_211552_create_packages_table', 1),
(12, '2022_06_27_212813_create_settings_table', 1),
(13, '2022_06_27_213421_create_points_table', 1),
(14, '2022_06_27_214748_create_permissions_types_table', 1),
(15, '2022_06_27_215209_create_clinics_table', 1),
(16, '2022_06_27_225632_create_clinic_device_tokens_table', 1),
(17, '2022_06_27_231016_create_complaint_boxes_table', 1),
(18, '2022_06_27_231622_create_posts_settings_table', 1),
(19, '2022_06_27_232046_create_posts_table', 1),
(20, '2022_06_27_233208_create_clinic_points_table', 1),
(21, '2022_06_27_233830_create_permissions_requests_table', 1),
(22, '2022_06_27_234909_create_days_table', 1),
(23, '2022_06_27_234910_create_attendances_table', 1),
(24, '2022_06_27_235410_create_statuses_table', 1),
(25, '2022_06_28_004344_create_departments_table', 1),
(26, '2022_06_28_004803_create_clinic_specialists_table', 1),
(27, '2022_06_28_005653_create_shifts_table', 1),
(28, '2022_06_28_010408_create_shift_dates_table', 1),
(29, '2022_06_28_011901_create_clinic_offers_table', 1),
(30, '2022_07_13_141517_add_clinic_id_to_app_types_table', 2),
(31, '2022_08_09_232904_create_shift_employees_table', 3),
(32, '2022_08_10_025811_create_working_days_table', 4),
(33, '2022_08_19_235428_create_notifications_table', 4),
(39, '2022_09_18_182249_create_subscriptions_package_users_table', 2),
(40, '2022_09_18_185311_create_ratings_table', 3),
(41, '2022_09_18_185418_create_clinic_ratings_table', 3),
(42, '2022_09_18_192614_create_users_members_table', 4),
(43, '2022_09_18_201126_create_doctor_appointments_table', 5),
(44, '2022_11_04_101420_create_reservations_table', 6),
(47, '2022_11_05_081946_add__i_d__number_to_users_members_table', 8),
(48, '2022_11_05_090352_create_test_results_table', 9),
(49, '2022_11_05_092005_create_drugs_table', 9),
(50, '2022_11_05_095343_create_pharmacy_prescriptions_table', 9),
(51, '2022_11_05_100850_create_medical_reports_table', 9),
(55, '2022_11_05_104640_create_patient_medical_reports_table', 10),
(56, '2022_11_05_105821_create_coupons_table', 10),
(57, '2023_04_30_220340_create_clinic_posts_counts_table', 11),
(58, '2023_05_20_090018_create_permission_tables', 12),
(64, '2023_11_04_201350_create_reservation_chats_table', 17),
(65, '2023_11_05_083329_create_vital_signs_table', 18),
(66, '2023_11_17_124838_add_parent_id_to_reservations_table', 19),
(67, '2023_11_28_010821_create_services_categories_table', 20),
(68, '2023_11_28_010921_create_services_table', 20),
(70, '2023_11_28_013050_create_patient_services_table', 21),
(71, '2023_12_15_133031_create_sick_leaves_table', 22),
(72, '2023_12_15_143630_add_nationality_to_users_table', 22),
(73, '2023_12_29_093541_create_reservation_vital_signs_table', 23),
(74, '2024_02_15_012634_create_insurance_companies_table', 24),
(75, '2024_02_15_013042_create_insurance_classes_table', 24),
(77, '2024_02_15_014053_create_regions_table', 25),
(78, '2024_02_15_014054_add_insurance_company_to_users_table', 26),
(79, '2024_02_15_083415_create_status_conversions_table', 27),
(80, '2024_02_19_084515_create_payment_methods_table', 28),
(81, '2024_02_20_081342_create_invoices_table', 29),
(83, '2024_02_20_085234_add_invoice_id_to_patient_services_table', 30),
(84, '2024_02_20_190134_create_bonds_table', 31),
(85, '2024_03_01_233207_add_manager_in_clinics_table', 32),
(86, '2024_03_01_235813_create_clinic_point_nursings_table', 33),
(87, '2024_03_02_005847_add_nursing_point_in_clinics_table', 34),
(88, '2024_03_02_013631_add_notes_in_clinics_table', 35),
(89, '2024_03_02_223509_add_nurse_id_in_patient_services_table', 36),
(90, '2024_03_03_183503_add_clinic_id_in_patient_services_table', 37),
(92, '2024_03_04_200114_create_emergencies_table', 38),
(93, '2024_03_04_212153_add_emergency_id_in_vital_signs_table', 39),
(94, '2024_03_04_222428_create_doctor_emergencies_table', 40),
(95, '2024_03_04_222454_create_nurse_emergencies_table', 40),
(96, '2024_03_04_222527_create_drugs_emergencies_table', 40),
(97, '2024_03_04_231141_update_vital_signs_set_nullable', 41),
(98, '2024_03_06_214227_update_users_set_nullable', 42),
(99, '2024_03_07_234659_add_notes_in_vital_signs_table', 42),
(100, '2024_03_27_090111_create_accounts_trees_table', 43),
(101, '2024_04_06_114122_add_waiting_list_to_reservations_table', 43),
(102, '2024_04_06_161934_create_takafoul_discounts_table', 44),
(103, '2024_04_15_203939_add_clinic_to_insurance_classes_table', 44),
(104, '2024_04_16_084800_add_clinic_to_insurance_companies_table', 45),
(105, '2024_04_18_050604_add_clinic_to_services_table', 46),
(107, '2024_04_18_093908_create_clinic_services_table', 47),
(110, '2024_04_20_164438_create_insurances_companies_services_table', 48),
(111, '2024_04_06_152157_add_new_columns_to_clinics_table', 49),
(112, '2024_04_06_193249_create_drug_clinics_taxes_table', 49),
(113, '2024_04_08_190139_create_item_units_table', 49),
(114, '2024_04_09_121043_create_customers_table', 49),
(115, '2024_04_09_121106_create_suppliers_table', 49),
(116, '2024_04_10_113227_add_type_column_to_drugs_table', 49),
(117, '2024_04_11_131520_add_another_columns_to_drugs_table', 50),
(118, '2024_04_11_131718_create_drug_sections_table', 50),
(119, '2024_04_13_193817_create_responsible_people_table', 50),
(120, '2024_04_13_194534_create_stores_table', 50),
(121, '2024_04_17_193830_create_inventories_table', 50),
(122, '2024_04_20_221809_add_drug_sections_to_insurances_companies_services_table', 51),
(123, '2024_04_20_224353_add_category_id_to_drug_sections_table', 52),
(124, '2024_04_21_094540_add_insurance_to_insurance_companies_table', 53),
(125, '2024_04_21_121735_create_insurance_policies_table', 54),
(126, '2024_04_22_094427_add_confirm_patient_to_patient_services_table', 55),
(127, '2024_04_18_233216_create_inventory_records_table', 56),
(128, '2024_04_18_233319_create_inventory_record_items_table', 56),
(129, '2024_04_20_001012_add_another_columns_to_bonds_table', 57),
(130, '2024_04_20_181150_create_purchase_invoices_table', 57),
(131, '2024_04_20_181212_create_purchase_invoice_items_table', 57),
(132, '2024_04_20_234109_create_sale_invoices_table', 57),
(133, '2024_04_20_234134_create_sale_invoice_items_table', 57),
(134, '2024_04_22_205116_create_patient_sale_invoices_table', 57),
(135, '2024_04_22_205147_create_patient_sale_invoice_items_table', 57),
(136, '2024_04_29_222745_create_result_manuals_table', 58),
(137, '2024_05_03_190601_create_age_categories_table', 59),
(138, '2024_05_03_204235_create_service_analysis_attributes_table', 60),
(139, '2024_05_11_191709_add_clinic_id_to_services_categories_table', 61),
(140, '2024_05_10_004949_create_pharmacy_invoices_table', 62),
(141, '2024_05_10_010156_create_pharmacy_invoice_items_table', 62),
(142, '2024_05_29_185621_create_user_points_table', 63),
(143, '2024_05_29_191456_create_electronic_payments_table', 64),
(144, '2024_06_08_144241_change_responsible_person_id_in_stores_table', 65),
(145, '2024_07_06_162738_create_points_exchanges_table', 65),
(153, '2024_11_17_060355_create_cost_centers_table', 66),
(154, '2024_11_17_060455_create_daily_entries_table', 66),
(155, '2024_11_17_075321_create_restrictions_table', 66),
(156, '2025_07_28_115106_create_verification_codes_table', 67);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(44, 'App\\Models\\Role', 2),
(108, 'App\\Models\\Role', 2),
(111, 'App\\Models\\Role', 2),
(15, 'App\\Models\\Clinic', 37),
(22, 'App\\Models\\Clinic', 37),
(27, 'App\\Models\\Clinic', 37),
(32, 'App\\Models\\Clinic', 37),
(5, 'App\\Models\\Clinic', 41),
(6, 'App\\Models\\Clinic', 41),
(7, 'App\\Models\\Clinic', 41),
(8, 'App\\Models\\Clinic', 41),
(15, 'App\\Models\\Clinic', 52),
(16, 'App\\Models\\Clinic', 52),
(17, 'App\\Models\\Clinic', 52),
(18, 'App\\Models\\Clinic', 52),
(19, 'App\\Models\\Clinic', 52),
(20, 'App\\Models\\Clinic', 52),
(32, 'App\\Models\\Clinic', 55),
(33, 'App\\Models\\Clinic', 55),
(34, 'App\\Models\\Clinic', 55),
(12, 'App\\Models\\Clinic', 118),
(13, 'App\\Models\\Clinic', 118),
(15, 'App\\Models\\Clinic', 118),
(16, 'App\\Models\\Clinic', 118),
(17, 'App\\Models\\Clinic', 118),
(18, 'App\\Models\\Clinic', 118),
(19, 'App\\Models\\Clinic', 118),
(20, 'App\\Models\\Clinic', 118),
(22, 'App\\Models\\Clinic', 118),
(23, 'App\\Models\\Clinic', 118),
(24, 'App\\Models\\Clinic', 118),
(25, 'App\\Models\\Clinic', 118),
(27, 'App\\Models\\Clinic', 118),
(28, 'App\\Models\\Clinic', 118),
(29, 'App\\Models\\Clinic', 118),
(2, 'App\\Models\\Clinic', 119),
(3, 'App\\Models\\Clinic', 119),
(5, 'App\\Models\\Clinic', 119),
(7, 'App\\Models\\Clinic', 119),
(10, 'App\\Models\\Clinic', 119),
(12, 'App\\Models\\Clinic', 119),
(13, 'App\\Models\\Clinic', 119),
(15, 'App\\Models\\Clinic', 119),
(16, 'App\\Models\\Clinic', 119),
(17, 'App\\Models\\Clinic', 119),
(24, 'App\\Models\\Clinic', 119),
(27, 'App\\Models\\Clinic', 119),
(28, 'App\\Models\\Clinic', 119),
(29, 'App\\Models\\Clinic', 119),
(30, 'App\\Models\\Clinic', 119),
(32, 'App\\Models\\Clinic', 119),
(33, 'App\\Models\\Clinic', 119),
(34, 'App\\Models\\Clinic', 119),
(35, 'App\\Models\\Clinic', 119),
(37, 'App\\Models\\Clinic', 119),
(39, 'App\\Models\\Clinic', 119),
(40, 'App\\Models\\Clinic', 119),
(42, 'App\\Models\\Clinic', 119),
(15, 'App\\Models\\Clinic', 171),
(22, 'App\\Models\\Clinic', 171),
(27, 'App\\Models\\Clinic', 171),
(32, 'App\\Models\\Clinic', 171),
(15, 'App\\Models\\Clinic', 218),
(16, 'App\\Models\\Clinic', 218),
(17, 'App\\Models\\Clinic', 218),
(18, 'App\\Models\\Clinic', 218),
(19, 'App\\Models\\Clinic', 218),
(20, 'App\\Models\\Clinic', 218),
(15, 'App\\Models\\Clinic', 219),
(16, 'App\\Models\\Clinic', 219),
(17, 'App\\Models\\Clinic', 219),
(18, 'App\\Models\\Clinic', 219),
(19, 'App\\Models\\Clinic', 219),
(20, 'App\\Models\\Clinic', 219);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `receiver_id` int DEFAULT NULL COMMENT 'send account id if clinic or user ',
  `type` int NOT NULL DEFAULT '1' COMMENT ' 0 all , 1 send to user , 2 other',
  `title_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `message_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `app_type` int NOT NULL DEFAULT '1' COMMENT ' send to this account',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_status` int DEFAULT '0',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint NOT NULL DEFAULT '0' COMMENT ' 0 not seen , 1 seen',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `flag` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `admin_id`, `clinic_id`, `user_id`, `receiver_id`, `type`, `title_en`, `title_ar`, `message_en`, `message_ar`, `app_type`, `url`, `coupon_status`, `image`, `is_read`, `deleted_at`, `created_at`, `updated_at`, `flag`) VALUES
(1, 1, 1, NULL, 1, 0, 'Welcome to our Randevu Medical Family! 🩺', 'أهلاً بك في عائلتنا الطبية رنديفو! 🩺', 'We\'re delighted to have you join us. Now you can easily book appointments for yourself and your family, and contact your preferred clinic anytime. Your journey to easier healthcare starts here!', ' يسعدنا انضمامك إلينا. الآن يمكنك حجز مواعيدك ومواعيد عائلتك بسهولة، والتواصل مع عيادتك المفضلة في أي وقت. رحلتك نحو رعاية صحية أسهل تبدأ من هنا!', 1, NULL, 0, NULL, 0, NULL, '2025-05-07 07:09:39', NULL, 1),
(5, NULL, 1, 45, 45, 1, 'Booking rejected', 'تم رفض الحجز', 'Booking #310696980 has been rejected. No available slots.', 'تم رفض الحجز رقم 310696980', 1, NULL, 0, NULL, 0, NULL, '2026-05-17 12:42:29', '2026-05-17 12:42:29', 1),
(7, NULL, 1, 61, 61, 1, 'Booking rejected', 'تم رفض الحجز', 'Booking #287141415 has been rejected. No available slots.', 'تم رفض الحجز رقم 287141415', 1, NULL, 0, NULL, 0, NULL, '2026-05-18 04:05:59', '2026-05-18 04:05:59', 1),
(8, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 11:28:30', '2026-06-17 11:28:30', 1),
(9, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 11:28:59', '2026-06-17 11:28:59', 1),
(10, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 11:32:44', '2026-06-17 11:32:44', 1),
(11, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 11:37:45', '2026-06-17 11:37:45', 1),
(12, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #647983653 needs confirmation.', 'يوجد حجز جديد رقم 647983653 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 11:38:15', '2026-06-17 11:38:15', 1),
(13, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #647983653 needs confirmation.', 'يوجد حجز جديد رقم 647983653 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 11:38:15', '2026-06-17 11:38:15', 1),
(14, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:17:53', '2026-06-17 12:17:53', 1),
(15, NULL, 1, NULL, 194, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:17:53', '2026-06-17 12:17:53', 1),
(16, NULL, 1, NULL, 196, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:17:53', '2026-06-17 12:17:53', 1),
(17, NULL, 1, NULL, 225, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:17:53', '2026-06-17 12:17:53', 1),
(18, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:18:19', '2026-06-17 12:18:19', 1),
(19, NULL, 1, NULL, 194, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:18:19', '2026-06-17 12:18:19', 1),
(20, NULL, 1, NULL, 196, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:18:19', '2026-06-17 12:18:19', 1),
(21, NULL, 1, NULL, 225, 2, 'New message', 'New message', 'You have a new booking message.', 'You have a new booking message.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:18:19', '2026-06-17 12:18:19', 1),
(22, NULL, 1, NULL, 1, 2, 'New message', 'New message', 'مرحبا', 'مرحبا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:24:37', '2026-06-17 12:24:37', 1),
(23, NULL, 1, NULL, 194, 2, 'New message', 'New message', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:24:37', '2026-06-17 12:24:37', 1),
(24, NULL, 1, NULL, 196, 2, 'New message', 'New message', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:24:37', '2026-06-17 12:24:37', 1),
(25, NULL, 1, NULL, 225, 2, 'New message', 'New message', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:24:37', '2026-06-17 12:24:37', 1),
(26, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #601039788 needs confirmation.', 'يوجد حجز جديد رقم 601039788 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:25:11', '2026-06-17 12:25:11', 1),
(27, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #601039788 needs confirmation.', 'يوجد حجز جديد رقم 601039788 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:25:11', '2026-06-17 12:25:11', 1),
(28, NULL, 1, NULL, 1, 2, 'New complaint', 'New complaint', 'A new complaint has been sent from the mobile app.', 'A new complaint has been sent from the mobile app.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:28:51', '2026-06-17 12:28:51', 1),
(29, NULL, 1, NULL, 194, 2, 'New complaint', 'New complaint', 'A new complaint has been sent from the mobile app.', 'A new complaint has been sent from the mobile app.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:28:51', '2026-06-17 12:28:51', 1),
(30, NULL, 1, NULL, 196, 2, 'New complaint', 'New complaint', 'A new complaint has been sent from the mobile app.', 'A new complaint has been sent from the mobile app.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:28:51', '2026-06-17 12:28:51', 1),
(31, NULL, 1, NULL, 225, 2, 'New complaint', 'New complaint', 'A new complaint has been sent from the mobile app.', 'A new complaint has been sent from the mobile app.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:28:51', '2026-06-17 12:28:51', 1),
(32, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #774820741 needs confirmation.', 'يوجد حجز جديد رقم 774820741 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:41:27', '2026-06-17 12:41:27', 1),
(33, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #774820741 needs confirmation.', 'يوجد حجز جديد رقم 774820741 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:41:27', '2026-06-17 12:41:27', 1),
(34, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'مرحبا', 'مرحبا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:42:16', '2026-06-17 12:42:16', 1),
(35, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:42:16', '2026-06-17 12:42:16', 1),
(36, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:42:16', '2026-06-17 12:42:16', 1),
(37, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'مرحبا', 'مرحبا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:42:16', '2026-06-17 12:42:16', 1),
(38, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'اهلا وسهلا', 'اهلا وسهلا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:43:06', '2026-06-17 12:43:06', 1),
(39, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'اهلا وسهلا', 'اهلا وسهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:43:06', '2026-06-17 12:43:06', 1),
(40, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'اهلا وسهلا', 'اهلا وسهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:43:06', '2026-06-17 12:43:06', 1),
(41, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'اهلا وسهلا', 'اهلا وسهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:43:06', '2026-06-17 12:43:06', 1),
(42, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:32', '2026-06-17 12:54:32', 1),
(43, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:32', '2026-06-17 12:54:32', 1),
(44, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:32', '2026-06-17 12:54:32', 1),
(45, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:32', '2026-06-17 12:54:32', 1),
(46, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:49', '2026-06-17 12:54:49', 1),
(47, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:49', '2026-06-17 12:54:49', 1),
(48, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:49', '2026-06-17 12:54:49', 1),
(49, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'اهلا', 'اهلا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:54:49', '2026-06-17 12:54:49', 1),
(50, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'تيست', 'تيست', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:04', '2026-06-17 12:55:04', 1),
(51, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'تيست', 'تيست', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:04', '2026-06-17 12:55:04', 1),
(52, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'تيست', 'تيست', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:04', '2026-06-17 12:55:04', 1),
(53, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'تيست', 'تيست', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:04', '2026-06-17 12:55:04', 1),
(54, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'ا', 'ا', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:58', '2026-06-17 12:55:58', 1),
(55, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'ا', 'ا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:58', '2026-06-17 12:55:58', 1),
(56, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'ا', 'ا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:58', '2026-06-17 12:55:58', 1),
(57, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'ا', 'ا', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 12:55:58', '2026-06-17 12:55:58', 1),
(58, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'يشتغل', 'يشتغل', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 13:00:57', '2026-06-17 13:00:57', 1),
(59, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'يشتغل', 'يشتغل', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:00:57', '2026-06-17 13:00:57', 1),
(60, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'يشتغل', 'يشتغل', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:00:57', '2026-06-17 13:00:57', 1),
(61, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'يشتغل', 'يشتغل', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:00:57', '2026-06-17 13:00:57', 1),
(62, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'وة', 'وة', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 13:01:13', '2026-06-17 13:01:13', 1),
(63, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'وة', 'وة', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:01:13', '2026-06-17 13:01:13', 1),
(64, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'وة', 'وة', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:01:13', '2026-06-17 13:01:13', 1),
(65, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'وة', 'وة', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:01:13', '2026-06-17 13:01:13', 1),
(66, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'Kk', 'Kk', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:20', '2026-06-17 13:39:20', 1),
(67, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'Kk', 'Kk', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:20', '2026-06-17 13:39:20', 1),
(68, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'Kk', 'Kk', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:20', '2026-06-17 13:39:20', 1),
(69, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'Kk', 'Kk', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:20', '2026-06-17 13:39:20', 1),
(70, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'J', 'J', 7, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:33', '2026-06-17 13:39:33', 1),
(71, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'J', 'J', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:33', '2026-06-17 13:39:33', 1),
(72, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'J', 'J', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:33', '2026-06-17 13:39:33', 1),
(73, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'J', 'J', 2, NULL, 0, NULL, 0, NULL, '2026-06-17 13:39:33', '2026-06-17 13:39:33', 1),
(76, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #129182554 needs confirmation.', 'يوجد حجز جديد رقم 129182554 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-22 23:42:37', '2026-06-22 23:42:37', 1),
(77, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #129182554 needs confirmation.', 'يوجد حجز جديد رقم 129182554 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-22 23:42:37', '2026-06-22 23:42:37', 1),
(78, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 7, NULL, 0, NULL, 0, NULL, '2026-06-25 22:02:41', '2026-06-25 22:02:41', 1),
(79, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 2, NULL, 0, NULL, 0, NULL, '2026-06-25 22:02:41', '2026-06-25 22:02:41', 1),
(80, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 2, NULL, 0, NULL, 0, NULL, '2026-06-25 22:02:41', '2026-06-25 22:02:41', 1),
(81, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', 2, NULL, 0, NULL, 0, NULL, '2026-06-25 22:02:41', '2026-06-25 22:02:41', 1),
(82, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #366307372 needs confirmation.', 'يوجد حجز جديد رقم 366307372 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-26 13:05:59', '2026-06-26 13:05:59', 1),
(83, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #366307372 needs confirmation.', 'يوجد حجز جديد رقم 366307372 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-26 13:05:59', '2026-06-26 13:05:59', 1),
(84, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #463616993 needs confirmation.', 'يوجد حجز جديد رقم 463616993 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-06-27 11:27:11', '2026-06-27 11:27:11', 1),
(85, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #463616993 needs confirmation.', 'يوجد حجز جديد رقم 463616993 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-06-27 11:27:11', '2026-06-27 11:27:11', 1),
(87, NULL, 1, NULL, 1, 2, 'New complaint', 'شكوى جديدة', 'A new complaint has been sent from the mobile app.', 'لديك شكوى جديدة من التطبيق.', 7, NULL, 0, NULL, 0, NULL, '2026-07-04 12:27:23', '2026-07-04 12:27:23', 1),
(88, NULL, 1, NULL, 194, 2, 'New complaint', 'شكوى جديدة', 'A new complaint has been sent from the mobile app.', 'لديك شكوى جديدة من التطبيق.', 2, NULL, 0, NULL, 0, NULL, '2026-07-04 12:27:23', '2026-07-04 12:27:23', 1),
(89, NULL, 1, NULL, 196, 2, 'New complaint', 'شكوى جديدة', 'A new complaint has been sent from the mobile app.', 'لديك شكوى جديدة من التطبيق.', 2, NULL, 0, NULL, 0, NULL, '2026-07-04 12:27:23', '2026-07-04 12:27:23', 1),
(90, NULL, 1, NULL, 225, 2, 'New complaint', 'شكوى جديدة', 'A new complaint has been sent from the mobile app.', 'لديك شكوى جديدة من التطبيق.', 2, NULL, 0, NULL, 0, NULL, '2026-07-04 12:27:23', '2026-07-04 12:27:23', 1),
(91, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #474268200 needs confirmation.', 'يوجد حجز جديد رقم 474268200 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-04 12:28:19', '2026-07-04 12:28:19', 1),
(92, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #474268200 needs confirmation.', 'يوجد حجز جديد رقم 474268200 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-04 12:28:19', '2026-07-04 12:28:19', 1),
(93, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #369561168 needs confirmation.', 'يوجد حجز جديد رقم 369561168 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-05 14:26:11', '2026-07-05 14:26:11', 1),
(94, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #369561168 needs confirmation.', 'يوجد حجز جديد رقم 369561168 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-05 14:26:11', '2026-07-05 14:26:11', 1),
(95, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #377807291 needs confirmation.', 'يوجد حجز جديد رقم 377807291 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-06 14:36:13', '2026-07-06 14:36:13', 1),
(96, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #377807291 needs confirmation.', 'يوجد حجز جديد رقم 377807291 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-06 14:36:13', '2026-07-06 14:36:13', 1),
(97, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #325586673 needs confirmation.', 'يوجد حجز جديد رقم 325586673 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-06 16:48:58', '2026-07-06 16:48:58', 1),
(98, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #325586673 needs confirmation.', 'يوجد حجز جديد رقم 325586673 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-06 16:48:58', '2026-07-06 16:48:58', 1),
(99, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #688230489 needs confirmation.', 'يوجد حجز جديد رقم 688230489 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-06 17:38:01', '2026-07-06 17:38:01', 1),
(100, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #688230489 needs confirmation.', 'يوجد حجز جديد رقم 688230489 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-06 17:38:01', '2026-07-06 17:38:01', 1),
(101, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #218507639 needs confirmation.', 'يوجد حجز جديد رقم 218507639 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-07 08:36:53', '2026-07-07 08:36:53', 1),
(102, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #218507639 needs confirmation.', 'يوجد حجز جديد رقم 218507639 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-07 08:36:53', '2026-07-07 08:36:53', 1),
(103, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #465912024 needs confirmation.', 'يوجد حجز جديد رقم 465912024 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-07 09:32:36', '2026-07-07 09:32:36', 1),
(104, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #465912024 needs confirmation.', 'يوجد حجز جديد رقم 465912024 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-07 09:32:36', '2026-07-07 09:32:36', 1),
(105, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #115273934 needs confirmation.', 'يوجد حجز جديد رقم 115273934 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-07 11:19:51', '2026-07-07 11:19:51', 1),
(106, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #115273934 needs confirmation.', 'يوجد حجز جديد رقم 115273934 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-07 11:19:51', '2026-07-07 11:19:51', 1),
(107, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #610639346 needs confirmation.', 'يوجد حجز جديد رقم 610639346 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-08 15:26:59', '2026-07-08 15:26:59', 1),
(108, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #610639346 needs confirmation.', 'يوجد حجز جديد رقم 610639346 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-08 15:26:59', '2026-07-08 15:26:59', 1),
(109, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #444429282 needs confirmation.', 'يوجد حجز جديد رقم 444429282 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-08 16:36:37', '2026-07-08 16:36:37', 1),
(110, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #444429282 needs confirmation.', 'يوجد حجز جديد رقم 444429282 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-08 16:36:37', '2026-07-08 16:36:37', 1),
(111, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #756207191 needs confirmation.', 'يوجد حجز جديد رقم 756207191 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-09 03:18:39', '2026-07-09 03:18:39', 1),
(112, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #756207191 needs confirmation.', 'يوجد حجز جديد رقم 756207191 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-09 03:18:39', '2026-07-09 03:18:39', 1),
(113, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #637108044 needs confirmation.', 'يوجد حجز جديد رقم 637108044 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-09 10:48:31', '2026-07-09 10:48:31', 1),
(114, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #637108044 needs confirmation.', 'يوجد حجز جديد رقم 637108044 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-09 10:48:31', '2026-07-09 10:48:31', 1),
(115, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #407655199 needs confirmation.', 'يوجد حجز جديد رقم 407655199 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-11 09:33:07', '2026-07-11 09:33:07', 1),
(116, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #407655199 needs confirmation.', 'يوجد حجز جديد رقم 407655199 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-11 09:33:07', '2026-07-11 09:33:07', 1),
(117, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #896642054 needs confirmation.', 'يوجد حجز جديد رقم 896642054 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-12 08:32:01', '2026-07-12 08:32:01', 1),
(118, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #896642054 needs confirmation.', 'يوجد حجز جديد رقم 896642054 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-12 08:32:01', '2026-07-12 08:32:01', 1),
(119, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #689592209 needs confirmation.', 'يوجد حجز جديد رقم 689592209 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-12 09:19:16', '2026-07-12 09:19:16', 1),
(120, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #689592209 needs confirmation.', 'يوجد حجز جديد رقم 689592209 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-12 09:19:16', '2026-07-12 09:19:16', 1),
(121, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #160771254 needs confirmation.', 'يوجد حجز جديد رقم 160771254 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-12 12:06:01', '2026-07-12 12:06:01', 1),
(122, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #160771254 needs confirmation.', 'يوجد حجز جديد رقم 160771254 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-12 12:06:02', '2026-07-12 12:06:02', 1),
(123, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #780445803 needs confirmation.', 'يوجد حجز جديد رقم 780445803 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-12 18:11:03', '2026-07-12 18:11:03', 1),
(124, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #780445803 needs confirmation.', 'يوجد حجز جديد رقم 780445803 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-12 18:11:03', '2026-07-12 18:11:03', 1),
(125, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #455976689 needs confirmation.', 'يوجد حجز جديد رقم 455976689 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-13 07:58:53', '2026-07-13 07:58:53', 1),
(126, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #455976689 needs confirmation.', 'يوجد حجز جديد رقم 455976689 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-13 07:58:53', '2026-07-13 07:58:53', 1),
(127, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #856876185 needs confirmation.', 'يوجد حجز جديد رقم 856876185 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-13 12:39:12', '2026-07-13 12:39:12', 1),
(128, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #856876185 needs confirmation.', 'يوجد حجز جديد رقم 856876185 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-13 12:39:12', '2026-07-13 12:39:12', 1),
(129, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #548962082 needs confirmation.', 'يوجد حجز جديد رقم 548962082 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-14 13:27:37', '2026-07-14 13:27:37', 1),
(130, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #548962082 needs confirmation.', 'يوجد حجز جديد رقم 548962082 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-14 13:27:37', '2026-07-14 13:27:37', 1),
(131, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #981859293 needs confirmation.', 'يوجد حجز جديد رقم 981859293 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-15 11:57:18', '2026-07-15 11:57:18', 1),
(132, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #981859293 needs confirmation.', 'يوجد حجز جديد رقم 981859293 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-15 11:57:18', '2026-07-15 11:57:18', 1),
(133, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #997372259 needs confirmation.', 'يوجد حجز جديد رقم 997372259 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-15 17:27:21', '2026-07-15 17:27:21', 1),
(134, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #997372259 needs confirmation.', 'يوجد حجز جديد رقم 997372259 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-15 17:27:21', '2026-07-15 17:27:21', 1),
(135, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #535960666 needs confirmation.', 'يوجد حجز جديد رقم 535960666 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-16 10:13:25', '2026-07-16 10:13:25', 1),
(136, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #535960666 needs confirmation.', 'يوجد حجز جديد رقم 535960666 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-16 10:13:25', '2026-07-16 10:13:25', 1),
(137, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #474030633 needs confirmation.', 'يوجد حجز جديد رقم 474030633 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-16 17:01:50', '2026-07-16 17:01:50', 1),
(138, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #474030633 needs confirmation.', 'يوجد حجز جديد رقم 474030633 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-16 17:01:50', '2026-07-16 17:01:50', 1),
(139, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #199861326 needs confirmation.', 'يوجد حجز جديد رقم 199861326 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-18 04:54:13', '2026-07-18 04:54:13', 1),
(140, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #199861326 needs confirmation.', 'يوجد حجز جديد رقم 199861326 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-18 04:54:13', '2026-07-18 04:54:13', 1),
(141, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #145988692 needs confirmation.', 'يوجد حجز جديد رقم 145988692 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-19 16:10:21', '2026-07-19 16:10:21', 1),
(142, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #145988692 needs confirmation.', 'يوجد حجز جديد رقم 145988692 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-19 16:10:22', '2026-07-19 16:10:22', 1),
(143, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #317111252 needs confirmation.', 'يوجد حجز جديد رقم 317111252 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-20 08:18:59', '2026-07-20 08:18:59', 1),
(144, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #317111252 needs confirmation.', 'يوجد حجز جديد رقم 317111252 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-20 08:18:59', '2026-07-20 08:18:59', 1),
(145, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #594931848 needs confirmation.', 'يوجد حجز جديد رقم 594931848 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-21 08:41:50', '2026-07-21 08:41:50', 1),
(146, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #594931848 needs confirmation.', 'يوجد حجز جديد رقم 594931848 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-21 08:41:50', '2026-07-21 08:41:50', 1),
(147, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #586044671 needs confirmation.', 'يوجد حجز جديد رقم 586044671 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-22 12:05:43', '2026-07-22 12:05:43', 1),
(148, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #586044671 needs confirmation.', 'يوجد حجز جديد رقم 586044671 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-22 12:05:43', '2026-07-22 12:05:43', 1),
(149, NULL, 1, NULL, 1, 2, 'New message', 'رسالة جديدة', 'ام', 'ام', 7, NULL, 0, NULL, 0, NULL, '2026-07-25 05:32:10', '2026-07-25 05:32:10', 1),
(150, NULL, 1, NULL, 194, 2, 'New message', 'رسالة جديدة', 'ام', 'ام', 2, NULL, 0, NULL, 0, NULL, '2026-07-25 05:32:10', '2026-07-25 05:32:10', 1),
(151, NULL, 1, NULL, 196, 2, 'New message', 'رسالة جديدة', 'ام', 'ام', 2, NULL, 0, NULL, 0, NULL, '2026-07-25 05:32:10', '2026-07-25 05:32:10', 1),
(152, NULL, 1, NULL, 225, 2, 'New message', 'رسالة جديدة', 'ام', 'ام', 2, NULL, 0, NULL, 0, NULL, '2026-07-25 05:32:10', '2026-07-25 05:32:10', 1),
(153, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #508349866 needs confirmation.', 'يوجد حجز جديد رقم 508349866 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-28 14:04:03', '2026-07-28 14:04:03', 1),
(154, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #508349866 needs confirmation.', 'يوجد حجز جديد رقم 508349866 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-28 14:04:03', '2026-07-28 14:04:03', 1),
(155, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #411175636 needs confirmation.', 'يوجد حجز جديد رقم 411175636 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-29 12:50:39', '2026-07-29 12:50:39', 1),
(156, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #411175636 needs confirmation.', 'يوجد حجز جديد رقم 411175636 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-29 12:50:39', '2026-07-29 12:50:39', 1),
(157, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #501311736 needs confirmation.', 'يوجد حجز جديد رقم 501311736 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-30 13:15:10', '2026-07-30 13:15:10', 1),
(158, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #501311736 needs confirmation.', 'يوجد حجز جديد رقم 501311736 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-30 13:15:11', '2026-07-30 13:15:11', 1),
(159, NULL, 1, 10, 10, 1, 'Rate your booking', 'Rate your booking', 'Booking #501311736 is completed. You can rate it now.', 'Booking #501311736 is completed. You can rate it now.', 1, NULL, 0, NULL, 0, NULL, '2026-07-30 13:39:14', '2026-07-30 13:39:14', 1),
(160, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #375870296 needs confirmation.', 'يوجد حجز جديد رقم 375870296 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-07-30 14:48:37', '2026-07-30 14:48:37', 1),
(161, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #375870296 needs confirmation.', 'يوجد حجز جديد رقم 375870296 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-07-30 14:48:37', '2026-07-30 14:48:37', 1),
(162, NULL, 1, NULL, 1, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #741735634 needs confirmation.', 'يوجد حجز جديد رقم 741735634 يحتاج تأكيد.', 7, NULL, 0, NULL, 0, NULL, '2026-08-01 14:53:38', '2026-08-01 14:53:38', 1),
(163, NULL, 1, NULL, 194, 2, 'New booking needs confirmation', 'حجز جديد يحتاج تأكيد', 'New booking #741735634 needs confirmation.', 'يوجد حجز جديد رقم 741735634 يحتاج تأكيد.', 2, NULL, 0, NULL, 0, NULL, '2026-08-01 14:53:38', '2026-08-01 14:53:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification_events`
--

CREATE TABLE `notification_events` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_events`
--

INSERT INTO `notification_events` (`id`, `key`, `name_en`, `name_ar`, `description_en`, `description_ar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'clinic.registered', 'Clinic registration', 'تسجيل عيادة جديدة', 'Sent when a clinic completes subscription registration.', 'يُرسل عند إتمام تسجيل عيادة جديدة عبر صفحة الاشتراك.', 1, '2026-05-20 23:21:57', '2026-05-20 23:21:57'),
(2, 'demo.requested', 'Demo request', 'طلب حجز موعد', 'Sent when a visitor submits a book-demo form.', 'يُرسل عند إرسال نموذج حجز موعد تجريبي.', 1, '2026-05-20 23:21:57', '2026-05-20 23:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `notification_event_recipient`
--

CREATE TABLE `notification_event_recipient` (
  `id` bigint UNSIGNED NOT NULL,
  `notification_recipient_id` bigint UNSIGNED NOT NULL,
  `notification_event_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_event_recipient`
--

INSERT INTO `notification_event_recipient` (`id`, `notification_recipient_id`, `notification_event_id`) VALUES
(3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `notification_recipients`
--

CREATE TABLE `notification_recipients` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_recipients`
--

INSERT INTO `notification_recipients` (`id`, `email`, `label`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'shabankareem919@gmail.com', 'support', 1, '2026-07-31 19:25:08', '2026-07-31 19:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `nurse_emergencies`
--

CREATE TABLE `nurse_emergencies` (
  `id` bigint UNSIGNED NOT NULL,
  `emergency_id` bigint UNSIGNED DEFAULT NULL,
  `nurse_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duration` int NOT NULL DEFAULT '5' COMMENT 'days',
  `price` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_after_discount` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_months` int DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name_en`, `name_ar`, `features_en`, `features_ar`, `duration`, `price`, `discount`, `price_after_discount`, `free_months`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Medical Centers & Groups (Multi-Branch) One Year', 'باقة العيادات (العيادة الفردية) سنة', 'Advanced management solutions for large centers and hospitals.\r\nExtended Free Trial: 60 Days of full access to test our ecosystem should pay subscription to join the free trial .\r\nLimited Offer: 3,000 SAR / Year for the main branch (Full upfront payment).\r\nFlexible Payment: 3,600 SAR / Year (Payable every 6 months).\r\nScope: Designed for medical complexes, hospitals, and multi-branch clinics.\r\nAdvanced Analytics Dashboard: A unique interface featuring all the data and KPIs needed for professional tracking.\r\nGrowth Insights & Reports: Detailed analytics on patient volume and growth rates to help you scale your practice.\r\n24/7 Technical Support: Our dedicated team is available all week long to ensure your operations never stop.\r\nOnboarding & Training: Hands-on training for your staff on the app and management dashboard.\r\nFree Marketing Campaigns: We boost your clinic’s visibility through dedicated digital marketing efforts.\r\nOutsourced Customer Service: We provide professional customer support if your clinic lacks a dedicated desk.', 'المثالية للعيادات المتخصصة التي تسعى للتميز والنمو.\r\nالفترة التجريبية: 30 يوماً مجاناً بالكامل عند دفع الاشتراك يمكنك الاستمتاع بالتجربة المجانية مضافة إلى مدة الاشتراك .\r\nالعرض المحدود: 3000 ريال سعودي سنوياً (عند السداد الكامل).\r\nوفر 600 ريال سعودي الآن! (السعر الأصلي 3600 ريال).\r\nخيار السداد المرن: 3600 ريال سعودي (يُسدد على دفعتين كل 6 أشهر).\r\nالنطاق: الاشتراك مخصص لفرع/عيادة واحدة فقط.\r\nلوحة تحكم ذكية (Analytics): واجهة فريدة تمنحك كافة الأرقام والبيانات اللازمة لمتابعة سير العمل بدقة.\r\nتقارير معدلات النمو: تقارير تفصيلية توضح عدد المرضى، الحجوزات، وتحليل البيانات لدعم اتخاذ القرار.\r\nدعم فني متواصل: فريق دعم فني مخصص متاح طوال أيام الأسبوع للإجابة على استفساراتكم.\r\nتدريب شامل: جلسات تدريبية مكثفة للطاقم الطبي والإداري على استخدام التطبيق ولوحة التحكم.\r\nالتسويق الرقمي: حملات تسويقية مجانية لزيادة ظهور عيادتك وجذب مرضى جدد.\r\nخدمة العملاء: نوفر لك خدمة عملاء احترافية لعيادتك في حال عدم توفر كادر مخصص لديك.', 360, '3000', NULL, NULL, 1, 1, NULL, '2022-09-18 17:57:26', '2026-06-03 17:28:59'),
(2, 'Medical Centers & Groups (Multi-Branch) 6 Months', 'باقة العيادات (العيادة الفردية) ٦ شهور', 'Advanced management solutions for large centers and hospitals.\r\nBranch Expansion: Add any additional branch or clinic for only 2,000 SAR.\r\nExtended Free Trial: 60 Days of full access to test our ecosystem should pay subscription to join the free trial .\r\nScope: Designed for medical complexes, hospitals, and multi-branch clinics.\r\nAdvanced Analytics Dashboard: A unique interface featuring all the data and KPIs needed for professional tracking.\r\nGrowth Insights & Reports: Detailed analytics on patient volume and growth rates to help you scale your practice.\r\n24/7 Technical Support: Our dedicated team is available all week long to ensure your operations never stop.\r\nOnboarding & Training: Hands-on training for your staff on the app and management dashboard.\r\nFree Marketing Campaigns: We boost your clinic’s visibility through dedicated digital marketing efforts.\r\nOutsourced Customer Service: We provide professional customer support if your clinic lacks a dedicated desk.', 'المثالية للعيادات المتخصصة التي تسعى للتميز والنمو.\r\nالفترة التجريبية: 30 يوماً مجاناً بالكامل عند دفع الاشتراك يمكنك الاستمتاع بالتجربة المجانية مضافة إلى مدة الاشتراك .\r\nالنطاق: الاشتراك مخصص لفرع/عيادة واحدة فقط.\r\n لوحة تحكم ذكية (Analytics): واجهة فريدة تمنحك كافة الأرقام والبيانات اللازمة لمتابعة سير العمل بدقة.\r\nتقارير معدلات النمو: تقارير تفصيلية توضح عدد المرضى، الحجوزات، وتحليل البيانات لدعم اتخاذ القرار.\r\nدعم فني متواصل: فريق دعم فني مخصص متاح طوال أيام الأسبوع للإجابة على استفساراتكم.\r\nتدريب شامل: جلسات تدريبية مكثفة للطاقم الطبي والإداري على استخدام التطبيق ولوحة التحكم.\r\nالتسويق الرقمي: حملات تسويقية مجانية لزيادة ظهور عيادتك وجذب مرضى جدد.\r\nخدمة العملاء: نوفر لك خدمة عملاء احترافية لعيادتك في حال عدم توفر كادر مخصص لديك.', 180, '3600', NULL, NULL, 1, 1, NULL, '2022-09-18 17:57:26', '2026-06-03 17:24:19'),
(3, 'Medical Centers & Groups (Multi-Branch)', 'باقة المجمعات الطبية (الفروع والمستشفيات)', 'Advanced management solutions for large centers and hospitals.\r\nExtended Free Trial: 60 Days of full access to test our ecosystem should pay subscription to join the free trial .\r\nLimited Offer: 3,000 SAR / Year for the main branch (Full upfront payment).\r\nFlexible Payment: 3,600 SAR / Year (Payable every 6 months).\r\nScope: Designed for medical complexes, hospitals, and multi-branch clinics.\r\nAdvanced Analytics Dashboard: A unique interface featuring all the data and KPIs needed for professional tracking.\r\nGrowth Insights & Reports: Detailed analytics on patient volume and growth rates to help you scale your practice.\r\n24/7 Technical Support: Our dedicated team is available all week long to ensure your operations never stop.\r\nOnboarding & Training: Hands-on training for your staff on the app and management dashboard.\r\nFree Marketing Campaigns: We boost your clinic’s visibility through dedicated digital marketing efforts.\r\nOutsourced Customer Service: We provide professional customer support if your clinic lacks a dedicated desk.', 'المثالية للعيادات المتخصصة التي تسعى للتميز والنمو.\r\nالفترة التجريبية: 30 يوماً مجاناً بالكامل عند دفع الاشتراك يمكنك الاستمتاع بالتجربة المجانية مضافة إلى مدة الاشتراك .\r\nالعرض المحدود: 3000 ريال سعودي سنوياً (عند السداد الكامل).\r\nوفر 600 ريال سعودي الآن! (السعر الأصلي 3600 ريال).\r\nخيار السداد المرن: 3600 ريال سعودي (يُسدد على دفعتين كل 6 أشهر).\r\nالنطاق: الاشتراك مخصص لفرع/عيادة واحدة فقط.\r\nلوحة تحكم ذكية (Analytics): واجهة فريدة تمنحك كافة الأرقام والبيانات اللازمة لمتابعة سير العمل بدقة.\r\nتقارير معدلات النمو: تقارير تفصيلية توضح عدد المرضى، الحجوزات، وتحليل البيانات لدعم اتخاذ القرار.\r\nدعم فني متواصل: فريق دعم فني مخصص متاح طوال أيام الأسبوع للإجابة على استفساراتكم.\r\nتدريب شامل: جلسات تدريبية مكثفة للطاقم الطبي والإداري على استخدام التطبيق ولوحة التحكم.\r\nالتسويق الرقمي: حملات تسويقية مجانية لزيادة ظهور عيادتك وجذب مرضى جدد.\r\nخدمة العملاء: نوفر لك خدمة عملاء احترافية لعيادتك في حال عدم توفر كادر مخصص لديك.', 365, '3000', NULL, NULL, 0, 1, NULL, '2022-09-18 17:57:26', '2026-06-03 17:31:25'),
(4, 'Free', 'مجانا', NULL, NULL, 7, '0', NULL, NULL, 0, 0, NULL, '2022-09-18 17:57:26', '2026-06-03 17:13:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_medical_reports`
--

CREATE TABLE `patient_medical_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `report_id` bigint UNSIGNED DEFAULT NULL,
  `answer_id` bigint UNSIGNED DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `report_type` int DEFAULT NULL,
  `answer_flag` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_medical_reports`
--

INSERT INTO `patient_medical_reports` (`id`, `user_id`, `report_id`, `answer_id`, `parent_id`, `reason`, `report_type`, `answer_flag`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, NULL, NULL, NULL, 'Yes', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(2, 3, 8, 8, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(3, 3, 7, 7, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(4, 3, 6, 6, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(5, 3, 5, 5, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(6, 3, 4, 4, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(7, 3, 3, 3, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(8, 3, 2, 2, 1, NULL, 1, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(9, 3, 9, 9, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(10, 3, 10, 10, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(11, 3, 11, 11, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(12, 3, 12, 12, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(13, 3, 13, 13, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(14, 3, 14, 14, 1, NULL, 2, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(15, 3, 15, 15, 1, NULL, 3, 'No', NULL, '2023-08-25 08:14:38', '2023-08-25 08:14:38'),
(16, 3, NULL, NULL, NULL, NULL, NULL, 'Yes', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(17, 3, 8, 8, 16, NULL, 1, 'Yes', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(18, 3, 7, 7, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(19, 3, 6, 6, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(20, 3, 5, 5, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(21, 3, 4, 4, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(22, 3, 3, 3, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(23, 3, 2, 2, 16, NULL, 1, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(24, 3, 9, 9, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(25, 3, 10, 10, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(26, 3, 11, 11, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(27, 3, 12, 12, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(28, 3, 13, 13, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(29, 3, 14, 14, 16, NULL, 2, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(30, 3, 15, 15, 16, NULL, 3, 'No', NULL, '2023-08-25 08:15:05', '2023-08-25 08:15:05'),
(31, 3, NULL, NULL, NULL, NULL, NULL, 'Yes', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(32, 3, 8, 8, 31, NULL, 1, 'Yes', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(33, 3, 7, 7, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(34, 3, 6, 6, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(35, 3, 5, 5, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(36, 3, 4, 4, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(37, 3, 3, 3, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(38, 3, 2, 2, 31, NULL, 1, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(39, 3, 9, 9, 31, 'test', 2, 'Yes', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(40, 3, 10, 10, 31, NULL, 2, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(41, 3, 11, 11, 31, NULL, 2, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(42, 3, 12, 12, 31, NULL, 2, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(43, 3, 13, 13, 31, NULL, 2, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(44, 3, 14, 14, 31, NULL, 2, 'No', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(45, 3, 15, 15, 31, 'لا يوجد امراض أخري', 3, 'Yes', NULL, '2023-08-25 11:49:02', '2023-08-25 11:49:02'),
(46, 4, NULL, NULL, NULL, NULL, NULL, 'Yes', NULL, '2023-11-11 17:59:31', '2023-11-11 17:59:31'),
(47, 4, 8, 8, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(48, 4, 7, 7, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(49, 4, 6, 6, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(50, 4, 5, 5, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(51, 4, 4, 4, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(52, 4, 3, 3, 46, NULL, 1, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(53, 4, 2, 2, 46, NULL, 1, 'Yes', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(54, 4, 9, 9, 46, 'تيست', 2, 'Yes', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(55, 4, 10, 10, 46, NULL, 2, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(56, 4, 11, 11, 46, NULL, 2, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(57, 4, 12, 12, 46, NULL, 2, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(58, 4, 13, 13, 46, NULL, 2, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(59, 4, 14, 14, 46, NULL, 2, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32'),
(60, 4, 15, 15, 46, NULL, 3, 'No', NULL, '2023-11-11 17:59:32', '2023-11-11 17:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `patient_sale_invoices`
--

CREATE TABLE `patient_sale_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `is_saved_invoice` tinyint NOT NULL DEFAULT '0',
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `account_tree_id` bigint UNSIGNED DEFAULT NULL COMMENT 'network',
  `cache_value` bigint DEFAULT NULL,
  `network_value` bigint DEFAULT NULL,
  `transfer_value` bigint DEFAULT NULL,
  `okay_or_cache` enum('okay','cache') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_value` bigint NOT NULL,
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL,
  `tax_value` int DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_sale_invoice_items`
--

CREATE TABLE `patient_sale_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_sale_invoice_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `balance` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_type` enum('grand_unit','micro_unit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` enum('sales','returns') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage_discount` int NOT NULL DEFAULT '0',
  `value_discount` int NOT NULL DEFAULT '0',
  `tax` int NOT NULL DEFAULT '0',
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL COMMENT 'this is value = net_total - (bonus + percentage_discount + value_discount + fixed_discount-tax) ',
  `tax_value` int DEFAULT NULL COMMENT 'net_total_after_discount * tax/100',
  `total_amount` int DEFAULT NULL COMMENT 'net_total_after_discount + tax_value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_services`
--

CREATE TABLE `patient_services` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED DEFAULT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `point_id` bigint UNSIGNED DEFAULT NULL,
  `nurse_id` bigint UNSIGNED DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `tax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `debit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lab_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'doctor notes',
  `lab_notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int DEFAULT '0',
  `type` int DEFAULT NULL COMMENT '1 analysis , 2 rays, 3 service',
  `status` tinyint NOT NULL DEFAULT '0',
  `confirm` tinyint NOT NULL DEFAULT '0',
  `confirm_insurance` int NOT NULL DEFAULT '1' COMMENT '1 confirmed, 0 waiting, 2 cancel',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `clinic_id`, `name_en`, `name_ar`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Debit Card', 'بطاقة ائتمان', 1, NULL, '2024-02-19 07:19:47', '2024-02-19 07:19:47'),
(2, 1, 'Gpay', 'Gpay', 1, NULL, '2024-02-19 07:19:47', '2024-02-19 07:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `permission` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` int NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '1',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `type` enum('admin','clinic') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `name_en`, `name_ar`, `flag`, `status`, `parent_id`, `deleted_at`, `guard_name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'complaints', 'complaints', 'الشكاوى', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(2, 'complaint_view', 'View', 'عرض', 1, 1, 1, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(3, 'complaint_reply', 'Reply', 'الرد', 6, 1, 1, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(4, 'posts', 'Posts', 'المشاركات', 0, 0, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(5, 'post_view', 'View', 'عرض', 1, 1, 4, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(6, 'post_add', 'Add', 'اضافة', 2, 1, 4, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(7, 'post_edit', 'Edit', 'تعديل', 3, 1, 4, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(8, 'post_delete', 'Delete', 'حذف', 4, 1, 4, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(9, 'attendance', 'Attendance', 'الحضور والانصراف', 0, 0, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(10, 'attendance_view', 'manage', 'إدارة الحضور والاذونات', 1, 1, 9, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(11, 'Profile', 'profile', 'الملف الشخصى', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(12, 'profile_view', 'View ', 'عرض', 1, 1, 11, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(13, 'profile_edit', 'Edit', 'تعديل', 3, 1, 11, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(14, 'Departments', 'departments', 'الاقسام', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(15, 'department_view', 'View', 'عرض', 1, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(16, 'department_add', 'Add', 'اضافة', 2, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(17, 'department_edit', 'Edit', 'تعديل', 3, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(18, 'department_status', 'Status', 'تفعيل وعدم تفعيل', 5, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(19, 'department_shift', 'Shifts department', 'ادارة شيفت', 7, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(20, 'department_employees', 'show employees', 'عرض موظفيين', 8, 1, 14, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(21, 'Offers', 'Offers', 'العروض', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(22, 'offers_view', 'View', 'عرض', 1, 1, 21, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(23, 'offers_add', 'Add', 'اضافة', 2, 1, 21, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(24, 'offer_edit', 'Edit', 'تعديل', 3, 1, 21, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(25, 'offers_delete', 'Delete', 'حذف ', 4, 1, 21, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(26, 'specialties', 'specialties', 'التخصصات', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(27, 'specialties_view', 'View', 'عرض', 1, 1, 26, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(28, 'specialties_add', 'Add', 'اضافة', 2, 1, 26, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(29, 'specialties_edit', 'Edit', 'تعديل', 3, 1, 26, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(30, 'specialties_delete', 'Delete', 'حذف', 4, 1, 26, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(31, 'Branches', 'branches', 'فروع العيادة', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(32, 'branches_view', 'View', 'عرض', 1, 1, 31, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(33, 'branches_add', 'Add', 'اضافة', 2, 1, 31, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(34, 'branches_edit', 'Edit', 'تعديل', 3, 1, 31, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(35, 'branches_status', 'Status', 'تفعيل وعدم تفعيل', 5, 1, 31, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(36, 'Supervisors managment', 'supervisors', 'المشرفيين', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(37, 'supervisors_view', 'View', 'عرض', 1, 1, 36, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(38, 'supervisors_add', 'Add', 'اضافة', 2, 1, 36, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(39, 'supervisors_edit', 'Edit', 'تعديل', 3, 1, 36, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(40, 'supervisors_delete', 'Delete', 'حذف', 4, 1, 36, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(41, 'Points', 'points', 'نقاط العيادة', 0, 1, NULL, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(42, 'points_view', 'View', 'عرض', 1, 1, 41, NULL, 'web', 'clinic', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(43, 'dashboard', 'dashboard', 'الصفحة الرئيسية', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(44, 'dashboard_view', 'View', 'عرض', 1, 1, 43, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(45, 'users', 'Users', 'المستخدميين', 1, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(46, 'user_view', 'View', 'عرض', 1, 1, 45, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(47, 'user_add', 'Add', 'اضافة', 2, 1, 45, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(48, 'user_edit', 'Edit', 'تعديل', 3, 1, 45, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(49, 'user_delete', 'Delete', 'حذف', 4, 1, 45, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(50, 'clinics', 'Clinics', 'العيادات بتخصصاتها', 1, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(51, 'clinic_view', 'View', 'عرض', 1, 1, 50, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(52, 'clinic_delete', 'Delete', 'حذف', 4, 1, 50, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(53, 'appointments', 'Appointments', 'قائمة المواعيد', 1, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(54, 'appointment_view', 'View', 'عرض', 1, 1, 53, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(55, 'All specialties', 'Specialties', 'التخصصات', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(56, 'speciality_view', 'View', 'عرض', 1, 1, 55, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(57, 'speciality_add', 'Add', 'اضافة', 2, 1, 55, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(58, 'speciality_edit', 'Edit', 'تعديل', 3, 1, 55, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(59, 'speciality_delete', 'Delete', 'حذف', 4, 1, 55, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(60, 'cities', 'Cities', 'المدن', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(61, 'city_view', 'View', 'عرض', 1, 1, 60, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(62, 'city_add', 'Add', 'اضافة', 2, 1, 60, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(63, 'city_edit', 'Edit', 'تعديل', 3, 1, 60, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(64, 'city_delete', 'Delete', 'حذف', 4, 1, 60, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(65, 'All points', 'All Points', 'النقاط', 1, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(66, 'point_view', 'View', 'عرض', 1, 1, 65, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(67, 'Supervisors', 'Supervisors', 'المشرفيين', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(68, 'supervisor_view', 'View', 'عرض', 1, 1, 67, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(69, 'supervisor_add', 'Add', 'اضافة', 2, 1, 67, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(70, 'supervisor_edit', 'Edit', 'تعديل', 3, 1, 67, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(71, 'supervisor_delete', 'Delete', 'حذف', 4, 1, 67, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(72, 'packages', 'Packages', 'الباقات', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(73, 'package_view', 'View', 'عرض', 1, 1, 72, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(74, 'package_add', 'Add', 'اضافة', 2, 1, 72, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(75, 'package_edit', 'Edit', 'تعديل', 3, 1, 72, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(76, 'package_delete', 'Delete', 'حذف', 4, 1, 72, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(77, 'permissions', 'Permissions', 'انواع الاذونات', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(78, 'permission_view', 'View', 'عرض', 1, 1, 77, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(79, 'permission_add', 'Add', 'اضافة', 2, 1, 77, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(80, 'permission_edit', 'Edit', 'تعديل', 3, 1, 77, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(81, 'permission_delete', 'Delete', 'حذف', 4, 1, 77, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(82, 'notifications', 'Notifications', 'الاشعارات', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(83, 'notification_view', 'View', 'عرض', 1, 1, 82, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(84, 'notification_add', 'Add', 'اضافة', 2, 1, 82, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(85, 'notification_edit', 'Edit', 'تعديل', 3, 1, 82, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(86, 'notification_delete', 'Delete', 'حذف', 4, 1, 82, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(87, 'points exchanges', 'points exchanges', 'تحويلات النقاط', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(88, 'point_exchange_view', 'View', 'عرض', 1, 1, 87, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(89, 'point_exchange_add', 'Add', 'اضافة', 2, 1, 87, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(90, 'point_exchange_edit', 'Edit', 'تعديل', 3, 1, 87, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(91, 'point_exchange_delete', 'Delete', 'حذف', 4, 1, 87, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(92, 'medicine departments', 'medicine departments', 'اقسام الأدوية', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(93, 'medicine_departments_view', 'View', 'عرض', 1, 1, 92, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(94, 'medicine_departments_add', 'Add', 'اضافة', 2, 1, 92, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(95, 'medicine_departments_edit', 'Edit', 'تعديل', 3, 1, 92, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(96, 'medicine_departments_delete', 'Delete', 'حذف', 4, 1, 92, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(97, 'drugs', 'drugs', 'الأدوية', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(98, 'drug_view', 'View', 'عرض', 1, 1, 97, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(99, 'drug_add', 'Add', 'اضافة', 2, 1, 97, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(100, 'drug_edit', 'Edit', 'تعديل', 3, 1, 97, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(101, 'drug_delete', 'Delete', 'حذف', 4, 1, 97, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(102, 'roles', 'roles', 'صلاحيات المشرفيين', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(103, 'role_view', 'View', 'عرض', 1, 1, 102, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(104, 'role_add', 'Add', 'اضافة', 2, 1, 102, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(105, 'role_edit', 'Edit', 'تعديل', 3, 1, 102, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(106, 'role_delete', 'Delete', 'حذف', 4, 1, 102, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(107, 'reports', 'reports', 'التقارير', 1, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(108, 'report_view', 'View', 'عرض', 1, 1, 107, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(109, 'complains box', 'complains box', 'صندوق الشكاوى', 0, 1, NULL, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(110, 'complains_box_view', 'View', 'عرض', 1, 1, 109, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(111, 'complains_box_edit', 'Edit', 'تعديل', 3, 1, 109, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34'),
(112, 'complains_box_delete', 'Delete', 'حذف', 4, 1, 109, NULL, 'web', 'admin', '2022-08-19 16:55:34', '2022-08-19 16:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `permissions_requests`
--

CREATE TABLE `permissions_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `dateA` date DEFAULT NULL,
  `permission_owner` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `permission_type` bigint UNSIGNED NOT NULL,
  `reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 waiting , 1 accept , 2 reject',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions_types`
--

CREATE TABLE `permissions_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions_types`
--

INSERT INTO `permissions_types` (`id`, `name_en`, `name_ar`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Permission to leave Early', 'إذن بالمغادرة مبكرا', 1, NULL, '2022-08-15 12:45:14', '2022-08-15 12:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_invoices`
--

CREATE TABLE `pharmacy_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `is_saved_invoice` tinyint NOT NULL DEFAULT '0',
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `invoice_type` enum('purchases','sales','patient_sales') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_type` enum('purchases','purchases_return','sales','sales_return') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('cache','visa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `store_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `account_tree_id` bigint UNSIGNED DEFAULT NULL COMMENT 'network',
  `cache_value` bigint DEFAULT NULL,
  `network_value` bigint DEFAULT NULL,
  `transfer_value` bigint DEFAULT NULL,
  `okay_or_cache` enum('okay','cache') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count_items_in_invoice` int NOT NULL,
  `paid_value` bigint NOT NULL,
  `net_total` bigint DEFAULT NULL,
  `net_total_after_discount` bigint DEFAULT NULL,
  `tax_value` bigint DEFAULT NULL,
  `total_amount` bigint DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_invoice_items`
--

CREATE TABLE `pharmacy_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `pharmacy_invoice_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `drug_guidelines` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` enum('barcode','without_barcode') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type` enum('grand_unit','micro_unit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` int DEFAULT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `bonus` int DEFAULT '0',
  `percentage_discount` int DEFAULT '0',
  `value_discount` int DEFAULT '0',
  `fixed_discount` int DEFAULT '0',
  `tax` int DEFAULT '0',
  `today_date` date DEFAULT NULL,
  `production_date` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL COMMENT 'this is value = net_total - (bonus + percentage_discount + value_discount + fixed_discount-tax) ',
  `tax_value` int DEFAULT NULL COMMENT 'net_total_after_discount * tax/100',
  `total_amount` int DEFAULT NULL COMMENT 'net_total_after_discount + tax_value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_prescriptions`
--

CREATE TABLE `pharmacy_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `diagnosis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_prescriptions_details`
--

CREATE TABLE `pharmacy_prescriptions_details` (
  `id` bigint UNSIGNED NOT NULL,
  `pharmacy_prescription_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED DEFAULT NULL,
  `repetition` int NOT NULL DEFAULT '1' COMMENT 'time per day',
  `number_days` int NOT NULL DEFAULT '1' COMMENT 'Number of days',
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` int NOT NULL DEFAULT '1',
  `type` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`id`, `name_en`, `name_ar`, `point`, `type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Answer to the question from the doctor', 'جواب سؤال الطبيب', 5, 1, 1, NULL, '2022-08-10 18:13:36', '2022-08-10 18:13:36'),
(2, 'Answer question from the pharmacist', 'أجب عن سؤال الصيدلي', 5, 1, 1, NULL, '2022-08-10 18:13:36', '2022-08-10 18:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `points_exchanges`
--

CREATE TABLE `points_exchanges` (
  `id` bigint UNSIGNED NOT NULL,
  `points` int NOT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points_exchanges`
--

INSERT INTO `points_exchanges` (`id`, `points`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 200, '50', 1, '2024-07-06 14:34:14', '2024-07-06 14:34:14'),
(2, 500, '125', 1, '2024-07-06 14:34:14', '2024-07-06 14:34:14'),
(3, 600, '150', 1, '2024-07-06 14:34:14', '2024-07-06 14:34:14'),
(5, 58, '150', 1, '2025-08-15 15:48:11', '2025-08-15 15:48:11');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `clinic_id`, `image`, `content`, `created_at`, `updated_at`) VALUES
(7, 1, '16595632688036.jpeg', 'sassa as salsjlasasl as', '2023-05-06 05:08:44', '2022-08-04 01:47:48'),
(9, 1, '16596298669640.jpeg', 'new content', '2023-05-06 05:08:44', '2022-08-04 23:17:46'),
(10, 1, '16596498531780.jpg', 'new content', '2023-05-06 05:08:44', '2022-08-05 04:50:53'),
(14, 1, '16596969076110.jpg', 'test', '2023-05-06 05:08:44', '2022-08-05 17:55:07'),
(15, 1, '16596969838279.jpg', 'تجربه', '2023-05-06 05:08:44', '2022-08-05 17:56:23'),
(16, 1, '16600647965875.jpg', 'ssss\nssss\n\nas\naasas', '2023-05-06 05:08:44', '2022-12-20 01:50:23'),
(22, 1, '16805464317652.jpg', 'welcome', '2023-05-06 05:08:44', '2023-04-03 16:27:11'),
(23, 1, '16805939975814.png', 'new post', '2023-05-06 05:08:44', '2023-04-04 05:39:57'),
(24, 1, '16805940168292.jpeg', 'bbb', '2023-05-06 05:08:44', '2023-04-04 05:40:16'),
(26, 1, '16805940532391.jpeg', 'bbb', '2023-05-06 05:08:44', '2023-04-04 05:40:53'),
(27, 1, '16811936486116.png', 'sdsds', '2023-05-06 05:08:44', '2023-04-11 04:14:08'),
(41, 1, '16850301207120.jpg', 'مستشفى الحياة الطبي يتشرف ب الانضمام لنظام عوافي الطبي', '2023-05-25 12:55:20', '2023-05-25 12:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `posts_settings`
--

CREATE TABLE `posts_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `from` int NOT NULL DEFAULT '1' COMMENT 'count doctors',
  `to` int NOT NULL DEFAULT '1' COMMENT 'count doctors',
  `posts_count` int NOT NULL DEFAULT '1' COMMENT 'posts count in week',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts_settings`
--

INSERT INTO `posts_settings` (`id`, `from`, `to`, `posts_count`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, '2022-07-12 16:11:55', '2022-07-12 16:11:55'),
(2, 4, 10, 3, '2022-07-12 16:11:55', '2022-07-12 16:11:55'),
(3, 11, 100, 7, '2022-07-12 16:11:55', '2022-07-12 16:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoices`
--

CREATE TABLE `purchase_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `is_saved_invoice` tinyint NOT NULL DEFAULT '0',
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `movement_type` enum('receipt_bond','exchange_bond') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('cache','visa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `store_id` bigint UNSIGNED NOT NULL,
  `paid_value` bigint NOT NULL,
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL,
  `tax_value` int DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice_items`
--

CREATE TABLE `purchase_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_invoice_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `unit_type` enum('grand_unit','micro_unit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` enum('barcode','without_barcode') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `bonus` int DEFAULT '0',
  `percentage_discount` int DEFAULT '0',
  `value_discount` int DEFAULT '0',
  `fixed_discount` int DEFAULT '0',
  `tax` int DEFAULT '0',
  `today_date` date NOT NULL,
  `production_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL COMMENT 'this is value = net_total - (bonus + percentage_discount + value_discount + fixed_discount-tax) ',
  `tax_value` int DEFAULT NULL COMMENT 'net_total_after_discount * tax/100',
  `total_amount` int DEFAULT NULL COMMENT 'net_total_after_discount + tax_value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `name_en`, `name_ar`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Quality Of Service', 'جوده الخدمة', 1, NULL, '2022-09-18 19:04:52', '2022-09-18 19:04:52'),
(2, 'Price Of the  Service', 'سعر الخدمة', 1, NULL, '2022-09-18 19:04:52', '2022-09-18 19:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name_en`, `name_ar`, `city_id`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Riyadh', 'الرياض', 1, 1, NULL, '2023-12-03 13:47:38', '2026-05-31 21:00:00'),
(2, 'Diriyah', 'الدرعية', 1, 1, NULL, '2023-12-03 13:49:39', '2026-05-31 21:00:00'),
(3, 'Al Kharj', 'الخرج', 1, 1, NULL, '2024-01-25 00:41:09', '2026-05-31 21:00:00'),
(4, 'Al Dawadmi', 'الدوادمي', 1, 1, NULL, '2024-01-25 00:41:37', '2026-05-31 21:00:00'),
(5, 'Al Majmaah', 'المجمعة', 1, 1, NULL, '2024-01-25 00:42:27', '2026-05-31 21:00:00'),
(6, 'Al Quwayiyah', 'القويعية', 1, 1, NULL, '2024-01-25 00:42:56', '2026-05-31 21:00:00'),
(7, 'Al Aflaj', 'الأفلاج', 1, 1, NULL, '2024-01-25 00:43:22', '2026-05-31 21:00:00'),
(8, 'Wadi Al Dawasir', 'وادي الدواسر', 1, 1, NULL, '2024-01-25 00:45:38', '2026-05-31 21:00:00'),
(9, 'Al Zulfi', 'الزلفي', 1, 1, NULL, '2024-01-25 00:46:49', '2026-05-31 21:00:00'),
(10, 'Shaqra', 'شقراء', 1, 1, NULL, '2024-01-25 00:47:25', '2026-05-31 21:00:00'),
(11, 'Hotat Bani Tamim', 'حوطة بني تميم', 1, 1, NULL, '2024-01-25 00:47:59', '2026-05-31 21:00:00'),
(12, 'Afif', 'عفيف', 1, 1, NULL, '2024-01-25 00:48:28', '2026-05-31 21:00:00'),
(13, 'Al Ghat', 'الغاط', 1, 1, NULL, '2024-01-25 00:49:02', '2026-05-31 21:00:00'),
(14, 'Al Sulayyil', 'السليل', 1, 1, NULL, '2024-01-25 00:49:26', '2026-05-31 21:00:00'),
(15, 'Dhurma', 'ضرما', 1, 1, NULL, '2024-01-25 00:51:01', '2026-05-31 21:00:00'),
(16, 'Al Muzahmiyah', 'المزاحمية', 1, 1, NULL, '2024-01-25 00:51:24', '2026-05-31 21:00:00'),
(17, 'Ramah', 'رماح', 1, 1, NULL, '2024-01-25 00:52:01', '2026-05-31 21:00:00'),
(18, 'Thadiq', 'ثادق', 1, 1, NULL, '2024-01-25 00:52:56', '2026-05-31 21:00:00'),
(19, 'Huraymila', 'حريملاء', 1, 1, NULL, '2024-01-25 00:53:25', '2026-05-31 21:00:00'),
(20, 'Al Hariq', 'الحريق', 1, 1, NULL, '2024-01-25 00:53:46', '2026-05-31 21:00:00'),
(21, 'Marat', 'مرات', 1, 1, NULL, '2024-01-25 00:55:14', '2026-05-31 21:00:00'),
(22, 'Al Rayn', 'الرين', 1, 1, NULL, '2024-01-25 00:55:30', '2026-05-31 21:00:00'),
(23, 'Al Dilam', 'الدلم', 1, 1, NULL, '2024-01-25 00:55:50', '2026-05-31 21:00:00'),
(24, 'Makkah', 'مكة المكرمة', NULL, 1, NULL, '2024-01-25 00:56:51', '2026-05-31 21:00:00'),
(25, 'Jeddah', 'جدة', NULL, 1, NULL, '2024-01-25 00:57:29', '2026-05-31 21:00:00'),
(26, 'Taif', 'الطائف', NULL, 1, NULL, '2024-01-25 00:58:52', '2026-05-31 21:00:00'),
(27, 'Al Qunfudhah', 'القنفذة', NULL, 1, NULL, '2024-01-25 00:59:16', '2026-05-31 21:00:00'),
(28, 'Al Lith', 'الليث', NULL, 1, NULL, '2024-01-25 00:59:45', '2026-05-31 21:00:00'),
(29, 'Rabigh', 'رابغ', NULL, 1, NULL, '2024-01-25 01:00:33', '2026-05-31 21:00:00'),
(30, 'Khulays', 'خليص', NULL, 1, NULL, '2024-01-25 01:01:01', '2026-05-31 21:00:00'),
(31, 'Al Khurmah', 'الخرمة', NULL, 1, NULL, '2024-01-25 01:02:11', '2026-05-31 21:00:00'),
(32, 'Ranyah', 'رنية', NULL, 1, NULL, '2024-01-25 01:02:29', '2026-05-31 21:00:00'),
(33, 'Turubah', 'تربة', NULL, 1, NULL, '2024-01-25 01:02:52', '2026-05-31 21:00:00'),
(34, 'Al Jumum', 'الجموم', NULL, 1, NULL, '2024-01-25 01:03:28', '2026-05-31 21:00:00'),
(35, 'Al Kamil', 'الكامل', NULL, 1, NULL, '2024-01-25 01:03:58', '2026-05-31 21:00:00'),
(36, 'Al Muwayh', 'المويه', NULL, 1, NULL, '2024-01-25 01:05:36', '2026-05-31 21:00:00'),
(37, 'Maysan', 'ميسان', NULL, 1, NULL, '2024-01-25 01:05:55', '2026-05-31 21:00:00'),
(38, 'Adham', 'أضم', NULL, 1, NULL, '2024-01-25 01:06:14', '2026-05-31 21:00:00'),
(39, 'Al Ardiyat', 'العرضيات', NULL, 1, NULL, '2024-01-25 01:06:35', '2026-05-31 21:00:00'),
(40, 'Bahrah', 'بحرة', NULL, 1, NULL, '2024-01-25 01:06:57', '2026-05-31 21:00:00'),
(41, 'Madinah', 'المدينة المنورة', NULL, 1, NULL, '2024-01-25 01:07:19', '2026-05-31 21:00:00'),
(42, 'Yanbu', 'ينبع', NULL, 1, NULL, '2024-01-25 01:07:44', '2026-05-31 21:00:00'),
(43, 'Al Ula', 'العلا', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(44, 'Mahd', 'المهد', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(45, 'Al Henakiyah', 'الحناكية', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(46, 'Badr', 'بدر', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(47, 'Khaybar', 'خيبر', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(48, 'Al Ais', 'العيص', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(49, 'Wadi Al Fara', 'وادي الفرع', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(50, 'Buraydah', 'بريدة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(51, 'Unaizah', 'عنيزة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(52, 'Ar Rass', 'الرس', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(53, 'Al Mithnab', 'المذنب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(54, 'Al Bukayriyah', 'البكيرية', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(55, 'Al Badayea', 'البدائع', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(56, 'Asyah', 'الأسياح', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(57, 'Al Nabhaniyah', 'النبهانية', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(58, 'Al Shimasiyah', 'الشماسية', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(59, 'Uyun Al Jiwa', 'عيون الجواء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(60, 'Riyadh Al Khabra', 'رياض الخبراء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(61, 'Uqlat Al Suqur', 'عقلة الصقور', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(62, 'Dariyah', 'ضرية', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(63, 'Abanat', 'أبانات', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(64, 'Dammam', 'الدمام', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(65, 'Al Ahsa', 'الأحساء', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(66, 'Hafar Al Batin', 'حفر الباطن', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(67, 'Jubail', 'الجبيل', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(68, 'Qatif', 'القطيف', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(69, 'Khobar', 'الخبر', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(70, 'Khafji', 'الخفجي', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(71, 'Ras Tanura', 'رأس تنورة', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(72, 'Abqaiq', 'بقيق', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(73, 'Nairyah', 'النعيرية', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(74, 'Qaryat Al Ulya', 'قرية العليا', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(75, 'Al Udayd', 'العديد', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(76, 'Al Bayda', 'البيضاء', 5, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(77, 'Abha', 'أبها', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(78, 'Khamis Mushait', 'خميس مشيط', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(79, 'Bisha', 'بيشة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(80, 'Al Namas', 'النماص', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(81, 'Muhayil Asir', 'محايل عسير', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(82, 'Dhahran Al Janub', 'ظهران الجنوب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(83, 'Tathlith', 'تثليث', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(84, 'Sarat Abidah', 'سراة عبيدة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(85, 'Rijal Almaa', 'رجال ألمع', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(86, 'Balqarn', 'بلقرن', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(87, 'Ahad Rafidah', 'أحد رفيدة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(88, 'Al Majardah', 'المجاردة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(89, 'Al Birk', 'البرك', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(90, 'Bariq', 'بارق', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(91, 'Tanomah', 'تنومة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(92, 'Tarib', 'طريب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(93, 'Al Harajah', 'الحرجة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(94, 'Tabuk', 'تبوك', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(95, 'Al Wajh', 'الوجه', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(96, 'Duba', 'ضباء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(97, 'Tayma', 'تيماء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(98, 'Umluj', 'أملج', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(99, 'Haql', 'حقل', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(100, 'Al Bad', 'البدع', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(101, 'Hail', 'حائل', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(102, 'Baqaa', 'بقعاء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(103, 'Al Ghazalah', 'الغزالة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(104, 'Ash Shinan', 'الشنان', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(105, 'Al Hait', 'الحائط', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(106, 'Al Sulaimi', 'السليمي', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(107, 'Ash Shamli', 'الشملي', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(108, 'Mawqaq', 'موقق', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(109, 'Sumaira', 'سميراء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(110, 'Arar', 'عرعر', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(111, 'Rafha', 'رفحاء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(112, 'Turaif', 'طريف', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(113, 'Al Uwayqilah', 'العويقيلة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(114, 'Jazan', 'جازان', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(115, 'Sabya', 'صبيا', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(116, 'Abu Arish', 'أبو عريش', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(117, 'Samtah', 'صامطة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(118, 'Baish', 'بيش', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(119, 'Al Darb', 'الدرب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(120, 'Al Harth', 'الحرث', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(121, 'Damad', 'ضمد', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(122, 'Ar Rayth', 'الريث', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(123, 'Farasan', 'فرسان', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(124, 'Al Dayer', 'الدائر', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(125, 'Al Aridah', 'العارضة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(126, 'Ahad Al Masarihah', 'أحد المسارحة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(127, 'Al Eidabi', 'العيدابي', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(128, 'Fayfa', 'فيفاء', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(129, 'Al Tuwal', 'الطوال', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(130, 'Harub', 'هروب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(131, 'Najran', 'نجران', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(132, 'Sharurah', 'شرورة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(133, 'Habuna', 'حبونا', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(134, 'Badr Al Janub', 'بدر الجنوب', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(135, 'Yadamah', 'يدمة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(136, 'Thar', 'ثار', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(137, 'Khubash', 'خباش', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(138, 'Al Bahah', 'الباحة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(139, 'Baljurashi', 'بلجرشي', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(140, 'Al Mandaq', 'المندق', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(141, 'Al Makhwah', 'المخواة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(142, 'Qilwah', 'قلوة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(143, 'Al Aqiq', 'العقيق', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(144, 'Al Qara', 'القرى', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(145, 'Ghamid Al Zinad', 'غامد الزناد', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(146, 'Al Hajrah', 'الحجرة', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(147, 'Bani Hasan', 'بني حسن', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(148, 'Sakaka', 'سكاكا', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(149, 'Qurayyat', 'القريات', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(150, 'Dumat Al Jandal', 'دومة الجندل', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00'),
(151, 'Tabarjal', 'طبرجل', NULL, 1, NULL, '2026-05-31 21:00:00', '2026-05-31 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_number` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `reception_id` bigint UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `appointment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `status_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `sub_specialist_id` bigint DEFAULT NULL,
  `diagnosis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `symptoms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `clinical_examination` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommendations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `schedule_consultation_date` date DEFAULT NULL,
  `schedule_consultation_time` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int DEFAULT '1' COMMENT '1 user from app, 2 doctor from admin',
  `follow_up` bigint NOT NULL DEFAULT '0',
  `payment_status` int NOT NULL DEFAULT '0',
  `waiting_list` int DEFAULT NULL,
  `booking_flow` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'instant',
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform_commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `platform_commission_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `clinic_net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `settlement_direction` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `financial_cycle_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `booking_number`, `user_id`, `parent_id`, `doctor_id`, `clinic_id`, `reception_id`, `date`, `appointment`, `price`, `status_id`, `sub_specialist_id`, `diagnosis`, `symptoms`, `clinical_examination`, `recommendations`, `notes`, `schedule_consultation_date`, `schedule_consultation_time`, `type`, `follow_up`, `payment_status`, `waiting_list`, `booking_flow`, `payment_method`, `payment_reference`, `platform_commission_rate`, `platform_commission_amount`, `clinic_net_amount`, `settlement_direction`, `financial_cycle_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(32, 176380160, 28, 28, 211, 1, 194, '2026-02-23', '1:16 AM - 1:31 AM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-02-22 23:24:58', '2026-02-22 23:24:58'),
(33, 494786142, 28, 28, 211, 1, 194, '2026-02-23', '1:31 AM - 1:46 AM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-02-22 23:25:06', '2026-02-22 23:25:06'),
(34, 970212715, 28, 28, 211, 1, 194, '2026-02-23', '1:46 AM - 2:01 AM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-02-22 23:25:16', '2026-02-22 23:25:16'),
(37, 350268024, 28, 28, 210, 1, 194, '2026-02-25', '9:31 AM - 9:46 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-02-25 06:27:06', '2026-03-02 01:42:43'),
(42, 209502128, 28, 28, 210, 1, 194, '2026-03-05', '11:01 PM - 11:16 PM', NULL, 5, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-05 19:55:29', '2026-03-05 19:56:01'),
(44, 711256147, 4, 4, 211, 1, 194, '2026-03-25', '5:31 PM - 5:46 PM', NULL, 5, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-25 14:29:18', '2026-03-25 14:30:22'),
(45, 255293732, 4, 4, 211, 1, 194, '2026-03-25', '10:46 PM - 11:01 PM', NULL, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-25 14:32:36', '2026-03-25 14:34:14'),
(47, 183410305, 4, 4, 209, 1, 194, '2026-03-25', '9:01 PM - 9:16 PM', NULL, 5, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-25 14:34:56', '2026-03-30 08:02:56'),
(48, 441259595, 28, 28, 210, 1, 196, '2026-03-25', '6:16 PM - 6:31 PM', NULL, 2, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-25 15:14:11', '2026-03-25 15:16:49'),
(49, 811129983, 28, 28, 209, 1, 194, '2026-03-25', '7:31 PM - 7:46 PM', NULL, 5, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-25 15:20:33', '2026-04-02 21:49:01'),
(52, 279646814, 4, 4, 210, 1, 194, '2026-03-26', '8:16 PM - 8:31 PM', NULL, 1, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-26 16:00:32', '2026-03-26 16:00:32'),
(56, 262171283, 28, 28, 209, 1, 196, '2026-03-27', '12:01 AM - 12:16 AM', NULL, 6, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-26 21:49:41', '2026-03-26 22:04:34'),
(57, 889774765, 28, 28, 209, 1, 194, '2026-03-28', '12:01 AM - 12:16 AM', NULL, 5, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-26 21:57:01', '2026-03-26 22:07:51'),
(60, 502587285, 28, 28, 205, 1, 196, '2026-03-28', '12:16 AM - 12:31 AM', NULL, 6, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-27 21:39:22', '2026-03-27 21:45:33'),
(61, 957501522, 28, 28, 205, 1, 194, '2026-03-29', '12:01 AM - 12:16 AM', NULL, 5, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-27 21:50:21', '2026-03-27 21:51:04'),
(65, 458460812, 28, 28, 202, 1, 194, '2026-03-28', '11:31 PM - 11:46 PM', NULL, 5, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-28 21:29:14', '2026-03-28 21:41:55'),
(66, 554152541, 28, 28, 202, 1, 196, '2026-03-29', '12:16 AM - 12:31 AM', NULL, 6, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-28 21:38:51', '2026-03-28 21:45:18'),
(69, 839215414, 28, 28, 203, 1, 196, '2026-03-29', '5:16 PM - 5:31 PM', NULL, 6, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-29 15:03:47', '2026-03-29 15:05:18'),
(70, 124496408, 28, 28, 204, 1, 196, '2026-03-29', '5:31 PM - 5:46 PM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-29 15:07:20', '2026-03-29 15:08:23'),
(71, 264028439, 28, 28, 204, 1, 194, '2026-03-29', '5:46 PM - 6:01 PM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-29 15:08:48', '2026-03-29 15:15:02'),
(72, 111517154, 28, 28, 210, 1, 194, '2026-03-29', '6:00 PM - 6:15 PM', NULL, 6, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-29 15:12:37', '2026-03-29 15:14:43'),
(73, 803021954, 28, 28, 204, 1, 194, '2026-03-29', '6:16 PM - 6:31 PM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-29 15:15:21', '2026-04-01 22:54:09'),
(75, 880032072, 4, 4, 204, 1, 194, '2026-03-30', '5:01 PM - 5:16 PM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-30 08:02:38', '2026-04-01 22:53:40'),
(80, 792880923, 28, 28, 202, 1, 194, '2026-03-31', '11:31 PM - 11:46 PM', NULL, 5, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-31 21:18:27', '2026-03-31 21:33:22'),
(81, 271912330, 28, 28, 210, 1, 194, '2026-04-14', '2:00 PM - 2:15 PM', NULL, 5, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-03-31 21:48:29', '2026-04-01 22:09:15'),
(82, 394407330, 28, 28, 206, 1, 194, '2026-04-01', '7:01 AM - 7:16 AM', NULL, 5, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-01 04:59:57', '2026-04-01 22:09:11'),
(84, 698481213, 53, 28, 202, 1, 196, '2026-04-02', '1:01 AM - 1:16 AM', NULL, 6, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-01 22:54:49', '2026-04-01 23:02:36'),
(85, 517403945, 53, 28, 203, 1, 194, '2026-04-02', '1:16 AM - 1:31 AM', NULL, 5, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-01 23:04:30', '2026-04-01 23:05:00'),
(86, 807090563, 53, 28, 204, 1, 194, '2026-04-02', '1:31 AM - 1:46 AM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-01 23:05:36', '2026-04-01 23:05:45'),
(90, 710637866, 45, 45, 210, 1, 189, '2026-04-03', '12:31 AM - 1:01 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 04:07:16', '2026-04-02 21:46:10'),
(92, 715837848, 28, 28, 210, 1, 194, '2026-04-02', '10:31 AM - 11:01 AM', NULL, 1, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 06:16:33', '2026-04-02 06:16:33'),
(93, 695684122, 28, 28, 203, 1, 194, '2026-04-02', '11:31 AM - 12:01 PM', NULL, 1, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 06:16:51', '2026-04-02 06:16:51'),
(94, 196346320, 4, 4, 209, 1, 194, '2026-04-02', '10:01 PM - 10:31 PM', NULL, 1, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 18:31:37', '2026-04-02 18:31:37'),
(95, 406439148, 4, 4, 210, 1, 194, '2026-04-02', '10:01 PM - 10:31 PM', NULL, 1, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 18:32:46', '2026-04-02 18:32:46'),
(96, 351491352, 45, 45, 210, 1, 196, '2026-04-03', '1:31 AM - 2:01 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 21:19:33', '2026-04-02 21:46:04'),
(97, 899768853, 28, 28, 202, 1, 196, '2026-04-03', '12:31 AM - 1:01 AM', NULL, 6, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 2, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 21:35:41', '2026-04-02 21:43:13'),
(98, 952859411, 28, 28, 209, 1, 196, '2026-04-03', '12:31 AM - 1:01 AM', NULL, 6, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 21:46:41', '2026-04-02 21:50:24'),
(99, 429223535, 45, 45, 202, 1, 196, '2026-04-04', '12:01 AM - 12:31 AM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 22:21:45', '2026-04-02 22:21:45'),
(100, 556960905, 28, 28, 204, 1, 196, '2026-04-03', '1:01 AM - 1:31 AM', NULL, 6, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-02 22:42:58', '2026-04-02 22:44:04'),
(114, 552777487, 4, 4, 209, 1, 194, '2026-04-05', '12:31 PM - 1:01 PM', NULL, 6, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-05 08:03:17', '2026-04-05 08:03:17'),
(115, 323796673, 4, 4, 211, 1, 194, '2026-04-07', '9:01 AM - 9:31 AM', NULL, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-07 04:17:32', '2026-04-09 14:54:12'),
(116, 494566622, 4, 4, 208, 1, 194, '2026-04-07', '11:01 AM - 11:31 AM', NULL, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-04-07 04:17:49', '2026-04-07 04:18:01'),
(142, 223022313, 4, 4, 203, 1, 194, '2026-05-04', '12:01 AM - 12:31 AM', NULL, 1, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-03 20:31:07', '2026-05-03 20:31:07'),
(143, 432200033, 4, 4, 204, 1, 196, '2026-05-06', '12:01 AM - 12:31 AM', NULL, 4, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-05 20:44:33', '2026-05-06 15:28:45'),
(145, 342783353, 4, 4, 209, 1, 194, '2026-05-07', '10:31 PM - 11:01 PM', NULL, 1, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-07 19:09:59', '2026-05-07 19:09:59'),
(146, 594166607, 4, 4, 211, 1, 194, '2026-05-07', '10:31 PM - 11:01 PM', NULL, 5, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-07 19:14:16', '2026-06-25 22:04:00'),
(148, 786387695, 45, 45, 202, 1, 196, '2026-05-18', '12:31 PM - 1:01 PM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 04:07:57', '2026-05-17 04:07:57'),
(149, 310696980, 45, 45, 202, 1, 196, '2026-05-17', '12:01 PM - 12:31 PM', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 04:09:18', '2026-05-17 12:42:29'),
(150, 291777647, 57, 57, 203, 1, 196, '2026-05-17', '2:01 PM - 2:31 PM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 04:10:48', '2026-05-17 04:10:48'),
(151, 337944163, 58, 58, 205, 1, 196, '2026-05-17', '12:01 PM - 12:31 PM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 04:12:24', '2026-05-17 04:12:24'),
(152, 632695526, 58, 58, 202, 1, 196, '2026-05-19', '12:01 AM - 12:31 AM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 13:07:05', '2026-05-17 13:07:05'),
(154, 312226145, 61, 61, 202, 1, 196, '2026-05-18', '12:01 AM - 12:31 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 14:18:18', '2026-05-17 14:22:49'),
(155, 179641687, 4, 4, 205, 1, 194, '2026-05-17', '6:01 PM - 6:31 PM', NULL, 6, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 14:42:26', '2026-05-17 14:47:56'),
(156, 296535508, 4, 4, 202, 1, 194, '2026-05-17', '6:01 PM - 6:31 PM', NULL, 5, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-17 14:52:00', '2026-05-17 14:52:18'),
(157, 287141415, 61, 61, 204, 1, 196, '2026-05-18', '8:01 AM - 8:31 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-18 04:05:40', '2026-05-18 04:06:11'),
(158, 313063437, 60, 60, 203, 1, 196, '2026-05-18', '8:01 AM - 8:31 AM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-18 04:13:12', '2026-05-20 14:26:24'),
(159, 951918092, 62, 62, 202, 1, 194, '2026-05-19', '3:01 PM - 3:31 PM', NULL, 5, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-18 04:26:44', '2026-05-18 04:28:03'),
(160, 956786649, 68, 68, 202, 1, 196, '2026-05-22', '3:31 PM - 4:01 PM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-22 12:59:41', '2026-05-22 12:59:54'),
(161, 372233367, 68, 68, 202, 1, 196, '2026-05-23', '9:31 PM - 10:01 PM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-23 11:52:19', '2026-05-23 11:52:19'),
(162, 695464234, 68, 68, 203, 1, 196, '2026-05-30', '11:01 PM - 11:31 PM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-30 20:06:17', '2026-05-30 20:06:49'),
(163, 888220342, 67, 67, 203, 1, 196, '2026-05-31', '10:01 PM - 10:31 PM', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-31 10:44:31', '2026-05-31 10:44:31'),
(164, 735642548, 65, 65, 203, 1, 196, '2026-05-31', '3:01 PM - 3:31 PM', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-05-31 10:44:57', '2026-05-31 10:45:05'),
(165, 353477877, 65, 65, 209, 1, 194, '2026-06-07', '3:31 PM - 4:01 PM', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-07 11:17:45', '2026-06-07 11:17:45'),
(166, 878994053, 10, 10, 211, 1, 194, '2026-06-09', '10:01 PM - 10:31 PM', NULL, 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-09 18:05:30', '2026-06-09 18:06:16'),
(167, 830694231, 65, 65, 209, 1, 194, '2026-06-17', '4:01 PM - 4:31 PM', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-17 11:31:54', '2026-06-17 11:31:54'),
(168, 647983653, 65, 65, 211, 1, 194, '2026-06-17', '3:31 PM - 4:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-17 11:38:15', '2026-06-17 11:38:15'),
(169, 601039788, 65, 65, 211, 1, 194, '2026-06-17', '4:31 PM - 5:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-17 12:25:11', '2026-06-17 12:25:11'),
(170, 774820741, 65, 65, 205, 1, 194, '2026-06-17', '4:01 PM - 4:31 PM', NULL, 2, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-17 12:41:27', '2026-06-17 12:41:27'),
(171, 129182554, 72, 72, 203, 1, 194, '2026-06-23', '3:01 AM - 3:31 AM', NULL, 5, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-22 23:42:37', '2026-06-22 23:43:49'),
(172, 366307372, 10, 10, 209, 1, 194, '2026-06-30', '1:31 AM - 2:01 AM', NULL, 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-26 13:05:59', '2026-06-26 13:10:59'),
(173, 463616993, 10, 10, 208, 1, 194, '2026-06-27', '2:31 PM - 3:01 PM', NULL, 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-06-27 11:27:10', '2026-06-27 11:29:32'),
(174, 474268200, 10, 10, 209, 1, 194, '2026-07-04', '3:31 PM - 4:01 PM', NULL, 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-04 12:28:19', '2026-07-04 12:37:23'),
(175, 296101026, 73, 73, 234, 1, 196, '2026-07-22', '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-05 14:19:19', '2026-07-05 14:19:19'),
(176, 369561168, 10, 10, 210, 1, 194, '2026-07-05', '5:31 PM - 6:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-05 14:26:11', '2026-07-05 14:26:11'),
(177, 377807291, 10, 10, 202, 1, 194, '2026-07-06', '7:01 PM - 7:31 PM', NULL, 2, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-06 14:36:13', '2026-07-06 14:36:13'),
(178, 325586673, 10, 10, 210, 1, 194, '2026-07-06', '9:01 PM - 9:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-06 16:48:58', '2026-07-06 16:48:58'),
(179, 688230489, 10, 10, 211, 1, 194, '2026-07-06', '9:01 PM - 9:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-06 17:38:01', '2026-07-06 17:38:01'),
(180, 218507639, 10, 10, 211, 1, 194, '2026-07-07', '12:01 PM - 12:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-07 08:36:53', '2026-07-07 08:36:53'),
(181, 465912024, 10, 10, 211, 1, 194, '2026-07-07', '2:01 PM - 2:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-07 09:32:36', '2026-07-07 09:32:36'),
(182, 115273934, 10, 10, 211, 1, 194, '2026-07-07', '6:01 PM - 6:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-07 11:19:51', '2026-07-07 11:19:51'),
(183, 610639346, 10, 10, 211, 1, 194, '2026-07-08', '8:31 PM - 9:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-08 15:26:59', '2026-07-08 15:26:59'),
(184, 444429282, 4, 4, 202, 1, 194, '2026-07-08', '8:01 PM - 8:31 PM', NULL, 2, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-08 16:36:37', '2026-07-08 16:36:37'),
(185, 756207191, 10, 10, 210, 1, 194, '2026-07-09', '7:31 AM - 8:01 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-09 03:18:39', '2026-07-09 03:18:39'),
(186, 637108044, 10, 10, 211, 1, 194, '2026-07-09', '6:31 PM - 7:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-09 10:48:31', '2026-07-09 10:48:31'),
(187, 407655199, 10, 10, 211, 1, 194, '2026-07-11', '2:31 PM - 3:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-11 09:33:06', '2026-07-11 09:33:06'),
(188, 896642054, 10, 10, 211, 1, 194, '2026-07-12', '12:01 PM - 12:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-12 08:32:01', '2026-07-12 08:32:01'),
(189, 689592209, 10, 10, 211, 1, 194, '2026-07-12', '12:31 PM - 1:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-12 09:19:16', '2026-07-12 09:19:16'),
(190, 160771254, 10, 10, 211, 1, 194, '2026-07-12', '4:01 PM - 4:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-12 12:06:01', '2026-07-12 12:06:01'),
(191, 780445803, 10, 10, 211, 1, 194, '2026-07-15', '12:01 AM - 12:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-12 18:11:03', '2026-07-12 18:11:03'),
(192, 455976689, 10, 10, 211, 1, 194, '2026-07-13', '11:01 AM - 11:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-13 07:58:53', '2026-07-13 07:58:53'),
(193, 856876185, 10, 10, 211, 1, 194, '2026-07-14', '12:01 AM - 12:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-13 12:39:12', '2026-07-13 12:39:12'),
(194, 548962082, 10, 10, 211, 1, 194, '2026-07-22', '2:01 AM - 2:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-14 13:27:37', '2026-07-14 13:27:37'),
(195, 981859293, 10, 10, 211, 1, 194, '2026-07-21', '12:31 AM - 1:01 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-15 11:57:18', '2026-07-15 11:57:18'),
(196, 997372259, 10, 10, 211, 1, 194, '2026-07-22', '12:01 AM - 12:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-15 17:27:21', '2026-07-15 17:27:21'),
(197, 535960666, 10, 10, 210, 1, 194, '2026-07-18', '5:01 AM - 5:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-16 10:13:25', '2026-07-16 10:13:25'),
(198, 474030633, 10, 10, 211, 1, 194, '2026-07-18', '12:01 AM - 12:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-16 17:01:50', '2026-07-16 17:01:50'),
(199, 199861326, 10, 10, 209, 1, 194, '2026-07-18', '9:01 AM - 9:31 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-18 04:54:13', '2026-07-18 04:54:13'),
(200, 145988692, 10, 10, 211, 1, 194, '2026-07-20', '3:31 AM - 4:01 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-19 16:10:21', '2026-07-19 16:10:21'),
(201, 317111252, 10, 10, 211, 1, 194, '2026-07-22', '2:31 AM - 3:01 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-20 08:18:59', '2026-07-20 08:18:59'),
(202, 594931848, 10, 10, 211, 1, 194, '2026-07-23', '1:31 AM - 2:01 AM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-21 08:41:50', '2026-07-21 08:41:50'),
(203, 586044671, 10, 10, 210, 1, 194, '2026-07-22', '7:31 PM - 8:01 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-22 12:05:43', '2026-07-22 12:05:43'),
(204, 508349866, 10, 10, 211, 1, 194, '2026-07-28', '6:01 PM - 6:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-28 14:04:03', '2026-07-28 14:04:03'),
(205, 411175636, 10, 10, 211, 1, 194, '2026-07-29', '5:01 PM - 5:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-29 12:50:38', '2026-07-29 12:50:38'),
(206, 501311736, 10, 10, 211, 1, 194, '2026-07-30', '4:31 PM - 5:01 PM', NULL, 6, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-30 13:15:10', '2026-07-30 13:39:14'),
(207, 375870296, 10, 10, 211, 1, 194, '2026-07-30', '8:01 PM - 8:31 PM', NULL, 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-07-30 14:48:37', '2026-07-30 14:48:37'),
(208, 741735634, 78, 78, 202, 1, 194, '2026-08-01', '6:01 PM - 6:31 PM', NULL, 2, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, 'instant', 'cash', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2026-08-01 14:53:38', '2026-08-01 14:53:38');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_chats`
--

CREATE TABLE `reservation_chats` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `sender_id` bigint UNSIGNED NOT NULL COMMENT '2 reception , 1 patient',
  `receiver_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `receiver_type` int DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'file or image',
  `record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_flag` int NOT NULL DEFAULT '1' COMMENT '1 message, 2 image, 3 file, 4 record',
  `sender_type` int DEFAULT NULL,
  `is_read` tinyint NOT NULL DEFAULT '0' COMMENT '0 not seen , 1 seen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_chats`
--

INSERT INTO `reservation_chats` (`id`, `user_id`, `sender_id`, `receiver_id`, `reservation_id`, `receiver_type`, `message`, `file`, `record`, `media_flag`, `sender_type`, `is_read`, `created_at`, `updated_at`) VALUES
(13, 28, 0, 1, 48, NULL, 'بتواصل معكم', NULL, NULL, 1, 1, 0, '2026-03-25 15:18:30', '2026-03-25 15:18:30'),
(14, 28, 0, 1, 56, NULL, 'بتواصل مع دكتور عمر', NULL, NULL, 1, 1, 0, '2026-03-26 21:51:02', '2026-03-26 21:51:02'),
(15, 28, 0, 1, 60, NULL, 'سلام عليكم', NULL, NULL, 1, 1, 0, '2026-03-27 21:43:20', '2026-03-27 21:43:20'),
(16, NULL, 196, 28, 60, NULL, 'ug', NULL, NULL, 1, 2, 0, '2026-03-27 21:43:57', '2026-03-27 21:43:57'),
(17, NULL, 196, 28, 60, NULL, 'ug', NULL, NULL, 1, 2, 0, '2026-03-27 21:43:57', '2026-03-27 21:43:57'),
(18, NULL, 196, 28, 60, NULL, 'عليكم السلام', NULL, NULL, 1, 2, 0, '2026-03-27 21:44:13', '2026-03-27 21:44:13'),
(19, 28, 0, 1, 60, NULL, 'تيست', NULL, NULL, 1, 1, 0, '2026-03-27 21:45:00', '2026-03-27 21:45:00'),
(22, 28, 0, 1, 65, NULL, 'تمام', NULL, NULL, 1, 1, 0, '2026-03-28 21:31:43', '2026-03-28 21:31:43'),
(23, 28, 0, 1, 66, NULL, 'تيست', NULL, NULL, 1, 1, 0, '2026-03-28 21:42:19', '2026-03-28 21:42:19'),
(24, 28, 0, 1, 69, NULL, 'تيست', NULL, NULL, 1, 1, 0, '2026-03-29 15:04:44', '2026-03-29 15:04:44'),
(25, 4, 0, 1, 52, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-03-29 15:42:00', '2026-03-29 15:42:00'),
(29, 28, 0, 1, 80, NULL, 'تيست \nتيست \nتيست', NULL, NULL, 1, 1, 0, '2026-03-31 21:18:56', '2026-03-31 21:18:56'),
(30, 28, 0, 1, 84, NULL, 'بتواصل معكم', NULL, NULL, 1, 1, 0, '2026-04-01 23:00:53', '2026-04-01 23:00:53'),
(31, NULL, 196, 28, 60, NULL, 'ييييييي', NULL, NULL, 1, 2, 0, '2026-04-02 04:55:31', '2026-04-02 04:55:31'),
(32, NULL, 196, 28, 60, NULL, NULL, '69cdff8067f5cمواساه-المدينه-المنوره1.jpg.jpg', NULL, 1, 2, 0, '2026-04-02 05:32:48', '2026-04-02 05:32:48'),
(33, NULL, 196, 28, 93, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-04-02 07:56:01', '2026-04-02 07:56:01'),
(34, 4, 0, 1, 95, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-04-02 18:33:16', '2026-04-02 18:33:16'),
(35, NULL, 196, 28, 93, NULL, 'test', NULL, NULL, 1, 2, 0, '2026-04-02 21:16:34', '2026-04-02 21:16:34'),
(36, NULL, 196, 28, 60, NULL, NULL, '69cedef8498b111.jpg.jpg', NULL, 1, 2, 0, '2026-04-02 21:26:16', '2026-04-02 21:26:16'),
(37, NULL, 196, 28, 60, NULL, 'test', NULL, NULL, 1, 2, 0, '2026-04-02 21:26:26', '2026-04-02 21:26:26'),
(38, 28, 0, 1, 97, NULL, 'تمام', NULL, NULL, 1, 1, 0, '2026-04-02 21:37:49', '2026-04-02 21:37:49'),
(39, NULL, 196, 28, 97, NULL, 'jlhl', NULL, NULL, 1, 2, 0, '2026-04-02 21:38:05', '2026-04-02 21:38:05'),
(40, NULL, 196, 28, 97, NULL, 'الرساله مش ظاهره', NULL, NULL, 1, 2, 0, '2026-04-02 21:38:48', '2026-04-02 21:38:48'),
(41, 28, 0, 1, 97, NULL, 'ايوه', NULL, NULL, 1, 1, 0, '2026-04-02 21:39:04', '2026-04-02 21:39:04'),
(42, 28, 0, 1, 49, NULL, 'تمام', NULL, NULL, 1, 1, 0, '2026-04-02 21:48:33', '2026-04-02 21:48:33'),
(43, NULL, 196, 28, 98, NULL, 'ايه', NULL, NULL, 1, 2, 0, '2026-04-02 21:48:41', '2026-04-02 21:48:41'),
(44, 28, 0, 1, 98, NULL, 'تمام', NULL, NULL, 1, 1, 0, '2026-04-02 21:49:30', '2026-04-02 21:49:30'),
(111, 4, 0, 1, 95, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-05-03 15:53:41', '2026-05-03 15:53:41'),
(112, 4, 196, 4, 95, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-05-03 15:54:05', '2026-05-03 15:54:05'),
(113, 4, 196, 4, 95, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-05-03 15:54:14', '2026-05-03 15:54:14'),
(114, 4, 196, 4, 95, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-05-03 15:54:15', '2026-05-03 15:54:15'),
(115, 4, 196, 4, 95, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-05-03 15:54:24', '2026-05-03 15:54:24'),
(116, 4, 196, 4, 95, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-05-03 15:54:25', '2026-05-03 15:54:25'),
(117, 4, 0, 1, 95, NULL, NULL, '69f76ffd1324escaled_1000374582.jpg.jpg', NULL, 1, 1, 0, '2026-05-03 15:55:41', '2026-05-03 15:55:41'),
(118, 4, 196, 4, 95, NULL, NULL, '69f7700f0457b61dJr4NizcL._AC_SX569_.jpg.jpg', NULL, 1, 2, 0, '2026-05-03 15:55:59', '2026-05-03 15:55:59'),
(119, 4, 196, 4, 95, NULL, 'هاي', NULL, NULL, 1, 2, 0, '2026-05-03 15:56:45', '2026-05-03 15:56:45'),
(120, 4, 0, 1, 94, NULL, 'هاي', NULL, NULL, 1, 1, 0, '2026-05-03 15:59:52', '2026-05-03 15:59:52'),
(121, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-03 16:00:16', '2026-05-03 16:00:16'),
(122, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:13:43', '2026-05-17 04:13:43'),
(123, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:13:49', '2026-05-17 04:13:49'),
(124, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:13:50', '2026-05-17 04:13:50'),
(125, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:13:51', '2026-05-17 04:13:51'),
(126, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:14:00', '2026-05-17 04:14:00'),
(127, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:14:00', '2026-05-17 04:14:00'),
(128, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:14:00', '2026-05-17 04:14:00'),
(129, 4, 196, 4, 94, NULL, 'السلام عليكم', NULL, NULL, 1, 2, 0, '2026-05-17 04:14:00', '2026-05-17 04:14:00'),
(130, 61, 196, 61, 154, NULL, 'هاي', NULL, NULL, 1, 2, 0, '2026-05-17 14:20:28', '2026-05-17 14:20:28'),
(131, 61, 196, 61, 154, NULL, 'هاي', NULL, NULL, 1, 2, 0, '2026-05-17 14:20:38', '2026-05-17 14:20:38'),
(132, 61, 196, 61, 154, NULL, 'تيست', NULL, NULL, 1, 2, 0, '2026-05-17 14:20:47', '2026-05-17 14:20:47'),
(133, 61, 196, 61, 154, NULL, 'تيست', NULL, NULL, 1, 2, 0, '2026-05-17 14:20:49', '2026-05-17 14:20:49'),
(134, 61, 196, 61, 154, NULL, 'بلايبلايبلابالبالبالبالباسلا', NULL, NULL, 1, 2, 0, '2026-05-17 14:22:09', '2026-05-17 14:22:09'),
(135, 61, 196, 61, 154, NULL, 'بلايبلايبلابالبالبالبالباسلا', NULL, NULL, 1, 2, 0, '2026-05-17 14:22:19', '2026-05-17 14:22:19'),
(136, 4, 0, 1, 155, NULL, 'هاي', NULL, NULL, 1, 1, 0, '2026-05-17 14:44:02', '2026-05-17 14:44:02'),
(137, 4, 196, 4, 155, NULL, 'هاي', NULL, NULL, 1, 2, 0, '2026-05-17 14:45:04', '2026-05-17 14:45:04'),
(138, 4, 196, 4, 155, NULL, 'هاي', NULL, NULL, 1, 2, 0, '2026-05-17 14:45:12', '2026-05-17 14:45:12'),
(139, 4, 0, 1, 155, NULL, 'عندي استفسار', NULL, NULL, 1, 1, 0, '2026-05-17 14:47:08', '2026-05-17 14:47:08'),
(140, 4, 196, 4, 155, NULL, 'ما هو', NULL, NULL, 1, 2, 0, '2026-05-17 14:47:25', '2026-05-17 14:47:25'),
(141, 62, 0, 1, 159, NULL, 'Hi', NULL, NULL, 1, 1, 0, '2026-05-18 04:27:23', '2026-05-18 04:27:23'),
(142, 4, 196, 4, 155, NULL, 'اتفضل', NULL, NULL, 1, 2, 0, '2026-06-17 11:27:30', '2026-06-17 11:27:30'),
(143, 65, 0, 1, 165, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-06-17 11:28:30', '2026-06-17 11:28:30'),
(144, 65, 0, 1, 165, NULL, 'اهلا', NULL, NULL, 1, 1, 0, '2026-06-17 11:28:59', '2026-06-17 11:28:59'),
(145, 65, 196, 65, 165, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-06-17 11:29:13', '2026-06-17 11:29:13'),
(146, 65, 196, 65, 167, NULL, 'اهلا', NULL, NULL, 1, 2, 0, '2026-06-17 11:32:20', '2026-06-17 11:32:20'),
(147, 65, 0, 1, 167, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-06-17 11:32:44', '2026-06-17 11:32:44'),
(148, 65, 196, 65, 167, NULL, 'ييي', NULL, NULL, 1, 2, 0, '2026-06-17 11:37:27', '2026-06-17 11:37:27'),
(149, 65, 0, 1, 167, NULL, 'تمام', NULL, NULL, 1, 1, 0, '2026-06-17 11:37:45', '2026-06-17 11:37:45'),
(150, 65, 0, 1, 168, NULL, 'تيست', NULL, NULL, 1, 1, 0, '2026-06-17 12:17:53', '2026-06-17 12:17:53'),
(151, 65, 196, 65, 168, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-06-17 12:18:11', '2026-06-17 12:18:11'),
(152, 65, 0, 1, 168, NULL, 'نيست', NULL, NULL, 1, 1, 0, '2026-06-17 12:18:19', '2026-06-17 12:18:19'),
(153, 65, 196, 65, 168, NULL, 'اهلا', NULL, NULL, 1, 2, 0, '2026-06-17 12:24:08', '2026-06-17 12:24:08'),
(154, 65, 0, 1, 168, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-06-17 12:24:37', '2026-06-17 12:24:37'),
(155, 65, 0, 1, 170, NULL, 'مرحبا', NULL, NULL, 1, 1, 0, '2026-06-17 12:42:16', '2026-06-17 12:42:16'),
(156, 65, 196, 65, 170, NULL, 'اهلا', NULL, NULL, 1, 2, 0, '2026-06-17 12:42:31', '2026-06-17 12:42:31'),
(157, 65, 196, 65, 170, NULL, 'مرحبا', NULL, NULL, 1, 2, 0, '2026-06-17 12:42:51', '2026-06-17 12:42:51'),
(158, 65, 0, 1, 170, NULL, 'اهلا وسهلا', NULL, NULL, 1, 1, 0, '2026-06-17 12:43:06', '2026-06-17 12:43:06'),
(159, 65, 196, 65, 170, NULL, 'هلا', NULL, NULL, 1, 2, 0, '2026-06-17 12:49:46', '2026-06-17 12:49:46'),
(160, 65, 196, 65, 170, NULL, 'هلااا', NULL, NULL, 1, 2, 0, '2026-06-17 12:54:16', '2026-06-17 12:54:16'),
(161, 65, 0, 1, 170, NULL, 'اهلا', NULL, NULL, 1, 1, 0, '2026-06-17 12:54:32', '2026-06-17 12:54:32'),
(162, 65, 0, 1, 170, NULL, 'اهلا', NULL, NULL, 1, 1, 0, '2026-06-17 12:54:49', '2026-06-17 12:54:49'),
(163, 65, 0, 1, 170, NULL, 'تيست', NULL, NULL, 1, 1, 0, '2026-06-17 12:55:04', '2026-06-17 12:55:04'),
(164, 65, 0, 1, 170, NULL, 'ا', NULL, NULL, 1, 1, 0, '2026-06-17 12:55:58', '2026-06-17 12:55:58'),
(165, 65, 0, 1, 170, NULL, 'يشتغل', NULL, NULL, 1, 1, 0, '2026-06-17 13:00:57', '2026-06-17 13:00:57'),
(166, 65, 0, 1, 170, NULL, 'وة', NULL, NULL, 1, 1, 0, '2026-06-17 13:01:13', '2026-06-17 13:01:13'),
(167, 65, 0, 1, 170, NULL, 'Kk', NULL, NULL, 1, 1, 0, '2026-06-17 13:39:20', '2026-06-17 13:39:20'),
(168, 65, 0, 1, 170, NULL, 'J', NULL, NULL, 1, 1, 0, '2026-06-17 13:39:33', '2026-06-17 13:39:33'),
(169, 4, 0, 1, 146, NULL, 'مرحبا هل موجود دكتور حسام لطفي لحجز موعد', NULL, NULL, 1, 1, 0, '2026-06-25 22:02:41', '2026-06-25 22:02:41'),
(170, 10, 0, 1, 203, NULL, 'ام', NULL, NULL, 1, 1, 0, '2026-07-25 05:32:10', '2026-07-25 05:32:10'),
(171, 10, 189, 10, 206, NULL, 'ب انتظارك', NULL, NULL, 1, 2, 0, '2026-07-30 13:34:56', '2026-07-30 13:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_drugs`
--

CREATE TABLE `reservation_drugs` (
  `id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `drug_id` bigint UNSIGNED DEFAULT NULL,
  `repetition` int DEFAULT NULL,
  `nums_days` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_rates`
--

CREATE TABLE `reservation_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rate_value` tinyint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_rates`
--

INSERT INTO `reservation_rates` (`id`, `clinic_id`, `doctor_id`, `reservation_id`, `user_id`, `comment`, `rate_value`, `created_at`, `updated_at`) VALUES
(6, 1, 209, 56, 28, 'ممتاز', 4, '2026-03-26 22:05:22', '2026-03-26 22:05:22'),
(7, 1, 205, 60, 28, 'جيد جدا', 5, '2026-03-27 21:46:01', '2026-03-27 21:46:01'),
(8, 1, 202, 66, 28, 'جيد', 5, '2026-03-28 21:45:37', '2026-03-28 21:45:37'),
(10, 1, 203, 69, 28, 'جيد', 3, '2026-03-29 15:05:55', '2026-03-29 15:05:55'),
(18, 1, 205, 155, 4, 'جيد', 5, '2026-05-17 14:48:37', '2026-05-17 14:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_vital_signs`
--

CREATE TABLE `reservation_vital_signs` (
  `id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED NOT NULL,
  `heat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pulse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breathing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pregnant` tinyint NOT NULL DEFAULT '0',
  `blood_pressure` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sports_habits` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responsible_people`
--

CREATE TABLE `responsible_people` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restrictions`
--

CREATE TABLE `restrictions` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `cost_center_id` bigint UNSIGNED DEFAULT NULL,
  `credit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process` int NOT NULL DEFAULT '1' COMMENT '1 restrictions, 2 catch_receipt, 3 receipt',
  `account_type` int NOT NULL DEFAULT '1' COMMENT '1 main, 2 branch',
  `daily_entry_id` bigint UNSIGNED DEFAULT NULL,
  `final_accounts` bigint UNSIGNED DEFAULT NULL,
  `account_balance` int NOT NULL DEFAULT '1' COMMENT '1 credit, 2 debit',
  `payment_method` int NOT NULL DEFAULT '1' COMMENT '1 cash, 2 visa',
  `tax` int NOT NULL DEFAULT '1',
  `reference` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notice` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `result_manuals`
--

CREATE TABLE `result_manuals` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_service_id` bigint UNSIGNED NOT NULL,
  `PLT` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RBCs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HB` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HCT` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MCV` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MCH` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MCHC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RDW` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `WBCs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `name_ar`, `guard_name`, `status`, `created_at`, `updated_at`) VALUES
(2, 'accounting', 'محاسب', 'web', 1, '2025-09-22 21:52:56', '2025-09-22 21:52:56');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoices`
--

CREATE TABLE `sale_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `is_saved_invoice` tinyint NOT NULL DEFAULT '0',
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `account_tree_id` bigint UNSIGNED DEFAULT NULL COMMENT 'network',
  `cache_value` bigint DEFAULT NULL,
  `network_value` bigint DEFAULT NULL,
  `transfer_value` bigint DEFAULT NULL,
  `okay_or_cache` enum('okay','cache') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_value` bigint NOT NULL,
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL,
  `tax_value` int DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice_items`
--

CREATE TABLE `sale_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_invoice_id` bigint UNSIGNED NOT NULL,
  `drug_id` bigint UNSIGNED NOT NULL,
  `drug_guidelines` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_type` enum('grand_unit','micro_unit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` enum('sales','returns') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage_discount` int NOT NULL DEFAULT '0',
  `value_discount` int NOT NULL DEFAULT '0',
  `tax` int NOT NULL DEFAULT '0',
  `net_total` int DEFAULT NULL,
  `net_total_after_discount` int DEFAULT NULL COMMENT 'this is value = net_total - (bonus + percentage_discount + value_discount + fixed_discount-tax) ',
  `tax_value` int DEFAULT NULL COMMENT 'net_total_after_discount * tax/100',
  `total_amount` int DEFAULT NULL COMMENT 'net_total_after_discount + tax_value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `specialty_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `flag` int NOT NULL DEFAULT '1' COMMENT '1 admin, 2 clinic',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `abbrev` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normal_value` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `code`, `name_en`, `name_ar`, `category_id`, `specialty_id`, `clinic_id`, `flag`, `created_by`, `price`, `notes`, `abbrev`, `unit`, `normal_value`, `type`, `status`, `created_at`, `updated_at`) VALUES
(7, 'CO005', 'Ringing on the brain', 'رنين علي المخ', 8, 4, NULL, 1, NULL, '1000', NULL, NULL, NULL, NULL, 2, 1, '2023-12-08 00:52:11', '2023-12-08 00:52:11'),
(8, 'CO006', 'New x-rays', 'اشعة جديدة', 8, 8, NULL, 1, NULL, '500', 'تيست', NULL, NULL, NULL, 2, 1, '2024-01-19 23:59:32', '2024-01-20 00:03:07'),
(10, NULL, 'Complete Blood Count', 'فحص دم شامل', 12, NULL, NULL, 1, NULL, '0', NULL, 'CBC', NULL, NULL, 1, 1, '2024-04-26 16:58:01', '2024-04-26 16:58:01'),
(11, NULL, 'Erythrocyte Sedimentation Rate', 'معدل الترسيب', 12, NULL, NULL, 1, NULL, '0', NULL, 'ESR', NULL, NULL, 1, 1, '2024-04-26 16:58:58', '2024-04-26 16:58:58'),
(12, NULL, 'Blood Group', 'فصيلة الدم', 12, NULL, NULL, 1, NULL, '0', NULL, 'ABO-Rh', NULL, NULL, 1, 1, '2024-04-26 17:08:01', '2024-04-26 17:08:01'),
(13, NULL, 'Clotting time', 'وقت التخثر', 12, NULL, NULL, 1, NULL, '0', NULL, 'CT', NULL, NULL, 1, 1, '2024-04-26 17:14:29', '2024-04-26 17:14:29'),
(14, NULL, 'Bleeding time', 'وقت النزيف', 12, NULL, NULL, 1, NULL, '0', NULL, 'BT', NULL, NULL, 1, 1, '2024-04-26 17:15:21', '2024-04-26 17:15:21'),
(15, NULL, 'Rhesus Factor', 'عامل ريسوس', 12, NULL, NULL, 1, NULL, '0', NULL, 'Rh', NULL, NULL, 1, 1, '2024-04-26 17:16:16', '2024-04-26 17:16:16'),
(16, NULL, 'Direct Coombs Test', 'اختبار كومبس المباشر', 12, NULL, NULL, 1, NULL, '0', NULL, 'DCT', NULL, NULL, 1, 1, '2024-04-26 17:17:07', '2024-04-26 17:17:07'),
(17, NULL, 'Indirect Coombs Test', 'اختبار كومبس غير المباشر', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:18:23', '2024-04-26 17:18:23'),
(18, NULL, 'Hb Electrophoresis', 'الهيموجلوبين الكهربائي', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:19:03', '2024-04-26 17:19:03'),
(19, NULL, 'Glycosylated hemoglobin A1c', 'الهيموجلوبين الغليكوزيلاتي A1c', 12, NULL, NULL, 1, NULL, '0', NULL, 'HbA1c', NULL, NULL, 1, 1, '2024-04-26 17:20:04', '2024-04-26 17:20:04'),
(20, NULL, 'Sickle Cell Test', 'اختبار الخلايا المنجلية', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:20:28', '2024-04-26 17:20:28'),
(21, NULL, 'Glucose-6-Phosphate Dehydrogenase Test', 'اختبار هيدروجيناز الجلوكوز 6 فوسفات', 12, NULL, NULL, 1, NULL, '0', NULL, 'G6PD', NULL, NULL, 1, 1, '2024-04-26 17:21:07', '2024-04-26 17:21:07'),
(22, NULL, 'Reticulocyte Count', 'تعداد الشبكيات', 12, NULL, NULL, 1, NULL, '0', NULL, 'Retics', NULL, NULL, 1, 1, '2024-04-26 17:21:43', '2024-04-26 17:21:43'),
(23, NULL, 'Prothrombin Time', 'وقت البروثرومبين', 12, NULL, NULL, 1, NULL, '0', NULL, 'PT', NULL, NULL, 1, 1, '2024-04-26 17:22:24', '2024-04-26 17:22:24'),
(24, NULL, 'International Normalized Ratio', 'نسبة التطبيع الدولية', 12, NULL, NULL, 1, NULL, '0', NULL, 'INR', NULL, NULL, 1, 1, '2024-04-26 17:23:00', '2024-04-26 17:23:00'),
(25, NULL, 'Partial Thromboplastin Time', 'زمن الثرومبوبلاستين الجزئي', 12, NULL, NULL, 1, NULL, '0', NULL, 'PTT', NULL, NULL, 1, 1, '2024-04-26 17:23:45', '2024-04-26 17:23:45'),
(26, NULL, 'Thrombophilia Risk Screen', 'شاشة خطر التخثر', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:24:10', '2024-04-26 17:24:10'),
(27, NULL, 'Fibrinogen', 'الفيبرينوجين', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:24:40', '2024-04-26 17:24:40'),
(28, NULL, 'D-Dimer', 'د-ديمر', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:25:07', '2024-04-26 17:25:07'),
(29, NULL, 'Lupus Anticoagulant', 'الذئبة تخثر', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:25:37', '2024-04-26 17:25:37'),
(30, NULL, 'Protein C', 'بروتين ج', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:26:04', '2024-04-26 17:26:04'),
(31, NULL, 'Protein S', 'بروتين س', 12, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 17:26:34', '2024-04-26 17:26:34'),
(32, NULL, 'Biopsy Small', 'خزعة صغيرة', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:33:50', '2024-04-26 19:33:50'),
(33, NULL, 'Biopsy Medium', 'خزعة متوسطة', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:34:16', '2024-04-26 19:34:16'),
(34, NULL, 'Biopsy Large', 'خزعة كبيرة', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:34:39', '2024-04-26 19:34:39'),
(35, NULL, 'Cytology (LBC)', 'علم الخلايا (LBC)', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:35:06', '2024-04-26 19:35:06'),
(36, NULL, 'Cytology (FNA)', 'علم الخلايا (FNA)', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:35:47', '2024-04-26 19:35:47'),
(37, NULL, 'Cytology ( body fluid)', 'علم الخلايا (سوائل الجسم)', 14, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:36:09', '2024-04-26 19:36:09'),
(38, NULL, 'Gram Stain', 'غرام وصمة عار', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:37:24', '2024-04-26 19:37:24'),
(39, NULL, 'KOH Preparation', 'تحضير كوه', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:37:49', '2024-04-26 19:37:49'),
(40, NULL, 'Acid Fast Bacteria Smear or Ziehl-Neelsen stain', 'مسحة البكتيريا الحمضية السريعة أو صبغة زيل نيلسن', 15, NULL, NULL, 1, NULL, '0', NULL, 'AFB Smear or ZN Stain', NULL, NULL, 1, 1, '2024-04-26 19:38:25', '2024-04-26 19:38:25'),
(41, NULL, 'Urine Culture', 'ثقافة التبول', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:38:46', '2024-04-26 19:38:46'),
(42, NULL, 'Stool Culture', 'ثقافة البراز', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:39:14', '2024-04-26 19:39:14'),
(43, NULL, 'Vaginal Culture', 'الثقافة المهبلية', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:39:37', '2024-04-26 19:39:37'),
(44, NULL, 'Throat Culture', 'ثقافة الحلق', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:40:00', '2024-04-26 19:40:00'),
(45, NULL, 'Urethral Culture', 'ثقافة مجرى البول', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:40:22', '2024-04-26 19:40:22'),
(46, NULL, 'Sputum Culture', 'ثقافة البلغم', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:40:44', '2024-04-26 19:40:44'),
(47, NULL, 'Wound Culture', 'ثقافة الجرح', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:41:09', '2024-04-26 19:41:09'),
(48, NULL, 'Skin Culture', 'ثقافة الجلد', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:41:29', '2024-04-26 19:41:29'),
(49, NULL, 'Ear Culture', 'ثقافة الأذن', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:41:52', '2024-04-26 19:41:52'),
(50, NULL, 'Eye Culture', 'ثقافة العين', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:42:17', '2024-04-26 19:42:17'),
(51, NULL, 'Semen Culture', 'ثقافة السائل المنوي', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:42:43', '2024-04-26 19:42:43'),
(52, NULL, 'Fungus Culture', 'ثقافة الفطريات', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:43:08', '2024-04-26 19:43:08'),
(53, NULL, 'Urine analysis', 'تحليل بول', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:44:08', '2024-04-26 19:44:08'),
(54, NULL, 'Stool analysis', 'تحليل البراز', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:44:34', '2024-04-26 19:44:34'),
(55, NULL, 'Semen analysis', 'تحليل السائل المنوي', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:44:55', '2024-04-26 19:44:55'),
(56, NULL, 'Stool Occult Blood', 'الدم الخفي في البراز', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:45:13', '2024-04-26 19:45:13'),
(57, NULL, 'Fructose in Semen', 'الفركتوز في السائل المنوي', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:45:41', '2024-04-26 19:45:41'),
(58, NULL, 'H.Pylori Ag in Stool', 'H.Pylori Ag في البراز', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:46:11', '2024-04-26 19:46:11'),
(59, NULL, 'Body Fluid Analysis', 'تحليل سوائل الجسم', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:46:30', '2024-04-26 19:46:30'),
(60, NULL, 'MalariaSmear', 'مسحة الملاريا', 15, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:46:56', '2024-04-26 19:46:56'),
(61, NULL, 'Alpha Fetoprotein', 'ألفا فيتوبروتين', 16, NULL, NULL, 1, NULL, '0', NULL, 'AFP', NULL, NULL, 1, 1, '2024-04-26 19:48:57', '2024-04-26 19:49:11'),
(62, NULL, 'Carcinoembryonic Antigen', 'مستضد سرطاني مضغي', 16, NULL, NULL, 1, NULL, '0', NULL, 'CEA', NULL, NULL, 1, 1, '2024-04-26 19:49:45', '2024-04-26 19:49:45'),
(63, NULL, 'Free Prostate Specific Antigen', 'مستضد البروستاتا النوعي الحر', 16, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 19:50:07', '2024-04-26 19:50:07'),
(64, NULL, 'Total Prostate Specific Antigen', 'إجمالي المستضد النوعي للبروستاتا', 16, NULL, NULL, 1, NULL, '0', NULL, 'PSA-Total', NULL, NULL, 1, 1, '2024-04-26 19:50:44', '2024-04-26 19:50:44'),
(65, NULL, 'Cancer antigen15 - 3', 'مستضد السرطان 15 - 3', 16, NULL, NULL, 1, NULL, '0', NULL, 'CA 15 - 3', NULL, NULL, 1, 1, '2024-04-26 19:51:17', '2024-04-26 19:51:17'),
(66, NULL, 'Cancer antigen 125', 'مستضد السرطان 125', 16, NULL, NULL, 1, NULL, '0', NULL, 'CA 125', NULL, NULL, 1, 1, '2024-04-26 19:52:32', '2024-04-26 19:52:32'),
(67, NULL, 'Carbohydrate Antigen 19 - 9', 'مستضد الكربوهيدرات 19 - 9', 16, NULL, NULL, 1, NULL, '0', NULL, 'CA19 - 9', NULL, NULL, 1, 1, '2024-04-26 19:53:11', '2024-04-26 19:53:11'),
(68, NULL, 'Thyroid Hormones', 'هرمونات الغدة الدرقية', 17, NULL, NULL, 1, NULL, '300', NULL, 'Thyroid Hormones', NULL, NULL, 1, 1, '2024-04-26 19:56:47', '2024-05-17 21:08:33'),
(69, NULL, 'Thyroid Stimulating Hormone', 'تحفيز الغدة الدرقية', 17, NULL, NULL, 1, NULL, '0', NULL, 'TSH', NULL, NULL, 1, 1, '2024-04-26 19:57:16', '2024-04-26 19:57:50'),
(70, NULL, 'triiodothyronine (free)', 'ثلاثي يودوثيرونين (مجاني)', 17, NULL, NULL, 1, NULL, '0', NULL, 'T3 (Total)', NULL, NULL, 1, 1, '2024-04-26 19:58:38', '2024-04-26 19:58:38'),
(71, NULL, 'triiodothyronine (Total)', 'ثلاثي يودوثيرونين (المجموع)', 17, NULL, NULL, 1, NULL, '0', NULL, 'FT4', NULL, NULL, 1, 1, '2024-04-26 19:59:09', '2024-04-26 19:59:09'),
(72, NULL, 'thyroxine (Free)', 'هرمون الغدة الدرقية (مجاني)', 17, NULL, NULL, 1, NULL, '0', NULL, 'T4 (Total)', NULL, NULL, 1, 1, '2024-04-26 19:59:46', '2024-04-26 19:59:46'),
(73, NULL, 'thyroxine (Total)', 'هرمون الغدة الدرقية (المجموع)', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:00:18', '2024-04-26 20:00:18'),
(74, NULL, 'Follicular Stimulating Hormone', 'هرمون تحفيز الجريبات', 17, NULL, NULL, 1, NULL, '0', NULL, 'FSH', NULL, NULL, 1, 1, '2024-04-26 20:00:53', '2024-04-26 20:00:53'),
(75, NULL, 'Luteinizing Hormone', 'الهرمون الملوتن', 17, NULL, NULL, 1, NULL, '0', NULL, 'LH', NULL, NULL, 1, 1, '2024-04-26 20:01:23', '2024-04-26 20:01:23'),
(76, NULL, 'Prolactin', 'البرولاكتين', 17, NULL, NULL, 1, NULL, '0', NULL, 'PRL', NULL, NULL, 1, 1, '2024-04-26 20:01:57', '2024-04-26 20:01:57'),
(77, NULL, 'Testosterone – Free', 'التستوستيرون – مجاني', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:02:18', '2024-04-26 20:02:18'),
(78, NULL, 'Testosterone – Total', 'التستوستيرون - المجموع', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:02:38', '2024-04-26 20:02:38'),
(79, NULL, 'Progesterone', 'البروجسترون', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:03:00', '2024-04-26 20:03:00'),
(80, NULL, 'Estradiol', 'استراديول', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:03:21', '2024-04-26 20:03:21'),
(81, NULL, 'Anti-Mullerian Hormone', 'المضادة للهرمون مولريان', 17, NULL, NULL, 1, NULL, '0', NULL, 'AMH', NULL, NULL, 1, 1, '2024-04-26 20:03:55', '2024-04-26 20:03:55'),
(82, NULL, 'beta Human chorionic gonadotropin', 'بيتا موجهة الغدد التناسلية المشيمية البشرية', 17, NULL, NULL, 1, NULL, '0', NULL, 'B-HCG', NULL, NULL, 1, 1, '2024-04-26 20:05:15', '2024-04-26 20:05:15'),
(83, NULL, 'Dehydroepiandrosterone Sulfate', 'كبريتات ديهيدرو إيبي أندروستيرون', 17, NULL, NULL, 1, NULL, '0', NULL, 'DHEA -S', NULL, NULL, 1, 1, '2024-04-26 20:05:57', '2024-04-26 20:05:57'),
(84, NULL, 'Cortisol', 'الكورتيزول', 17, NULL, NULL, 1, NULL, '0', NULL, NULL, NULL, NULL, 1, 1, '2024-04-26 20:06:22', '2024-04-26 20:06:22'),
(85, NULL, 'Adrenocorticotropic hormone', 'الهرمون الموجه للغدة الكظرية', 17, NULL, NULL, 1, NULL, '0', NULL, 'ACTH', NULL, NULL, 1, 1, '2024-04-26 20:06:56', '2024-04-26 20:06:56'),
(86, NULL, 'Growth Hormone', 'هرمون النمو', 17, NULL, NULL, 1, NULL, '0', NULL, 'GH', NULL, NULL, 1, 1, '2024-04-26 20:07:27', '2024-04-26 20:07:27'),
(87, NULL, 'Parathyroid Hormone', 'هرمون الغدة الدرقية', 17, NULL, NULL, 1, NULL, '0', NULL, 'PTH', NULL, NULL, 1, 1, '2024-04-26 20:08:20', '2024-04-26 20:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `services_categories`
--

CREATE TABLE `services_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `flag` int NOT NULL DEFAULT '1' COMMENT '1 admin, 2 clinic',
  `type` int NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services_categories`
--

INSERT INTO `services_categories` (`id`, `name_en`, `name_ar`, `clinic_id`, `flag`, `type`, `status`, `created_at`, `updated_at`) VALUES
(8, 'MRI on the back', 'اشعة رنين علي الظهر', NULL, 1, 2, 1, '2023-12-08 00:51:41', '2024-01-19 23:58:50'),
(9, 'Resonance rays', 'اشعة رنين', NULL, 1, 2, 1, '2024-01-19 23:58:21', '2024-01-19 23:58:21'),
(12, 'Hematology Section', 'قسم أمراض الدم', NULL, 1, 1, 1, '2024-04-26 16:56:54', '2024-04-26 16:56:54'),
(13, 'Biochemistry Section', 'قسم الكيمياء الحيوية', NULL, 1, 1, 1, '2024-04-26 17:36:13', '2024-04-26 17:36:13'),
(14, 'HISTOPATH & CYTOLOGY', 'الهيستوباث وعلم الخلايا', NULL, 1, 1, 1, '2024-04-26 19:33:11', '2024-04-26 19:33:11'),
(15, 'BACTERIOLOGY & MICROSCOPY', 'علم البكتيريا والمجهر', NULL, 1, 1, 1, '2024-04-26 19:37:01', '2024-04-26 19:37:01'),
(16, 'TUMOR MARKERS', 'علامات الورم', NULL, 1, 1, 1, '2024-04-26 19:47:58', '2024-04-26 19:47:58'),
(17, 'HORMOES', 'الهرمونات', NULL, 1, 1, 1, '2024-04-26 19:53:53', '2024-04-26 19:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `service_analysis_attributes`
--

CREATE TABLE `service_analysis_attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `age_id` bigint UNSIGNED DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normal_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `normal_value_female` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_analysis_attributes`
--

INSERT INTO `service_analysis_attributes` (`id`, `name`, `service_id`, `age_id`, `parent_id`, `unit`, `normal_value`, `normal_value_female`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'sssss', 42, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-06-15 21:22:06', '2024-06-15 21:22:06'),
(2, 'wee', 42, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-06-15 21:22:23', '2024-06-15 21:22:23'),
(3, '1', 42, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-06-15 21:25:14', '2024-06-15 21:25:14'),
(4, 'sssss', 42, NULL, 3, '0', '98', NULL, 1, NULL, '2024-06-15 21:25:14', '2024-06-15 21:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `title_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings_type` enum('terms','about','privacy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_type` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title_en`, `title_ar`, `content_en`, `content_ar`, `image`, `settings_type`, `app_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'terms clinic', 'شروط الاستخدام', 'By using the Randevu app, you agree to the following:\n\nAccurate and complete information must be entered during registration.\n\nThe app is not for use in medical emergencies.\nAll appointments are subject to doctor availability.\nThe user is responsible for maintaining the confidentiality of their account.\nThe app reserves the right to suspend the account in case of misuse.\nPayments (if any) are non-refundable except in specific circumstances.\nThe app reserves the right to modify the terms and conditions at any time.', '\nباستخدامك لتطبيق رانديفو، فإنك توافق على ما يلي\nيجب إدخال بيانات صحيحة وكاملة عند التسجيل\nالتطبيق لا يُستخدم في حالات الطوارئ الطبية\nجميع المواعيد تعتمد على توفر الأطباء\nالمستخدم مسؤول عن الحفاظ على سرية حسابه\nيحق للتطبيق إيقاف الحساب في حال إساءة الاستخدام\nالمدفوعات (إن وجدت) غير قابلة للاسترداد إلا في حالات محددة\nيحق للتطبيق تعديل الشروط في أي وقت', '17406801029020.jpg', 'terms', 1, 1, '2022-07-14 15:41:15', '2025-02-27 18:15:02'),
(2, 'About clinic ', 'عن رانديفو', 'Welcome to Randevu, an integrated digital platform designed to facilitate communication between patients and doctors. The app allows users to book appointments, consult with doctors, manage medical records, and access healthcare services easily and securely.\n\nWe strive to improve the quality of healthcare by providing a simple and fast user experience for both patients and doctors.\n\nOur Mission: To provide accessible and reliable medical services using modern technology.\nOur Vision: To become one of the leading digital healthcare platforms in the region.', 'مرحبًا بك في رانديفو منصة رقمية متكاملة تهدف إلى تسهيل التواصل بين المرضى والأطباء. يتيح التطبيق للمستخدمين حجز المواعيد، استشارة الأطباء، متابعة السجلات الطبية، والحصول على خدمات صحية بسهولة وأمان\nنسعى إلى تحسين جودة الرعاية الصحية من خلال تقديم تجربة استخدام بسيطة وسريعة لكل من المرضى والأطباء\nرسالتنا\nتقديم خدمات طبية سهلة الوصول وموثوقة باستخدام التكنولوجيا الحديثة\nرؤيتنا\nأن نصبح من أفضل منصات الرعاية الصحية الرقمية في المنطقة', NULL, 'about', 1, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(3, 'Privacy clinic ', 'سياسة الخصوصية', 'At Randevu, we respect your privacy.\n\nWe collect data such as (name, phone number, email address).\n\nMedical data is stored securely and confidentially.\n\nWe use data to improve the user experience.\nWe do not sell user data to any third party.\nUsers can request the deletion of their data at any time.\nWe use advanced security measures to protect information.', '\nنحن في رنديفو نحترم خصوصيتك\nنقوم بجمع بيانات مثل (الاسم، رقم الهاتف، البريد الإلكتروني)\nيتم حفظ البيانات الطبية بشكل آمن وسري\nنستخدم البيانات لتحسين تجربة المستخدم\nلا نقوم ببيع بيانات المستخدمين لأي طرف ثالث\nيمكن للمستخدم طلب حذف بياناته في أي وقت\nنستخدم وسائل حماية متقدمة للحفاظ على المعلومات', NULL, 'privacy', 1, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(4, 'terms Employees', 'شروط واحكام تطبيق الموظفيين', '# Terms and Conditions for Randevu Application\n\nWelcome to the Randevu Application.\n\nPlease read these Terms and Conditions carefully before using the application. By using the Randevu Application, creating an account, or booking an appointment through it, you acknowledge that you have read, understood, and agreed to comply with these Terms and Conditions.\n\nThe Randevu Application is owned and operated by Quantum Technical Solutions, a company registered in the Kingdom of Saudi Arabia under Unified National Number 7053734971.\n\nThese Terms are intended to regulate the relationship between the application and the user and clarify the rights and obligations of each party when using the application’s services.\n\nIf you do not agree to these Terms or any part thereof, please do not use the application.\n\n---\n\n## 1. Definition of the Application and Its Services\n\nThe Randevu Application is an electronic platform operating within the Kingdom of Saudi Arabia that aims to facilitate users’ access to healthcare providers such as hospitals, clinics, and doctors by displaying their information, services, locations, and available offers, and enabling users to search, compare, and book appointments in an easy and organized manner.\n\nThe application provides users with several services including, but not limited to:\n\n* Browsing hospitals, clinics, and doctors registered in the application.\n* Searching and filtering healthcare providers based on available information.\n* Viewing healthcare provider locations and related information.\n* Viewing offers or services published by healthcare providers.\n* Booking appointments with hospitals, clinics, or doctors.\n* Managing family member or dependent information when available.\n* Sending complaints or feedback related to the application or displayed services.\n* Receiving messages or notifications related to bookings or application usage.\n\nThe user acknowledges that the role of the Randevu Application is limited to providing a technical platform that facilitates access to healthcare providers and organizes the booking process. Displaying any hospital, clinic, or doctor within the application does not mean that the application itself provides medical services or guarantees their outcomes.\n\n---\n\n## 2. Registration and Account Creation\n\nTo benefit from certain services such as appointment booking, family management, or complaints submission, users may be required to create an account and provide certain registration information.\n\nUsers agree to provide accurate, correct, and updated information and are responsible for any data entered into the application, including name, mobile number, email address, and any other requested information.\n\nUsers are responsible for maintaining the confidentiality of their login credentials, including passwords and verification codes, and must not share their accounts with others.\n\nIf users discover unauthorized use of their account or suspect any security breach, they must notify the application administration immediately through approved communication channels.\n\nRandevu reserves the right to suspend, restrict, or delete any account if false information is provided or if the account is used in violation of these Terms or in a manner harmful to the application, users, or healthcare providers.\n\n---\n\n## 3. Management of User and Family Data\n\nThe Randevu Application allows users to manage their personal information and add information related to family members or dependents to facilitate appointment bookings and interactions with healthcare providers.\n\nUsers acknowledge responsibility for the accuracy of all information entered, whether relating to themselves or their family members or dependents.\n\nUsers further confirm that they possess the legal authority to provide information relating to family members, especially children or minors, and that they have obtained all required approvals where necessary.\n\nUsers must update information whenever changes occur and bear responsibility for any issues arising from inaccurate or incomplete data.\n\nRandevu handles such information in accordance with its Privacy Policy and applicable laws in the Kingdom of Saudi Arabia.\n\n---\n\n## 4. Searching for Hospitals, Doctors, and Offers\n\nThe application allows users to search for hospitals, clinics, doctors, and healthcare providers and to view related information such as names, locations, specialties, services, offers, and other available data.\n\nSuch information is displayed solely to assist users in making booking decisions and does not constitute a medical recommendation or endorsement by the application.\n\nOffers, services, and information displayed may be provided directly by healthcare providers, who remain responsible for their accuracy and updates unless proven otherwise.\n\nRandevu reserves the right to modify, rearrange, update, or remove displayed information to improve user experience or maintain service quality.\n\n---\n\n## 5. Bookings and Appointments\n\nThe Randevu Application enables users to book appointments with hospitals, clinics, doctors, and healthcare providers based on available schedules and displayed information.\n\nUsers acknowledge that confirmation, modification, or cancellation of appointments may be subject to the healthcare provider’s own policies, while the application’s role is limited to facilitating the booking process.\n\nUsers agree to attend scheduled appointments or modify/cancel bookings within a reasonable time where such functionality is available.\n\nRandevu is not responsible for delays, cancellations, or appointment changes caused by healthcare providers or circumstances beyond its control. However, users may submit complaints or feedback through available channels.\n\nUsers also acknowledge that booking through the application does not constitute medical consultation or replace direct medical evaluation by a qualified healthcare provider.\n\n---\n\n## 6. Role of the Application as an Intermediary\n\nUsers acknowledge that Randevu operates solely as an intermediary electronic platform facilitating access to healthcare providers and organizing search, booking, and communication processes.\n\nThe application is not a hospital, clinic, or medical center and does not provide direct medical services, diagnosis, treatment, prescriptions, or medical consultations.\n\nThe presence of a healthcare provider within the application does not guarantee the quality or outcome of medical services provided. The direct medical relationship exists solely between the user and the selected healthcare provider.\n\n---\n\n## 7. Technical Failures and Third-Party Services\n\nRandevu may rely on third-party service providers such as hosting providers, servers, telecommunications companies, internet providers, SMS services, notification services, location systems, and other technical services.\n\nUsers acknowledge that interruptions, delays, or technical failures may occur due to reasons beyond the application’s control, including server failures, internet disruptions, telecommunications issues, operating system problems, maintenance, or external technical service interruptions.\n\nRandevu shall not be liable for service interruptions or temporary loss of access caused by third parties or circumstances beyond reasonable control.\n\nNevertheless, the application strives to resolve issues and improve service stability without guaranteeing uninterrupted or error-free service.\n\n---\n\n## 8. Responsibility of Hospitals and Doctors\n\nHealthcare providers, including hospitals, clinics, doctors, and other medical entities, bear full responsibility for the medical services they provide, including examinations, diagnosis, treatment, prescriptions, medical procedures, follow-up, service quality, and medical information accuracy.\n\nUsers acknowledge that any medical decisions or recommendations are solely the responsibility of the healthcare provider.\n\nHealthcare providers are also responsible for updating their information, schedules, offers, and displayed content.\n\nUsers retain the right to submit complaints regarding bookings or services through the application, but this does not mean that Randevu assumes medical responsibility.\n\n---\n\n## 9. Complaints and Messages\n\nRandevu allows users to submit complaints, feedback, or messages related to the application, bookings, or healthcare providers.\n\nUsers must provide accurate and appropriate information and refrain from abuse, defamation, false reports, or misuse of this feature.\n\nThe application administration may review complaints and contact users or healthcare providers when additional information is required.\n\nReceiving a complaint does not necessarily confirm its validity nor obligate the application to achieve a specific result.\n\nRandevu reserves the right to remove or ignore abusive or unlawful complaints or messages.\n\n---\n\n## 10. Medical Offers and Service Prices\n\nThe application may display offers, services, or prices provided by healthcare providers to assist users in selecting suitable services.\n\nHealthcare providers remain solely responsible for the accuracy, validity, and application of such offers and prices unless the error originates directly from the application.\n\nPrices and offers may change over time and may be subject to conditions, durations, or limited availability.\n\nUsers are encouraged to review offer details before booking and may contact healthcare providers directly for clarification.\n\nRandevu is not responsible for price changes, expired offers, or refusal by healthcare providers to honor offers due to unmet conditions.\n\nCurrently, Randevu does not collect payments from users unless explicitly stated otherwise in future updates.\n\n---\n\n## 11. Emergency and Medical Disclaimer\n\nUsers acknowledge that Randevu does not provide emergency services and must not be relied upon for urgent or life-threatening medical situations.\n\nIn emergencies, users should immediately contact emergency authorities, visit the nearest hospital, or call emergency numbers within Saudi Arabia.\n\nThe application does not provide medical diagnosis, treatment, prescriptions, or consultations. Any medical decisions must be made directly by qualified healthcare professionals after examination and evaluation.\n\nRandevu shall not be liable for any harm or medical consequences resulting from reliance on general information displayed within the application or delays in seeking professional medical care.\n\n---\n\n## 12. Account Deletion\n\nUsers may request deletion of their accounts through the application or by contacting the administration through approved communication channels.\n\nUpon deletion, access to the account and related services may be terminated, including booking history, family data, messages, and complaints, according to technical procedures.\n\nCertain information may be retained where required for legal, regulatory, accounting, fraud prevention, or dispute resolution purposes in accordance with Saudi regulations and the application’s Privacy Policy.\n\nDeletion requests may not immediately remove all backup or technical log data, but the application will process requests according to applicable legal and technical requirements.\n\nFor inquiries regarding account or data deletion, users may contact:\n**[info@randevuksa.com](mailto:info@randevuksa.com)**\n\n---\n\n## 13. Account Suspension for Misuse\n\nRandevu reserves the right to suspend, restrict, or permanently delete user accounts if misuse or violations of these Terms are detected.\n\nMisuse includes, but is not limited to:\n\n* Providing false information.\n* Creating fake or excessive bookings.\n* Using another person’s account without authorization.\n* Sending malicious complaints or abusive messages.\n* Attempting to hack or disrupt the application.\n* Using the application unlawfully or against public morals.\n\nThe application may take necessary protective measures, including restricting services, removing violating content, or notifying competent authorities where legally required.\n\nAccount suspension or deletion does not prevent the application from seeking compensation or legal action for damages caused by misuse.\n\n---\n\n## 14. Intellectual Property\n\nAll rights related to the Randevu Application, including trade names, logos, designs, interfaces, texts, images, software, databases, content presentation methods, and other materials are owned or licensed by Quantum Technical Solutions unless otherwise stated.\n\nUsers may not copy, modify, republish, distribute, or exploit any part of the application without prior written permission.\n\nUsers are granted only a limited personal and lawful right to use the application in accordance with these Terms.\n\nNames, logos, trademarks, and information relating to hospitals, clinics, doctors, or healthcare providers displayed within the application remain the property of their respective owners.\n\n---\n\n## 15. Amendments to the Terms and Conditions\n\nRandevu reserves the right to amend or update these Terms and Conditions from time to time for operational, technical, legal, or service improvement reasons.\n\nAny substantial changes will be published within the application or communicated through appropriate means.\n\nContinued use of the application after updates constitutes acceptance of the revised Terms and Conditions.\n\nUsers are advised to review these Terms periodically.\n\n---\n\n## 16. Governing Law\n\nThese Terms and Conditions shall be governed and interpreted in accordance with the laws and regulations applicable in the Kingdom of Saudi Arabia.\n\nIn the event of any dispute related to the application or its services, the parties shall first attempt to resolve the matter amicably through communication with the application administration.\n\nIf no amicable resolution is reached, jurisdiction shall belong to the competent courts within the Kingdom of Saudi Arabia unless otherwise required by applicable law.\n\nInvalidity or unenforceability of any provision shall not affect the validity of the remaining provisions.\n\n---\n\n## 17. Contact Us\n\nFor inquiries, complaints, or requests related to these Terms and Conditions or the use of the Randevu Application, please contact us at:\n\n**[info@randevuksa.com](mailto:info@randevuksa.com)**', 'الشروط والأحكام\nلتطبيق رنديفو\nمرحبًا بك في تطبيق رنديفو.\nيرجى قراءة هذه الشروط والأحكام بعناية قبل استخدام التطبيق. باستخدامك لتطبيق رنديفو أو إنشاء حساب أو إجراء حجز من خلاله فإنك تقر بأنك قرأت هذه الشروط وفهمتها ووافقت على الالتزام بها.\nتطبيق رانديفو مملوك ومشغل من قبل شركة كوانتم تيكنيكال سولوشنز / Quantum Technical Solutions وهي شركة مسجلة في المملكة العربية السعودية بالرقم الوطني الموحد 7053734971\nتهدف هذه الشروط إلى تنظيم العلاقة بين التطبيق والمستخدم وتوضيح حقوق والتزامات كل طرف عند استخدام خدمات التطبيق .\nإذا كنت لا توافق على هذه الشروط أو أي جزء منها فيرجى عدم استخدام التطبيق.\n1.	تعريف التطبيق وخدماته\nتطبيق رنديفو هو منصة إلكترونية تعمل داخل المملكة العربية السعودية وتهدف إلى تسهيل وصول المستخدمين إلى مقدمي الخدمات الطبية مثل المستشفيات والعيادات والأطباء من خلال عرض بياناتهم وخدماتهم ومواقعهم والعروض المتاحة لديهم وتمكين المستخدم من البحث والمقارنة وحجز المواعيد بطريقة سهلة ومنظمة.\nيوفر التطبيق للمستخدم عددًا من الخدمات من بينها على سبيل المثال:\n•	استعراض المستشفيات والعيادات والأطباء المسجلين في التطبيق.\n•	البحث عن مقدمي الخدمات الطبية وتصفيتهم حسب البيانات المتاحة داخل التطبيق.\n•	الاطلاع على مواقع مقدمي الخدمة وبعض المعلومات المتعلقة بهم.\n•	الاطلاع على العروض أو الخدمات المنشورة من مقدمي الخدمة.\n•	حجز المواعيد لدى المستشفيات أو العيادات أو الأطباء.\n•	إدارة بيانات أفراد الأسرة أو التابعين للمستخدم متى كانت هذه الخاصية متاحة.\n•	إرسال الشكاوى أو الملاحظات المتعلقة باستخدام التطبيق أو بالخدمات المعروضة من خلاله.\n•	استقبال الرسائل أو الإشعارات المرتبطة بالحجوزات أو استخدام التطبيق.\nويقر المستخدم بأن دور تطبيق رنديفو يقتصر على توفير وسيلة تقنية تساعد في الوصول إلى مقدمي الخدمات الطبية وتنظيم عملية الحجز ولا يعني عرض أي مستشفى أو عيادة أو طبيب داخل التطبيق أن التطبيق يقدم الخدمة الطبية بنفسه أو يضمن نتائجها.\n2.	التسجيل وإنشاء الحساب\nحتى يتمكن المستخدم من الاستفادة من بعض خدمات تطبيق رنديفو مثل حجز المواعيد أو إدارة بيانات الأسرة أو إرسال الشكاوى قد يُطلب منه إنشاء حساب داخل التطبيق وإدخال بعض البيانات اللازمة للتسجيل.\nيلتزم المستخدم عند إنشاء الحساب بتقديم بيانات صحيحة ودقيقة ومحدثة ويكون مسؤولًا عن أي بيانات يقوم بإدخالها داخل التطبيق بما في ذلك الاسم ورقم الجوال والبريد الإلكتروني وأي بيانات أخرى يطلبها التطبيق لإتمام التسجيل أو تقديم الخدمة.\nيتحمل المستخدم مسؤولية الحفاظ على سرية بيانات الدخول الخاصة بحسابه بما في ذلك كلمة المرور أو أي رمز تحقق يتم إرساله إليه كما يلتزم بعدم مشاركة حسابه مع الغير أو السماح لأي شخص آخر باستخدامه دون إذن.\nفي حال اكتشف المستخدم أي استخدام غير مصرح به لحسابه أو اشتبه في وجود اختراق أو دخول غير آمن فعليه إبلاغ إدارة التطبيق في أقرب وقت ممكن عبر وسائل التواصل المعتمدة.\nيحق لإدارة تطبيق رنديفو إيقاف أو تقييد أو حذف أي حساب إذا تبين أن البيانات المقدمة غير صحيحة أو أن الحساب يُستخدم بطريقة مخالفة لهذه الشروط أو بطريقة قد تضر بالتطبيق أو المستخدمين أو مقدمي الخدمات الطبية.\n3.	إدارة بيانات المستخدم وأفراد أسرته\nيتيح تطبيق رنديفو للمستخدم إمكانية إدارة بياناته الشخصية وكذلك إضافة بيانات أفراد أسرته أو التابعين له وذلك بهدف تسهيل حجز المواعيد وتنظيم التعامل مع مقدمي الخدمات الطبية المسجلين في التطبيق.\nيقر المستخدم بأنه مسؤول عن صحة ودقة البيانات التي يقوم بإدخالها داخل التطبيق سواء كانت تخصه شخصيًا أو تخص أحد أفراد أسرته أو التابعين له.\nكما يقر المستخدم بأنه يملك الحق أو الصفة النظامية التي تخوله إدخال بيانات أفراد أسرته أو التابعين له وخاصة في حال إدخال بيانات الأطفال أو القُصّر وأنه حصل على الموافقات اللازمة لذلك متى كانت مطلوبة.\nيلتزم المستخدم بتحديث البيانات متى طرأ عليها أي تغيير ويتحمل المسؤولية عن أي خطأ أو تأخير أو مشكلة تنتج عن إدخال بيانات غير صحيحة أو غير مكتملة.\nويتعامل تطبيق رنديفو مع هذه البيانات وفقًا لسياسة الخصوصية الخاصة بالتطبيق وبما يتوافق مع الأنظمة المعمول بها في المملكة العربية السعودية.\n4.	البحث عن المستشفيات والأطباء والعروض\nيوفر تطبيق رنديفو للمستخدم إمكانية البحث عن المستشفيات والعيادات والأطباء ومقدمي الخدمات الطبية المتاحين داخل التطبيق والاطلاع على بعض المعلومات المتعلقة بهم مثل الاسم، الموقع، التخصصات، الخدمات، العروض أو أي بيانات أخرى يتم إتاحتها من خلال التطبيق.\nيتم عرض هذه البيانات بهدف مساعدة المستخدم على الاختيار والحجز بسهولة ولا يُعد ذلك توصية أو ترشيحًا طبيًا من التطبيق لمقدم خدمة معين دون غيره.\nكما أن العروض أو الخدمات أو المعلومات المنشورة داخل التطبيق قد تكون مقدمة من المستشفيات أو العيادات أو الأطباء أو الجهات الطبية المعنية ويكون مقدم الخدمة هو المسؤول عن صحة ودقة هذه المعلومات وتحديثها ما لم يثبت خلاف ذلك.\nويحق لإدارة تطبيق رنديفو تعديل طريقة عرض البيانات أو ترتيبها أو تحديثها أو حذفها متى رأت ضرورة لذلك بما يحسن تجربة المستخدم أو يحافظ على جودة الخدمة داخل التطبيق.\n\n5.	 الحجز والمواعيد\nيتيح تطبيق رنديفو للمستخدم إمكانية حجز المواعيد لدى المستشفيات أو العيادات أو الأطباء أو مقدمي الخدمات الطبية المتاحين داخل التطبيق وذلك بحسب المواعيد والبيانات التي تظهر للمستخدم وقت الحجز.\nيقر المستخدم بأن تأكيد الموعد أو تعديله أو إلغاؤه قد يخضع لسياسة مقدم الخدمة الطبية الذي تم الحجز لديه وأن دور التطبيق يقتصر على تسهيل عملية الحجز وتنظيم التواصل بين المستخدم ومقدم الخدمة.\nيلتزم المستخدم بالحضور في الموعد المحدد أو تعديل أو إلغاء الحجز خلال الوقت المناسب متى كانت هذه الخاصية متاحة وذلك حتى لا يتسبب في تعطيل المواعيد أو الإضرار بمقدمي الخدمة أو المستخدمين الآخرين.\nولا يتحمل تطبيق رنديفو المسؤولية عن أي تأخير أو إلغاء أو تغيير في الموعد يكون سببه مقدم الخدمة الطبية أو ظروف خارجة عن إرادة التطبيق ومع ذلك يجوز للمستخدم تقديم شكوى أو ملاحظة من خلال الوسائل المتاحة داخل التطبيق.\nكما يقر المستخدم بأن الحجز من خلال التطبيق لا يعني حصوله على استشارة طبية من التطبيق ولا يغني عن التقييم أو الكشف الطبي المباشر لدى الطبيب أو مقدم الخدمة المختص.\n6.	دور التطبيق كوسيط بين المستخدم ومقدم الخدمة الطبية\nيقر المستخدم بأن تطبيق رنديفو يعمل كمنصة إلكترونية وسيطة تهدف إلى تسهيل الوصول إلى مقدمي الخدمات الطبية وتنظيم عملية البحث والحجز والتواصل بينهم وبين المستخدمين.\nويقتصر دور التطبيق على عرض بيانات المستشفيات والعيادات والأطباء ومقدمي الخدمات الطبية المتاحين وتمكين المستخدم من حجز المواعيد أو إرسال الملاحظات والشكاوى من خلال الوسائل المتاحة داخل التطبيق.\nولا يُعد تطبيق رنديفو مستشفى أو عيادة أو مركزًا طبيًا ولا يقدم خدمات طبية مباشرة ولا يمارس التشخيص أو العلاج أو وصف الأدوية أو تقديم الاستشارات الطبية بأي شكل من الأشكال.\nكما أن وجود مقدم خدمة طبية داخل التطبيق لا يعني أن التطبيق يضمن جودة الخدمة الطبية المقدمة منه أو نتائجها وإنما تكون العلاقة الطبية المباشرة بين المستخدم ومقدم الخدمة الذي اختاره المستخدم وقام بالحجز لديه.\n7.	الأعطال والخدمات المقدمة من أطراف ثالثة\nقد يعتمد تطبيق رنديفو في تشغيله أو تقديم بعض خدماته على أطراف ثالثة مثل مزودي خدمات الاستضافة والخوادم، شركات الاتصالات والإنترنت، مزودي خدمات الرسائل النصية أو الإشعارات، أنظمة تحديد الموقع أو أي خدمات تقنية أخرى لازمة لتشغيل التطبيق.\nويقر المستخدم بأن بعض الأعطال أو الانقطاعات أو التأخير في الخدمة قد تحدث نتيجة أسباب خارجة عن سيطرة تطبيق رنديفو بما في ذلك تعطل الخوادم ضعف أو انقطاع شبكة الإنترنت ، أعطال شركات الاتصالات، مشاكل أنظمة التشغيل أو الأجهزة، انقطاع الخدمات التقنية الخارجية، أعمال الصيانة أو أي ظروف فنية لا تكون ناشئة عن خطأ مباشر من التطبيق.\nولا يتحمل تطبيق رنديفو المسؤولية عن أي توقف أو تأخير أو خلل أو فقدان مؤقت في الوصول إلى الخدمة متى كان ذلك ناتجًا عن طرف ثالث أو سبب خارج عن السيطرة المعقولة للتطبيق.\nومع ذلك يسعى تطبيق رنديفو إلى اتخاذ الإجراءات المناسبة لمعالجة الأعطال وتحسين استقرار الخدمة قدر الإمكان دون أن يضمن أن تكون الخدمة متاحة بشكل مستمر أو خالية تمامًا من الانقطاع أو الأخطاء.\n8.	مسؤولية المستشفيات والأطباء عن الخدمة الطبية\nيتحمل مقدم الخدمة الطبية سواء كان مستشفى أو عيادة أو طبيبًا أو أي جهة طبية أخرى المسؤولية الكاملة عن الخدمات الطبية التي يقدمها للمستخدم بما في ذلك الكشف، التشخيص، العلاج، الوصفات الطبية، الإجراءات الطبية، المتابعة، جودة الخدمة ودقة المعلومات الطبية المقدمة منه.\nويقر المستخدم بأن أي قرار طبي أو علاج أو إجراء أو توصية تصدر من مقدم الخدمة الطبية تكون تحت مسؤولية مقدم الخدمة وحده ولا يكون تطبيق رنديفو مسؤولًا عنها طالما أن التطبيق لم يتدخل في تقديم الخدمة الطبية أو محتواها.\nكما يتحمل مقدم الخدمة الطبية مسؤولية تحديث بياناته ومواعيده وعروضه ومعلوماته المعروضة داخل التطبيق متى كانت هذه البيانات مقدمة منه أو يتم إدارتها من خلاله.\nوفي جميع الأحوال يظل للمستخدم الحق في تقديم شكوى أو ملاحظة من خلال التطبيق إذا واجه مشكلة تتعلق بالحجز أو التعامل أو الخدمة على أن يقوم التطبيق بالتعامل معها وفق الإجراءات المتاحة لديه ودون أن يعني ذلك تحمله المسؤولية الطبية عن الخدمة المقدمة.\n9.	الشكاوى والرسائل\nيوفر تطبيق رنديفو للمستخدم إمكانية إرسال الشكاوى أو الملاحظات أو الرسائل المتعلقة باستخدام التطبيق أو بالحجوزات أو بمقدمي الخدمات الطبية المسجلين فيه.\nيلتزم المستخدم عند إرسال أي شكوى أو رسالة بأن تكون المعلومات المقدمة صحيحة وواضحة وأن يستخدم هذه الخاصية بطريقة جادة ومناسبة بعيدًا عن الإساءة أو التشهير أو تقديم بلاغات كيدية أو معلومات غير صحيحة.\nتقوم إدارة التطبيق بمراجعة الشكاوى والملاحظات الواردة إليها والتعامل معها وفقًا لطبيعتها والإجراءات المناسبة وقد يتم التواصل مع المستخدم أو مقدم الخدمة الطبية عند الحاجة إلى معلومات إضافية.\nويقر المستخدم بأن استقبال التطبيق للشكوى أو الرسالة لا يعني بالضرورة ثبوت صحتها كما لا يعني التزام التطبيق بتحقيق نتيجة مُعينة وإنما يسعى التطبيق إلى تحسين تجربة المستخدم ومتابعة الملاحظات بالقدر الممكن.\nويحق لإدارة تطبيق رنديفو حذف أو تجاهل أي رسالة أو شكوى تتضمن ألفاظًا مسيئة أو محتوى مخالفًا أو بيانات غير صحيحة أو استخدامًا غير مشروع لخدمات التطبيق.\n10.	العروض الطبية وأسعار الخدمات\nقد يعرض تطبيق رنديفو بعض العروض أو الخدمات أو الأسعار الخاصة بالمستشفيات أو العيادات أو الأطباء أو مقدمي الخدمات الطبية المسجلين في التطبيق وذلك بهدف إتاحة المعلومات للمستخدم ومساعدته على اختيار الخدمة المناسبة له.\nيقر المستخدم بأن العروض والأسعار والمعلومات المتعلقة بالخدمات الطبية تكون مقدمة من مقدم الخدمة الطبية نفسه ويكون مقدم الخدمة هو المسؤول عن صحتها وتحديثها وتطبيقها ما لم يثبت أن الخطأ صادر من التطبيق مباشرة.\nقد تختلف الأسعار أو تفاصيل العروض من وقت لآخر بحسب سياسة مقدم الخدمة الطبية وقد تكون بعض العروض مرتبطة بمدة محددة أو شروط معينة أو عدد محدود من الحجوزات.\nلذلك يلتزم المستخدم بمراجعة تفاصيل العرض أو الخدمة قبل الحجز كما يحق له الاستفسار من مقدم الخدمة الطبية عن أي تفاصيل إضافية تتعلق بالسعر أو نطاق الخدمة أو شروط الاستفادة من العرض.\nولا يتحمل تطبيق رنديفو أي مسؤولية عن تغيير الأسعار أو انتهاء العروض أو رفض مقدم الخدمة تطبيق عرض معين إذا كان ذلك راجعًا إلى مقدم الخدمة أو إلى شروط لم يتم استيفاؤها من قبل المستخدم.\nولا يقوم تطبيق رنديفو حاليًا بتحصيل أي مبالغ مالية من المستخدمين مقابل الحجز أو الخدمات المعروضة ما لم يتم النص على خلاف ذلك داخل التطبيق أو وفق تحديث لاحق لهذه الشروط.\n11.إخلاء مسؤولية التطبيق عن الطوارئ والتشخيص النهائي\nيقر المستخدم بأن تطبيق رنديفو لا يُقدم خدمات طوارئ ولا يُستخدم للتعامل مع الحالات الطبية العاجلة أو الحرجة ولا يجب الاعتماد عليه في طلب المساعدة الفورية عند وجود خطر على الصحة أو الحياة.\nفي حال وجود حالة طبية طارئة يجب على المستخدم التواصل فورًا مع الجهات المختصة أو التوجه إلى أقرب مركز طوارئ أو مستشفى أو الاتصال بأرقام الطوارئ المعتمدة في المملكة العربية السعودية.\nكما يقر المستخدم بأن التطبيق لا يقدم تشخيصًا طبيًا ولا علاجًا ولا وصفات طبية ولا استشارات طبية وأن أي تشخيص أو علاج أو قرار طبي يجب أن يتم من خلال الطبيب أو مقدم الخدمة الطبية المختص بعد الفحص والتقييم المباشر.\nولا يتحمل تطبيق رنديفو أي مسؤولية عن أي ضرر أو تأخير أو نتيجة طبية قد تنشأ عن اعتماد المستخدم على معلومات عامة معروضة داخل التطبيق أو عن تأخره في طلب الرعاية الطبية المناسبة من الجهات المختصة.\n12.حذف الحساب\nيتيح تطبيق رنديفو للمستخدم إمكانية طلب حذف حسابه من خلال الخاصية المتاحة داخل التطبيق أو من خلال التواصل مع إدارة التطبيق عبر وسائل التواصل المعتمدة.\nعند حذف الحساب قد يتم إيقاف وصول المستخدم إلى حسابه والخدمات المرتبطة به بما في ذلك بيانات الحجوزات أو أفراد الأسرة أو الرسائل أو الشكاوى المرتبطة بالحساب وذلك وفقًا للإجراءات الفنية المعتمدة داخل التطبيق.\nوقد تحتفظ إدارة التطبيق ببعض البيانات لمدة محددة متى كان الاحتفاظ بها ضروريًا لأغراض نظامية أو قانونية أو محاسبية أو لحماية الحقوق أو متابعة الشكاوى أو منع إساءة الاستخدام وذلك وفقًا للأنظمة المعمول بها في المملكة العربية السعودية وسياسة الخصوصية الخاصة بالتطبيق.\nيقر المستخدم بأن حذف الحساب قد لا يؤدي بالضرورة إلى حذف جميع البيانات فورًا من النسخ الاحتياطية أو السجلات الفنية إلا أن التطبيق سيتعامل مع طلب الحذف وفقًا للمدة والإجراءات المناسبة وبما يتوافق مع المتطلبات النظامية.\nوفي حال رغب المستخدم في الاستفسار عن حذف حسابه أو بياناته يمكنه التواصل مع إدارة تطبيق رنديفو عبر البريد الإلكتروني:  info@randevuksa.com\n\n13. إيقاف الحساب عند إساءة الاستخدام\nيحق لإدارة تطبيق رنديفو إيقاف أو تقييد أو حذف حساب المستخدم بشكل مؤقت أو دائم إذا تبين أن المستخدم قد أساء استخدام التطبيق أو خالف هذه الشروط والأحكام أو استخدم الخدمات بطريقة تضر بالتطبيق أو بمقدمي الخدمات الطبية أو بالمستخدمين الآخرين.\nويشمل إساءة الاستخدام على سبيل المثال لا الحصر إدخال بيانات غير صحيحة ، إنشاء حجوزات وهمية أو متكررة دون جدية، استخدام حساب الغير دون إذن، إرسال شكاوى كيدية أو رسائل مسيئة، محاولة اختراق التطبيق أو تعطيل خدماته أو استخدام التطبيق لأي غرض غير نظامي أو مخالف للآداب العامة.\nكما يحق لإدارة التطبيق اتخاذ الإجراءات المناسبة لحماية التطبيق ومستخدميه بما في ذلك منع المستخدم من الوصول إلى بعض الخدمات أو حذف المحتوى المخالف أو إبلاغ الجهات المختصة متى كان ذلك لازمًا وفقًا للأنظمة المعمول بها في المملكة العربية السعودية.\nولا يخل إيقاف الحساب أو حذفه بحق إدارة التطبيق في المطالبة بأي تعويض أو اتخاذ أي إجراء قانوني إذا ترتب على إساءة الاستخدام ضرر مباشر أو غير مباشر بالتطبيق أو بالشركة المالكة أو بأي طرف آخر.\n14.الملكية الفكرية\nجميع الحقوق المتعلقة بتطبيق رنديفو بما في ذلك الاسم التجاري، الشعار، التصميمات، الواجهات، النصوص، الصور، البرمجيات، قواعد البيانات، طريقة عرض المحتوى وأي عناصر أو مواد أخرى داخل التطبيق مملوكة أو مرخصة لصالح شركة كوانتم تيكنيكال سولوشنز / Quantum Technical Solutions ما لم يذكر خلاف ذلك.\nلا يجوز للمستخدم نسخ أو تعديل أو إعادة نشر أو توزيع أو استغلال أي جزء من التطبيق أو محتواه أو علاماته أو تصميماته أو برمجياته بأي وسيلة إلا بعد الحصول على موافقة كتابية مسبقة من إدارة التطبيق.\nويقتصر حق المستخدم على استخدام التطبيق والخدمات المتاحة داخله استخدامًا شخصيًا ومشروعًا، وبما يتوافق مع هذه الشروط والأحكام.\nأما الأسماء أو الشعارات أو العلامات أو البيانات الخاصة بالمستشفيات أو العيادات أو الأطباء أو مقدمي الخدمات الطبية المعروضة داخل التطبيق فتظل مملوكة لأصحابها ويتم عرضها داخل التطبيق لأغراض التعريف بالخدمة وتسهيل الحجز فقط.\n15.تعديل الشروط والأحكام\nيحق لإدارة تطبيق رنديفو تعديل أو تحديث هذه الشروط والأحكام من وقت لآخر متى دعت الحاجة إلى ذلك سواء لأسباب تشغيلية أو فنية أو قانونية أو لتحسين الخدمات المقدمة من خلال التطبيق.\nوفي حال إجراء أي تعديل جوهري على هذه الشروط سيتم نشر النسخة المحدثة داخل التطبيق أو إتاحتها للمستخدم بأي وسيلة مناسبة ويُعد استمرار المستخدم في استخدام التطبيق بعد نشر التحديثات موافقة منه على الشروط والأحكام المعدلة.\nلذلك يُنصح المستخدم بمراجعة هذه الشروط من وقت لآخر للاطلاع على أي تحديثات قد تطرأ عليها.\n16.القانون الواجب التطبيق\nتخضع هذه الشروط والأحكام وتفسر وفقًا للأنظمة واللوائح المعمول بها في المملكة العربية السعودية.\nوفي حال نشوء أي خلاف أو مطالبة تتعلق باستخدام تطبيق رنديفو أو الخدمات المتاحة من خلاله يتم السعي أولًا إلى حل النزاع وديًا من خلال التواصل مع إدارة التطبيق عبر وسائل التواصل المعتمدة.\nوفي حال تعذر الوصول إلى حل ودي يكون الاختصاص بنظر النزاع للجهات القضائية المختصة داخل المملكة العربية السعودية وذلك ما لم تقرر الأنظمة المعمول بها خلاف ذلك.\nولا يؤثر بطلان أو عدم قابلية تنفيذ أي بند من هذه الشروط على صحة ونفاذ باقي البنود وتظل الشروط الأخرى سارية ومنتجة لآثارها النظامية.\n17. التواصل معنا\nلأي استفسارات أو شكاوى أو طلبات تتعلق بهذه الشروط والأحكام أو باستخدام تطبيق رنديفو يمكن التواصل مع إدارة التطبيق عبر البريد الإلكتروني التالي: info@randevuksa.com', NULL, 'terms', NULL, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(5, 'terms Employees', 'شروط واحكام تطبيق الموظفيين', 'About Randevu\n\nRandevu is not just a booking application; it is a fully integrated technological ecosystem designed to bridge the gap between doctors and patients. We provide a “bridge of trust” through appointment automation and reducing customer service errors, ensuring that everyone’s time is valued and protected.\n\nOur vision is to become the leading standard for digital healthcare in the region by transforming every clinic into a smart facility that operates without waiting times.\n\nOur mission is to empower doctors to focus on their noble mission through advanced technological solutions that handle administrative organization while providing every patient with a smooth and secure booking experience.\n\nOur Goals:\n\n* Completely eliminate overcrowding and appointment scheduling chaos.\n* Reduce administrative errors by 100% through smart automation.\n* Build a trusted medical community based on real reviews and reliability.', 'عن رنديفو \nرنديفو  ليس مجرد تطبيق حجز، بل هو منظومة تقنية متكاملة صُممت لسد الفجوة بين الطبيب والمريض. نحن نوفر \"جسر الثقة\" عبر أتمتة المواعيد خفض معدل الأخطاء لخدمة العملاء ، لنضمن أن وقت الجميع مقدّر ومحمي.\n\nرؤيتنا أن نكون المعيار الأول للرعاية الصحية الرقمية في المنطقة، من خلال تحويل كل عيادة إلى منشأة ذكية تعمل بلا انتظار.\nو مهمتنا تمكين الأطباء من التركيز على رسالتهم السامية عبر حلول تقنية تتكفل بتنظيم الإدارة، وتوفير رحلة حجز سلسة وآمنة لكل مريض.\nأهدافنا:\nالقضاء التام على ظاهرة الازدحام وفوضى المواعيد.\nتقليل الأخطاء الإدارية بنسبة 100% عبر الأتمتة الذكية.\nبناء مجتمع طبي يعتمد على التقييمات الحقيقية والموثوقية.', NULL, 'about', NULL, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15');
INSERT INTO `settings` (`id`, `title_en`, `title_ar`, `content_en`, `content_ar`, `image`, `settings_type`, `app_type`, `status`, `created_at`, `updated_at`) VALUES
(6, 'terms Employees ', 'شروط واحكام تطبيق الموظفيين', '# Privacy Policy for Randevu Application\r\n\r\nWelcome to the Randevu Application.\r\n\r\nAt Randevu, we highly value the privacy and protection of users’ data. We understand that using an application related to medical services and appointment bookings may involve entering personal information, family member data, or information related to appointments and healthcare providers.\r\n\r\nThis Privacy Policy aims to explain how data is collected, used, stored, and shared when using the Randevu Application, in accordance with the laws and regulations applicable in the Kingdom of Saudi Arabia.\r\n\r\nBy using the application, creating an account, or booking an appointment through it, you acknowledge that you have read and understood this Privacy Policy and agree to the handling of your data as described herein.\r\n\r\nIf you do not agree to this policy or any part of it, please do not use the application.\r\n\r\n---\r\n\r\n## 1. Data Collected by the Application\r\n\r\nThe Randevu Application may collect certain data necessary to provide its services and improve the user experience when creating an account, using the application, booking appointments, adding family members, or submitting complaints or feedback.\r\n\r\nThe data that may be collected depending on how you use the application includes:\r\n\r\n* Account information such as name, mobile number, email address, password, or verification code.\r\n* Information related to family members or dependents added by the user within the application.\r\n* Appointment and booking data such as healthcare provider, appointment date and time, and booking status.\r\n* Information related to healthcare providers with whom the user interacts through the application, such as hospitals, clinics, or doctors.\r\n* Complaints, messages, or feedback submitted by the user through the application.\r\n* Geolocation data if the user allows the application to access it, in order to display nearby hospitals, clinics, or doctors.\r\n* Technical data related to application usage such as device type, operating system, IP address, and usage logs for security and performance improvement purposes.\r\n\r\nThe type of data collected may vary depending on the services used and the permissions granted by the user through their device.\r\n\r\n---\r\n\r\n## 2. Account Information\r\n\r\nWhen creating an account in the Randevu Application, users may be required to provide basic information necessary for registration and use of the application’s services, such as name, mobile number, email address, password, verification code, or any other information required to activate the account or verify the user’s identity.\r\n\r\nAccount information is used to enable users to log in, manage their accounts, book appointments, receive notifications or messages related to the application, and communicate with users regarding bookings, complaints, or technical support.\r\n\r\nUsers are responsible for providing accurate and updated information and for maintaining the confidentiality of their login credentials without sharing them with others.\r\n\r\nIf account information changes, users are advised to update it within the application to ensure uninterrupted service.\r\n\r\n---\r\n\r\n## 3. Family Member Information\r\n\r\nThe Randevu Application allows users to add information related to family members or dependents in order to facilitate appointment bookings and manage related bookings through the user’s account.\r\n\r\nSuch information may include the name, relationship, age or date of birth, mobile number (if applicable), and any other information necessary to complete the booking process or identify the appointment holder.\r\n\r\nUsers acknowledge that they are responsible for the accuracy of the information entered about family members or dependents and confirm that they have the legal authority to provide and use such data, especially when related to children or minors.\r\n\r\nFamily member data is used only as necessary to provide the application’s services such as account management, appointment booking, communication with healthcare providers, or handling complaints and feedback related to bookings.\r\n\r\n---\r\n\r\n## 4. Health and Medical Information\r\n\r\nThe Randevu Application may process certain health-related or medical information about users or their family members to the extent necessary for using the application’s services, such as booking appointments with hospitals, clinics, or doctors, or following up on complaints and feedback related to services.\r\n\r\nSuch information may include general details about the reason for booking, requested specialty, healthcare provider name, or any health notes entered by the user during booking or while submitting a complaint or message.\r\n\r\nUsers acknowledge that any health or medical information entered into the application must be accurate and that they are responsible for any information submitted by themselves or on behalf of family members or dependents.\r\n\r\nRandevu confirms that it does not use this information to provide medical diagnosis, treatment, or medical advice. Such information is used solely to facilitate booking and communication between users and healthcare providers in accordance with this Privacy Policy and applicable laws in the Kingdom of Saudi Arabia.\r\n\r\n---\r\n\r\n## 5. Booking and Appointment Information\r\n\r\nWhen using the Randevu Application to book an appointment, certain information may be collected and processed to complete and manage the booking, such as the user’s name or the family member’s name, selected healthcare provider, service type or specialty, appointment date and time, booking status, and any notes related to the appointment.\r\n\r\nBooking data is used to allow users to create, track, modify, or cancel appointments where such features are available, as well as to send notifications related to appointments.\r\n\r\nNecessary booking information may be shared with the selected hospital, clinic, doctor, or healthcare provider to confirm appointments, organize reception, or communicate with users regarding their bookings.\r\n\r\nUsers acknowledge that providing accurate booking information helps improve service quality and that inaccurate or incomplete information may result in appointment delays, booking errors, or inability to confirm appointments.\r\n\r\n---\r\n\r\n## 6. Complaints and Messages\r\n\r\nWhen using the complaints or messaging features within the Randevu Application, the application may collect and process information submitted by the user, such as complaint subject, details, associated healthcare provider, booking information (if applicable), and any attachments or additional information voluntarily provided.\r\n\r\nThis information is used for receiving complaints and feedback, reviewing them, communicating with users if needed, and coordinating with healthcare providers or relevant departments to improve user experience and address concerns.\r\n\r\nUsers must avoid including unnecessary information or information relating to third parties without authorization or consent and are responsible for the accuracy of submitted information.\r\n\r\nThe application may retain records of complaints and messages for a reasonable period when necessary to follow up on requests, protect rights, improve service quality, or comply with legal requirements.\r\n\r\n---\r\n\r\n## 7. Geolocation Information\r\n\r\nThe Randevu Application may request access to the user’s location to facilitate displaying nearby hospitals, clinics, or doctors and improve search results and services.\r\n\r\nLocation data is only used if the user grants permission through device settings, and users may disable or modify such permissions at any time.\r\n\r\nDisabling location access may reduce the accuracy of nearby service recommendations but does not necessarily prevent users from accessing other application services.\r\n\r\nRandevu processes location data only as necessary to provide services and improve user experience and does not use it for purposes beyond this policy unless required by law or authorized by the user.\r\n\r\n---\r\n\r\n## 8. Sharing Data with Hospitals, Doctors, and Service Providers\r\n\r\nThe Randevu Application may share certain user information, booking details, or family member data with the selected healthcare provider such as hospitals, clinics, or doctors to the extent necessary to complete bookings or provide services.\r\n\r\nShared information may include the user’s name, appointment holder information, contact number, booking details, selected provider, specialty, service type, and relevant notes.\r\n\r\nSuch sharing is solely for the purpose of fulfilling the user’s request and facilitating communication between users and healthcare providers. It does not mean that Randevu itself provides medical services or bears responsibility for diagnosis, treatment, or medical decisions made by healthcare providers.\r\n\r\nRandevu will not share user data with unrelated third parties except as necessary to provide services, with user consent, or as required by law in the Kingdom of Saudi Arabia.\r\n\r\n---\r\n\r\n## 9. Notifications\r\n\r\nThe Randevu Application may send notifications related to application usage such as booking confirmations, appointment reminders, appointment modifications or cancellations, responses to complaints or messages, or important updates regarding available services.\r\n\r\nNotifications may be delivered through the application, SMS, email, or other available communication methods based on the information provided by the user and granted permissions.\r\n\r\nUsers may manage certain notification settings through the application or device settings. Disabling notifications may result in missing important updates related to bookings or services.\r\n\r\nRandevu does not use users’ contact information to send unrelated promotional communications except as permitted by law or with the user’s consent where required.\r\n\r\n---\r\n\r\n## 10. Data Protection\r\n\r\nRandevu is committed to implementing appropriate technical and organizational measures to protect user data against unauthorized access, misuse, alteration, disclosure, loss, or damage within the limits of available technologies and service nature.\r\n\r\nProtective measures may include secure account management systems, restricted data access to authorized personnel only, and periodic security reviews.\r\n\r\nDespite these measures, users acknowledge that internet and application usage may involve technical or security risks beyond complete control. Users are therefore responsible for protecting their login credentials.\r\n\r\nIn the event of a security incident affecting user data, appropriate measures will be taken in accordance with applicable laws in the Kingdom of Saudi Arabia.\r\n\r\n---\r\n\r\n## 11. Data Retention Period\r\n\r\nRandevu retains user data for as long as necessary to fulfill the purposes for which it was collected, including account management, booking services, customer support, complaints handling, service improvement, and protection of rights.\r\n\r\nSome information may be retained longer when necessary for legal compliance, documentation, fraud prevention, dispute resolution, or legal claims.\r\n\r\nWhen data is no longer needed, it may be deleted, anonymized, or restricted according to appropriate technical procedures.\r\n\r\nRetention periods may vary depending on the type of data and applicable legal requirements in Saudi Arabia.\r\n\r\n---\r\n\r\n## 12. Account and Data Deletion\r\n\r\nUsers may request deletion of their Randevu account through the available application feature or by contacting the application administration via the official email address.\r\n\r\nUpon account deletion, access to the account and related services may be terminated, and associated data such as account information, family member data, bookings, messages, and complaints may be deleted or disabled according to technical procedures.\r\n\r\nHowever, some information may be retained where necessary for legal compliance, fraud prevention, dispute handling, or protection of rights.\r\n\r\nCertain backup copies or technical logs may not be deleted immediately, but access to them will be restricted in accordance with this policy and applicable laws.\r\n\r\n---\r\n\r\n## 13. User Rights\r\n\r\nIn accordance with applicable laws in the Kingdom of Saudi Arabia, users may request access to, correction of, updating, or deletion of their personal data where legally permitted.\r\n\r\nUsers may also object to data processing or request restrictions on data usage in cases permitted by law by contacting the application administration through approved communication channels.\r\n\r\nRandevu takes user requests seriously and may request verification information before processing requests to protect user data from unauthorized access.\r\n\r\nSome requests may not be fully or immediately executable where retaining or processing data remains legally necessary.\r\n\r\n---\r\n\r\n## 14. Children and Dependents Data\r\n\r\nThe Randevu Application allows users to add data related to their children, family members, or dependents to facilitate booking appointments and managing related services.\r\n\r\nUsers confirm that they are the parent, guardian, or legally authorized representative permitted to provide such data and that any legally required consents have been obtained.\r\n\r\nChildren’s or dependents’ data is used only to the extent necessary to provide services such as bookings, appointment management, communication, and complaint follow-up.\r\n\r\nUsers must not enter data relating to any child or dependent whom they are not authorized to represent and are fully responsible for the information they provide.\r\n\r\nRandevu handles such data with appropriate care in accordance with this Privacy Policy and applicable Saudi laws.\r\n\r\n---\r\n\r\n## 15. No In-App Payments\r\n\r\nThe Randevu Application currently does not provide electronic payment services or collect any financial payments through the application.\r\n\r\nThe application’s role is limited to facilitating the search for healthcare providers and appointment booking without processing payment information or bank card details.\r\n\r\nIf payment services are introduced in the future, this Privacy Policy and the Terms and Conditions will be updated accordingly before such services become available.\r\n\r\n---\r\n\r\n## 16. Updates to the Privacy Policy\r\n\r\nRandevu reserves the right to amend or update this Privacy Policy from time to time for operational, technical, legal, or service improvement reasons.\r\n\r\nIn the event of significant changes, the updated version will be published within the application or communicated through appropriate means.\r\n\r\nContinued use of the application after updates constitutes acceptance of the revised Privacy Policy. Users are advised to review this policy periodically.\r\n\r\n---\r\n\r\n## 17. Contact Us\r\n\r\nFor inquiries, requests, or complaints regarding this Privacy Policy or the handling of data within the Randevu Application, please contact us at:\r\n\r\n**[info@randevuksa.com](mailto:info@randevuksa.com)**\r\n', 'سياسة الخصوصية لتطبيق رنديفو\nنرحب بك في تطبيق رنديفو.\nنحن في تطبيق رنديفو نولي خصوصية المستخدمين وبياناتهم أهمية كبيرة وندرك أن استخدام تطبيق يرتبط بالخدمات الطبية والحجوزات قد يتضمن إدخال بيانات شخصية أو بيانات خاصة بأفراد الأسرة أو معلومات مرتبطة بالمواعيد ومقدمي الخدمات الطبية.\nتهدف هذه السياسة إلى توضيح كيفية جمع واستخدام وحفظ ومشاركة البيانات عند استخدامك لتطبيق رنديفو وذلك بما يتوافق مع الأنظمة المعمول بها في المملكة العربية السعودية.\nوباستخدامك للتطبيق أو إنشاء حساب أو إجراء حجز من خلاله فإنك تقر بأنك قرأت سياسة الخصوصية هذه وفهمت ما ورد فيها وتوافق على تعامل التطبيق مع بياناتك وفقًا لما هو موضح في هذه السياسة.\nإذا كنت لا توافق على هذه السياسة أو أي جزء منها فيرجى عدم استخدام التطبيق.\n1.البيانات التي يجمعها التطبيق\nقد يقوم تطبيق رنديفو بجمع بعض البيانات اللازمة لتقديم خدماته وتحسين تجربة المستخدم وذلك عند إنشاء الحساب أو استخدام التطبيق أو حجز موعد أو إضافة بيانات أحد أفراد الأسرة أو إرسال شكوى أو ملاحظة.\nوتشمل البيانات التي قد يجمعها التطبيق بحسب طريقة استخدامك له ما يلي:\n•	بيانات الحساب مثل الاسم، رقم الجوال، البريد الإلكتروني وكلمة المرور أو رمز التحقق.\n•	بيانات أفراد الأسرة أو التابعين الذين يضيفهم المستخدم داخل التطبيق.\n•	بيانات الحجز والمواعيد مثل مقدم الخدمة الطبية، تاريخ الموعد وقت الموعد وحالة الحجز.\n•	بيانات مرتبطة بمقدمي الخدمات الطبية الذين يتعامل معهم المستخدم من خلال التطبيق مثل المستشفى أو العيادة أو الطبيب الذي تم الحجز لديه.\n•	بيانات الشكاوى أو الرسائل أو الملاحظات التي يرسلها المستخدم من خلال التطبيق.\n•	بيانات الموقع الجغرافي إذا قام المستخدم بالسماح للتطبيق باستخدامها وذلك لتسهيل عرض المستشفيات أو العيادات أو الأطباء الأقرب إليه.\n•	بيانات فنية مرتبطة باستخدام التطبيق مثل نوع الجهاز، نظام التشغيل، عنوان بروتوكول الإنترنت IP وسجلات الاستخدام وذلك لأغراض الحماية وتحسين أداء التطبيق.\nوقد تختلف البيانات التي يتم جمعها بحسب الخدمات التي يستخدمها المستخدم داخل التطبيق وبحسب الصلاحيات التي يمنحها للتطبيق من خلال جهازه.\n2.بيانات الحساب\nعند إنشاء حساب في تطبيق رنديفو قد يُطلب من المستخدم تقديم بعض البيانات الأساسية اللازمة للتسجيل واستخدام خدمات التطبيق مثل الاسم، رقم الجوال، البريد الإلكتروني وكلمة المرور أو رمز التحقق وأي بيانات أخرى لازمة لتفعيل الحساب أو التحقق من هوية المستخدم.\nيتم استخدام بيانات الحساب لتمكين المستخدم من الدخول إلى التطبيق وإدارة حسابه وحجز المواعيد واستقبال التنبيهات أو الرسائل المتعلقة باستخدام التطبيق والتواصل معه عند الحاجة بشأن الحجوزات أو الشكاوى أو الدعم الفني.\nيلتزم المستخدم بتقديم بيانات صحيحة ومحدثة عند إنشاء الحساب كما يتحمل مسؤولية الحفاظ على سرية بيانات الدخول الخاصة به وعدم مشاركتها مع أي شخص آخر.\nوفي حال وجود أي تغيير في بيانات الحساب يُنصح المستخدم بتحديثها داخل التطبيق حتى يتمكن من الاستفادة من الخدمات بشكل صحيح ودون تأخير.\n3.بيانات أفراد الأسرة\nيتيح تطبيق رنديفو للمستخدم إمكانية إضافة بيانات أفراد أسرته أو التابعين له وذلك لتسهيل حجز المواعيد لهم وإدارة الحجوزات المرتبطة بهم من خلال حساب المستخدم.\nقد تشمل بيانات أفراد الأسرة بحسب ما يتيحه التطبيق الاسم، صلة القرابة، العمر أو تاريخ الميلاد، رقم الجوال إن وجد وأي بيانات أخرى لازمة لإتمام الحجز أو تمييز صاحب الموعد.\nيقر المستخدم بأنه مسؤول عن صحة البيانات التي يقوم بإدخالها عن أفراد أسرته أو التابعين له كما يقر بأنه يملك الحق أو الصفة النظامية التي تخوله إدخال هذه البيانات واستخدامها داخل التطبيق وخاصة إذا كانت البيانات تخص أطفالًا أو قُصّرًا.\nيتم استخدام بيانات أفراد الأسرة فقط بالقدر اللازم لتقديم خدمات التطبيق مثل إدارة الحساب، حجز المواعيد، تنظيم التواصل مع مقدم الخدمة الطبية أو متابعة الشكاوى والملاحظات المتعلقة بالحجز.\n4.البيانات الصحية والطبية\nقد يتعامل تطبيق رنديفو مع بعض البيانات المرتبطة بالحالة الصحية أو الطبية للمستخدم أو أحد أفراد أسرته وذلك في الحدود اللازمة لاستخدام خدمات التطبيق مثل حجز المواعيد لدى المستشفيات أو العيادات أو الأطباء أو متابعة الشكاوى والملاحظات المتعلقة بالخدمة.\nوقد تشمل هذه البيانات بحسب ما يتيحه التطبيق أو ما يقوم المستخدم بإدخاله معلومات عامة عن سبب الحجز، التخصص المطلوب، اسم مقدم الخدمة الطبية أو أي ملاحظات صحية يضيفها المستخدم عند الحجز أو عند إرسال شكوى أو رسالة من خلال التطبيق.\nيقر المستخدم بأن أي بيانات صحية أو طبية يقوم بإدخالها داخل التطبيق يجب أن تكون صحيحة ودقيقة وأنه يتحمل مسؤولية أي معلومات يقدمها بنفسه أو عن أحد أفراد أسرته أو التابعين له.\nويؤكد تطبيق رنديفو أنه لا يستخدم هذه البيانات لتقديم تشخيص طبي أو علاج أو استشارة طبية وإنما يتم استخدامها فقط لتسهيل الحجز وتنظيم التواصل بين المستخدم ومقدم الخدمة الطبية المختص وبما يتوافق مع سياسة الخصوصية والأنظمة المعمول بها في المملكة العربية السعودية.\n\n\n\n5.بيانات الحجز والمواعيد\nعند استخدام تطبيق رنديفو لحجز موعد قد يتم جمع ومعالجة بعض البيانات اللازمة لإتمام الحجز وتنظيمه مثل اسم المستخدم أو اسم فرد الأسرة صاحب الموعد، مقدم الخدمة الطبية المختار، نوع الخدمة أو التخصص، تاريخ الموعد وقت الموعد، حالة الحجز وأي ملاحظات مرتبطة بالموعد.\nتُستخدم بيانات الحجز والمواعيد لتمكين المستخدم من إنشاء الحجز متابعة حالته، تعديله أو إلغائه متى كانت هذه الخاصية متاحة وكذلك لإرسال الإشعارات أو التنبيهات المتعلقة بالموعد.\nوقد تتم مشاركة بيانات الحجز اللازمة مع المستشفى أو العيادة أو الطبيب أو مقدم الخدمة الطبية الذي اختاره المستخدم وذلك حتى يتمكن مقدم الخدمة من تأكيد الموعد أو تنظيم استقبال المستخدم أو التواصل معه بشأن الحجز عند الحاجة.\nويقر المستخدم بأن دقة بيانات الحجز تساعد على تقديم الخدمة بشكل أفضل وأن إدخال بيانات غير صحيحة أو ناقصة قد يؤدي إلى تعذر تأكيد الموعد أو تأخر تقديم الخدمة أو حدوث خطأ في بيانات الحجز.\n6.بيانات الشكاوى والرسائل\nعند استخدام المستخدم خاصية الشكاوى أو الرسائل داخل تطبيق رنديفو قد يقوم التطبيق بجمع ومعالجة البيانات التي يرسلها المستخدم ضمن الشكوى أو الرسالة مثل موضوع الشكوى، تفاصيلها، اسم مقدم الخدمة الطبية المرتبط بها، بيانات الحجز إن وجدت وأي معلومات أو مرفقات يضيفها المستخدم بنفسه.\nتُستخدم هذه البيانات لغرض استقبال الشكاوى والملاحظات، مراجعتها، التواصل مع المستخدم عند الحاجة ومتابعة الموضوع مع مقدم الخدمة الطبية أو الجهة المعنية داخل التطبيق وذلك بهدف تحسين تجربة المستخدم ومعالجة الملاحظات بالقدر الممكن.\nيلتزم المستخدم عند إرسال أي شكوى أو رسالة بعدم تضمين بيانات غير ضرورية أو معلومات تخص أشخاصًا آخرين دون حق أو موافقة كما يتحمل مسؤولية صحة ودقة المعلومات التي يقدمها من خلال هذه الخاصية.\nوقد يحتفظ التطبيق بسجل الشكاوى والرسائل لمدة مناسبة متى كان ذلك ضروريًا لمتابعة الطلبات أو حماية الحقوق أو تحسين جودة الخدمة أو الالتزام بأي متطلبات نظامية.\n7.بيانات الموقع الجغرافي\nقد يطلب تطبيق رنديفو إذن الوصول إلى الموقع الجغرافي للمستخدم وذلك لتسهيل عرض المستشفيات أو العيادات أو الأطباء الأقرب إليه أو تحسين نتائج البحث والخدمات المعروضة داخل التطبيق.\nلا يتم استخدام بيانات الموقع الجغرافي إلا إذا وافق المستخدم على منح التطبيق صلاحية الوصول إلى الموقع من خلال إعدادات جهازه ويمكن للمستخدم في أي وقت إيقاف هذه الصلاحية أو تعديلها من إعدادات الجهاز.\nقد يؤدي إيقاف صلاحية الموقع الجغرافي إلى عدم ظهور بعض الخدمات أو النتائج القريبة بشكل دقيق لكنه لا يمنع المستخدم بالضرورة من استخدام باقي خدمات التطبيق المتاحة.\nيتعامل تطبيق رنديفو مع بيانات الموقع الجغرافي بالقدر اللازم لتقديم الخدمة وتحسين تجربة المستخدم ولا يتم استخدامها لأغراض خارجة عن نطاق هذه السياسة إلا بموافقة المستخدم أو متى كان ذلك مطلوبًا نظامًا.\n\n8.مشاركة البيانات مع المستشفيات والأطباء ومقدمي الخدمة\nقد يشارك تطبيق رنديفو بعض بيانات المستخدم أو بيانات الحجز أو بيانات أفراد الأسرة مع مقدم الخدمة الطبية الذي يختاره المستخدم مثل المستشفى أو العيادة أو الطبيب وذلك بالقدر اللازم لإتمام الحجز أو تأكيد الموعد أو تنظيم تقديم الخدمة.\nقد تشمل البيانات التي تتم مشاركتها بحسب طبيعة الحجز اسم المستخدم أو اسم صاحب الموعد، رقم التواصل، بيانات الموعد، مقدم الخدمة المختار، التخصص أو نوع الخدمة وأي ملاحظات يضيفها المستخدم وتكون لازمة للتعامل مع الحجز.\nتتم هذه المشاركة فقط بهدف تنفيذ طلب المستخدم وتسهيل التواصل بينه وبين مقدم الخدمة الطبية ولا تعني مشاركة البيانات أن تطبيق رنديفو يقدم الخدمة الطبية بنفسه أو يتحمل مسؤولية التشخيص أو العلاج أو القرارات الطبية الصادرة من مقدم الخدمة.\nيلتزم تطبيق رنديفو بعدم مشاركة بيانات المستخدم مع أي طرف غير ذي علاقة إلا بالقدر اللازم لتقديم الخدمة أو بناءً على موافقة المستخدم أو تنفيذًا لمتطلب نظامي صادر من جهة مختصة داخل المملكة العربية السعودية.\n9.الإشعارات\nقد يرسل تطبيق رنديفو للمستخدم إشعارات أو تنبيهات مرتبطة باستخدام التطبيق مثل تأكيد الحجز، تذكير بالموعد، تعديل أو إلغاء الموعد، الرد على الشكاوى أو الرسائل أو أي تحديثات مهمة تتعلق بالخدمات المتاحة داخل التطبيق.\nوقد يتم إرسال هذه الإشعارات من خلال التطبيق نفسه أو عبر الرسائل النصية أو البريد الإلكتروني أو أي وسيلة تواصل أخرى يتيحها التطبيق وذلك بحسب البيانات التي يقدمها المستخدم والصلاحيات التي يمنحها للتطبيق.\nيمكن للمستخدم التحكم في بعض أنواع الإشعارات من خلال إعدادات التطبيق أو إعدادات الجهاز وقد يؤدي إيقاف بعض الإشعارات إلى عدم وصول تنبيهات مهمة متعلقة بالحجوزات أو استخدام الخدمات.\nولا يستخدم تطبيق رنديفو بيانات التواصل الخاصة بالمستخدم لإرسال رسائل غير مرتبطة بخدمات التطبيق إلا في الحدود المسموح بها نظامًا أو بعد الحصول على موافقة المستخدم متى كانت مطلوبة.\n10.حماية البيانات\nيلتزم تطبيق رنديفو باتخاذ التدابير الفنية والتنظيمية المناسبة لحماية بيانات المستخدمين من الوصول غير المصرح به أو الاستخدام أو التعديل أو الإفصاح أو الفقدان أو التلف وذلك في حدود الإمكانيات المتاحة وطبيعة الخدمات المقدمة من خلال التطبيق.\nوتشمل إجراءات الحماية بحسب ما يراه التطبيق مناسبًا استخدام وسائل آمنة لإدارة الحسابات، تقييد الوصول إلى البيانات على الأشخاص أو الجهات المصرح لهم فقط، ومراجعة إجراءات الحماية بشكل دوري لتحسين مستوى الأمان.\nورغم حرص تطبيق رنديفو على حماية البيانات فإن المستخدم يقر بأن استخدام التطبيقات والإنترنت لا يخلو من بعض المخاطر الفنية أو الأمنية الخارجة عن السيطرة الكاملة لذلك يلتزم المستخدم بالحفاظ على سرية بيانات دخوله وعدم مشاركتها مع أي طرف آخر.\nوفي حال حدوث أي واقعة أمنية تؤثر على بيانات المستخدمين فسيتم التعامل معها وفق الإجراءات المناسبة وبما يتوافق مع الأنظمة المعمول بها في المملكة العربية السعودية.\n11.مدة الاحتفاظ بالبيانات\nيحتفظ تطبيق رنديفو ببيانات المستخدمين للمدة اللازمة لتحقيق الأغراض التي جُمعت من أجلها مثل إنشاء الحساب، إدارة الحجوزات والمواعيد، تقديم الدعم، متابعة الشكاوى والرسائل، تحسين جودة الخدمة وحماية حقوق التطبيق أو المستخدم أو مقدمي الخدمات الطبية.\nوقد يتم الاحتفاظ ببعض البيانات لمدة أطول متى كان ذلك ضروريًا للالتزام بمتطلبات نظامية أو قانونية أو لأغراض التوثيق أو منع إساءة الاستخدام أو التعامل مع أي مطالبة أو نزاع قد ينشأ عن استخدام التطبيق.\nوعند انتهاء الغرض من الاحتفاظ بالبيانات أو عند عدم وجود حاجة مشروعة أو نظامية للاحتفاظ بها يقوم التطبيق بحذف البيانات أو إخفاء هويتها أو تعطيل استخدامها وفق الإجراءات الفنية المناسبة.\nوتختلف مدة الاحتفاظ بالبيانات بحسب نوع البيانات وطبيعة الخدمة المرتبطة بها والمتطلبات النظامية المعمول بها في المملكة العربية السعودية.\n12.حذف الحساب والبيانات\nيحق للمستخدم طلب حذف حسابه من تطبيق رنديفو من خلال الخاصية المتاحة داخل التطبيق أو عبر التواصل مع إدارة التطبيق من خلال البريد الإلكتروني المعتمد.\nعند حذف الحساب قد يتم إيقاف وصول المستخدم إلى حسابه والخدمات المرتبطة به وقد يتم حذف أو تعطيل البيانات المرتبطة بالحساب مثل بيانات الحساب، أفراد الأسرة، الحجوزات، الرسائل والشكاوى وذلك وفقًا للإجراءات الفنية المتبعة داخل التطبيق.\nومع ذلك قد يحتفظ التطبيق ببعض البيانات لمدة محددة إذا كان الاحتفاظ بها ضروريًا للالتزام بالأنظمة المعمول بها أو لحماية الحقوق أو متابعة الشكاوى أو منع الاحتيال وإساءة الاستخدام أو التعامل مع أي مطالبة أو نزاع.\nكما قد لا يتم حذف بعض البيانات فورًا من النسخ الاحتياطية أو السجلات الفنية إلا أنه سيتم التعامل معها بما يحد من استخدامها وبما يتوافق مع هذه السياسة والأنظمة المعمول بها في المملكة العربية السعودية.\n13.حقوق المستخدم\nيحق للمستخدم وفقًا للأنظمة المعمول بها في المملكة العربية السعودية أن يطلب الاطلاع على بياناته الشخصية المسجلة لدى تطبيق رنديفو أو تحديثها أو تصحيحها أو طلب حذفها متى كان ذلك ممكنًا ومسموحًا به نظامًا.\nكما يحق للمستخدم الاعتراض على معالجة بياناته أو طلب تقييد استخدامها في الحالات التي تجيزها الأنظمة وذلك من خلال التواصل مع إدارة التطبيق عبر وسائل التواصل المعتمدة.\nيتعامل تطبيق رنديفو مع طلبات المستخدمين المتعلقة ببياناتهم بجدية وقد يطلب من المستخدم تقديم بعض المعلومات اللازمة للتحقق من هويته قبل تنفيذ الطلب وذلك لحماية بياناته ومنع الوصول غير المصرح به.\nوقد يتعذر تنفيذ بعض الطلبات بشكل كامل أو فوري إذا كان الاحتفاظ بالبيانات أو استخدامها لازمًا للالتزام بمتطلبات نظامية أو قانونية أو لحماية الحقوق أو متابعة الشكاوى أو منع إساءة الاستخدام.\n14.بيانات الأطفال أو التابعين\nيتيح تطبيق رنديفو للمستخدم إضافة بيانات أطفاله أو أفراد أسرته أو التابعين له وذلك بهدف تسهيل حجز المواعيد لهم وإدارة الخدمات المرتبطة بهم من خلال حساب المستخدم.\nيقر المستخدم بأنه الولي أو الوصي أو صاحب الصفة النظامية التي تخوله إدخال بيانات الأطفال أو التابعين له داخل التطبيق كما يقر بأنه حصل على أي موافقات لازمة متى كانت مطلوبة نظامًا.\nتُستخدم بيانات الأطفال أو التابعين فقط بالقدر اللازم لتقديم خدمات التطبيق مثل إنشاء الحجز، إدارة الموعد، التواصل بشأن الخدمة أو متابعة الشكاوى والملاحظات المرتبطة بالحجز.\nيلتزم المستخدم بعدم إدخال بيانات أي طفل أو تابع لا يملك الحق في تمثيله أو إدارة بياناته ويتحمل المسؤولية عن أي بيانات يقوم بإدخالها أو استخدامها داخل التطبيق.\nويتعامل تطبيق رنديفو مع بيانات الأطفال أو التابعين بعناية مناسبة وبما يتوافق مع هذه السياسة والأنظمة المعمول بها في المملكة العربية السعودية.\n15.عدم وجود دفع داخل التطبيق\nلا يوفر تطبيق رنديفو حاليًا خدمة الدفع الإلكتروني أو تحصيل أي مبالغ مالية من المستخدمين من خلال التطبيق.\nويقتصر دور التطبيق على تسهيل البحث عن مقدمي الخدمات الطبية وحجز المواعيد لديهم دون معالجة أي بيانات دفع أو بطاقات بنكية داخل التطبيق.\nوفي حال تم تفعيل أي خدمة دفع مستقبلًا فسيتم تحديث سياسة الخصوصية والشروط والأحكام بما يوضح آلية الدفع والبيانات التي قد تتم معالجتها والجهات ذات العلاقة وذلك قبل أو عند إتاحة هذه الخدمة للمستخدمين.\n16.تحديثات سياسة الخصوصية\nيحق لإدارة تطبيق رنديفو تعديل أو تحديث سياسة الخصوصية هذه من وقت لآخر متى دعت الحاجة إلى ذلك سواء لأسباب تشغيلية أو فنية أو قانونية أو لتحسين الخدمات المقدمة من خلال التطبيق.\nوفي حال إجراء أي تعديل جوهري على هذه السياسة سيتم نشر النسخة المحدثة داخل التطبيق أو إتاحتها للمستخدم بأي وسيلة مناسبة.\nويُعد استمرار المستخدم في استخدام التطبيق بعد نشر التحديثات موافقة منه على سياسة الخصوصية المعدلة لذلك يُنصح المستخدم بمراجعة هذه السياسة من وقت لآخر للاطلاع على أي تحديثات قد تطرأ عليها.\n17.التواصل معنا\nلأي استفسارات أو طلبات أو شكاوى تتعلق بسياسة الخصوصية أو بطريقة جمع أو استخدام أو حفظ البيانات داخل تطبيق رنديفو يمكن التواصل مع إدارة التطبيق عبر البريد الإلكتروني التالي:\ninfo@randevuksa.com', NULL, 'privacy', NULL, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(7, 'terms Doctor', 'شروط واحكام تطبيق الاطباء', 'I acknowledge that the scheduled appointment time represents the patient’s arrival time at the clinic and registration at the reception desk, and does not necessarily represent the actual consultation time with the doctor.\n\nThe consultation time may be slightly delayed or brought forward depending on workflow conditions, emergency cases, and clinic scheduling arrangements.\n\nPatients are kindly requested to arrive 10–15 minutes before the appointment time to complete the necessary procedures.\n\nConfirmation of the appointment shall be deemed acceptance of these terms and arrangements.', 'أقرّ بأن الوقت المحدد في الحجز هو وقت حضور المريض إلى العيادة والتسجيل لدى الاستقبال، وليس بالضرورة وقت الدخول الفعلي على الطبيب.\nوقد يطرأ تأخير أو تقديم بسيط على موعد الدخول للطبيب بحسب ظروف العمل وعدد الحالات الطارئة والتنظيم داخل العيادة.\nويُرجى من المريض الحضور قبل الموعد بـ 10–15 دقيقة لاستكمال الإجراءات اللازمة.\nويُعد تأكيد الحجز موافقة على هذه الشروط والتنظيمات.', NULL, 'terms', 3, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(8, 'About Us Doctors', 'عن تطبيق الطبيب', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'about', 3, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(9, 'Privacy Policy Doctor App', 'سياسة الخصوصية الخاصة بتطبيق الطبيب', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '\nنحن في رنديفو نحترم خصوصيتك\nنقوم بجمع بيانات مثل (الاسم، رقم الهاتف، البريد الإلكتروني)\nيتم حفظ البيانات الطبية بشكل آمن وسري\nنستخدم البيانات لتحسين تجربة المستخدم\nلا نقوم ببيع بيانات المستخدمين لأي طرف ثالث\nيمكن للمستخدم طلب حذف بياناته في أي وقت\nنستخدم وسائل حماية متقدمة للحفاظ على المعلومات', NULL, 'privacy', 3, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(10, 'terms Pharmacy', 'شروط واحكام تطبيق الصيدلية', 'By using the Rendezvous app, you agree to the following:\n\nAccurate and complete information must be entered during registration.\n\nThe app is not for use in medical emergencies.\nAll appointments are subject to doctor availability.\nThe user is responsible for maintaining the confidentiality of their account.\nThe app reserves the right to suspend the account in case of misuse.\nPayments (if any) are non-refundable except in specific circumstances.\nThe app reserves the right to modify the terms and conditions at any time.', '\nباستخدامك لتطبيق رانديفو، فإنك توافق على ما يلي\nيجب إدخال بيانات صحيحة وكاملة عند التسجيل\nالتطبيق لا يُستخدم في حالات الطوارئ الطبية\nجميع المواعيد تعتمد على توفر الأطباء\nالمستخدم مسؤول عن الحفاظ على سرية حسابه\nيحق للتطبيق إيقاف الحساب في حال إساءة الاستخدام\nالمدفوعات (إن وجدت) غير قابلة للاسترداد إلا في حالات محددة\nيحق للتطبيق تعديل الشروط في أي وقت', NULL, 'terms', 5, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(11, 'About Us pharmacy', 'عن تطبيق الصيدلى', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'about', 5, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(12, 'Privacy Policy Pharmacy App', 'سياسة الخصوصية الخاصة بتطبيق الصيدلى', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '\nنحن في رنديفو نحترم خصوصيتك\nنقوم بجمع بيانات مثل (الاسم، رقم الهاتف، البريد الإلكتروني)\nيتم حفظ البيانات الطبية بشكل آمن وسري\nنستخدم البيانات لتحسين تجربة المستخدم\nلا نقوم ببيع بيانات المستخدمين لأي طرف ثالث\nيمكن للمستخدم طلب حذف بياناته في أي وقت\nنستخدم وسائل حماية متقدمة للحفاظ على المعلومات', NULL, 'privacy', 5, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(13, 'terms Lab', 'شروط واحكام تطبيق المعمل', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'terms', 25, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(14, 'About Us Lab', 'عن تطبيق المعمل ', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'about', 25, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(15, 'Privacy Policy Lab App', 'سياسة الخصوصية الخاصة بتطبيق المعمل', 'At Rendezvous, we respect your privacy.\n\nWe collect data such as (name, phone number, email address).\n\nMedical data is stored securely and confidentially.\n\nWe use data to improve the user experience.\nWe do not sell user data to any third party.\nUsers can request the deletion of their data at any time.\nWe use advanced security measures to protect information.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'privacy', 25, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(16, 'terms Rays', 'شروط واحكام تطبيق الاشعة', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'terms', 26, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(17, 'About Us Rays', 'عن تطبيق الاشعة', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'لوريم إيبسوم هو ببساطة نص شكلي يستخدم في صناعة الطباعة والتنضيد. كان Lorem Ipsum هو النص الوهمي القياسي في الصناعة منذ القرن الخامس عشر الميلادي ، عندما أخذت طابعة غير معروفة لوحًا من النوع وتدافعت عليه لعمل كتاب عينة. لقد صمد ليس فقط لخمسة قرون ، ولكن أيضًا القفزة في التنضيد الإلكتروني ، وظل دون تغيير جوهري. تم نشره في الستينيات من القرن الماضي مع إصدار أوراق Letraset التي تحتوي على مقاطع Lorem Ipsum ، ومؤخرًا مع برامج النشر المكتبي مثل Aldus PageMaker بما في ذلك إصدارات Lorem Ipsum.', NULL, 'about', 26, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15'),
(18, 'Privacy Policy Rays App', 'سياسة الخصوصية الخاصة بتطبيق الاشعة', 'At Rendezvous, we respect your privacy.\n\nWe collect data such as (name, phone number, email address).\n\nMedical data is stored securely and confidentially.\n\nWe use data to improve the user experience.\nWe do not sell user data to any third party.\nUsers can request the deletion of their data at any time.\nWe use advanced security measures to protect information.', '\nنحن في رنديفو نحترم خصوصيتك\nنقوم بجمع بيانات مثل (الاسم، رقم الهاتف، البريد الإلكتروني)\nيتم حفظ البيانات الطبية بشكل آمن وسري\nنستخدم البيانات لتحسين تجربة المستخدم\nلا نقوم ببيع بيانات المستخدمين لأي طرف ثالث\nيمكن للمستخدم طلب حذف بياناته في أي وقت\nنستخدم وسائل حماية متقدمة للحفاظ على المعلومات', NULL, 'privacy', 26, 1, '2022-07-14 15:41:15', '2022-07-14 15:41:15');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_type` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `minute_allow_delay` int NOT NULL DEFAULT '5',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 shift , 0 off',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `account_type`, `clinic_id`, `name`, `time_from`, `time_to`, `minute_allow_delay`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(54, 3, 1, 'شيفت الدكتور مسائي', '14:00:00', '22:00:00', 15, 1, NULL, '2025-12-17 13:09:50', '2026-02-15 15:37:32'),
(55, 3, 1, 'شيفت الدكتور صباحي', '09:00:00', '17:00:00', 15, 1, NULL, '2025-12-22 09:21:15', '2026-02-15 15:24:45'),
(56, 3, 1, 'شيفت الدكتور بياتي', '22:00:00', '09:00:00', 15, 1, NULL, '2025-12-22 09:34:00', '2026-02-15 16:01:39'),
(57, 2, 1, 'شيفت الاستقبال صباحي', '09:00:00', '17:00:00', 15, 1, NULL, '2026-02-15 15:18:55', '2026-02-15 15:18:55'),
(58, 2, 1, 'شيفت الاستقبال مسائي', '14:00:00', '22:00:00', 15, 1, NULL, '2026-02-15 15:19:51', '2026-02-15 15:19:51'),
(59, 2, 1, 'شيفت الاستقبال بياتي', '22:00:00', '09:00:00', 15, 1, NULL, '2026-02-15 15:20:27', '2026-05-13 21:35:20'),
(60, 3, 1, 'شيفت 24', '00:01:00', '23:59:00', 15, 1, NULL, '2026-02-15 16:15:16', '2026-02-15 16:24:55'),
(61, 3, 1, 'طوارء', '06:00:00', '18:00:00', 10, 1, NULL, '2026-03-31 08:14:11', '2026-04-28 21:22:57'),
(62, 3, 1, 'شيفت الدكتور', '08:08:00', '16:09:00', 15, 1, NULL, '2026-05-01 23:09:09', '2026-07-08 13:22:53'),
(65, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:02:55', '2026-06-29 13:02:55'),
(66, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:02:56', '2026-06-29 13:02:56'),
(67, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:06:06', '2026-06-29 13:06:06'),
(68, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:06:07', '2026-06-29 13:06:07'),
(69, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:10:42', '2026-06-29 13:10:42'),
(70, 3, 1, 'صباحي', '08:00:00', '11:00:00', 5, 1, NULL, '2026-06-29 13:10:44', '2026-06-29 13:10:44'),
(74, 3, 243, 'صباحي', '07:00:00', '11:00:00', 5, 1, NULL, '2026-07-04 13:07:26', '2026-07-06 14:40:05'),
(75, 3, 243, 'مسائي', '12:05:00', '23:11:00', 5, 1, NULL, '2026-07-04 13:07:51', '2026-07-06 14:40:37'),
(76, 3, 1, 'Main', '10:00:00', '18:00:00', 5, 1, NULL, '2026-07-06 15:59:14', '2026-07-06 15:59:14'),
(77, 3, 1, 'صباحي', '07:57:00', '19:57:00', 5, 1, NULL, '2026-07-18 04:57:50', '2026-07-18 04:57:50');

-- --------------------------------------------------------

--
-- Table structure for table `shift_dates`
--

CREATE TABLE `shift_dates` (
  `id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 shift , 0 off',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_employees`
--

CREATE TABLE `shift_employees` (
  `id` bigint UNSIGNED NOT NULL,
  `account_type` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `day_id` bigint DEFAULT NULL,
  `dateA` date DEFAULT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `checkin_another_employee` bigint UNSIGNED DEFAULT NULL,
  `checkout_another_employee` bigint UNSIGNED DEFAULT NULL,
  `attendance_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_delay_minute` int DEFAULT NULL,
  `total_extra_minute` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 shift , 0 off , 2 absence',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shift_employees`
--

INSERT INTO `shift_employees` (`id`, `account_type`, `clinic_id`, `employee_id`, `shift_id`, `day_id`, `dateA`, `check_in`, `check_out`, `checkin_another_employee`, `checkout_another_employee`, `attendance_status`, `total_delay_minute`, `total_extra_minute`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 211, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(2, 3, 1, 211, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(3, 3, 1, 211, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(4, 3, 1, 211, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(5, 3, 1, 211, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(6, 3, 1, 211, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 10:58:16', '2026-02-16 10:58:16'),
(7, 3, 1, 211, NULL, NULL, '2026-02-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-02-16 10:59:01', '2026-02-16 10:59:01'),
(8, 3, 1, 210, 54, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 17:22:42', '2026-02-16 17:22:42'),
(9, 3, 1, 210, 54, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 17:22:42', '2026-02-16 17:22:42'),
(10, 3, 1, 210, 54, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 17:22:42', '2026-02-16 17:22:42'),
(11, 3, 1, 210, 54, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 17:22:42', '2026-02-16 17:22:42'),
(12, 3, 1, 210, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-16 17:25:10', '2026-02-16 17:25:10'),
(14, 3, 1, 211, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:01:23', '2026-02-25 22:01:23'),
(15, 3, 1, 211, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:01:23', '2026-02-25 22:01:23'),
(16, 3, 1, 211, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:01:23', '2026-02-25 22:01:23'),
(17, 3, 1, 211, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:01:23', '2026-02-25 22:01:23'),
(18, 3, 1, 202, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:03:23', '2026-02-25 22:03:23'),
(19, 3, 1, 202, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:03:23', '2026-02-25 22:03:23'),
(20, 3, 1, 202, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:03:23', '2026-02-25 22:03:23'),
(21, 3, 1, 202, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:03:23', '2026-02-25 22:03:23'),
(22, 3, 1, 202, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:03:23', '2026-02-25 22:03:23'),
(23, 3, 1, 210, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:12:26', '2026-02-25 22:12:26'),
(24, 3, 1, 210, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:12:26', '2026-02-25 22:12:26'),
(25, 3, 1, 210, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:12:26', '2026-02-25 22:12:26'),
(26, 3, 1, 210, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:12:26', '2026-02-25 22:12:26'),
(27, 3, 1, 210, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-02-25 22:12:26', '2026-02-25 22:12:26'),
(28, 3, 1, 211, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(29, 3, 1, 211, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(30, 3, 1, 211, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(31, 3, 1, 211, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(32, 3, 1, 211, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(33, 3, 1, 211, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(34, 3, 1, 211, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:31:33', '2026-03-25 14:31:33'),
(35, 3, 1, 210, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(36, 3, 1, 210, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(37, 3, 1, 210, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(38, 3, 1, 210, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(39, 3, 1, 210, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(40, 3, 1, 210, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(41, 3, 1, 210, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:04', '2026-03-25 14:33:04'),
(42, 3, 1, 209, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(43, 3, 1, 209, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(44, 3, 1, 209, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(45, 3, 1, 209, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(46, 3, 1, 209, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(47, 3, 1, 209, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(48, 3, 1, 209, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:27', '2026-03-25 14:33:27'),
(49, 3, 1, 209, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(50, 3, 1, 209, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(51, 3, 1, 209, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(52, 3, 1, 209, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(53, 3, 1, 209, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(54, 3, 1, 209, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(55, 3, 1, 209, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:33:47', '2026-03-25 14:33:47'),
(56, 3, 1, 208, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(57, 3, 1, 208, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(58, 3, 1, 208, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(59, 3, 1, 208, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(60, 3, 1, 208, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(61, 3, 1, 208, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(62, 3, 1, 208, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:10', '2026-03-25 14:34:10'),
(63, 3, 1, 208, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(64, 3, 1, 208, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(65, 3, 1, 208, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(66, 3, 1, 208, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(67, 3, 1, 208, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(68, 3, 1, 208, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(69, 3, 1, 208, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:29', '2026-03-25 14:34:29'),
(70, 3, 1, 207, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(71, 3, 1, 207, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(72, 3, 1, 207, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(73, 3, 1, 207, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(74, 3, 1, 207, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(75, 3, 1, 207, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(76, 3, 1, 207, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:34:53', '2026-03-25 14:34:53'),
(77, 3, 1, 206, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(78, 3, 1, 206, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(79, 3, 1, 206, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(80, 3, 1, 206, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(81, 3, 1, 206, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(82, 3, 1, 206, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(83, 3, 1, 206, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:29', '2026-03-25 14:35:29'),
(84, 3, 1, 205, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(85, 3, 1, 205, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(86, 3, 1, 205, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(87, 3, 1, 205, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(88, 3, 1, 205, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(89, 3, 1, 205, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(90, 3, 1, 205, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:35:52', '2026-03-25 14:35:52'),
(91, 3, 1, 202, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(92, 3, 1, 202, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(93, 3, 1, 202, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(94, 3, 1, 202, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(95, 3, 1, 202, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(96, 3, 1, 202, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(97, 3, 1, 202, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:23', '2026-03-25 14:36:23'),
(98, 3, 1, 203, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(99, 3, 1, 203, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(100, 3, 1, 203, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(101, 3, 1, 203, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(102, 3, 1, 203, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(103, 3, 1, 203, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(104, 3, 1, 203, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:36:49', '2026-03-25 14:36:49'),
(105, 3, 1, 204, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(106, 3, 1, 204, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(107, 3, 1, 204, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(108, 3, 1, 204, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(109, 3, 1, 204, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(110, 3, 1, 204, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(111, 3, 1, 204, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-25 14:37:21', '2026-03-25 14:37:21'),
(112, 3, 1, 209, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(113, 3, 1, 209, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(114, 3, 1, 209, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(115, 3, 1, 209, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(116, 3, 1, 209, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(117, 3, 1, 209, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(118, 3, 1, 209, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-26 21:40:38', '2026-03-26 21:40:38'),
(119, 3, 1, 211, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(120, 3, 1, 211, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(121, 3, 1, 211, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(122, 3, 1, 211, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(123, 3, 1, 211, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(124, 3, 1, 211, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(125, 3, 1, 211, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:39:39', '2026-03-31 21:39:39'),
(126, 3, 1, 210, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(127, 3, 1, 210, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(128, 3, 1, 210, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(129, 3, 1, 210, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(130, 3, 1, 210, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(131, 3, 1, 210, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(132, 3, 1, 210, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:39', '2026-03-31 21:40:39'),
(133, 3, 1, 209, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(134, 3, 1, 209, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(135, 3, 1, 209, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(136, 3, 1, 209, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(137, 3, 1, 209, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(138, 3, 1, 209, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(139, 3, 1, 209, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(140, 3, 1, 209, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(141, 3, 1, 209, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(142, 3, 1, 209, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(143, 3, 1, 209, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(144, 3, 1, 209, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(145, 3, 1, 209, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(146, 3, 1, 209, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:40:59', '2026-03-31 21:40:59'),
(147, 3, 1, 208, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(148, 3, 1, 208, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(149, 3, 1, 208, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(150, 3, 1, 208, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(151, 3, 1, 208, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(152, 3, 1, 208, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(153, 3, 1, 208, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:15', '2026-03-31 21:41:15'),
(154, 3, 1, 207, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(155, 3, 1, 207, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(156, 3, 1, 207, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(157, 3, 1, 207, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(158, 3, 1, 207, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(159, 3, 1, 207, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:41:49', '2026-03-31 21:41:49'),
(160, 3, 1, 206, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(161, 3, 1, 206, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(162, 3, 1, 206, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(163, 3, 1, 206, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(164, 3, 1, 206, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(165, 3, 1, 206, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(166, 3, 1, 206, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:08', '2026-03-31 21:42:08'),
(167, 3, 1, 205, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(168, 3, 1, 205, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(169, 3, 1, 205, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(170, 3, 1, 205, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(171, 3, 1, 205, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(172, 3, 1, 205, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(173, 3, 1, 205, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:37', '2026-03-31 21:42:37'),
(174, 3, 1, 204, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(175, 3, 1, 204, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(176, 3, 1, 204, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(177, 3, 1, 204, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(178, 3, 1, 204, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(179, 3, 1, 204, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(180, 3, 1, 204, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:42:56', '2026-03-31 21:42:56'),
(181, 3, 1, 203, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(182, 3, 1, 203, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(183, 3, 1, 203, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(184, 3, 1, 203, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(185, 3, 1, 203, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(186, 3, 1, 203, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(187, 3, 1, 203, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:18', '2026-03-31 21:43:18'),
(188, 3, 1, 202, 60, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(189, 3, 1, 202, 60, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(190, 3, 1, 202, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(191, 3, 1, 202, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(192, 3, 1, 202, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(193, 3, 1, 202, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(194, 3, 1, 202, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-31 21:43:40', '2026-03-31 21:43:40'),
(197, 3, 1, 211, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-02 22:23:23', '2026-04-02 22:23:23'),
(198, 3, 1, 211, 60, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-02 22:23:23', '2026-04-02 22:23:23'),
(199, 3, 1, 211, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-02 22:23:23', '2026-04-02 22:23:23'),
(200, 3, 1, 211, 60, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-02 22:23:23', '2026-04-02 22:23:23'),
(201, 3, 1, 211, 60, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-02 22:23:23', '2026-04-02 22:23:23'),
(202, 3, 1, 215, 62, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-01 23:11:16', '2026-05-01 23:11:16'),
(203, 3, 1, 215, 62, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-01 23:11:16', '2026-05-01 23:11:16'),
(204, 3, 1, 215, 62, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-01 23:11:16', '2026-05-01 23:11:16'),
(205, 3, 1, 215, 62, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-01 23:11:16', '2026-05-01 23:11:16'),
(206, 3, 1, 215, 62, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-01 23:11:16', '2026-05-01 23:11:16'),
(214, 3, 1, 215, 54, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-16 21:30:03', '2026-05-16 21:30:03'),
(216, 3, 1, 215, 61, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-20 12:31:52', '2026-05-20 12:31:52'),
(217, 3, 1, 215, 54, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 12:50:54', '2026-05-22 12:50:54'),
(218, 3, 1, 213, 54, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 12:52:48', '2026-05-22 12:52:48'),
(219, 3, 1, 213, 54, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 12:52:48', '2026-05-22 12:52:48'),
(220, 3, 1, 213, 55, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 12:53:00', '2026-05-22 12:53:00'),
(221, 3, 1, 213, 55, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 12:53:00', '2026-05-22 12:53:00'),
(224, 3, 1, 213, 57, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-30 20:21:44', '2026-05-30 20:21:44'),
(230, 3, 1, 227, 55, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(231, 3, 1, 227, 55, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(232, 3, 1, 227, 55, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(233, 3, 1, 227, 55, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(234, 3, 1, 227, 55, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(235, 3, 1, 227, 55, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-01 14:57:35', '2026-06-01 14:57:35'),
(243, 3, 243, 245, 75, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-04 13:08:34', '2026-07-04 13:08:34'),
(244, 3, 243, 245, 75, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-04 13:08:34', '2026-07-04 13:08:34'),
(245, 3, 243, 245, 75, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-04 13:08:34', '2026-07-04 13:08:34'),
(246, 3, 243, 245, 74, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-04 13:08:52', '2026-07-04 13:08:52'),
(247, 3, 243, 245, 74, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-04 13:08:52', '2026-07-04 13:08:52'),
(248, 3, 1, 235, 62, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(249, 3, 1, 235, 62, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(250, 3, 1, 235, 62, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(251, 3, 1, 235, 62, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(252, 3, 1, 235, 62, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(253, 3, 1, 235, 62, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-05 14:36:21', '2026-07-05 14:36:21'),
(254, 3, 243, 246, 74, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:23', '2026-07-06 14:23:23'),
(255, 3, 243, 246, 74, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:23', '2026-07-06 14:23:23'),
(256, 3, 243, 246, 74, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:23', '2026-07-06 14:23:23'),
(257, 3, 243, 246, 75, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:43', '2026-07-06 14:23:43'),
(258, 3, 243, 246, 75, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:43', '2026-07-06 14:23:43'),
(259, 3, 243, 246, 75, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:23:43', '2026-07-06 14:23:43'),
(260, 3, 243, 246, 75, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 14:44:27', '2026-07-06 14:44:27'),
(261, 3, 1, 234, 76, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:00:12', '2026-07-06 16:00:12'),
(262, 3, 1, 234, 76, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:00:12', '2026-07-06 16:00:12'),
(263, 3, 1, 234, 70, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:27:01', '2026-07-06 16:27:01'),
(264, 3, 1, 234, 70, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:27:01', '2026-07-06 16:27:01'),
(265, 3, 1, 234, 70, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:27:01', '2026-07-06 16:27:01'),
(266, 3, 1, 234, 70, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:27:01', '2026-07-06 16:27:01'),
(267, 3, 1, 234, 70, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 16:27:01', '2026-07-06 16:27:01'),
(268, 3, 1, 235, 70, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 17:43:56', '2026-07-06 17:43:56'),
(269, 3, 1, 235, 70, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-06 17:43:56', '2026-07-06 17:43:56'),
(270, 3, 1, 235, 68, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-07 04:57:18', '2026-07-07 04:57:18'),
(271, 3, 1, 235, 70, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-07 05:35:10', '2026-07-07 05:35:10'),
(272, 3, 1, 235, 54, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-07 08:41:18', '2026-07-07 08:41:18'),
(273, 3, 1, 235, 60, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-08 17:06:40', '2026-07-08 17:06:40'),
(274, 3, 1, 235, 60, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-12 09:24:37', '2026-07-12 09:24:37'),
(275, 3, 1, 235, 61, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-07-12 12:13:41', '2026-07-12 12:13:41'),
(276, 3, 1, 235, NULL, NULL, '2026-07-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-12 18:09:35', '2026-07-12 18:09:35'),
(277, 3, 1, 235, NULL, NULL, '2026-07-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-12 18:09:35', '2026-07-12 18:09:35'),
(278, 3, 1, 234, NULL, NULL, '2026-07-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-13 12:47:28', '2026-07-13 12:47:28'),
(279, 3, 1, 234, NULL, NULL, '2026-07-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-13 12:47:28', '2026-07-13 12:47:28'),
(280, 3, 1, 234, NULL, NULL, '2026-07-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-29 12:55:33', '2026-07-29 12:55:33'),
(281, 3, 1, 234, NULL, NULL, '2026-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-07-29 12:55:33', '2026-07-29 12:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `sick_leaves`
--

CREATE TABLE `sick_leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED NOT NULL,
  `works` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Workplace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sick_days` int NOT NULL DEFAULT '1',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `medical_company` tinyint NOT NULL DEFAULT '0',
  `impossible_treat` tinyint NOT NULL DEFAULT '0',
  `physician_leave` tinyint NOT NULL DEFAULT '0',
  `Diagnosis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `directed_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_date` date NOT NULL,
  `companion_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relation_patient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Workplaces` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `companion_sick_days` int DEFAULT NULL,
  `companion_from_date` date DEFAULT NULL,
  `companion_to_date` date DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1 sick_leave , 2 companion_sick_leave',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `name_en`, `name_ar`, `status`, `parent_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Nutritionist', 'التغذية', 1, NULL, 189, NULL, '2022-07-13 14:44:45', '2026-02-15 14:20:26'),
(2, 'Psychiatrist', 'الطب النفسي', 1, NULL, 189, NULL, '2022-07-13 14:44:45', '2026-02-15 14:20:05'),
(3, 'Brain and nerve diseases', 'العظام', 1, NULL, 189, NULL, '2022-07-13 14:44:45', '2026-02-15 14:19:49'),
(4, 'Oncology', 'طب الأورام', 1, NULL, 189, NULL, '2022-07-13 14:44:45', '2026-05-07 21:33:26'),
(5, 'Clinical Nutritionist', 'تغذية علاجية', 1, 1, 189, NULL, '2022-07-13 17:44:45', '2026-02-15 14:45:09'),
(6, 'General Psychiatrist', 'الطب النفسي العام', 1, 2, 189, NULL, '2022-07-13 17:44:45', '2026-05-07 21:35:15'),
(7, 'Orthopedic Specialist', 'دكتور عظام', 1, 3, 189, NULL, '2022-07-13 17:44:45', '2026-02-15 14:56:20'),
(8, 'Medical Oncology', 'الأورام الطبية', 1, 4, 189, NULL, '2022-07-13 17:44:45', '2026-05-07 21:34:00'),
(9, 'new specialist', 'تخصص جديد', 0, NULL, 189, '2024-12-19 12:11:06', '2024-12-19 12:07:11', '2024-12-19 12:11:06'),
(10, 'new specialist', 'تخصص جديد', 1, 8, 189, NULL, '2024-12-19 14:06:43', '2024-12-19 14:06:43'),
(12, 'new specialistw', 'تخصص جديدw', 1, NULL, 189, '2025-07-04 04:30:33', '2025-07-03 12:27:55', '2025-07-04 04:30:33'),
(13, 'new specialist', 'تخصص جديد', 1, NULL, 189, '2025-07-04 05:07:51', '2025-07-04 05:07:29', '2025-07-04 05:07:51'),
(14, 'new specialiste', 'تخصص جديدe', 1, 4, 189, '2025-07-04 08:45:37', '2025-07-04 08:45:09', '2025-07-04 08:45:37'),
(15, 'Pediatrics & Neonatology', 'الأطفال وحديثي الولادة', 1, NULL, 189, NULL, '2025-12-22 10:02:19', '2026-02-15 14:19:06'),
(16, 'Pediatric Cardiology', 'أمراض القلب عند الأطفال', 1, 15, 189, NULL, '2025-12-22 10:04:12', '2026-05-07 21:18:08'),
(17, 'ENT', 'الأنف والأذن والحنجرة', 1, NULL, 189, NULL, '2026-02-15 14:21:01', '2026-02-15 14:21:01'),
(18, 'Ophthalmology', 'طب العيون', 1, NULL, 189, NULL, '2026-02-15 14:21:34', '2026-05-07 21:25:20'),
(19, 'Dermatologist', 'الجلدية والتجميل', 1, NULL, 189, NULL, '2026-02-15 14:21:50', '2026-02-15 14:21:50'),
(20, 'General Surgery', 'الجراحة العامة', 1, NULL, 189, NULL, '2026-02-15 14:22:09', '2026-05-07 21:14:16'),
(21, 'Internal Medicine', 'الطب الباطني', 1, NULL, 189, NULL, '2026-02-15 14:22:25', '2026-05-07 21:10:50'),
(22, 'Obstetrics & Gynecology', 'النساء والتوليد', 1, NULL, 189, NULL, '2026-02-15 14:22:49', '2026-02-15 14:22:49'),
(23, 'Neurology', 'الأعصاب', 1, NULL, 189, NULL, '2026-02-15 14:23:14', '2026-02-15 14:23:14'),
(24, 'Cardiologist', 'القلب', 1, NULL, 189, '2026-05-17 13:19:48', '2026-02-15 14:23:29', '2026-05-17 13:19:48'),
(25, 'Dentist', 'طب الأسنان', 1, NULL, 189, NULL, '2026-02-15 14:23:42', '2026-05-07 21:31:02'),
(26, 'orthodontics', 'تقويم الأسنان', 1, 25, 189, NULL, '2026-02-15 14:25:03', '2026-02-15 14:25:03'),
(27, 'Oral & Maxillofacial Surgeon', 'جراحة الفم والفكين', 1, 25, 189, NULL, '2026-02-15 14:25:17', '2026-05-07 21:31:44'),
(28, 'Prosthodontics', 'تركيبات الأسنان / الترميمات السنية', 1, 25, 189, NULL, '2026-02-15 14:25:32', '2026-05-07 21:32:25'),
(29, 'Dental Implantologist', 'زراعة أسنان', 1, 25, 189, NULL, '2026-02-15 14:25:48', '2026-02-15 14:25:48'),
(30, 'Endodontist', 'حشو عصب', 1, 25, 189, NULL, '2026-02-15 14:26:04', '2026-02-15 14:26:04'),
(31, 'Cosmetic Dentistry', 'طب أسنان تجميلي', 1, 25, 189, NULL, '2026-02-15 14:26:21', '2026-05-07 21:32:42'),
(32, 'Cardiologist', 'أمراض قلب', 1, 24, 189, NULL, '2026-02-15 14:27:31', '2026-02-15 14:27:31'),
(33, 'Pediatric Cardiologist', 'قلب أطفال', 1, 24, 189, NULL, '2026-02-15 14:27:45', '2026-02-15 14:27:45'),
(34, 'Interventional Cardiologist', 'قسطرة قلبية', 1, 24, 189, NULL, '2026-02-15 14:28:13', '2026-02-15 14:28:13'),
(35, 'Electrophysiologist', 'كهربية القلب', 1, 24, 189, NULL, '2026-02-15 14:28:27', '2026-02-15 14:28:27'),
(36, 'Heart Failure Specialist', 'قصور عضلة القلب', 1, 24, 189, NULL, '2026-02-15 14:28:43', '2026-02-15 14:28:43'),
(37, 'Valvular Heart Disease Specialist', 'صمامات القلب', 1, 24, 189, NULL, '2026-02-15 14:28:57', '2026-02-15 14:28:57'),
(38, 'Coronary Artery Disease Specialist', 'أمراض الشرايين التاجية', 1, 24, 189, NULL, '2026-02-15 14:29:14', '2026-02-15 14:29:14'),
(39, 'Hypertension Specialist', 'ضغط الدم', 1, 24, 189, NULL, '2026-02-15 14:29:31', '2026-02-15 14:29:31'),
(40, 'Cardiac Rehabilitation Specialist', 'تأهيل القلب', 1, 24, 189, NULL, '2026-02-15 14:29:51', '2026-02-15 14:29:51'),
(41, 'Preventive Cardiologist', 'طب القلب الوقائي', 1, 24, 189, NULL, '2026-02-15 14:30:08', '2026-02-15 14:30:08'),
(42, 'Arrhythmia Specialist', 'عدم انتظام ضربات القلب', 1, 24, 189, NULL, '2026-02-15 14:30:27', '2026-02-15 14:30:27'),
(43, 'Pediatric Neurologist', 'مخ وأعصاب أطفال', 1, 23, 189, NULL, '2026-02-15 14:31:55', '2026-02-15 14:31:55'),
(44, 'Neurologist', 'مخ وأعصاب', 1, 23, 189, NULL, '2026-02-15 14:32:09', '2026-02-15 14:32:09'),
(45, 'Epileptologist', 'صرع', 1, 23, 189, NULL, '2026-02-15 14:32:20', '2026-02-15 14:32:20'),
(46, 'Movement Disorders Specialist', 'أمراض الأعصاب الحركية', 1, 23, 189, NULL, '2026-02-15 14:32:33', '2026-05-07 21:28:25'),
(47, 'Neuromuscular Specialist', 'أمراض الأعصاب العضلية', 1, 23, 189, NULL, '2026-02-15 14:32:47', '2026-05-07 21:28:42'),
(48, 'Vascular Neurology', 'السكتة الدماغية والأعصاب الوعائية', 1, 23, 189, NULL, '2026-02-15 14:33:03', '2026-05-07 21:29:05'),
(49, 'Maternal-Fetal Medicine', 'طب الأمومة والجنين', 1, 22, 189, NULL, '2026-02-15 14:33:28', '2026-05-07 21:20:13'),
(50, 'Infertility Specialist', 'عقم وتأخر إنجاب', 1, 22, 189, NULL, '2026-02-15 14:33:38', '2026-02-15 14:33:38'),
(51, 'Cosmetic Gynecology', 'طب النساء التجملي', 1, 22, 189, NULL, '2026-02-15 14:33:52', '2026-02-15 14:33:52'),
(52, 'Gynecologic Laparoscopy', 'مناظير نساء', 1, 22, 189, NULL, '2026-02-15 14:34:05', '2026-02-15 14:34:05'),
(53, 'Infectious Diseases', 'الأمراض المعدية والحميات', 1, 21, 189, NULL, '2026-02-15 14:34:37', '2026-05-07 21:13:15'),
(54, 'Cardiology', 'أمراض القلب والشرايين', 1, 21, 189, NULL, '2026-02-15 14:34:49', '2026-05-07 21:11:15'),
(55, 'Pulmonologist', 'أمراض الصدر', 1, 21, 189, NULL, '2026-02-15 14:35:01', '2026-02-15 14:35:01'),
(56, 'Gastroenterology & Hepatology', 'أمراض الجهاز الهضمي والكبد', 1, 21, 189, NULL, '2026-02-15 14:35:15', '2026-05-07 21:11:37'),
(57, 'Nephrologist', 'أمراض الكلى', 1, 21, 189, NULL, '2026-02-15 14:35:27', '2026-02-15 14:35:27'),
(58, 'Endocrinology & Diabetes', 'الغدد الصماء والسكري', 1, 21, 189, NULL, '2026-02-15 14:35:39', '2026-05-07 21:12:01'),
(59, 'Hematologist', 'أمراض الدم', 1, 21, 189, NULL, '2026-02-15 14:35:58', '2026-02-15 14:35:58'),
(60, 'Rheumatology & Immunology', 'المناعة والروماتيزم', 1, 21, 189, NULL, '2026-02-15 14:36:16', '2026-05-07 21:12:54'),
(61, 'Vascular Surgery', 'جراحة الأوعية الدموية', 1, 20, 189, NULL, '2026-02-15 14:36:42', '2026-05-07 21:14:41'),
(62, 'Orthopedic Surgeon', 'جراحة عظام', 1, 20, 189, NULL, '2026-02-15 14:36:53', '2026-02-15 14:36:53'),
(63, 'Neurosurgeon', 'جراحة مخ وأعصاب', 1, 20, 189, NULL, '2026-02-15 14:37:07', '2026-02-15 14:37:07'),
(64, 'Plastic & Reconstructive Surgery', 'جراحة التجميل والترميم', 1, 20, 189, NULL, '2026-02-15 14:37:20', '2026-05-07 21:16:10'),
(65, 'Cardiothoracic Surgeon', 'جراحة قلب وصدر', 1, 20, 189, NULL, '2026-02-15 14:37:40', '2026-02-15 14:37:40'),
(66, 'Vascular Surgeon', 'جراحة أوعية دموية', 1, 20, 189, NULL, '2026-02-15 14:37:54', '2026-02-15 14:37:54'),
(67, 'Urologist', 'جراحة مسالك بولية', 1, 20, 189, NULL, '2026-02-15 14:38:07', '2026-02-15 14:38:07'),
(68, 'Pediatric Surgeon', 'جراحة أطفال', 1, 20, 189, NULL, '2026-02-15 14:38:20', '2026-02-15 14:38:20'),
(69, 'Dermatologist', 'جلدية العامه', 1, 19, 189, NULL, '2026-02-15 14:38:47', '2026-02-15 14:38:47'),
(70, 'Aesthetic & Laser Specialist', 'تجميل وليزر', 1, 19, 189, NULL, '2026-02-15 14:39:01', '2026-02-15 14:39:01'),
(71, 'Pediatric Dermatology', 'طب الجلد للأطفال', 1, 19, 189, NULL, '2026-02-15 14:39:14', '2026-02-15 14:39:14'),
(72, 'Skin surgery', 'جراحة الجلد', 1, 19, 189, NULL, '2026-02-15 14:39:28', '2026-02-15 14:39:28'),
(73, 'Dermatoimmunology', 'الأمراض الجلدية المناعية', 1, 19, 189, NULL, '2026-02-15 14:39:41', '2026-02-15 14:39:41'),
(74, 'Cornea & Refractive Surgery', 'جراحة القرنية والانكسار', 1, 18, 189, NULL, '2026-02-15 14:40:07', '2026-05-07 21:25:38'),
(75, 'Pediatric Ophthalmologist', 'طب العيون عند الأطفال', 1, 18, 189, NULL, '2026-02-15 14:40:20', '2026-05-07 21:27:01'),
(76, 'Refractive Surgeon (LASIK)', 'جراحة تصحيح الإبصار (ليزك)', 1, 18, 189, NULL, '2026-02-15 14:40:33', '2026-02-15 14:40:33'),
(77, 'Retina Specialist', 'شبكية وجسم زجاجي', 1, 18, 189, NULL, '2026-02-15 14:40:51', '2026-02-15 14:40:51'),
(78, 'Glaucoma Specialist', 'جلوكوما (مياه زرقاء)', 1, 18, 189, NULL, '2026-02-15 14:41:07', '2026-02-15 14:41:07'),
(79, 'Cornea Specialist', 'قرنية', 1, 18, 189, NULL, '2026-02-15 14:41:19', '2026-02-15 14:41:19'),
(80, 'Oculoplastic Surgery', 'طب العيون التجميلي والحجاج', 1, 18, 189, NULL, '2026-02-15 14:41:34', '2026-05-07 21:26:16'),
(81, 'Optometrist', 'فحص نظر', 1, 18, 189, NULL, '2026-02-15 14:41:45', '2026-02-15 14:41:45'),
(82, 'Otology & Neurotology', 'جراحة الأذن والعظميات', 1, 17, 189, NULL, '2026-02-15 14:42:32', '2026-05-07 21:22:54'),
(83, 'Pediatric ENT Specialist', 'أنف وأذن وحنجرة أطفال', 1, 17, 189, NULL, '2026-02-15 14:42:45', '2026-02-15 14:42:45'),
(84, 'Audiologist', 'سمعيات', 1, 17, 189, '2026-05-07 21:24:34', '2026-02-15 14:42:59', '2026-05-07 21:24:34'),
(85, 'Audiology', 'طب السمع والتوازن', 1, 17, 189, NULL, '2026-02-15 14:43:10', '2026-05-07 21:23:31'),
(86, 'Speech & Language Therapist', 'تخاطب واضطرابات النطق', 1, 17, 189, NULL, '2026-02-15 14:43:23', '2026-02-15 14:43:23'),
(87, 'Rhinology', 'جراحة الأنف والجيوب الأنفية', 1, 17, 189, NULL, '2026-02-15 14:43:35', '2026-05-07 21:22:28'),
(88, 'Weight Loss Nutritionist', 'تغذية لإنقاص الوزن', 1, 1, 189, NULL, '2026-02-15 14:45:20', '2026-02-15 14:45:20'),
(89, 'Weight Gain Nutritionist', 'تغذية لزيادة الوزن', 1, 1, 189, NULL, '2026-02-15 14:45:34', '2026-02-15 14:45:34'),
(90, 'Pediatric Nutritionist', 'تغذية الأطفال', 1, 1, 189, NULL, '2026-02-15 14:45:49', '2026-02-15 14:45:49'),
(91, 'Maternal Nutritionist', 'تغذية الحوامل والمرضعات', 1, 1, 189, NULL, '2026-02-15 14:46:04', '2026-02-15 14:46:04'),
(92, 'Diabetes Nutrition Specialist', 'تغذية مرضى السكر', 1, 1, 189, NULL, '2026-02-15 14:46:17', '2026-02-15 14:46:17'),
(93, 'Gastrointestinal Nutritionist', 'تغذية أمراض الجهاز الهضمي', 1, 1, 189, NULL, '2026-02-15 14:46:30', '2026-02-15 14:46:30'),
(94, 'Cardiac Nutritionist', 'تغذية مرضى القلب', 1, 1, 189, NULL, '2026-02-15 14:46:44', '2026-02-15 14:46:44'),
(95, 'Renal Nutritionist', 'تغذية مرضى الكلى', 1, 1, 189, NULL, '2026-02-15 14:47:07', '2026-02-15 14:47:07'),
(96, 'Thyroid Nutrition Specialist', 'تغذية أمراض الغدة الدرقية', 1, 1, 189, NULL, '2026-02-15 14:47:20', '2026-02-15 14:47:20'),
(97, 'Oncology Nutritionist', 'تغذية مرضى السرطان', 1, 1, 189, NULL, '2026-02-15 14:47:39', '2026-02-15 14:47:39'),
(98, 'Obesity Management Nutritionist', 'تغذية علاج السمنة', 1, 1, 189, NULL, '2026-02-15 14:47:51', '2026-02-15 14:47:51'),
(99, 'Sports Nutritionist', 'تغذية رياضية', 1, 1, 189, NULL, '2026-02-15 14:48:02', '2026-02-15 14:48:02'),
(100, 'IBS Nutrition Specialist', 'تغذية لمرضى القولون العصبي', 1, 1, 189, NULL, '2026-02-15 14:48:15', '2026-02-15 14:48:15'),
(101, 'Eating Disorders Nutritionist', 'تغذية علاج اضطرابات الأكل', 1, 1, 189, NULL, '2026-02-15 14:48:29', '2026-02-15 14:48:29'),
(102, 'حديثي الولادة', 'حديثي الولادة', 1, 15, 189, NULL, '2026-02-15 14:50:04', '2026-02-15 14:50:04'),
(103, 'Pediatric Gastroenterologist', 'أمراض جهاز هضمي للأطفال', 1, 15, 189, NULL, '2026-02-15 14:50:15', '2026-02-15 14:50:15'),
(104, 'Pediatric Hematology-Oncology', 'أمراض الدم والأورام عند الأطفال', 1, 15, 189, NULL, '2026-02-15 14:50:28', '2026-05-07 21:18:29'),
(105, 'Radiation Oncologist', 'أورام إشعاعية', 1, 4, 189, NULL, '2026-02-15 14:52:03', '2026-02-15 14:52:03'),
(106, 'Surgical Oncology', 'الأورام الجراحية', 1, 4, 189, NULL, '2026-02-15 14:52:15', '2026-05-07 21:34:19'),
(107, 'Hematologic Oncologist', 'أورام دم', 1, 4, 189, NULL, '2026-02-15 14:52:28', '2026-02-15 14:52:28'),
(108, 'Pediatric Oncologist', 'أورام أطفال', 1, 4, 189, NULL, '2026-02-15 14:52:43', '2026-02-15 14:52:43'),
(109, 'Breast Cancer Specialist', 'أورام ثدي', 1, 4, 189, NULL, '2026-02-15 14:52:58', '2026-02-15 14:52:58'),
(110, 'Gastrointestinal Oncologist', 'أورام الجهاز الهضمي', 1, 4, 189, NULL, '2026-02-15 14:53:13', '2026-02-15 14:53:13'),
(111, 'Thoracic / Lung Cancer Specialist', 'أورام الرئة', 1, 4, 189, NULL, '2026-02-15 14:53:26', '2026-02-15 14:53:26'),
(112, 'Hepatobiliary & Pancreatic Oncologist', 'أورام الكبد والبنكرياس', 1, 4, 189, NULL, '2026-02-15 14:53:40', '2026-02-15 14:53:40'),
(113, 'Colorectal Cancer Specialist', 'أورام القولون والمستقيم', 1, 4, 189, NULL, '2026-02-15 14:53:56', '2026-02-15 14:53:56'),
(114, 'Urologic Oncologist', 'أورام المسالك البولية', 1, 4, 189, NULL, '2026-02-15 14:54:15', '2026-02-15 14:54:15'),
(115, 'Gynecologic Oncologist', 'أورام نساء', 1, 4, 189, NULL, '2026-02-15 14:54:26', '2026-02-15 14:54:26'),
(116, 'Head & Neck Oncologist', 'أورام الرأس والرقبة', 1, 4, 189, NULL, '2026-02-15 14:54:44', '2026-02-15 14:54:44'),
(117, 'Dermato-Oncologist', 'أورام الجلد', 1, 4, 189, NULL, '2026-02-15 14:55:03', '2026-02-15 14:55:03'),
(118, 'Musculoskeletal / Bone Cancer Oncologist', 'أورام العظام والأنسجة الرخوة', 1, 4, 189, NULL, '2026-02-15 14:55:15', '2026-02-15 14:55:15'),
(119, 'Endocrine Oncologist', 'أورام الغدد الصماء', 1, 4, 189, NULL, '2026-02-15 14:55:28', '2026-02-15 14:55:28'),
(120, 'Neuro-Oncologist', 'أورام الجهاز العصبي', 1, 4, 189, NULL, '2026-02-15 14:55:40', '2026-02-15 14:55:40'),
(121, 'Pediatric Orthopedic Specialist', 'عظام أطفال', 1, 3, 189, NULL, '2026-02-15 14:56:32', '2026-02-15 14:56:32'),
(122, 'Joint Replacement Specialist', 'مفاصل', 1, 3, 189, NULL, '2026-02-15 14:56:45', '2026-02-15 14:56:45'),
(123, 'Arthroscopic Surgeon', 'مناظير مفاصل', 1, 3, 189, NULL, '2026-02-15 14:57:01', '2026-02-15 14:57:01'),
(124, 'Spine Surgeon', 'العمود الفقري', 1, 3, 189, NULL, '2026-02-15 14:57:44', '2026-02-15 14:57:44'),
(125, 'Deformity Correction Specialist', 'تشوهات عظام', 1, 3, 189, NULL, '2026-02-15 14:57:56', '2026-02-15 14:57:56'),
(126, 'Osteoporosis Specialist', 'هشاشة العظام', 1, 3, 189, NULL, '2026-02-15 14:58:08', '2026-02-15 14:58:08'),
(127, 'Orthopedic Oncologist', 'أورام العظام', 1, 3, 189, NULL, '2026-02-15 14:58:21', '2026-02-15 14:58:21'),
(128, 'Child & Adolescent Psychiatrist', 'الطب النفسي للأطفال والمراهقين', 1, 2, 189, NULL, '2026-02-15 14:59:06', '2026-02-15 14:59:06'),
(129, 'Addiction Psychiatrist', 'الطب النفسي الإدماني', 1, 2, 189, NULL, '2026-02-15 14:59:17', '2026-05-07 21:35:49'),
(130, 'Geriatric Psychiatrist', 'الطب النفسي للمسنين', 1, 2, 189, NULL, '2026-02-15 14:59:29', '2026-02-15 14:59:29'),
(131, 'Forensic Psychiatrist', 'الطب النفسي الشرعي', 1, 2, 189, NULL, '2026-02-15 14:59:43', '2026-02-15 14:59:43'),
(132, 'Neuropsychiatrist', 'الطب النفسي العصبي', 1, 2, 189, NULL, '2026-02-15 14:59:55', '2026-02-15 14:59:55'),
(133, 'Community Psychiatrist', 'الطب النفسي المجتمعي', 1, 2, 189, NULL, '2026-02-15 15:00:09', '2026-02-15 15:00:09'),
(134, 'Medical Oncology', 'الأورام الطبية', 1, 21, 189, NULL, '2026-05-07 21:12:27', '2026-05-07 21:12:27'),
(135, 'Geriatrics', 'طب الشيخوخة', 1, 21, 189, NULL, '2026-05-07 21:13:28', '2026-05-07 21:13:28'),
(136, 'Acute Medicine', 'طب الطوارئ الباطني', 1, 21, 189, NULL, '2026-05-07 21:13:41', '2026-05-07 21:13:41'),
(137, 'Surgical Oncology', 'جراحة الأورام', 1, 20, 189, NULL, '2026-05-07 21:15:10', '2026-05-07 21:15:10'),
(138, 'Laparoscopic Surgery', 'جراحة المناظير والجراحة الدقيقة', 1, 20, 189, NULL, '2026-05-07 21:15:28', '2026-05-07 21:15:28'),
(139, 'Orthopedic Surgery', 'جراحة العظام والمفاصل', 1, 20, 189, NULL, '2026-05-07 21:15:45', '2026-05-07 21:15:45'),
(140, 'Hand Surgery', 'جراحة اليد', 1, 20, 189, NULL, '2026-05-07 21:16:28', '2026-05-07 21:16:28'),
(141, 'Transplant Surgery', 'جراحة زراعة الأعضاء', 1, 20, 189, NULL, '2026-05-07 21:16:49', '2026-05-07 21:16:49'),
(142, 'Pediatric Nephrology', 'أمراض الكلى عند الأطفال', 1, 15, 189, NULL, '2026-05-07 21:18:56', '2026-05-07 21:18:56'),
(143, 'Developmental Pediatrics', 'طب نمو وتطور الأطفال', 1, 15, 189, NULL, '2026-05-07 21:19:09', '2026-05-07 21:19:09'),
(144, 'Pediatric Critical Care', 'طب الأطفال الحرج', 1, 15, 189, NULL, '2026-05-07 21:19:23', '2026-05-07 21:19:23'),
(145, 'Pediatric Critical Care', 'طب الأطفال الحرج', 1, 15, 189, NULL, '2026-05-07 21:19:23', '2026-05-07 21:19:23'),
(146, 'Gynecologic Oncology', 'أورام النساء', 1, 22, 189, NULL, '2026-05-07 21:20:37', '2026-05-07 21:20:37'),
(147, 'Pediatric & Adolescent Gynecology', 'طب نساء الأطفال والمراهقين', 1, 22, 189, NULL, '2026-05-07 21:20:51', '2026-05-07 21:20:51'),
(148, 'Urogynecology', 'طب نساء تعويضي', 1, 22, 189, NULL, '2026-05-07 21:21:03', '2026-05-07 21:21:03'),
(149, 'Head & Neck Surgery', 'جراحة الرأس والعنق', 1, 17, 189, NULL, '2026-05-07 21:24:52', '2026-05-07 21:24:52'),
(150, 'Laryngology', 'جراحة الحنجرة والبلعوم', 1, 17, 189, NULL, '2026-05-07 21:25:08', '2026-05-07 21:25:08'),
(151, 'Neuro-Ophthalmology', 'طب العيون العصبي', 1, 18, 189, NULL, '2026-05-07 21:26:46', '2026-05-07 21:26:46'),
(152, 'Behavioral Neurology', 'الأعصاب السلوكية والإدراكية', 1, 23, 189, NULL, '2026-05-07 21:29:22', '2026-05-07 21:29:22'),
(153, 'Pediatric Dentistry', 'طب أسنان الأطفال', 1, 25, 189, NULL, '2026-05-07 21:31:22', '2026-05-07 21:31:22'),
(154, 'Periodontics', 'أمراض اللثة', 1, 25, 189, NULL, '2026-05-07 21:31:55', '2026-05-07 21:31:55'),
(155, 'Endodontics', 'علاج الجذور', 1, 25, 189, NULL, '2026-05-07 21:32:06', '2026-05-07 21:32:06'),
(156, 'Forensic Odontology', 'طب أسنان شرعي', 1, 25, 189, NULL, '2026-05-07 21:32:56', '2026-05-07 21:32:56'),
(157, 'test', 'تيست', 1, NULL, 189, '2026-06-01 21:17:29', '2026-06-01 21:17:25', '2026-06-01 21:17:29'),
(158, 'Cardiac Surgery', 'جراحة القلب', 1, NULL, 189, '2026-06-29 14:19:51', '2026-06-29 11:44:26', '2026-06-29 14:19:51'),
(159, 'Nerves', 'الاعصاب', 1, NULL, 189, '2026-06-29 14:19:47', '2026-06-29 14:14:14', '2026-06-29 14:19:47'),
(160, 'Nerves', 'الاعصاب', 1, NULL, 189, NULL, '2026-06-29 14:14:15', '2026-06-29 14:14:15'),
(161, 'jjj', 'jjj', 1, NULL, 189, '2026-07-03 09:03:39', '2026-07-03 09:03:35', '2026-07-03 09:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name_en`, `name_ar`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'New', 'جديد', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57'),
(2, 'Confirm', 'تاكيد', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57'),
(3, 'Cancel by doctor', 'إلغاء من قبل الطبيب', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57'),
(4, 'Cancel by reception', 'إلغاء من قبل الاستقبال', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57'),
(5, 'Cancel by user', 'إلغاء من قبل المستخدم', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57'),
(6, 'Done', 'منتهى', 1, NULL, '2022-11-04 18:23:57', '2022-11-04 18:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `status_conversions`
--

CREATE TABLE `status_conversions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `reception_id` bigint UNSIGNED DEFAULT NULL,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '4 attachment',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_person_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_package_clinics`
--

CREATE TABLE `subscriptions_package_clinics` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions_package_clinics`
--

INSERT INTO `subscriptions_package_clinics` (`id`, `clinic_id`, `package_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(3, 200, 1, '2026-02-15', '2026-06-15', 1, '2026-02-15 09:11:45', '2026-02-15 09:11:45'),
(4, 201, 2, '2026-02-15', '2028-02-05', 1, '2026-02-15 15:11:01', '2026-02-15 15:11:01'),
(13, 243, 1, '2026-07-02', '2027-06-27', 1, '2026-07-01 21:10:28', '2026-07-01 21:10:28'),
(15, 249, 1, '2026-07-31', '2027-08-26', 1, '2026-07-31 17:57:53', '2026-07-31 17:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_package_users`
--

CREATE TABLE `subscriptions_package_users` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `info_payment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions_package_users`
--

INSERT INTO `subscriptions_package_users` (`id`, `package_id`, `user_id`, `price`, `invoice_number`, `expired_date`, `status`, `info_payment`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 1, 15, '120', NULL, '2023-03-28 18:51:55', 1, NULL, NULL, '2022-11-29 01:51:55', '2022-11-29 01:51:55'),
(5, 4, 15, '0', NULL, '2022-12-29 14:20:50', 1, NULL, NULL, '2022-12-29 21:20:50', '2022-12-29 21:20:50'),
(13, 4, 15, '0', NULL, '2023-01-02 17:04:52', 1, NULL, NULL, '2023-01-03 00:04:52', '2023-01-03 00:04:52'),
(20, 1, 15, '120', NULL, '2023-05-03 06:10:54', 1, NULL, NULL, '2023-01-03 13:10:54', '2023-01-03 13:10:54'),
(34, 4, 10, '0', NULL, '2023-01-03 07:19:11', 1, NULL, NULL, '2023-01-03 14:19:11', '2023-01-03 14:19:11'),
(35, 4, 10, '0', NULL, '2023-01-03 07:22:45', 1, NULL, NULL, '2023-01-03 14:22:45', '2023-01-03 14:22:45'),
(36, 4, 10, '0', NULL, '2023-01-03 07:23:11', 1, NULL, NULL, '2023-01-03 14:23:11', '2023-01-03 14:23:11'),
(37, 1, 10, '120', NULL, '2023-05-03 07:24:32', 1, NULL, NULL, '2023-01-03 14:24:32', '2023-01-03 14:24:32'),
(38, 3, 10, '300', NULL, '2024-01-03 07:24:48', 1, NULL, NULL, '2023-01-03 14:24:48', '2023-01-03 14:24:48'),
(39, 2, 10, '500', NULL, '2024-12-23 07:25:23', 1, NULL, NULL, '2023-01-03 14:25:23', '2023-01-03 14:25:23'),
(40, 2, 10, '500', NULL, '2024-12-23 07:26:33', 1, NULL, NULL, '2023-01-03 14:26:33', '2023-01-03 14:26:33'),
(45, 3, 15, '300', NULL, '2024-05-02 00:00:00', 1, NULL, NULL, '2023-01-03 16:55:05', '2023-01-03 16:55:05'),
(46, 4, 3, '0', NULL, '2023-01-10 09:57:43', 1, NULL, NULL, '2023-01-03 16:57:43', '2023-01-03 16:57:43'),
(47, 1, 10, '120', NULL, '2025-04-22 00:00:00', 1, NULL, NULL, '2023-01-03 23:12:55', '2023-01-03 23:12:55'),
(48, 3, 10, '300', NULL, '2026-04-22 00:00:00', 1, NULL, NULL, '2023-01-03 23:13:42', '2023-01-03 23:13:42'),
(53, 4, 4, '0', NULL, '2023-03-26 19:00:49', 1, NULL, NULL, '2023-03-19 17:00:49', '2023-03-19 17:00:49'),
(54, 2, 10, '500', NULL, '2028-04-11 00:00:00', 1, NULL, NULL, '2023-04-17 02:42:25', '2023-04-17 02:42:25'),
(60, 3, 10, '300', '18162467', '2029-04-11 00:00:00', 1, '{InvoiceId: 18162467, InvoiceStatus: Paid, InvoiceReference: 2023000011, CustomerReference: null, CreatedDate: 2023-05-30T20:46:20.66, ExpiryDate: June 2, 2023, InvoiceValue: 0.1, Comments: null, CustomerName: Anonymous, CustomerMobile: +966, CustomerEmail: null, UserDefinedField: null, InvoiceDisplayValue: 0.100 SR, InvoiceItems: [], InvoiceTransactions: [{TransactionDate: 2023-05-30T20:48:09.8833333, PaymentGateway: MADA, ReferenceId: 315017201615, TrackId: 30-05-2023_17801604, TransactionId: 201615, PaymentId: 0808181624671780160483, AuthorizationId: 395979, TransactionStatus: Succss, TransationValue: 0.100, CustomerServiceCharge: 0.000, DueValue: 0.100, PaidCurrency: SR, PaidCurrencyValue: 0.100, Currency: SR, Error: null, CardNumber: 529415xxxxxx3398, ErrorCode: }], Suppliers: [], RecurringId: }', NULL, '2023-05-30 14:48:45', '2023-05-30 14:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `pharmacy_id` bigint UNSIGNED NOT NULL,
  `fax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `takafoul_discounts`
--

CREATE TABLE `takafoul_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `takafoul_discounts`
--

INSERT INTO `takafoul_discounts` (`id`, `discount`, `created_at`, `updated_at`) VALUES
(1, 50, '2024-04-06 14:57:25', '2024-04-06 14:57:25');

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_results_details`
--

CREATE TABLE `test_results_details` (
  `id` bigint UNSIGNED NOT NULL,
  `test_result_id` bigint UNSIGNED NOT NULL,
  `member_id` bigint UNSIGNED DEFAULT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ID_Number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality_id` bigint UNSIGNED DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `gover_id` bigint UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `address_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم البوليصة',
  `insurance_card_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم بطاقة التامين',
  `card_expiry_date` date DEFAULT NULL COMMENT 'تاريخ انتهاء البطاقة',
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `class_id` bigint UNSIGNED DEFAULT NULL,
  `reception_id` bigint UNSIGNED DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `lat` double DEFAULT '0',
  `lng` double DEFAULT '0',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int DEFAULT '1' COMMENT '1 male, 2 female ,3 other',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `active` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `platform` tinyint NOT NULL DEFAULT '1' COMMENT '1 android ,2 ios, 3 reception',
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jwt_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `firebase_token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `image`, `ID_Number`, `referral_code`, `nationality_id`, `country_id`, `gover_id`, `city_id`, `region_id`, `address_1`, `address_2`, `postal_code`, `mobile_number`, `national_id`, `file_number`, `bill_number`, `insurance_card_number`, `card_expiry_date`, `company_id`, `class_id`, `reception_id`, `dob`, `lat`, `lng`, `address`, `gender`, `parent_id`, `active`, `status`, `platform`, `device_token`, `jwt_token`, `info`, `package_id`, `expired_date`, `firebase_token`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Muhammed Ahmed', 'muhammed@gmail.com', '546411356', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', NULL, '32652145', NULL, 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1991-01-01', 0, 0, NULL, 1, NULL, 0, 1, 1, NULL, NULL, NULL, 3, '2025-01-19', NULL, NULL, '2022-07-12 12:22:53', '2022-07-12 12:22:53', NULL),
(3, 'amgad78', 'user4@gmail.com', '01221274765', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16641878538032.png', '1123444544', 'user1234', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, '128885458', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 30.1624474, 31.4325935, 'العبور, القليوبية, 11785, مصر', 1, NULL, 0, 1, 1, 'device token', 'ung0Q31OWTmDaNfny5GNRtDMt8Zjkvi4YxmLelKfm1dowzuvRc1777976091', NULL, 3, '2025-01-10', NULL, NULL, '2022-09-26 17:24:13', '2026-05-05 10:14:51', NULL),
(4, 'amgad', 'user2@gmail.com', '+201234567890', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16655259507426.png', '11234445443', 'user1234', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, '٢٠٠٠', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', 'r175MWEkPMA7P5wD3ATNoaVkjv9pcXIwJa62FD1h6KjX9gwj2f1786225651', NULL, 3, '2024-03-26', NULL, NULL, '2022-10-12 05:05:50', '2026-08-08 21:47:31', NULL),
(6, 'eslam mohamed', 'eslam.smaz@gmail.com', '+201122334455', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16656368755948.jpg', '6120672521', '123456', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1993-11-01', 29.9669637, 31.2611865, '12 El-Salam, Ezbet Nafie, Maadi, Cairo Governorate 4230004, Egypt', 1, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-13 11:54:35', '2022-10-13 11:55:23', NULL),
(9, 'eslam mohamed', 'eslam5@gmail.com', '+201122334488', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16656799968193.png', '1234560', '123456', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1996-12-10', 29.9670153, 31.2608792, 'صلاح سالم، البساتين، محافظة القاهرة‬،، X786+R92, Ezbet Nafie, El Basatin, Cairo Governorate 4230010, Egypt', 1, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-13 23:53:16', '2022-10-13 23:53:34', NULL),
(10, 'a', 'dr_aymankmk@hotmail.com', '547872256', '$2y$10$oIqdb6l2hXff.Lf/C2HMPuKVhW1huhS.ZIOxPtdwepyYSMwspEkf.', '16656867955727.jpg', '201609', '1', 1, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, '201609', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1984-01-13', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 1, NULL, 0, 1, 1, NULL, 'duscc8U7MR8zX8phhZx3PxSfHnuWGNj1Jg8ZPFdFoZrqccBmcP1786462341', NULL, 3, '2029-04-11', NULL, NULL, '2022-10-14 01:46:35', '2026-08-11 15:32:21', NULL),
(11, 'amgad782', 'amgad@gmail.com', '012212747651', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16659379559444.png', '1123444544', 'user1234', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-16 23:32:35', '2022-11-08 20:34:03', NULL),
(12, 'eslam mohamed', 'eslam7@gmail.com', '+201122334499', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16660235905110.jpg', '12345', NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-01-01', 29.9670153, 31.2608792, 'صلاح سالم، البساتين، محافظة القاهرة‬،، X786+R92, Ezbet Nafie, El Basatin, Cairo Governorate 4230010, Egypt', 1, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-17 23:19:50', '2022-10-17 23:19:50', NULL),
(13, 'esllam', 'eslam8@gmail.com', '+201122334400', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16660238569526.jpg', '123456', NULL, 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-01-01', 29.9670153, 31.2608792, 'صلاح سالم، البساتين، محافظة القاهرة‬،، X786+R92, Ezbet Nafie, El Basatin, Cairo Governorate 4230010, Egypt', 1, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-17 23:24:16', '2022-10-17 23:24:16', NULL),
(14, 'Eslam Mohamed', 'eslam9@gmail.com', '+201122223344', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16660249264634.jpg', '123458', NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2011-12-01', 29.9669637, 31.2611865, '12 El-Salam, Ezbet Nafie, Maadi, Cairo Governorate 4230004, Egypt', 1, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-17 23:42:06', '2022-10-17 23:42:26', NULL),
(15, 'shaimaa', 'shaimaa.farouk27@gmail.com', '+201227198697', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16660305363099.heic', '51567855', NULL, 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1985-06-29', 30.1620953, 31.4323931, '5C6J+RWQ, El-Nahda, Second Al Salam, Cairo Governorate 4650110, Egypt', 2, NULL, 0, 1, 1, NULL, NULL, NULL, 3, '2024-05-02', NULL, NULL, '2022-10-18 01:15:36', '2023-07-11 19:08:38', NULL),
(16, 'shaimaa', 'shaimaa@gmail.com', '+201152389776', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16660314416557.heic', '5634567', '', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1985-06-29', 30.1620953, 31.4323931, '5C6J+RWQ, El-Nahda, Second Al Salam, Cairo Governorate 4650110, Egypt', 2, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-18 01:30:41', '2022-11-23 14:12:44', NULL),
(28, 'خليل', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16727304952562.jpg', '116711301', NULL, 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-03-16', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 1, 10, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-03 14:21:35', '2026-04-03 17:51:43', NULL),
(29, 'خليل', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', NULL, '684216668', NULL, 1, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-07-14', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 1, 10, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-16 16:57:15', '2023-03-16 16:57:15', NULL),
(30, 'خليل', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', NULL, '624182963', NULL, 1, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-15', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 1, 10, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-16 16:57:35', '2023-03-16 16:57:35', NULL),
(32, 'seif', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16792523546452.heic', '981710925', NULL, 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2014-11-03', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, 4, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-19 16:59:14', '2023-03-19 16:59:14', NULL),
(40, 'ايمان', 'imaneriyade1@gmail.com', '+966560452425', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '16874806624538.jpg', '2007985555', NULL, 1, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1987-04-05', 24.4229469, 39.5770271, 'القصواء, المدينة المنورة, محافظة المدينة المنورة, منطقة المدينة المنورة, 42318, السعودية', 2, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-22 21:37:42', '2023-06-22 21:42:00', NULL),
(45, 'besan-ahmed-hassan-ahmed', 'besan@gmail.com', '000012345', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', NULL, 'QaWH7Canr8', NULL, 1, NULL, NULL, 1, 7, 'الرياض شارع الجامع', '15 شارع الرحمن', '11311', '000012345', NULL, '2', NULL, NULL, NULL, NULL, NULL, 196, '2005-06-22', 0, 0, NULL, 2, NULL, 0, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 09:29:35', '2025-12-22 09:29:35', NULL),
(47, 'ليلي عبدالرحمن حلمي', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17744520091663.jpg', '668409593', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '554886', '544555', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-03', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 2, 28, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-25 15:20:09', '2026-03-25 15:20:09', NULL),
(48, 'ليلي عبدالرحمن حلمي', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17744521079978.jpg', '328449744', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '55458', '55447', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-03', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 2, 28, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-25 15:21:47', '2026-03-25 15:21:47', NULL),
(50, 'eslam', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17747287042992.jpg', '721721413', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-26', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, 4, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-28 20:11:44', '2026-03-28 20:11:44', NULL),
(51, 'eslam2', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17747287495781.jpg', '996528207', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-18', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, 4, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-28 20:12:29', '2026-04-09 14:53:58', NULL),
(53, 'مروه بدوي علي', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17750837563401.jpg', '660752641', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0102068528', '1234538', NULL, NULL, NULL, NULL, NULL, NULL, '2010-04-01', 24.448693, 39.5001123, '8731-8755 يحيى بن اسماعيل المهاجر، As Sikkah Al Hadid, Medina 42377, Saudi Arabia', 2, 28, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 22:49:16', '2026-04-01 22:49:16', NULL),
(54, 'Eslam Mohamed', 'eslam.smaz2@gmail.com', '966510510510', '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17752384976464.jpg', '2479001576', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-04-05', 29.9667836, 31.2608088, '8L, Omran Alley, Ma‘adi Al Khahiri, Cairo, 11634, Egypt', 1, NULL, 0, 1, 1, NULL, 'oEzHcXQHgojpmqmAlVY26qnAvjDGZQMg9HEz8x0zV2Z0RSK9sb1785867076', NULL, NULL, '2026-04-03', NULL, NULL, '2026-04-03 17:48:17', '2026-08-04 18:11:16', NULL),
(55, 'test', NULL, NULL, '$2y$12$4iZV6G9DJMYwSY2wZdGj2eDM4oRYt8rNsq7dBBR57S.41Ev0VLvKm', '17763466443237.jpg', '209811192', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2011', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-09', 0, 0, NULL, 1, 1, 0, 1, 1, NULL, 'IVIXRrXDE1Zh6Htg9ImzWqmmtrIgruuxA3gqlW68Z70LtSNwdzRuT7SHGHm6UsaOhsX6hpxdkJn', NULL, NULL, NULL, NULL, NULL, '2026-04-16 13:37:24', '2026-04-16 13:37:24', NULL),
(56, 'مصطفي-محمد-احمد-علي', 'most@gmail.com', '0102055285', NULL, NULL, '1qtkbxMO0R', NULL, 1, NULL, NULL, 1, 6, 'الرياض شارع الجامع', '15 شارع الرحمن', '11311', '0126594963', NULL, '1', NULL, NULL, NULL, NULL, NULL, 196, '2017-02-02', 0, 0, NULL, 1, NULL, 0, 1, 3, NULL, 'FjPijDqFnwZ7mcBQPK2f0rkMT2mrbelV3ml2UGKoKke9UupJi3oFN9Ekn2db7o3loqDM7KofDJw', NULL, NULL, NULL, NULL, NULL, '2026-05-01 23:17:20', '2026-05-01 23:17:20', NULL),
(57, 'مصطفي-محمد-احمد-علي', 'mosty60@gmail.com', '01020685285', NULL, NULL, 'CkDcj4zaWf', NULL, 1, NULL, NULL, 1, 6, 'الرياض شارع الجامع', '15 شارع الرحمن', '11311', '01020685285', NULL, '1', NULL, NULL, NULL, NULL, NULL, 196, '2017-02-02', 0, 0, NULL, 1, NULL, 0, 1, 3, NULL, 'NHdLLcBEJYWYbDpN5FYzw3moyxdgn2fXNzgeyvtLvVStIXIrB6MXNv9I6ZJUFprJBWpPdyoqf8R', NULL, NULL, NULL, NULL, NULL, '2026-05-01 23:19:38', '2026-05-01 23:19:38', NULL),
(58, 'Ahmed Shahin-Shahin-ahmed-shahin', 'magdywork961@gmail.com', '01155122222', NULL, NULL, 'I1LNirDSv8', NULL, 1, NULL, NULL, NULL, 25, '10th Hosny Othman\'s st., El-Sefarat, Nasr City', 'ggggggg', '11178', '01145555555', NULL, '1', NULL, NULL, NULL, NULL, NULL, 196, '1994-05-05', 0, 0, NULL, 1, NULL, 0, 1, 3, NULL, 'gL4Gq5t7bdRQh44ifj56PporWUwDsFjCkTvKuhUUTUaIiuoEBiGBluDehae3SnqvxJy7kFPPnQb', NULL, NULL, NULL, NULL, NULL, '2026-05-05 13:57:53', '2026-05-05 13:57:53', NULL),
(60, 'ايمن زين', 'ceo@quantum-technical.com', '966580161257', '$2y$10$Si0vGH79MX3vf38.fqDh..lqOB3Bvmyb95NNk1JrvGyj53a8zcYhS', NULL, '2016276459', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1986-05-14', 24.4226359, 39.577157, 'القصواء, المدينة المنورة, محافظة المدينة المنورة, منطقة المدينة المنورة, 42315, السعودية', 1, NULL, 0, 1, 1, NULL, 'SAE7UF3iUTAumxEhL6TpIpXJ8JotAUOmtS1AgAgT6RhYVhJRxC1779121764', NULL, NULL, '2026-05-17', NULL, NULL, '2026-05-17 13:59:06', '2026-05-18 16:29:24', NULL),
(61, 'سهيله-مصطفي-احمد-محمد', 'suhil@gmail.com', '01020685585', NULL, NULL, 'O4spCKDnOo', NULL, 1, NULL, NULL, 1, 6, 'الرياض شارع الجامع', '15 شارع الرحمن', '11311', '01020655285', NULL, '2', NULL, NULL, NULL, NULL, NULL, 196, '2021-01-03', 0, 0, NULL, 2, NULL, 0, 1, 3, NULL, 'UypDvkKMiIewOC60TzFBRRftsz0qdRtpRpffORB80MbIPNEtJXHcTIsRu3iXgDkkgFpf1qafECy', NULL, NULL, NULL, NULL, NULL, '2026-05-17 14:15:28', '2026-05-17 14:15:28', NULL),
(62, 'ahmed', 'ahmedr@gmail.com', '966570001146', '$2y$10$VP1nMMXlt4ql4FvGjBEtGuT96USNyCohw5tsFyqeibnEPs7LJ0v3C', NULL, '1234568896', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-05-19', 25.622542027718673, 42.35282517060525, 'محافظة النبهانيه, منطقة القصيم, السعودية', 1, NULL, 0, 1, 1, NULL, 'dPyn322fTl2MM0Homk1f416XXGFvcNfkw8s1soPe6um3lPXiHH1779078264', NULL, NULL, '2026-05-17', NULL, NULL, '2026-05-17 18:13:17', '2026-05-18 04:24:24', NULL),
(63, 'mohamed', NULL, NULL, '$2y$10$VP1nMMXlt4ql4FvGjBEtGuT96USNyCohw5tsFyqeibnEPs7LJ0v3C', '17790785385921.jpg', '848657411', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12', 25.622542027719, 42.352825170605, 'محافظة النبهانيه, منطقة القصيم, السعودية', 1, 62, 0, 1, 1, NULL, 'dWHhLOUHWKa8aibIXmIO4r7KIjmqJdxBIs6VSjwiDOrBEKYu4XJ5ba3PsIK6nAdTHgKh3EZsdJy', NULL, NULL, NULL, NULL, NULL, '2026-05-18 04:28:58', '2026-05-18 04:28:58', NULL),
(65, 'test one', 'w@gmail.com', '966512345698', '$2y$10$J70H3qBv0hZ0qMLPXoyIEeG0aW3WqQse.q1jLW9.K91rMdyCa6CDS', '17791447012201.jpg', '2648363826', 'we', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '12346578645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-05-10', 30.1677404, 31.446443, 'مساكن القاهرة, العبور, القليوبية, 11785, مصر', 1, NULL, 0, 1, 1, NULL, 'Cg6xuSyA52Zvb4I2ejmCXHkETCZXGD5Z5fR7B9ctxVHXo6OyOz1782741074', NULL, NULL, '2026-05-19', NULL, NULL, '2026-05-18 22:51:41', '2026-06-29 13:51:14', NULL),
(66, 'Eslam Mohamed', 'eslam.smaz3@gmail.com', '966510510511', '$2y$10$h70F92lIqbcWYPfhLKIoDukuZRgc1Bu2qTUYN9e2ZFptOAySYTHyO', '17793012877788.png', '2415256754', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-05-17', 24.7136, 46.6753, 'طريق العروبة, الورود, الرياض, محافظة الرياض, منطقة الرياض, 11355, السعودية', 1, NULL, 0, 1, 1, NULL, 'MR80WuOZCtmBUPi5MCQyc1CVExtEc8EVzZ5aba9dCqPzO9cPFM1779301386', NULL, NULL, '2026-05-20', NULL, NULL, '2026-05-20 18:21:27', '2026-05-20 18:23:06', NULL),
(67, 'Eslam Mohamed', 'eslam.smaz4@gmail.com', '966512512512', '$2y$10$VEwPqSVl35wDI2dqZEsEOOcbHp2niZ5g59IHHcy4tVE1lAOg8JoB6', '17793019839404.png', '2342342345', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-05-18', 24.7136, 46.6753, 'طريق العروبة, الورود, الرياض, محافظة الرياض, منطقة الرياض, 11355, السعودية', 1, NULL, 0, 1, 1, NULL, 'SQx76roZ8V5h8u47f3aFribd88vYtuNxCspShK42BjbCm20qF31786027038', NULL, NULL, '2026-05-20', NULL, NULL, '2026-05-20 18:33:03', '2026-08-06 14:37:18', NULL),
(68, 'ahmed farouk', 'ahmedf@gmail.com', '966580825090', '$2y$10$ySlNUx6I1suwL1mBEG9cnugRENOoJVrwuK4Bd3z5XJatK/hVJKlfC', '17794457281198.jpg', '2467688974', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1981-05-22', 24.7136, 46.6753, 'Al Uaroba Road, Al Wuroud District, Riyadh, Riyadh governorate, Riyadh Region, 11355, Saudi Arabia', 1, NULL, 0, 1, 1, NULL, 'fti0sdlm1z0l2VIMdTkaK8pZO7g0wbzsbEnW9SMBRNiU8voVeg1779633579', NULL, NULL, '2026-05-22', NULL, NULL, '2026-05-22 10:28:48', '2026-05-24 14:39:39', NULL),
(69, 'Wael Ali', 'newhalfa2020@gmail.com', '966559658981', '$2y$10$OgliG2w858ovUhioutVR4OQJlP..XM9vQh0yMgs6bYP/sMwLxeHqG', NULL, '2634662858', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1984-05-06', 24.7136, 46.6753, 'Al Uaroba Road, Al Wuroud District, Riyadh, Riyadh governorate, Riyadh Region, 11355, Saudi Arabia', 1, NULL, 0, 1, 1, NULL, 'FS0MvBiFlNY9Hjnxillhx69MXuI1oj1VoCKEpOj4hh3F8onM6i1780801850', NULL, NULL, '2026-06-07', NULL, NULL, '2026-06-07 03:10:21', '2026-06-07 03:10:50', NULL),
(70, 'Khalid Rashwan', 'Khalidirashwan@gmail.com', '966503307121', '$2y$10$CG0a07szkyYWIPQV4d6F1.OXUkNzNteBIA7hwYZArRUb8rU.qi1c2', NULL, '2539805768', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1988-02-10', 24.7136, 46.6753, 'طريق العروبة, الورود, الرياض, محافظة الرياض, منطقة الرياض, 11355, السعودية', 1, NULL, 0, 1, 1, NULL, 'iVVXDzhHqym99vsLFeCEKX7RCirLOD2zX7daq3Hnb2XPgeRIZ81781165410', NULL, NULL, '2026-06-11', NULL, NULL, '2026-06-11 08:09:49', '2026-06-11 08:10:10', NULL),
(71, 'منى', 'monah000111@gnail.com', '966531028313', '$2y$10$eu9ZoVEFqA1wl/PVCtJTM.rm3tyoMl10w.yoE01xYESb7xbkTy3UG', NULL, '2257345997', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1993-08-21', 24.7136, 46.6753, 'طريق العروبة, الورود, الرياض, محافظة الرياض, منطقة الرياض, 11355, السعودية', 2, NULL, 0, 1, 1, NULL, 'qblWQaf6RS7ml2QMjffW7ybhDcxbLMcRnfe9VNODz264msaytN1781789262', NULL, NULL, '2026-06-18', NULL, NULL, '2026-06-18 13:13:54', '2026-06-18 13:27:42', NULL),
(72, 'waleed saeed Hamaidah', 'waleed1976r@gmail.com', '966595942747', '$2y$10$hWQTmY7taRv8LkpRZYOw4.9l3zd3/Xxfn4JEA8cJWXbC8nrnWR.Me', NULL, '2020707556', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2020707556', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1976-01-01', 24.7136, 46.6753, 'Al Uaroba Road, Al Wuroud District, Riyadh, Riyadh governorate, Riyadh Region, 11355, Saudi Arabia', 1, NULL, 0, 1, 1, NULL, 'GBbBVuIUH6smpsPVWxLviSntPALoiqXhoAL6V9OHwJW7FJLv5I1782171555', NULL, NULL, '2026-06-23', NULL, NULL, '2026-06-22 23:36:36', '2026-06-22 23:39:15', NULL),
(73, 'amgad782', 'amgad12@gmail.com', '012212747652', '$2y$10$rkXOGvSLYhCxuyZD9ddfO.jubQVDWUO/tMRY6Rr.j03vcUXf6FImG', NULL, '1123444544', 'user1234', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', 'L7l6NRlWfLUowqYgfPXTtIIc9G1KHonT3pxoG7YZMALghAeArGpUkuOLEaag1TY7kSsvGuotwMb', NULL, NULL, '2026-07-03', 'token', NULL, '2026-07-03 16:03:28', '2026-07-03 16:03:28', NULL),
(74, 'ahmed', 'ahmedrouka@gmail.com', '966538119328', '$2y$10$aMAOXEh3daZN10cB9nSD/e9nkkypKOsFYUs8e1V1JaF3MOvejXpxG', NULL, '2346788805', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1981-10-22', 24.7136, 46.6753, 'Al Uaroba Road, Al Wuroud District, Riyadh, Riyadh governorate, Riyadh Region, 11355, Saudi Arabia', 1, NULL, 0, 1, 1, NULL, 'qLePZcIyk4hXia7qBA41P7WB8gZv7lcObdEULloxMqURPp8RQ51784838544', NULL, NULL, '2026-07-04', NULL, NULL, '2026-07-04 20:38:39', '2026-07-23 20:29:04', NULL),
(75, 'عبدالوهاب', 'wahbi14@gmail.com', '966548600413', '$2y$10$XfS5uOIUhGzx4KMVEcny1umvn3PIPnwC.zs3PlZb4UQ5etpaFRQIO', NULL, '1075617512', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1988-01-20', 24.7136, 46.6753, 'طريق العروبة, الورود, الرياض, محافظة الرياض, منطقة الرياض, 11355, السعودية', 1, NULL, 0, 1, 1, NULL, 'YcOgmMlWhaEeXeny6c2rEMiaQr9260xw3FoBtIBeNH0JA8jj9u1783429641', NULL, NULL, '2026-07-07', NULL, NULL, '2026-07-07 13:05:42', '2026-07-07 13:07:21', NULL),
(76, 'amgad782', 'amgad4@gmail.com', '012212747650', '$2y$10$ZQxt9yTxtUO5TVbsvSRmVefzwBfxbIBvXm/CcbHLZr5hkc3iGmufG', NULL, '1123444544', 'user1234', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', '3FM0Cjumoa7lRzKPuyTAZjdiz7ViUEfpEIHHzPcPrCtIRtRh2g1786389024', NULL, NULL, '2026-07-19', NULL, NULL, '2026-07-19 11:08:01', '2026-08-10 19:10:24', NULL),
(77, 'amgad782', 'amgad5@gmail.com', '012212747623', '$2y$10$dBkzR6IKgwrYjSUvZg8igeaTvzl5aPh9DqlmlsWWo35Z1VFjfanoy', NULL, '1123444544', 'user1234', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', 'tF78MFIHPusK2Lt8jrUK9FGeyfr86a3zXoOoxyhBxBILydGEUuDj0Ib5Cd4waqOIodakQpExXiC', NULL, NULL, '2026-07-19', 'token', NULL, '2026-07-19 11:56:21', '2026-07-19 11:56:21', NULL),
(78, 'amgad782', 'amgad6@gmail.com', '01050730355', '$2y$10$w/r1vY9MYcuafUMTibFICu7JRPr4LFLZgMsXex6kbvY/JIsPWWS1i', NULL, '1123444544', 'user1234', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-06-28', 12.0999889, 13.018833, 'egypt-masr elswdan', 1, NULL, 0, 1, 1, 'device token', '966yHtkIsrSpiPcFw4IF2We65c3dklzUx2awBbac1sjmLqOeFB1785595663', NULL, NULL, '2026-08-01', NULL, NULL, '2026-08-01 14:39:43', '2026-08-01 14:47:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_members`
--

CREATE TABLE `users_members` (
  `id` bigint UNSIGNED NOT NULL,
  `ID_Number` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` int DEFAULT '1' COMMENT '1 male, 2 female ,3 other',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_coupons`
--

CREATE TABLE `user_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_points`
--

CREATE TABLE `user_points` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `content_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type_verify` enum('active','reset_password','update_profile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` int NOT NULL DEFAULT '111111',
  `expired_at` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `user_id`, `type_verify`, `phone`, `code`, `expired_at`, `status`, `created_at`, `updated_at`) VALUES
(10, NULL, 'active', '580161257', 205150, '2026-05-08 13:40:55', 1, NULL, '2026-05-08 10:37:45'),
(11, NULL, 'reset_password', '547872256', 366215, '2026-05-08 21:56:58', 1, NULL, '2026-05-08 18:47:24'),
(12, NULL, 'active', '560452425', 189708, '2026-05-08 21:56:50', 1, NULL, '2026-05-08 18:53:35'),
(13, NULL, 'reset_password', '547872256', 591784, '2026-05-16 18:06:01', 1, NULL, '2026-05-16 14:58:43'),
(14, NULL, 'reset_password', '547872256', 739711, '2026-05-16 18:10:12', 1, NULL, '2026-05-16 15:01:06'),
(15, NULL, 'reset_password', '547872256', 277026, '2026-05-16 18:52:34', 1, NULL, '2026-05-16 15:45:14'),
(16, NULL, 'active', '510510510', 627847, '2026-05-16 18:52:17', 1, NULL, '2026-05-16 15:47:56'),
(17, NULL, 'reset_password', '547872256', 626050, '2026-05-17 16:58:13', 1, NULL, '2026-05-17 13:48:26'),
(18, NULL, 'active', '580161257', 943450, '2026-05-17 16:55:15', 1, NULL, '2026-05-17 13:50:57'),
(19, NULL, 'reset_password', '547872256', 908879, '2026-05-17 17:01:38', 1, NULL, '2026-05-17 13:51:48'),
(20, NULL, 'active', '580161257', 932601, '2026-05-17 16:58:47', 1, NULL, '2026-05-17 13:54:11'),
(21, NULL, 'active', '570001146', 201844, '2026-05-17 21:14:43', 1, NULL, '2026-05-17 18:10:43'),
(22, NULL, 'reset_password', '547872256', 871371, '2026-05-17 23:05:55', 1, NULL, '2026-05-17 19:56:28'),
(23, NULL, 'reset_password', '546411356', 944178, '2026-05-19 01:55:18', 1, NULL, '2026-05-18 22:46:19'),
(24, NULL, 'active', '512345698', 152399, '2026-05-19 01:53:52', 1, NULL, '2026-05-18 22:49:47'),
(25, NULL, 'reset_password', '512345698', 890419, '2026-05-19 02:02:26', 1, NULL, '2026-05-18 22:52:54'),
(26, NULL, 'reset_password', '547872256', 894686, '2026-05-27 15:09:41', 1, NULL, '2026-05-27 12:00:03'),
(27, NULL, 'active', '510510511', 145989, '2026-05-20 21:23:45', 0, NULL, NULL),
(28, NULL, 'reset_password', '512512512', 371916, '2026-05-20 22:02:37', 0, NULL, NULL),
(29, NULL, 'active', '580825090', 850455, '2026-05-22 13:31:46', 1, NULL, '2026-05-22 10:27:21'),
(30, NULL, 'reset_password', '580825090', 342947, '2026-05-22 13:39:37', 1, NULL, '2026-05-22 10:30:12'),
(31, NULL, 'active', '559658981', 327047, '2026-06-07 06:12:55', 1, NULL, '2026-06-07 03:08:13'),
(32, NULL, 'active', '503307121', 882092, '2026-06-11 11:12:57', 1, NULL, '2026-06-11 08:08:06'),
(33, NULL, 'active', '531028313', 611344, '2026-06-18 16:16:51', 1, NULL, '2026-06-18 13:12:02'),
(34, NULL, 'reset_password', '531028313', 622756, '2026-06-18 16:26:15', 1, NULL, '2026-06-18 13:16:22'),
(35, NULL, 'active', '595942747', 865854, '2026-06-23 02:39:26', 1, NULL, '2026-06-22 23:34:39'),
(36, NULL, 'active', '538119328', 411329, '2026-07-04 23:41:48', 1, NULL, '2026-07-04 20:37:14'),
(37, NULL, 'active', '548600413', 314005, '2026-07-07 16:08:56', 1, NULL, '2026-07-07 13:04:07'),
(38, NULL, 'reset_password', '548600413', 931364, '2026-07-07 16:16:36', 1, NULL, '2026-07-07 13:06:46'),
(39, NULL, 'active', '582251131', 361671, '2026-07-14 16:34:33', 1, NULL, '2026-07-14 13:29:45'),
(40, NULL, 'active', '512345678', 520612, '2026-07-26 06:31:26', 0, NULL, NULL),
(41, NULL, 'active', '501234567', 701796, '2026-08-07 10:08:25', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vital_signs`
--

CREATE TABLE `vital_signs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `emergency_id` bigint UNSIGNED DEFAULT NULL,
  `heat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pulse` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `breathing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pregnant` tinyint DEFAULT '0',
  `blood_pressure` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sports_habits` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FBS` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RBS` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pain_rate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_mass_rate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oxygen_ratio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `working_days`
--

CREATE TABLE `working_days` (
  `id` bigint UNSIGNED NOT NULL,
  `day_id` bigint UNSIGNED NOT NULL,
  `pharmacist_id` bigint UNSIGNED NOT NULL,
  `check_in_date` time DEFAULT NULL,
  `check_out_date` time DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD UNIQUE KEY `admins_phone_unique` (`phone`);

--
-- Indexes for table `age_categories`
--
ALTER TABLE `age_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `all_permissions`
--
ALTER TABLE `all_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `all_permissions_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `app_types`
--
ALTER TABLE `app_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_types_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_account_type_foreign` (`account_type`),
  ADD KEY `attendances_clinic_id_foreign` (`clinic_id`),
  ADD KEY `attendances_day_id_foreign` (`day_id`),
  ADD KEY `attendances_another_employee_foreign` (`another_employee`);

--
-- Indexes for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_settings_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `bonds`
--
ALTER TABLE `bonds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bonds_user_id_foreign` (`user_id`),
  ADD KEY `bonds_reception_id_foreign` (`reception_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_country_id_foreign` (`country_id`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clinics_email_unique` (`email`),
  ADD UNIQUE KEY `clinics_jwt_token_unique` (`jwt_token`),
  ADD UNIQUE KEY `clinics_phone_unique` (`phone`),
  ADD KEY `clinics_app_type_foreign` (`app_type`),
  ADD KEY `clinics_parent_id_foreign` (`parent_id`),
  ADD KEY `clinics_city_id_foreign` (`city_id`),
  ADD KEY `clinics_nursing_point_id_foreign` (`nursing_point_id`);

--
-- Indexes for table `clinics_permissions`
--
ALTER TABLE `clinics_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinics_permissions_admin_id_foreign` (`admin_id`),
  ADD KEY `clinics_permissions_parent_id_foreign` (`parent_id`),
  ADD KEY `clinics_permissions_child_id_foreign` (`child_id`);

--
-- Indexes for table `clinic_contracts`
--
ALTER TABLE `clinic_contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clinic_contracts_clinic_id_unique` (`clinic_id`);

--
-- Indexes for table `clinic_device_tokens`
--
ALTER TABLE `clinic_device_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_device_tokens_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_offers_clinic_id_foreign` (`clinic_id`),
  ADD KEY `clinic_offers_specialty_id_foreign` (`specialty_id`);

--
-- Indexes for table `clinic_points`
--
ALTER TABLE `clinic_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_points_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `clinic_point_nursings`
--
ALTER TABLE `clinic_point_nursings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_point_nursings_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `clinic_posts_counts`
--
ALTER TABLE `clinic_posts_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinic_ratings`
--
ALTER TABLE `clinic_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_ratings_clinic_id_foreign` (`clinic_id`),
  ADD KEY `clinic_ratings_user_id_foreign` (`user_id`),
  ADD KEY `clinic_ratings_rating_id_foreign` (`rating_id`);

--
-- Indexes for table `clinic_services`
--
ALTER TABLE `clinic_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_services_clinic_id_foreign` (`clinic_id`),
  ADD KEY `clinic_services_service_id_foreign` (`service_id`),
  ADD KEY `clinic_services_created_by_foreign` (`created_by`);

--
-- Indexes for table `clinic_specialists`
--
ALTER TABLE `clinic_specialists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_specialists_specialty_id_foreign` (`specialty_id`),
  ADD KEY `clinic_specialists_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `cms_items`
--
ALTER TABLE `cms_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_items_cms_section_id_index` (`cms_section_id`),
  ADD KEY `cms_items_order_index` (`order`),
  ADD KEY `cms_items_is_active_index` (`is_active`),
  ADD KEY `cms_items_type_index` (`type`),
  ADD KEY `cms_items_slug_index` (`slug`);

--
-- Indexes for table `cms_item_translations`
--
ALTER TABLE `cms_item_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_item_translations_cms_item_id_locale_unique` (`cms_item_id`,`locale`),
  ADD KEY `cms_item_translations_locale_index` (`locale`);

--
-- Indexes for table `cms_languages`
--
ALTER TABLE `cms_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_languages_code_unique` (`code`),
  ADD KEY `cms_languages_is_active_index` (`is_active`),
  ADD KEY `cms_languages_is_default_index` (`is_default`),
  ADD KEY `cms_languages_order_index` (`order`);

--
-- Indexes for table `cms_links`
--
ALTER TABLE `cms_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_links_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`),
  ADD KEY `cms_links_is_active_index` (`is_active`),
  ADD KEY `cms_links_order_index` (`order`);

--
-- Indexes for table `cms_link_translations`
--
ALTER TABLE `cms_link_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_link_translations_cms_link_id_locale_unique` (`cms_link_id`,`locale`),
  ADD KEY `cms_link_translations_locale_index` (`locale`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_pages_slug_unique` (`slug`);

--
-- Indexes for table `cms_page_translations`
--
ALTER TABLE `cms_page_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_page_translations_cms_page_id_locale_unique` (`cms_page_id`,`locale`),
  ADD KEY `cms_page_translations_locale_index` (`locale`);

--
-- Indexes for table `cms_sections`
--
ALTER TABLE `cms_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_sections_cms_page_id_foreign` (`cms_page_id`);

--
-- Indexes for table `cms_section_translations`
--
ALTER TABLE `cms_section_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_section_translations_cms_section_id_locale_unique` (`cms_section_id`,`locale`),
  ADD KEY `cms_section_translations_locale_index` (`locale`);

--
-- Indexes for table `complaint_boxes`
--
ALTER TABLE `complaint_boxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_boxes_user_id_foreign` (`user_id`),
  ADD KEY `complaint_boxes_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_us_read_by_foreign` (`read_by`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_code_unique` (`code`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupons_city_id_foreign` (`city_id`),
  ADD KEY `coupons_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_clinic_id_index` (`clinic_id`),
  ADD KEY `customers_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `days`
--
ALTER TABLE `days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `demo_requests`
--
ALTER TABLE `demo_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departments_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_appointments_day_id_foreign` (`day_id`),
  ADD KEY `doctor_appointments_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `doctor_conditions`
--
ALTER TABLE `doctor_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_conditions_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `doctor_degrees`
--
ALTER TABLE `doctor_degrees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_emergencies`
--
ALTER TABLE `doctor_emergencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_emergencies_emergency_id_foreign` (`emergency_id`),
  ADD KEY `doctor_emergencies_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drugs_doctor_id_foreign` (`doctor_id`),
  ADD KEY `drugs_parent_id_foreign` (`parent_id`),
  ADD KEY `drugs_type_index` (`type`),
  ADD KEY `drugs_alternative_id_index` (`alternative_id`),
  ADD KEY `drugs_clinic_id_index` (`clinic_id`),
  ADD KEY `drugs_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `drugs_emergencies`
--
ALTER TABLE `drugs_emergencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drugs_emergencies_emergency_id_foreign` (`emergency_id`),
  ADD KEY `drugs_emergencies_drugs_id_foreign` (`drugs_id`);

--
-- Indexes for table `drug_clinics_taxes`
--
ALTER TABLE `drug_clinics_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drug_clinics_taxes_clinic_id_index` (`clinic_id`),
  ADD KEY `drug_clinics_taxes_pharmacy_id_index` (`pharmacy_id`),
  ADD KEY `drug_clinics_taxes_drug_id_index` (`drug_id`);

--
-- Indexes for table `drug_sections`
--
ALTER TABLE `drug_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drug_sections_grand_unit_id_foreign` (`grand_unit_id`),
  ADD KEY `drug_sections_micro_unit_id_foreign` (`micro_unit_id`),
  ADD KEY `drug_sections_clinic_id_index` (`clinic_id`),
  ADD KEY `drug_sections_pharmacy_id_index` (`pharmacy_id`),
  ADD KEY `drug_sections_drug_id_index` (`drug_id`),
  ADD KEY `drug_sections_supplier_id_index` (`supplier_id`),
  ADD KEY `drug_sections_category_id_foreign` (`category_id`);

--
-- Indexes for table `electronic_payments`
--
ALTER TABLE `electronic_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `electronic_payments_user_id_foreign` (`user_id`),
  ADD KEY `electronic_payments_clinic_id_foreign` (`clinic_id`),
  ADD KEY `electronic_payments_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `emergencies`
--
ALTER TABLE `emergencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emergencies_clinic_id_foreign` (`clinic_id`),
  ADD KEY `emergencies_user_id_foreign` (`user_id`),
  ADD KEY `emergencies_nurse_id_foreign` (`nurse_id`);

--
-- Indexes for table `emergency_hospitals`
--
ALTER TABLE `emergency_hospitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emergency_hospitals_city_id_foreign` (`city_id`),
  ADD KEY `emergency_hospitals_region_id_foreign` (`region_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `insurances_companies_services`
--
ALTER TABLE `insurances_companies_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurances_companies_services_clinic_id_foreign` (`clinic_id`),
  ADD KEY `insurances_companies_services_clinic_service_id_foreign` (`clinic_service_id`),
  ADD KEY `insurances_companies_services_insurance_id_foreign` (`insurance_id`),
  ADD KEY `insurances_companies_services_company_id_foreign` (`company_id`),
  ADD KEY `insurances_companies_services_class_id_foreign` (`class_id`),
  ADD KEY `insurances_companies_services_drug_section_id_foreign` (`drug_section_id`);

--
-- Indexes for table `insurance_classes`
--
ALTER TABLE `insurance_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurance_classes_company_id_foreign` (`company_id`),
  ADD KEY `insurance_classes_insurance_id_foreign` (`insurance_id`),
  ADD KEY `insurance_classes_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `insurance_companies`
--
ALTER TABLE `insurance_companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurance_companies_insurance_id_foreign` (`insurance_id`),
  ADD KEY `insurance_companies_clinic_id_foreign` (`clinic_id`),
  ADD KEY `insurance_companies_insurance_company_id_foreign` (`insurance_company_id`);

--
-- Indexes for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurance_policies_clinic_id_foreign` (`clinic_id`),
  ADD KEY `insurance_policies_company_id_foreign` (`company_id`),
  ADD KEY `insurance_policies_class_id_foreign` (`class_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_store_id_index` (`store_id`),
  ADD KEY `inventories_drug_id_index` (`drug_id`),
  ADD KEY `inventories_clinic_id_index` (`clinic_id`),
  ADD KEY `inventories_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `inventory_records`
--
ALTER TABLE `inventory_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_records_created_by_index` (`created_by`),
  ADD KEY `inventory_records_clinic_id_index` (`clinic_id`),
  ADD KEY `inventory_records_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `inventory_record_items`
--
ALTER TABLE `inventory_record_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_record_items_inventory_record_id_index` (`inventory_record_id`),
  ADD KEY `inventory_record_items_inventory_id_index` (`inventory_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_reception_id_foreign` (`reception_id`),
  ADD KEY `invoices_doctor_id_foreign` (`doctor_id`),
  ADD KEY `invoices_reservation_id_foreign` (`reservation_id`),
  ADD KEY `invoices_payment_method_foreign` (`payment_method`),
  ADD KEY `invoices_company_id_foreign` (`company_id`);

--
-- Indexes for table `item_units`
--
ALTER TABLE `item_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_units_clinic_id_index` (`clinic_id`),
  ADD KEY `item_units_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_coupon_redemptions`
--
ALTER TABLE `loyalty_coupon_redemptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_point_rules`
--
ALTER TABLE `loyalty_point_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_point_rules_key_unique` (`key`);

--
-- Indexes for table `loyalty_point_transactions`
--
ALTER TABLE `loyalty_point_transactions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `loyalty_point_transactions_clinic_id_foreign` (`clinic_id`),
  ADD KEY `loyalty_point_transactions_reservation_id_foreign` (`reservation_id`),
  ADD KEY `loyalty_point_transactions_user_id_status_expires_at_index` (`user_id`,`status`,`expires_at`),
  ADD KEY `loyalty_point_transactions_source_type_source_id_index` (`source_type`,`source_id`);

--
-- Indexes for table `loyalty_reward_coupons`
--
ALTER TABLE `loyalty_reward_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_reward_coupons_clinic_id_status_expires_at_index` (`clinic_id`,`status`,`expires_at`);

--
-- Indexes for table `loyalty_share_logs`
--
ALTER TABLE `loyalty_share_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_share_logs_clinic_id_foreign` (`clinic_id`),
  ADD KEY `loyalty_share_logs_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `medical_reports`
--
ALTER TABLE `medical_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_reports_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_admin_id_foreign` (`admin_id`),
  ADD KEY `notifications_clinic_id_foreign` (`clinic_id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `notification_events`
--
ALTER TABLE `notification_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_events_key_unique` (`key`);

--
-- Indexes for table `notification_event_recipient`
--
ALTER TABLE `notification_event_recipient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_recipient_event_unique` (`notification_recipient_id`,`notification_event_id`),
  ADD KEY `notification_event_recipient_notification_event_id_foreign` (`notification_event_id`);

--
-- Indexes for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_recipients_email_unique` (`email`);

--
-- Indexes for table `nurse_emergencies`
--
ALTER TABLE `nurse_emergencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nurse_emergencies_emergency_id_foreign` (`emergency_id`),
  ADD KEY `nurse_emergencies_nurse_id_foreign` (`nurse_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `patient_medical_reports`
--
ALTER TABLE `patient_medical_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_medical_reports_user_id_foreign` (`user_id`),
  ADD KEY `patient_medical_reports_report_id_foreign` (`report_id`),
  ADD KEY `patient_medical_reports_answer_id_foreign` (`answer_id`),
  ADD KEY `patient_medical_reports_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `patient_sale_invoices`
--
ALTER TABLE `patient_sale_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_sale_invoices_customer_id_index` (`customer_id`),
  ADD KEY `patient_sale_invoices_account_tree_id_index` (`account_tree_id`),
  ADD KEY `patient_sale_invoices_clinic_id_index` (`clinic_id`),
  ADD KEY `patient_sale_invoices_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `patient_sale_invoice_items`
--
ALTER TABLE `patient_sale_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_sale_invoice_items_patient_sale_invoice_id_index` (`patient_sale_invoice_id`),
  ADD KEY `patient_sale_invoice_items_drug_id_index` (`drug_id`);

--
-- Indexes for table `patient_services`
--
ALTER TABLE `patient_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_services_service_id_foreign` (`service_id`),
  ADD KEY `patient_services_user_id_foreign` (`user_id`),
  ADD KEY `patient_services_doctor_id_foreign` (`doctor_id`),
  ADD KEY `patient_services_reservation_id_foreign` (`reservation_id`),
  ADD KEY `patient_services_invoice_id_foreign` (`invoice_id`),
  ADD KEY `patient_services_clinic_id_foreign` (`clinic_id`),
  ADD KEY `patient_services_point_id_foreign` (`point_id`),
  ADD KEY `patient_services_nurse_id_foreign` (`nurse_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`permission`,`guard_name`) USING BTREE,
  ADD KEY `permissions_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `permissions_requests`
--
ALTER TABLE `permissions_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_requests_permission_owner_foreign` (`permission_owner`),
  ADD KEY `permissions_requests_clinic_id_foreign` (`clinic_id`),
  ADD KEY `permissions_requests_permission_type_foreign` (`permission_type`);

--
-- Indexes for table `permissions_types`
--
ALTER TABLE `permissions_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pharmacy_invoices`
--
ALTER TABLE `pharmacy_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_invoices_supplier_id_index` (`supplier_id`),
  ADD KEY `pharmacy_invoices_store_id_index` (`store_id`),
  ADD KEY `pharmacy_invoices_patient_id_index` (`patient_id`),
  ADD KEY `pharmacy_invoices_doctor_id_index` (`doctor_id`),
  ADD KEY `pharmacy_invoices_customer_id_index` (`customer_id`),
  ADD KEY `pharmacy_invoices_account_tree_id_index` (`account_tree_id`),
  ADD KEY `pharmacy_invoices_clinic_id_index` (`clinic_id`),
  ADD KEY `pharmacy_invoices_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `pharmacy_invoice_items`
--
ALTER TABLE `pharmacy_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_invoice_items_pharmacy_invoice_id_index` (`pharmacy_invoice_id`),
  ADD KEY `pharmacy_invoice_items_drug_id_index` (`drug_id`);

--
-- Indexes for table `pharmacy_prescriptions`
--
ALTER TABLE `pharmacy_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_prescriptions_user_id_foreign` (`user_id`),
  ADD KEY `pharmacy_prescriptions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `pharmacy_prescriptions_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `pharmacy_prescriptions_details`
--
ALTER TABLE `pharmacy_prescriptions_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_prescriptions_details_pharmacy_prescription_id_foreign` (`pharmacy_prescription_id`),
  ADD KEY `pharmacy_prescriptions_details_drug_id_foreign` (`drug_id`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `points_exchanges`
--
ALTER TABLE `points_exchanges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `posts_settings`
--
ALTER TABLE `posts_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_invoices_supplier_id_index` (`supplier_id`),
  ADD KEY `purchase_invoices_store_id_index` (`store_id`),
  ADD KEY `purchase_invoices_clinic_id_index` (`clinic_id`),
  ADD KEY `purchase_invoices_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_invoice_items_purchase_invoice_id_index` (`purchase_invoice_id`),
  ADD KEY `purchase_invoice_items_drug_id_index` (`drug_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regions_city_id_foreign` (`city_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_user_id_foreign` (`user_id`),
  ADD KEY `reservations_doctor_id_foreign` (`doctor_id`),
  ADD KEY `reservations_clinic_id_foreign` (`clinic_id`),
  ADD KEY `reservations_reception_id_foreign` (`reception_id`),
  ADD KEY `reservations_status_id_foreign` (`status_id`),
  ADD KEY `reservations_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `reservation_chats`
--
ALTER TABLE `reservation_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_chats_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `reservation_drugs`
--
ALTER TABLE `reservation_drugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_drugs_reservation_id_foreign` (`reservation_id`),
  ADD KEY `reservation_drugs_doctor_id_foreign` (`doctor_id`),
  ADD KEY `reservation_drugs_user_id_foreign` (`user_id`),
  ADD KEY `reservation_drugs_drug_id_foreign` (`drug_id`);

--
-- Indexes for table `reservation_rates`
--
ALTER TABLE `reservation_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_rates_doctor_id_foreign` (`doctor_id`),
  ADD KEY `reservation_rates_reservation_id_foreign` (`reservation_id`),
  ADD KEY `reservation_rates_user_id_foreign` (`user_id`),
  ADD KEY `reservation_rates_clinic_id_doctor_id_index` (`clinic_id`,`doctor_id`);

--
-- Indexes for table `reservation_vital_signs`
--
ALTER TABLE `reservation_vital_signs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_vital_signs_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `responsible_people`
--
ALTER TABLE `responsible_people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restrictions`
--
ALTER TABLE `restrictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restrictions_clinic_id_foreign` (`clinic_id`),
  ADD KEY `restrictions_account_id_foreign` (`account_id`),
  ADD KEY `restrictions_cost_center_id_foreign` (`cost_center_id`),
  ADD KEY `restrictions_daily_entry_id_foreign` (`daily_entry_id`),
  ADD KEY `restrictions_final_accounts_foreign` (`final_accounts`);

--
-- Indexes for table `result_manuals`
--
ALTER TABLE `result_manuals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_manuals_patient_service_id_foreign` (`patient_service_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sale_invoices`
--
ALTER TABLE `sale_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_invoices_customer_id_index` (`customer_id`),
  ADD KEY `sale_invoices_account_tree_id_index` (`account_tree_id`),
  ADD KEY `sale_invoices_clinic_id_index` (`clinic_id`),
  ADD KEY `sale_invoices_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `sale_invoice_items`
--
ALTER TABLE `sale_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_invoice_items_sale_invoice_id_index` (`sale_invoice_id`),
  ADD KEY `sale_invoice_items_drug_id_index` (`drug_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_category_id_foreign` (`category_id`),
  ADD KEY `services_specialty_id_foreign` (`specialty_id`),
  ADD KEY `services_clinic_id_foreign` (`clinic_id`),
  ADD KEY `services_created_by_foreign` (`created_by`);

--
-- Indexes for table `services_categories`
--
ALTER TABLE `services_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_categories_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `service_analysis_attributes`
--
ALTER TABLE `service_analysis_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_analysis_attributes_service_id_foreign` (`service_id`),
  ADD KEY `service_analysis_attributes_age_id_foreign` (`age_id`),
  ADD KEY `service_analysis_attributes_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_app_type_foreign` (`app_type`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_account_type_foreign` (`account_type`),
  ADD KEY `shifts_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `shift_dates`
--
ALTER TABLE `shift_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_dates_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `shift_employees`
--
ALTER TABLE `shift_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_employees_account_type_foreign` (`account_type`),
  ADD KEY `shift_employees_clinic_id_foreign` (`clinic_id`),
  ADD KEY `shift_employees_employee_id_foreign` (`employee_id`),
  ADD KEY `shift_employees_shift_id_foreign` (`shift_id`),
  ADD KEY `shift_employees_checkin_another_employee_foreign` (`checkin_another_employee`),
  ADD KEY `shift_employees_checkout_another_employee_foreign` (`checkout_another_employee`);

--
-- Indexes for table `sick_leaves`
--
ALTER TABLE `sick_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sick_leaves_user_id_foreign` (`user_id`),
  ADD KEY `sick_leaves_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialties_parent_id_foreign` (`parent_id`),
  ADD KEY `specialties_created_by_foreign` (`created_by`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_conversions`
--
ALTER TABLE `status_conversions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_conversions_user_id_foreign` (`user_id`),
  ADD KEY `status_conversions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `status_conversions_reception_id_foreign` (`reception_id`),
  ADD KEY `status_conversions_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stores_responsible_person_id_index` (`responsible_person_id`),
  ADD KEY `stores_clinic_id_index` (`clinic_id`),
  ADD KEY `stores_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `subscriptions_package_clinics`
--
ALTER TABLE `subscriptions_package_clinics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_package_clinics_clinic_id_foreign` (`clinic_id`),
  ADD KEY `subscriptions_package_clinics_package_id_foreign` (`package_id`);

--
-- Indexes for table `subscriptions_package_users`
--
ALTER TABLE `subscriptions_package_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_package_users_package_id_foreign` (`package_id`),
  ADD KEY `subscriptions_package_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_clinic_id_index` (`clinic_id`),
  ADD KEY `suppliers_pharmacy_id_index` (`pharmacy_id`);

--
-- Indexes for table `takafoul_discounts`
--
ALTER TABLE `takafoul_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_results_user_id_foreign` (`user_id`),
  ADD KEY `test_results_doctor_id_foreign` (`doctor_id`),
  ADD KEY `test_results_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `test_results_details`
--
ALTER TABLE `test_results_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_results_details_test_result_id_foreign` (`test_result_id`),
  ADD KEY `test_results_details_member_id_foreign` (`member_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_jwt_token_unique` (`jwt_token`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_city_id_foreign` (`city_id`),
  ADD KEY `users_parent_id_foreign` (`parent_id`),
  ADD KEY `users_nationality_id_foreign` (`nationality_id`),
  ADD KEY `users_country_id_foreign` (`country_id`),
  ADD KEY `users_gover_id_foreign` (`gover_id`),
  ADD KEY `users_company_id_foreign` (`company_id`),
  ADD KEY `users_class_id_foreign` (`class_id`),
  ADD KEY `users_reception_id_foreign` (`reception_id`),
  ADD KEY `users_region_id_foreign` (`region_id`);

--
-- Indexes for table `users_members`
--
ALTER TABLE `users_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_members_phone_unique` (`phone`),
  ADD KEY `users_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_coupons_user_id_foreign` (`user_id`),
  ADD KEY `user_coupons_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_points_user_id_foreign` (`user_id`),
  ADD KEY `user_points_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verification_codes_user_id_foreign` (`user_id`);

--
-- Indexes for table `vital_signs`
--
ALTER TABLE `vital_signs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vital_signs_user_id_foreign` (`user_id`),
  ADD KEY `vital_signs_doctor_id_foreign` (`doctor_id`),
  ADD KEY `vital_signs_clinic_id_foreign` (`clinic_id`),
  ADD KEY `vital_signs_emergency_id_foreign` (`emergency_id`),
  ADD KEY `vital_signs_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `working_days`
--
ALTER TABLE `working_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `working_days_day_id_foreign` (`day_id`),
  ADD KEY `working_days_pharmacist_id_foreign` (`pharmacist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `age_categories`
--
ALTER TABLE `age_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `all_permissions`
--
ALTER TABLE `all_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `app_types`
--
ALTER TABLE `app_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bonds`
--
ALTER TABLE `bonds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `clinics_permissions`
--
ALTER TABLE `clinics_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinic_contracts`
--
ALTER TABLE `clinic_contracts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clinic_device_tokens`
--
ALTER TABLE `clinic_device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `clinic_points`
--
ALTER TABLE `clinic_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `clinic_point_nursings`
--
ALTER TABLE `clinic_point_nursings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clinic_posts_counts`
--
ALTER TABLE `clinic_posts_counts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clinic_ratings`
--
ALTER TABLE `clinic_ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `clinic_services`
--
ALTER TABLE `clinic_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clinic_specialists`
--
ALTER TABLE `clinic_specialists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT for table `cms_items`
--
ALTER TABLE `cms_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `cms_item_translations`
--
ALTER TABLE `cms_item_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `cms_languages`
--
ALTER TABLE `cms_languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cms_links`
--
ALTER TABLE `cms_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_link_translations`
--
ALTER TABLE `cms_link_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cms_page_translations`
--
ALTER TABLE `cms_page_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cms_sections`
--
ALTER TABLE `cms_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cms_section_translations`
--
ALTER TABLE `cms_section_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `complaint_boxes`
--
ALTER TABLE `complaint_boxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `days`
--
ALTER TABLE `days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `demo_requests`
--
ALTER TABLE `demo_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `doctor_conditions`
--
ALTER TABLE `doctor_conditions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `doctor_degrees`
--
ALTER TABLE `doctor_degrees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctor_emergencies`
--
ALTER TABLE `doctor_emergencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `drugs`
--
ALTER TABLE `drugs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `drugs_emergencies`
--
ALTER TABLE `drugs_emergencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drug_clinics_taxes`
--
ALTER TABLE `drug_clinics_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drug_sections`
--
ALTER TABLE `drug_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `electronic_payments`
--
ALTER TABLE `electronic_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergencies`
--
ALTER TABLE `emergencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emergency_hospitals`
--
ALTER TABLE `emergency_hospitals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurances_companies_services`
--
ALTER TABLE `insurances_companies_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `insurance_classes`
--
ALTER TABLE `insurance_classes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `insurance_companies`
--
ALTER TABLE `insurance_companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_records`
--
ALTER TABLE `inventory_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_record_items`
--
ALTER TABLE `inventory_record_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `item_units`
--
ALTER TABLE `item_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loyalty_coupon_redemptions`
--
ALTER TABLE `loyalty_coupon_redemptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loyalty_point_transactions`
--
ALTER TABLE `loyalty_point_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `loyalty_reward_coupons`
--
ALTER TABLE `loyalty_reward_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loyalty_share_logs`
--
ALTER TABLE `loyalty_share_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `medical_reports`
--
ALTER TABLE `medical_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `notification_events`
--
ALTER TABLE `notification_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification_event_recipient`
--
ALTER TABLE `notification_event_recipient`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nurse_emergencies`
--
ALTER TABLE `nurse_emergencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patient_medical_reports`
--
ALTER TABLE `patient_medical_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `patient_sale_invoices`
--
ALTER TABLE `patient_sale_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_sale_invoice_items`
--
ALTER TABLE `patient_sale_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_services`
--
ALTER TABLE `patient_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `permissions_requests`
--
ALTER TABLE `permissions_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permissions_types`
--
ALTER TABLE `permissions_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy_invoices`
--
ALTER TABLE `pharmacy_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy_invoice_items`
--
ALTER TABLE `pharmacy_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy_prescriptions`
--
ALTER TABLE `pharmacy_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pharmacy_prescriptions_details`
--
ALTER TABLE `pharmacy_prescriptions_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `points_exchanges`
--
ALTER TABLE `points_exchanges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `posts_settings`
--
ALTER TABLE `posts_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `reservation_chats`
--
ALTER TABLE `reservation_chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `reservation_drugs`
--
ALTER TABLE `reservation_drugs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reservation_rates`
--
ALTER TABLE `reservation_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reservation_vital_signs`
--
ALTER TABLE `reservation_vital_signs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `responsible_people`
--
ALTER TABLE `responsible_people`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restrictions`
--
ALTER TABLE `restrictions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `result_manuals`
--
ALTER TABLE `result_manuals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sale_invoices`
--
ALTER TABLE `sale_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_invoice_items`
--
ALTER TABLE `sale_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `services_categories`
--
ALTER TABLE `services_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `service_analysis_attributes`
--
ALTER TABLE `service_analysis_attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `shift_dates`
--
ALTER TABLE `shift_dates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_employees`
--
ALTER TABLE `shift_employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `sick_leaves`
--
ALTER TABLE `sick_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `status_conversions`
--
ALTER TABLE `status_conversions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions_package_clinics`
--
ALTER TABLE `subscriptions_package_clinics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscriptions_package_users`
--
ALTER TABLE `subscriptions_package_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `takafoul_discounts`
--
ALTER TABLE `takafoul_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_results_details`
--
ALTER TABLE `test_results_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `users_members`
--
ALTER TABLE `users_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_coupons`
--
ALTER TABLE `user_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_points`
--
ALTER TABLE `user_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `vital_signs`
--
ALTER TABLE `vital_signs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `working_days`
--
ALTER TABLE `working_days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `all_permissions`
--
ALTER TABLE `all_permissions`
  ADD CONSTRAINT `all_permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `all_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_account_type_foreign` FOREIGN KEY (`account_type`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendances_another_employee_foreign` FOREIGN KEY (`another_employee`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendances_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendances_day_id_foreign` FOREIGN KEY (`day_id`) REFERENCES `days` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance_settings`
--
ALTER TABLE `attendance_settings`
  ADD CONSTRAINT `attendance_settings_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bonds`
--
ALTER TABLE `bonds`
  ADD CONSTRAINT `bonds_reception_id_foreign` FOREIGN KEY (`reception_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bonds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinics`
--
ALTER TABLE `clinics`
  ADD CONSTRAINT `clinics_app_type_foreign` FOREIGN KEY (`app_type`) REFERENCES `app_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinics_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinics_nursing_point_id_foreign` FOREIGN KEY (`nursing_point_id`) REFERENCES `clinic_point_nursings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinics_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinics_permissions`
--
ALTER TABLE `clinics_permissions`
  ADD CONSTRAINT `clinics_permissions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinics_permissions_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `all_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinics_permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `all_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_contracts`
--
ALTER TABLE `clinic_contracts`
  ADD CONSTRAINT `clinic_contracts_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clinic_device_tokens`
--
ALTER TABLE `clinic_device_tokens`
  ADD CONSTRAINT `clinic_device_tokens_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  ADD CONSTRAINT `clinic_offers_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_offers_specialty_id_foreign` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_points`
--
ALTER TABLE `clinic_points`
  ADD CONSTRAINT `clinic_points_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_point_nursings`
--
ALTER TABLE `clinic_point_nursings`
  ADD CONSTRAINT `clinic_point_nursings_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_ratings`
--
ALTER TABLE `clinic_ratings`
  ADD CONSTRAINT `clinic_ratings_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_ratings_rating_id_foreign` FOREIGN KEY (`rating_id`) REFERENCES `ratings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_services`
--
ALTER TABLE `clinic_services`
  ADD CONSTRAINT `clinic_services_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_specialists`
--
ALTER TABLE `clinic_specialists`
  ADD CONSTRAINT `clinic_specialists_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_specialists_specialty_id_foreign` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_items`
--
ALTER TABLE `cms_items`
  ADD CONSTRAINT `cms_items_cms_section_id_foreign` FOREIGN KEY (`cms_section_id`) REFERENCES `cms_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_item_translations`
--
ALTER TABLE `cms_item_translations`
  ADD CONSTRAINT `cms_item_translations_cms_item_id_foreign` FOREIGN KEY (`cms_item_id`) REFERENCES `cms_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_link_translations`
--
ALTER TABLE `cms_link_translations`
  ADD CONSTRAINT `cms_link_translations_cms_link_id_foreign` FOREIGN KEY (`cms_link_id`) REFERENCES `cms_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_page_translations`
--
ALTER TABLE `cms_page_translations`
  ADD CONSTRAINT `cms_page_translations_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_sections`
--
ALTER TABLE `cms_sections`
  ADD CONSTRAINT `cms_sections_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cms_section_translations`
--
ALTER TABLE `cms_section_translations`
  ADD CONSTRAINT `cms_section_translations_cms_section_id_foreign` FOREIGN KEY (`cms_section_id`) REFERENCES `cms_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint_boxes`
--
ALTER TABLE `complaint_boxes`
  ADD CONSTRAINT `complaint_boxes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaint_boxes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD CONSTRAINT `contact_us_read_by_foreign` FOREIGN KEY (`read_by`) REFERENCES `clinics` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupons_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customers_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  ADD CONSTRAINT `doctor_appointments_day_id_foreign` FOREIGN KEY (`day_id`) REFERENCES `days` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_conditions`
--
ALTER TABLE `doctor_conditions`
  ADD CONSTRAINT `doctor_conditions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_emergencies`
--
ALTER TABLE `doctor_emergencies`
  ADD CONSTRAINT `doctor_emergencies_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_emergencies_emergency_id_foreign` FOREIGN KEY (`emergency_id`) REFERENCES `emergencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drugs`
--
ALTER TABLE `drugs`
  ADD CONSTRAINT `drugs_alternative_id_foreign` FOREIGN KEY (`alternative_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drugs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drugs_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drugs_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drugs_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drugs_emergencies`
--
ALTER TABLE `drugs_emergencies`
  ADD CONSTRAINT `drugs_emergencies_drugs_id_foreign` FOREIGN KEY (`drugs_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drugs_emergencies_emergency_id_foreign` FOREIGN KEY (`emergency_id`) REFERENCES `emergencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drug_clinics_taxes`
--
ALTER TABLE `drug_clinics_taxes`
  ADD CONSTRAINT `drug_clinics_taxes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_clinics_taxes_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_clinics_taxes_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drug_sections`
--
ALTER TABLE `drug_sections`
  ADD CONSTRAINT `drug_sections_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_sections_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_sections_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_sections_grand_unit_id_foreign` FOREIGN KEY (`grand_unit_id`) REFERENCES `item_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_sections_micro_unit_id_foreign` FOREIGN KEY (`micro_unit_id`) REFERENCES `item_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drug_sections_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `electronic_payments`
--
ALTER TABLE `electronic_payments`
  ADD CONSTRAINT `electronic_payments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `electronic_payments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `electronic_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergencies`
--
ALTER TABLE `emergencies`
  ADD CONSTRAINT `emergencies_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `emergencies_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `emergencies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_hospitals`
--
ALTER TABLE `emergency_hospitals`
  ADD CONSTRAINT `emergency_hospitals_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `emergency_hospitals_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `insurances_companies_services`
--
ALTER TABLE `insurances_companies_services`
  ADD CONSTRAINT `insurances_companies_services_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `insurance_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurances_companies_services_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurances_companies_services_clinic_service_id_foreign` FOREIGN KEY (`clinic_service_id`) REFERENCES `clinic_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurances_companies_services_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurances_companies_services_drug_section_id_foreign` FOREIGN KEY (`drug_section_id`) REFERENCES `drug_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurances_companies_services_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insurance_classes`
--
ALTER TABLE `insurance_classes`
  ADD CONSTRAINT `insurance_classes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_classes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_classes_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insurance_companies`
--
ALTER TABLE `insurance_companies`
  ADD CONSTRAINT `insurance_companies_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_companies_insurance_company_id_foreign` FOREIGN KEY (`insurance_company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_companies_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  ADD CONSTRAINT `insurance_policies_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `insurance_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_policies_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insurance_policies_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventories_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventories_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventories_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_records`
--
ALTER TABLE `inventory_records`
  ADD CONSTRAINT `inventory_records_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_records_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_record_items`
--
ALTER TABLE `inventory_record_items`
  ADD CONSTRAINT `inventory_record_items_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_record_items_inventory_record_id_foreign` FOREIGN KEY (`inventory_record_id`) REFERENCES `inventory_records` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_payment_method_foreign` FOREIGN KEY (`payment_method`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_reception_id_foreign` FOREIGN KEY (`reception_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_units`
--
ALTER TABLE `item_units`
  ADD CONSTRAINT `item_units_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_units_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_reports`
--
ALTER TABLE `medical_reports`
  ADD CONSTRAINT `medical_reports_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `medical_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification_event_recipient`
--
ALTER TABLE `notification_event_recipient`
  ADD CONSTRAINT `notification_event_recipient_notification_event_id_foreign` FOREIGN KEY (`notification_event_id`) REFERENCES `notification_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_event_recipient_notification_recipient_id_foreign` FOREIGN KEY (`notification_recipient_id`) REFERENCES `notification_recipients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nurse_emergencies`
--
ALTER TABLE `nurse_emergencies`
  ADD CONSTRAINT `nurse_emergencies_emergency_id_foreign` FOREIGN KEY (`emergency_id`) REFERENCES `emergencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nurse_emergencies_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_medical_reports`
--
ALTER TABLE `patient_medical_reports`
  ADD CONSTRAINT `patient_medical_reports_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `medical_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_medical_reports_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `patient_medical_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_medical_reports_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `medical_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_medical_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
