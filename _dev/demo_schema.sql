-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Okt 2011 um 01:21
-- Server Version: 5.1.57
-- PHP-Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `p3`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fc_category`
--

CREATE TABLE IF NOT EXISTS `fc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `fc_category`
--

INSERT INTO `fc_category` (`id`, `name`) VALUES
(1, 'Widgets'),
(2, 'Behaviors'),
(3, 'Components');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fc_description`
--

CREATE TABLE IF NOT EXISTS `fc_description` (
  `title` varchar(45) DEFAULT NULL,
  `text` text,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `fk_fc_description_fc_product1` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `fc_description`
--

INSERT INTO `fc_description` (`title`, `text`, `product_id`) VALUES
('Hello World!', 'Templates', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fc_feature`
--

CREATE TABLE IF NOT EXISTS `fc_feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `fc_feature`
--

INSERT INTO `fc_feature` (`id`, `name`) VALUES
(3, 'Base classes'),
(4, 'Code Templates'),
(5, 'Access control'),
(6, 'Shell'),
(8, 'GUI');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fc_feature_has_product`
--

CREATE TABLE IF NOT EXISTS `fc_feature_has_product` (
  `feature_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`feature_id`,`product_id`),
  KEY `fk_feature_has_product_feature1` (`feature_id`),
  KEY `fk_feature_has_product_product1` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `fc_feature_has_product`
--

INSERT INTO `fc_feature_has_product` (`feature_id`, `product_id`) VALUES
(3, 3),
(3, 13),
(4, 3),
(4, 13),
(5, 3),
(8, 3),
(8, 14);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fc_product`
--

CREATE TABLE IF NOT EXISTS `fc_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `madeAt` date DEFAULT NULL,
  `isAvailable` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_category1` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `fc_product`
--

INSERT INTO `fc_product` (`id`, `category_id`, `name`, `madeAt`, `isAvailable`) VALUES
(2, 2, 'CSaveRelation', '0000-00-00', NULL),
(3, 3, 'fullCrud', NULL, NULL),
(13, 3, 'fullModel', NULL, NULL),
(14, 1, 'ckeditor', NULL, NULL);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `fc_description`
--
ALTER TABLE `fc_description`
  ADD CONSTRAINT `fk_fc_description_fc_product1` FOREIGN KEY (`product_id`) REFERENCES `fc_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `fc_feature_has_product`
--
ALTER TABLE `fc_feature_has_product`
  ADD CONSTRAINT `fk_feature_has_product_feature1` FOREIGN KEY (`feature_id`) REFERENCES `fc_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feature_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `fc_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fc_product`
--
ALTER TABLE `fc_product`
  ADD CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_id`) REFERENCES `fc_category` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
