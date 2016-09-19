# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Hôte: 127.0.0.1 (MySQL 5.7.15)
# Base de données: bigloo
# Temps de génération: 2016-09-19 18:06:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table pic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pic`;

CREATE TABLE `pic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_trip` int(11) NOT NULL,
  `path` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `pic` WRITE;
/*!40000 ALTER TABLE `pic` DISABLE KEYS */;

INSERT INTO `pic` (`id`, `id_trip`, `path`, `description`)
VALUES
	(1,1,'e715b25f355f72c8b9a630cba96f5944.bmp','I\'m sooo happy !');

/*!40000 ALTER TABLE `pic` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table trip
# ------------------------------------------------------------

DROP TABLE IF EXISTS `trip`;

CREATE TABLE `trip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `password` char(10) NOT NULL DEFAULT '0000000000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `trip` WRITE;
/*!40000 ALTER TABLE `trip` DISABLE KEYS */;

INSERT INTO `trip` (`id`, `name`, `password`)
VALUES
	(1,'France','0000000001'),
	(2,'Italie','0000000002');

/*!40000 ALTER TABLE `trip` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
