-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2013 at 09:24 AM
-- Server version: 5.5.15
-- PHP Version: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `osc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE IF NOT EXISTS `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL,
  `sort_order` int(3) DEFAULT NULL,
  `date_added` int(32) DEFAULT NULL,
  `last_modified` int(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category_image_ref`
--

CREATE TABLE IF NOT EXISTS `tbl_category_image_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`category_id`,`image_id`),
  KEY `fk_tbl_category_image_ref_tbl_category_idx` (`category_id`),
  KEY `fk_tbl_category_image_ref_tbl_image1_idx` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category_multilingual`
--

CREATE TABLE IF NOT EXISTS `tbl_category_multilingual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`category_id`,`language_id`),
  KEY `idx_categories_name` (`name`),
  KEY `fk_categories_description_categories1_idx` (`category_id`),
  KEY `fk_tbl_category_description_tbl_language1_idx` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE IF NOT EXISTS `tbl_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` longblob NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `size` int(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE IF NOT EXISTS `tbl_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` char(4) NOT NULL,
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_LANGUAGES_NAME` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language_image_ref`
--

CREATE TABLE IF NOT EXISTS `tbl_language_image_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbl_language_image_ref_tbl_language1_idx` (`language_id`),
  KEY `fk_tbl_language_image_ref_tbl_image1_idx` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturer`
--

CREATE TABLE IF NOT EXISTS `tbl_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `manufacturers_image` varchar(64) DEFAULT NULL,
  `date_added` int(32) DEFAULT NULL,
  `last_modified` int(32) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `url_clicked` int(11) DEFAULT NULL,
  `date_last_click` int(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_MANUFACTURERS_NAME` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturer_image_ref`
--

CREATE TABLE IF NOT EXISTS `tbl_manufacturer_image_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufactorer_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbl_manufacturer_image_ref_tbl_manufacturer1_idx` (`manufactorer_id`),
  KEY `fk_tbl_manufacturer_image_ref_tbl_image1_idx` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturer_multilingual`
--

CREATE TABLE IF NOT EXISTS `tbl_manufacturer_multilingual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `manufacturers_url` varchar(255) DEFAULT NULL,
  `url_clicked` int(5) DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_manufacturers_info_manufacturers1_idx` (`manufacturer_id`),
  KEY `fk_tbl_manufacturer_multilingual_tbl_language1_idx` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE IF NOT EXISTS `tbl_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `sku` varchar(128) DEFAULT NULL,
  `quantity` int(4) DEFAULT NULL COMMENT '	',
  `model` varchar(12) DEFAULT NULL,
  `price` decimal(15,4) DEFAULT NULL,
  `date_added` int(32) NOT NULL,
  `last_modified` int(32) DEFAULT NULL,
  `date_available` int(32) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `tax_class_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `description` text COMMENT '	',
  `url` varchar(255) DEFAULT NULL,
  `viewed` int(11) DEFAULT NULL,
  `ordered` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_products_model` (`model`),
  KEY `idx_products_date_added` (`date_added`),
  KEY `fk_products_manufacturers1_idx` (`manufacturer_id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_attribute`
--

CREATE TABLE IF NOT EXISTS `tbl_product_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_attribute_multilingual`
--

CREATE TABLE IF NOT EXISTS `tbl_product_attribute_multilingual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbl_product_attribute_multilingual_tbl_product_attribute_idx` (`attribute_id`),
  KEY `fk_tbl_product_attribute_multilingual_tbl_language1_idx` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_attribute_value`
--

CREATE TABLE IF NOT EXISTS `tbl_product_attribute_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbl_product_attribute_value_tbl_product_attribute1_idx` (`attribute_id`),
  KEY `fk_tbl_product_attribute_value_tbl_product1_idx` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category_ref`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`product_id`,`category_id`),
  KEY `fk_products_to_categories_products1_idx` (`product_id`),
  KEY `fk_products_to_categories_categories1_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_image_ref`
--

CREATE TABLE IF NOT EXISTS `tbl_product_image_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `htmlcontent` text,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`product_id`,`image_id`),
  KEY `products_images_prodid` (`product_id`),
  KEY `fk_tbl_product_image_ref_tbl_image1_idx` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_multilingual`
--

CREATE TABLE IF NOT EXISTS `tbl_product_multilingual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `desc` text,
  `url` varchar(255) DEFAULT NULL,
  `viewed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbl_product_multilingual_tbl_product1_idx` (`product_id`),
  KEY `fk_tbl_product_multilingual_tbl_language1_idx` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profiles`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profiles_fields`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_category_image_ref`
--
ALTER TABLE `tbl_category_image_ref`
  ADD CONSTRAINT `fk_tbl_category_image_ref_tbl_category` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_category_image_ref_tbl_image1` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_category_multilingual`
--
ALTER TABLE `tbl_category_multilingual`
  ADD CONSTRAINT `fk_categories_description_categories1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_category_description_tbl_language1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_language_image_ref`
--
ALTER TABLE `tbl_language_image_ref`
  ADD CONSTRAINT `fk_tbl_language_image_ref_tbl_language1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_language_image_ref_tbl_image1` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_manufacturer_image_ref`
--
ALTER TABLE `tbl_manufacturer_image_ref`
  ADD CONSTRAINT `fk_tbl_manufacturer_image_ref_tbl_manufacturer1` FOREIGN KEY (`manufactorer_id`) REFERENCES `tbl_manufacturer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_manufacturer_image_ref_tbl_image1` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_manufacturer_multilingual`
--
ALTER TABLE `tbl_manufacturer_multilingual`
  ADD CONSTRAINT `fk_manufacturers_info_manufacturers1` FOREIGN KEY (`manufacturer_id`) REFERENCES `tbl_manufacturer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_manufacturer_multilingual_tbl_language1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `fk_products_manufacturers1` FOREIGN KEY (`manufacturer_id`) REFERENCES `tbl_manufacturer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_attribute_multilingual`
--
ALTER TABLE `tbl_product_attribute_multilingual`
  ADD CONSTRAINT `fk_tbl_product_attribute_multilingual_tbl_product_attribute1` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_product_attribute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_product_attribute_multilingual_tbl_language1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_attribute_value`
--
ALTER TABLE `tbl_product_attribute_value`
  ADD CONSTRAINT `fk_tbl_product_attribute_value_tbl_product_attribute1` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_product_attribute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_product_attribute_value_tbl_product1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_category_ref`
--
ALTER TABLE `tbl_product_category_ref`
  ADD CONSTRAINT `fk_products_to_categories_products1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_to_categories_categories1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_image_ref`
--
ALTER TABLE `tbl_product_image_ref`
  ADD CONSTRAINT `fk_products_images_products1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_product_image_ref_tbl_image1` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_multilingual`
--
ALTER TABLE `tbl_product_multilingual`
  ADD CONSTRAINT `fk_tbl_product_multilingual_tbl_product1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_product_multilingual_tbl_language1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
