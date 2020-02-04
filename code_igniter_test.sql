
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `code_igniter_test` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `code_igniter_test`;


CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `real_order_id` varchar(255) NOT NULL,
  `status` enum('declined','paid') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `real_id_uniq` (`real_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `real_order_id`, `status`) VALUES
	(1, '123-12345', 'declined');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;


CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `real_transaction_id` varchar(255) NOT NULL,
  `status` enum('failed','success') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `real_id_uniq` (`real_transaction_id`),
  KEY `FK_transactions_orders` (`order_id`),
  CONSTRAINT `FK_transactions_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` (`id`, `order_id`, `real_transaction_id`, `status`) VALUES
	(1, 1, '1-12345', 'failed'),
	(3, 1, '2-12345', 'success');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
