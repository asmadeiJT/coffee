-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.17-0ubuntu0.16.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for symfony
CREATE DATABASE IF NOT EXISTS `symfony` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `symfony`;


-- Dumping structure for table symfony.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `Name` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table symfony.users: ~7 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `Name`) VALUES
	(1, 'Vasiliy'),
	(2, 'Stepan'),
	(3, 'Max'),
	(4, 'Yury'),
	(5, 'Vitaly'),
	(6, 'Evgeny_S'),
	(7, 'Evgeny_N');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table symfony.сoffee_consumption
CREATE TABLE IF NOT EXISTS `сoffee_consumption` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` tinyint(4) DEFAULT NULL,
  `cups` tinyint(4) DEFAULT '1',
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

-- Dumping data for table symfony.сoffee_consumption: ~5 rows (approximately)
DELETE FROM `сoffee_consumption`;
/*!40000 ALTER TABLE `сoffee_consumption` DISABLE KEYS */;
INSERT INTO `сoffee_consumption` (`id`, `user_id`, `cups`, `create_date`) VALUES
	(11, 6, 1, '2017-05-16 14:33:12'),
	(12, 3, 1, '2017-05-16 14:34:25'),
	(13, 7, 1, '2017-05-16 14:39:12'),
	(14, 2, 1, '2017-05-16 14:42:25'),
	(16, 1, 1, '2017-05-16 14:43:29'),
	(17, 1, 1, '2017-05-16 14:45:58'),
	(18, 5, 1, '2017-05-16 14:47:23'),
	(19, 2, 1, '2017-05-16 16:42:35'),
	(20, 6, 1, '2017-05-16 16:42:50'),
	(21, 3, 1, '2017-05-16 16:42:57'),
	(22, 3, 1, '2017-05-16 16:45:54'),
	(23, 4, 1, '2017-05-17 09:57:45'),
	(24, 1, 1, '2017-05-17 09:57:50'),
	(25, 6, 1, '2017-05-17 09:58:12'),
	(26, 6, 1, '2017-05-17 09:58:17'),
	(27, 6, 1, '2017-05-17 11:38:19'),
	(28, 4, 1, '2017-05-17 11:38:26'),
	(29, 1, 1, '2017-05-17 11:38:30'),
	(30, 3, 1, '2017-05-17 11:38:41'),
	(31, 6, 1, '2017-05-17 14:46:14'),
	(32, 2, 2, '2017-05-17 14:46:28'),
	(33, 3, 1, '2017-05-17 14:46:38'),
	(34, 1, 1, '2017-05-17 14:46:49'),
	(35, 7, 1, '2017-05-17 15:06:44'),
	(36, 5, 1, '2017-05-17 17:31:11'),
	(37, 2, 1, '2017-05-17 18:54:56'),
	(41, 6, 1, '2017-05-18 09:44:26'),
	(42, 1, 1, '2017-05-18 10:05:32'),
	(43, 1, 1, '2017-05-18 10:06:14'),
	(44, 3, 1, '2017-05-18 10:13:12'),
	(45, 6, 1, '2017-05-18 15:11:37'),
	(46, 1, 1, '2017-05-18 15:11:45'),
	(47, 3, 1, '2017-05-18 15:11:50'),
	(48, 2, 1, '2017-05-18 15:11:57'),
	(49, 4, 1, '2017-05-18 15:12:34'),
	(50, 7, 1, '2017-05-18 16:02:25'),
	(51, 5, 1, '2017-05-18 16:02:35'),
	(52, 6, 1, '2017-05-18 16:02:43'),
	(54, 1, 2, '2017-05-19 10:07:04'),
	(55, 4, 1, '2017-05-19 10:07:14'),
	(56, 6, 1, '2017-05-19 10:07:19'),
	(57, 3, 1, '2017-05-19 11:26:49'),
	(58, 1, 1, '2017-05-19 14:14:33'),
	(59, 2, 1, '2017-05-19 14:56:26'),
	(60, 6, 1, '2017-05-19 14:56:58'),
	(61, 1, 1, '2017-05-19 15:07:35'),
	(62, 7, 1, '2017-05-19 15:11:05'),
	(63, 6, 1, '2017-05-19 16:15:14'),
	(64, 1, 1, '2017-05-19 16:44:32'),
	(65, 4, 1, '2017-05-19 16:44:47'),
	(66, 1, 1, '2017-05-22 10:06:08'),
	(67, 6, 1, '2017-05-22 10:06:13'),
	(68, 3, 1, '2017-05-22 10:06:18'),
	(69, 1, 1, '2017-05-22 11:43:45'),
	(70, 6, 1, '2017-05-22 11:43:59'),
	(71, 6, 1, '2017-05-22 14:20:50'),
	(72, 2, 1, '2017-05-22 14:20:55'),
	(73, 7, 1, '2017-05-22 14:21:01'),
	(74, 1, 1, '2017-05-22 14:22:34'),
	(75, 4, 1, '2017-05-22 14:22:39'),
	(76, 5, 1, '2017-05-22 15:26:31');
/*!40000 ALTER TABLE `сoffee_consumption` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
