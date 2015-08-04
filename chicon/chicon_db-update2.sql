-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Client :  chiconfrwmdbman.mysql.db
-- Généré le :  Mar 04 Août 2015 à 09:36
-- Version du serveur :  5.5.43-0+deb7u1-log
-- Version de PHP :  5.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `chiconfrwmdbman`
--

-- --------------------------------------------------------

--
-- Structure de la table `hdw_list`
--

CREATE TABLE IF NOT EXISTS `hdw_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `common_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `firmware_version` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `hdw_list`
--

INSERT INTO `hdw_list` (`id`, `common_name`, `model`, `firmware_version`) VALUES
(1, 'chicon_demo', 'D01', '0.1a'),
(2, 'chicon_cube', 'C01', '0.1a');

-- --------------------------------------------------------

--
-- Structure de la table `hdw_service_compatibility`
--

CREATE TABLE IF NOT EXISTS `hdw_service_compatibility` (
  `id_hdw` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  PRIMARY KEY (`id_hdw`,`id_service`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `known_hdw`
--

CREATE TABLE IF NOT EXISTS `known_hdw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `insertionDate` datetime NOT NULL,
  `user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `led_hdw_list`
--

CREATE TABLE IF NOT EXISTS `led_hdw_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_led_hdw` int(11) NOT NULL,
  `id_hdw` int(11) NOT NULL,
  `led_capability` int(11) NOT NULL,
  `common_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hw` (`id_hdw`),
  KEY `id_led_hw` (`id_led_hdw`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `led_hdw_list`
--

INSERT INTO `led_hdw_list` (`id`, `id_led_hdw`, `id_hdw`, `led_capability`, `common_name`) VALUES
(1, 1, 1, 31, 'LED GROUP 1 FADDING'),
(2, 2, 1, 11, 'LED GROUP 2 TRICOLOR'),
(3, 3, 1, 25, 'LED GROUP 3 BINARY'),
(4, 1, 2, 31, 'LED_GROUP_LEFT'),
(5, 2, 2, 31, 'LED_GROUP_TOP'),
(6, 3, 2, 31, 'LED_GROUP_RIGHT');

-- --------------------------------------------------------

--
-- Structure de la table `led_service_list`
--

CREATE TABLE IF NOT EXISTS `led_service_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_led_service` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `led_type` int(11) NOT NULL,
  `common_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_service` (`id_service`),
  KEY `id_led_service` (`id_led_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `led_type`
--

CREATE TABLE IF NOT EXISTS `led_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `led_type` int(11) NOT NULL,
  `icon` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `led_type`
--

INSERT INTO `led_type` (`id`, `led_type`, `icon`) VALUES
(1, 1, 'css/images/light_binary.png'),
(2, 2, 'css/images/light_tricolor.png'),
(3, 4, 'css/images/light_rvb.png'),
(4, 8, 'css/images/light_blinking.png');

-- --------------------------------------------------------

--
-- Structure de la table `service_list`
--

CREATE TABLE IF NOT EXISTS `service_list` (
  `srvGlobalId` int(11) NOT NULL AUTO_INCREMENT,
  `common_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exec_script` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `config_script` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `exec_freq` int(11) NOT NULL,
  PRIMARY KEY (`srvGlobalId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `service_list`
--

INSERT INTO `service_list` (`srvGlobalId`, `common_name`, `icon`, `description`, `exec_script`, `config_script`, `exec_freq`) VALUES
(1, 'Weather Forecast', 'css/images/meteoLogo.jpg', 'Give the weather forecast. Light gives you trend of the forecasted weather. Idea of temperature and ', 'srvScript/exec_weather.php', 'srvScript/config_weather.php', 60000),
(2, 'Demo', 'css/images/demoLogo.png', 'Demo application, each 15 second your Chicon devices light will change of color.', 'srvScript/exec_multiLedGroup.php', 'srvScript/config_multiLedGroup.php', 15000),
(3, 'Traffic Forecast', 'css/images/trafficLogo.jpg', 'Traffic forecast. Light will give you an idea of real time journey', 'srvScript/exec_traffic.php', 'srvScript/config_traffic.php', 300000),
(4, 'Reminder', 'css/images/reminderLogo.png', 'Reminder will light on a light if you have something to do', 'srvScript/exec_reminder.php', 'srvScript/config_reminder.php', 300000),
(5, 'Air Quality', 'css/images/airQualityLogo.png', 'Gives your city''s air quality', 'srvScript/exec_airQuality.php', 'srvScript/config_airQuality.php', 600000),
(6, 'Tweeter', 'css/images/twitterLogo.png', 'Blink a light when a tweet match a pattern', 'srvScript/exec_tweet.php', 'srvScript/config_tweet.php', 120000);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `familyName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `registerHash` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_hdw_list`
--

CREATE TABLE IF NOT EXISTS `users_hdw_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_hdw` int(11) NOT NULL,
  `serial_hdw` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `firmware_hdw` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `magicNumber_hdw` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `registered` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `serial_hdw` (`serial_hdw`),
  KEY `id_user` (`id_user`),
  KEY `id_hdw` (`id_hdw`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_hdw_service_configuration`
--

CREATE TABLE IF NOT EXISTS `users_hdw_service_configuration` (
  `srvLocalId` int(11) NOT NULL AUTO_INCREMENT,
  `serial_hdw` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `id_service` int(11) NOT NULL,
  `service_args` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`srvLocalId`),
  KEY `serial_hdw` (`serial_hdw`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_hdw_service_led_mapping`
--

CREATE TABLE IF NOT EXISTS `users_hdw_service_led_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_hdw` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `localId_service` int(11) NOT NULL,
  `id_led_hdw` int(11) NOT NULL,
  `id_led_service` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `serial_hdw` (`serial_hdw`),
  KEY `id_service` (`localId_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_service_list`
--

CREATE TABLE IF NOT EXISTS `users_service_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_srv` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_user_2` (`id_user`),
  KEY `id` (`id`),
  KEY `id_srv` (`id_srv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `hdw_service_compatibility`
--
ALTER TABLE `hdw_service_compatibility`
  ADD CONSTRAINT `HDW_SERVICE_COMPATIBILITY_ibfk_1` FOREIGN KEY (`id_hdw`) REFERENCES `hdw_list` (`id`),
  ADD CONSTRAINT `HDW_SERVICE_COMPATIBILITY_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `service_list` (`srvGlobalId`);

--
-- Contraintes pour la table `led_hdw_list`
--
ALTER TABLE `led_hdw_list`
  ADD CONSTRAINT `LED_HDW_LIST_ibfk_1` FOREIGN KEY (`id_hdw`) REFERENCES `hdw_list` (`id`);

--
-- Contraintes pour la table `led_service_list`
--
ALTER TABLE `led_service_list`
  ADD CONSTRAINT `LED_SERVICE_LIST_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service_list` (`srvGlobalId`);

--
-- Contraintes pour la table `users_hdw_list`
--
ALTER TABLE `users_hdw_list`
  ADD CONSTRAINT `USERS_HDW_LIST_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `USERS_HDW_LIST_ibfk_2` FOREIGN KEY (`id_hdw`) REFERENCES `hdw_list` (`id`);

--
-- Contraintes pour la table `users_hdw_service_configuration`
--
ALTER TABLE `users_hdw_service_configuration`
  ADD CONSTRAINT `USER_HDW_SERVICE_CONFIGURATION_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service_list` (`srvGlobalId`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HDW_SERVICE_CONFIGURATION_ibfk_2` FOREIGN KEY (`serial_hdw`) REFERENCES `users_hdw_list` (`serial_hdw`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_hdw_service_led_mapping`
--
ALTER TABLE `users_hdw_service_led_mapping`
  ADD CONSTRAINT `USER_HDW_SERVICE_LED_MAPPING_ibfk_2` FOREIGN KEY (`localId_service`) REFERENCES `users_hdw_service_configuration` (`srvLocalId`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HDW_SERVICE_LED_MAPPING_ibfk_4` FOREIGN KEY (`serial_hdw`) REFERENCES `users_hdw_list` (`serial_hdw`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_service_list`
--
ALTER TABLE `users_service_list`
  ADD CONSTRAINT `users_service_list_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_service_list_ibfk_2` FOREIGN KEY (`id_srv`) REFERENCES `service_list` (`srvGlobalId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
