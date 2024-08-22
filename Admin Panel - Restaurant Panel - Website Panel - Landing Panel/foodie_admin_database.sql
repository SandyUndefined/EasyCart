-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 22, 2022 at 03:07 PM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodie_admin_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `routes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `role_id`, `permission`, `routes`, `created_at`, `updated_at`) VALUES
(1, 1, 'drivers', 'drivers', '2024-06-10 06:24:09', '2024-06-10 06:24:09'),
(2, 1, 'god-eye', 'map', '2024-06-11 05:31:22', '2024-06-11 05:31:22'),
(3, 1, 'zone', 'zone.list', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(4, 1, 'zone', 'zone.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(5, 1, 'zone', 'zone.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(6, 1, 'zone', 'zone.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(7, 1, 'roles', 'role.index', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(8, 1, 'roles', 'role.save', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(9, 1, 'roles', 'role.store', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(10, 1, 'roles', 'role.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(11, 1, 'roles', 'role.update', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(12, 1, 'roles', 'role.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(13, 1, 'admins', 'admin.users', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(14, 1, 'admins', 'admin.users.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(15, 1, 'admins', 'admin.users.store', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(16, 1, 'admins', 'admin.users.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(17, 1, 'admins', 'admin.users.update', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(18, 1, 'admins', 'admin.users.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(19, 1, 'users', 'users', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(20, 1, 'users', 'users.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(21, 1, 'users', 'users.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(22, 1, 'users', 'users.view', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(23, 1, 'user', 'user.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(24, 1, 'vendors', 'vendors', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(25, 1, 'vendors-document', 'vendor.document.list', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(26, 1, 'vendors-document', 'vendor.document.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(27, 1, 'restaurants', 'restaurants', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(28, 1, 'restaurants', 'restaurants.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(29, 1, 'restaurants', 'restaurants.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(30, 1, 'restaurants', 'restaurants.view', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(31, 1, 'restaurant', 'restaurant.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(32, 1, 'drivers', 'drivers', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(33, 1, 'drivers', 'drivers.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(34, 1, 'drivers', 'drivers.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(35, 1, 'drivers', 'drivers.view', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(36, 1, 'driver', 'driver.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(37, 1, 'drivers-document', 'driver.document.list', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(38, 1, 'drivers-document', 'driver.document.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(39, 1, 'reports', 'report.index', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(40, 1, 'category', 'categories', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(41, 1, 'category', 'categories.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(42, 1, 'category', 'categories.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(43, 1, 'category', 'category.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(44, 1, 'foods', 'foods', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(45, 1, 'foods', 'foods.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(46, 1, 'foods', 'foods.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(47, 1, 'foods', 'foods.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(48, 1, 'item-attribute', 'attributes', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(49, 1, 'item-attribute', 'attributes.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(50, 1, 'item-attribute', 'attributes.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(51, 1, 'attributes', 'attributes.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(52, 1, 'review-attribute', 'reviewattributes', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(53, 1, 'review-attribute', 'reviewattributes.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(54, 1, 'review-attribute', 'reviewattributes.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(55, 1, 'reviewattributes', 'reviewattributes.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(56, 1, 'orders', 'orders', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(57, 1, 'orders', 'vendors.orderprint', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(58, 1, 'orders', 'orders.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(59, 1, 'orders', 'orders.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(60, 1, 'dinein-orders', 'restaurants.booktable', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(61, 1, 'dinein-orders', 'booktable.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(62, 1, 'gift-cards', 'gift-card.index', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(63, 1, 'gift-cards', 'gift-card.save', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(64, 1, 'gift-cards', 'gift-card.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(65, 1, 'gift-card', 'gift-card.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(66, 1, 'coupons', 'coupons', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(67, 1, 'coupons', 'coupons.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(68, 1, 'coupons', 'coupons.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(69, 1, 'coupons', 'coupons.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(70, 1, 'documents', 'documents.list', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(71, 1, 'documents', 'documents.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(72, 1, 'documents', 'documents.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(73, 1, 'documents', 'documents.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(74, 1, 'general-notifications', 'notification', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(75, 1, 'general-notifications', 'notification.send', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(76, 1, 'notification', 'notification.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(77, 1, 'dynamic-notifications', 'dynamic-notification.index', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(78, 1, 'dynamic-notifications', 'dynamic-notification.save', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(79, 1, 'payments', 'payments', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(80, 1, 'restaurant-payouts', 'restaurantsPayouts', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(81, 1, 'restaurant-payouts', 'restaurantsPayouts.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(82, 1, 'driver-payments', 'driver.driverpayments', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(83, 1, 'driver-payouts', 'driversPayouts', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(84, 1, 'driver-payouts', 'driversPayouts.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(85, 1, 'wallet-transaction', 'walletstransaction', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(86, 1, 'payout-request', 'payoutRequests.drivers', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(87, 1, 'payout-request', 'payoutRequests.restaurants', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(88, 1, 'banners', 'setting.banners', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(89, 1, 'banners', 'setting.banners.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(90, 1, 'banners', 'setting.banners.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(91, 1, 'banners', 'banners.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(92, 1, 'cms', 'cms', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(93, 1, 'cms', 'cms.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(94, 1, 'cms', 'cms.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(95, 1, 'cms', 'cms.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(96, 1, 'email-template', 'email-templates.index', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(97, 1, 'email-template', 'email-templates.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(98, 1, 'global-setting', 'settings.app.globals', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(99, 1, 'currency', 'currencies', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(100, 1, 'currency', 'currencies.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(101, 1, 'currency', 'currencies.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(102, 1, 'currency', 'currency.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(103, 1, 'payment-method', 'payment-method', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(104, 1, 'admin-commission', 'settings.app.adminCommission', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(105, 1, 'radius', 'settings.app.radiusConfiguration', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(106, 1, 'dinein', 'settings.app.bookTable', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(107, 1, 'tax', 'tax', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(108, 1, 'tax', 'tax.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(109, 1, 'tax', 'tax.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(110, 1, 'tax', 'tax.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(111, 1, 'delivery-charge', 'settings.app.deliveryCharge', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(112, 1, 'language', 'settings.app.languages', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(113, 1, 'language', 'settings.app.languages.create', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(114, 1, 'language', 'settings.app.languages.edit', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(115, 1, 'language', 'language.delete', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(116, 1, 'special-offer', 'setting.specialOffer', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(117, 1, 'terms', 'termsAndConditions', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(118, 1, 'privacy', 'privacyPolicy', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(119, 1, 'home-page', 'homepageTemplate', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(120, 1, 'footer', 'footerTemplate', '2024-06-11 05:35:27', '2024-06-11 05:35:27'),
(121, 1, 'restaurantsPayouts', 'restaurantsPayouts.delete', '2024-06-12 10:08:27', '2024-06-12 10:08:27'),
(122, 1, 'driversPayouts', 'driversPayouts.delete', '2024-06-12 10:08:27', '2024-06-12 10:08:27'),
(123, 1, 'document-verification', 'settings.app.documentVerification', '2024-06-12 10:08:27', '2024-06-12 10:08:27'),
(124, 1, 'approve_vendors', 'approve.vendors.list', '2024-06-12 13:16:06', '2024-06-12 13:16:06'),
(125, 1, 'pending_vendors', 'pending.vendors.list', '2024-06-12 13:16:06', '2024-06-12 13:16:06'),
(126, 1, 'approve_drivers', 'approve.driver.list', '2024-06-12 13:16:06', '2024-06-12 13:16:06'),
(127, 1, 'pending_drivers', 'pending.driver.list', '2024-06-12 13:16:06', '2024-06-12 13:16:06'),
(128, 1, 'approve_vendors', 'approve.vendors.delete', '2024-06-12 13:45:34', '2024-06-12 13:45:34'),
(129, 1, 'pending_vendors', 'pending.vendors.delete', '2024-06-12 13:45:34', '2024-06-12 13:45:34'),
(130, 1, 'vendors', 'vendors.delete', '2024-06-12 13:45:47', '2024-06-12 13:45:47'),
(131, 1, 'approve_drivers', 'approve.driver.delete', '2024-06-12 13:49:17', '2024-06-12 13:49:17'),
(132, 1, 'pending_drivers', 'pending.driver.delete', '2024-06-12 13:49:17', '2024-06-12 13:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', '2023-11-27 05:10:43', '2023-11-27 06:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(15) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@foodie.com', NULL, '$2y$10$4D/Oi3x7gxPwZ/zxCKtgCOlPNujUnUER0vkMjQ0moL7l3cAJwTIJa', 1, 'xmMQOp8aT80phlL2714CfAxZxeNw7SNcHzGCWIflETi8sfsygU4VZuh5xZTg', '2022-02-26 12:22:29', '2023-11-29 10:45:57');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
