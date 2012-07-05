/*
SQLyog Professional v9.20 
MySQL - 5.1.53-community-log : Database - serveaiuto
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `categorie` */

DROP TABLE IF EXISTS `categorie`;

CREATE TABLE `categorie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `offerte` */

DROP TABLE IF EXISTS `offerte`;

CREATE TABLE `offerte` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `categoria_id` smallint(6) DEFAULT NULL,
  `offerta` text COLLATE utf8_unicode_ci,
  `telefono` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sito` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indirizzo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referente` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_evidenza` tinyint(1) unsigned DEFAULT '0',
  `verificata` tinyint(1) DEFAULT '0',
  `completa` tinyint(1) unsigned DEFAULT '0',
  `pubblica` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `offerte_province` */

DROP TABLE IF EXISTS `offerte_province`;

CREATE TABLE `offerte_province` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offerta_id` int(11) NOT NULL,
  `provincia_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `offerte_tags` */

DROP TABLE IF EXISTS `offerte_tags`;

CREATE TABLE `offerte_tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `offerta_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offerta_id` (`offerta_id`,`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `province` */

DROP TABLE IF EXISTS `province`;

CREATE TABLE `province` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `provincia` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `riferimenti` text COLLATE utf8_unicode_ci NOT NULL,
  `aperta` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `province_richieste` */

DROP TABLE IF EXISTS `province_richieste`;

CREATE TABLE `province_richieste` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provincia_id` int(11) NOT NULL,
  `richiesta_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `richieste` */

DROP TABLE IF EXISTS `richieste`;

CREATE TABLE `richieste` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pubblica` tinyint(1) unsigned DEFAULT '0',
  `cosa_serve` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `categoria_id` smallint(6) DEFAULT NULL,
  `dove_a_chi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `testo` text COLLATE utf8_unicode_ci,
  `telefono` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(12) DEFAULT NULL,
  `scadenza` date DEFAULT NULL,
  `attiva` tinyint(1) DEFAULT '1',
  `in_evidenza` tinyint(1) DEFAULT '0',
  `verificata` tinyint(1) DEFAULT '0',
  `supertop` tinyint(1) DEFAULT '0',
  `segnala_in_indice_sito` tinyint(1) DEFAULT '0',
  `indirizzo_reale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localita_gmaps` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completa` tinyint(1) DEFAULT '0',
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `richieste_tags` */

DROP TABLE IF EXISTS `richieste_tags`;

CREATE TABLE `richieste_tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `richiesta_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `richiesta_id` (`richiesta_id`,`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tipi` */

DROP TABLE IF EXISTS `tipi`;

CREATE TABLE `tipi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `immagine` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `provincia_id` smallint(6) DEFAULT NULL,
  `password_reset` varchar(40) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  `role_id` smallint(6) DEFAULT '3',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `volontari` */

DROP TABLE IF EXISTS `volontari`;

CREATE TABLE `volontari` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `indirizzo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cellulare` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `disponibile` tinyint(1) DEFAULT '1',
  `disponibilita_certa` tinyint(1) DEFAULT '0',
  `attivita_lista` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
