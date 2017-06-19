# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.23)
# Database: blade_log
# Generation Time: 2017-06-19 08:37:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table interface_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `interface_info`;

CREATE TABLE `interface_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int(11) unsigned NOT NULL,
  `method` varchar(128) NOT NULL,
  `uri` text NOT NULL,
  `avg_request_time` int(7) NOT NULL DEFAULT '0',
  `max_request_time` int(7) NOT NULL DEFAULT '0',
  `min_request_time` int(7) NOT NULL DEFAULT '0',
  `request_count` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_system_id` (`system_id`),
  KEY `idx_uri` (`uri`(200))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table interface_statistics
# ------------------------------------------------------------

DROP TABLE IF EXISTS `interface_statistics`;

CREATE TABLE `interface_statistics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int(11) unsigned NOT NULL,
  `interface_id` int(11) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `max_request_time` int(7) unsigned NOT NULL DEFAULT '0',
  `min_request_time` int(7) unsigned NOT NULL DEFAULT '0',
  `avg_request_time` int(7) unsigned NOT NULL DEFAULT '0',
  `request_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `code_200_count` int(11) NOT NULL DEFAULT '0',
  `code_4xx_count` int(10) unsigned NOT NULL DEFAULT '0',
  `code_499_count` int(10) unsigned NOT NULL DEFAULT '0',
  `code_5xx_count` int(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_date_system_interface` (`date`,`system_id`,`interface_id`),
  KEY `idx_avgrequest` (`avg_request_time`),
  KEY `idx_systemid` (`system_id`),
  KEY `idx_interface_id` (`interface_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_log`;

CREATE TABLE `request_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `accesslog_file` varchar(128) NOT NULL,
  `accesslog_offset` int(11) NOT NULL,
  `system_id` int(11) unsigned NOT NULL DEFAULT '0',
  `interface_id` int(11) unsigned NOT NULL DEFAULT '0',
  `server_ip` varchar(128) NOT NULL,
  `client_ip` varchar(128) NOT NULL DEFAULT '',
  `request_header` varchar(512) NOT NULL,
  `request_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `request_querystr` text NOT NULL,
  `http_code` int(4) unsigned NOT NULL,
  `country` varchar(128) NOT NULL DEFAULT '',
  `region` varchar(128) NOT NULL DEFAULT '',
  `city` varchar(128) NOT NULL DEFAULT '',
  `request_consume` int(7) NOT NULL COMMENT '请求耗时，毫秒',
  `upstream_consume` int(7) NOT NULL COMMENT 'nginx到fpm的耗时，毫秒',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_accesslog_clientIp` (`accesslog_file`,`accesslog_offset`,`client_ip`),
  KEY `idx_systemid` (`system_id`),
  KEY `idx_interfaceid` (`interface_id`),
  KEY `idx_request_time` (`request_time`),
  KEY `idx_request_consume` (`request_consume`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table system
# ------------------------------------------------------------

DROP TABLE IF EXISTS `system`;

CREATE TABLE `system` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
