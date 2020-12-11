-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `xf3f_wfdownloads_broken`;
CREATE TABLE `xf3f_wfdownloads_broken` (
  `reportid` int(5) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `sender` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `date` varchar(11) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`),
  KEY `lid` (`lid`),
  KEY `sender` (`sender`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_cat`;
CREATE TABLE `xf3f_wfdownloads_cat` (
  `cid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `imgurl` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  `summary` text NOT NULL,
  `spotlighttop` int(11) NOT NULL DEFAULT '0',
  `spotlighthis` int(11) NOT NULL DEFAULT '0',
  `dohtml` tinyint(1) NOT NULL DEFAULT '0',
  `dosmiley` tinyint(1) NOT NULL DEFAULT '1',
  `doxcode` tinyint(1) NOT NULL DEFAULT '1',
  `doimage` tinyint(1) NOT NULL DEFAULT '1',
  `dobr` tinyint(1) NOT NULL DEFAULT '1',
  `weight` int(11) NOT NULL DEFAULT '0',
  `formulize_fid` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_downloads`;
CREATE TABLE `xf3f_wfdownloads_downloads` (
  `lid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(150) NOT NULL DEFAULT '',
  `filetype` varchar(100) NOT NULL DEFAULT '',
  `homepage` varchar(100) NOT NULL DEFAULT '',
  `version` varchar(20) NOT NULL DEFAULT '',
  `size` int(8) NOT NULL DEFAULT '0',
  `platform` varchar(50) NOT NULL DEFAULT '',
  `screenshot` varchar(255) NOT NULL DEFAULT '',
  `screenshot2` varchar(255) NOT NULL DEFAULT '',
  `screenshot3` varchar(255) NOT NULL DEFAULT '',
  `screenshot4` varchar(255) NOT NULL DEFAULT '',
  `submitter` int(11) NOT NULL DEFAULT '0',
  `publisher` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `rating` double(6,4) NOT NULL DEFAULT '0.0000',
  `votes` int(11) unsigned NOT NULL DEFAULT '0',
  `comments` int(11) unsigned NOT NULL DEFAULT '0',
  `license` varchar(255) NOT NULL DEFAULT '',
  `mirror` varchar(255) NOT NULL DEFAULT '',
  `price` varchar(10) NOT NULL DEFAULT 'Free',
  `paypalemail` varchar(255) NOT NULL DEFAULT '',
  `features` text NOT NULL,
  `requirements` text NOT NULL,
  `homepagetitle` varchar(255) NOT NULL DEFAULT '',
  `forumid` int(11) NOT NULL DEFAULT '0',
  `limitations` varchar(255) NOT NULL DEFAULT '30 day trial',
  `versiontypes` varchar(255) NOT NULL DEFAULT 'None',
  `dhistory` text NOT NULL,
  `published` int(11) NOT NULL DEFAULT '1089662528',
  `expired` int(10) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `offline` tinyint(1) NOT NULL DEFAULT '0',
  `summary` text NOT NULL,
  `description` text NOT NULL,
  `ipaddress` varchar(120) NOT NULL DEFAULT '0',
  `notifypub` int(1) NOT NULL DEFAULT '0',
  `formulize_idreq` int(5) NOT NULL DEFAULT '0',
  `screenshots` text NOT NULL,
  `dohtml` tinyint(1) NOT NULL DEFAULT '0',
  `dosmiley` tinyint(1) NOT NULL DEFAULT '1',
  `doxcode` tinyint(1) NOT NULL DEFAULT '1',
  `doimage` tinyint(1) NOT NULL DEFAULT '1',
  `dobr` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`lid`),
  KEY `cid` (`cid`),
  KEY `status` (`status`),
  KEY `title` (`title`(40))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_indexpage`;
CREATE TABLE `xf3f_wfdownloads_indexpage` (
  `indeximage` varchar(255) NOT NULL DEFAULT 'blank.png',
  `indexheading` varchar(255) NOT NULL DEFAULT 'Wfdownloads',
  `indexheader` text NOT NULL,
  `indexfooter` text NOT NULL,
  `nohtml` tinyint(8) NOT NULL DEFAULT '1',
  `nosmiley` tinyint(8) NOT NULL DEFAULT '1',
  `noxcodes` tinyint(8) NOT NULL DEFAULT '1',
  `noimages` tinyint(8) NOT NULL DEFAULT '1',
  `nobreak` tinyint(4) NOT NULL DEFAULT '1',
  `indexheaderalign` varchar(25) NOT NULL DEFAULT 'left',
  `indexfooteralign` varchar(25) NOT NULL DEFAULT 'center',
  FULLTEXT KEY `indexheading` (`indexheading`),
  FULLTEXT KEY `indexheader` (`indexheader`),
  FULLTEXT KEY `indexfooter` (`indexfooter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_ip_log`;
CREATE TABLE `xf3f_wfdownloads_ip_log` (
  `ip_logid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`ip_logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_meta`;
CREATE TABLE `xf3f_wfdownloads_meta` (
  `metakey` varchar(50) NOT NULL DEFAULT '',
  `metavalue` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`metakey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mimetypes`;
CREATE TABLE `xf3f_wfdownloads_mimetypes` (
  `mime_id` int(11) NOT NULL AUTO_INCREMENT,
  `mime_ext` varchar(60) NOT NULL DEFAULT '',
  `mime_types` text NOT NULL,
  `mime_name` varchar(255) NOT NULL DEFAULT '',
  `mime_admin` int(1) NOT NULL DEFAULT '1',
  `mime_user` int(1) NOT NULL DEFAULT '0',
  KEY `mime_id` (`mime_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mirrors`;
CREATE TABLE `xf3f_wfdownloads_mirrors` (
  `mirror_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `homeurl` varchar(100) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `continent` varchar(255) NOT NULL DEFAULT '',
  `downurl` varchar(255) NOT NULL DEFAULT '',
  `submit` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mirror_id`),
  KEY `categoryid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mod`;
CREATE TABLE `xf3f_wfdownloads_mod` (
  `requestid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) unsigned NOT NULL DEFAULT '0',
  `cid` int(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(150) NOT NULL DEFAULT '',
  `filetype` varchar(100) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(20) NOT NULL DEFAULT '',
  `size` int(8) NOT NULL DEFAULT '0',
  `platform` varchar(50) NOT NULL DEFAULT '',
  `screenshot` varchar(255) NOT NULL DEFAULT '',
  `screenshot2` varchar(255) NOT NULL DEFAULT '',
  `screenshot3` varchar(255) NOT NULL DEFAULT '',
  `screenshot4` varchar(255) NOT NULL DEFAULT '',
  `submitter` int(11) NOT NULL DEFAULT '0',
  `publisher` text NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `rating` double(6,4) NOT NULL DEFAULT '0.0000',
  `votes` int(11) unsigned NOT NULL DEFAULT '0',
  `comments` int(11) unsigned NOT NULL DEFAULT '0',
  `license` varchar(255) NOT NULL DEFAULT '',
  `mirror` varchar(255) NOT NULL DEFAULT '',
  `price` varchar(10) NOT NULL DEFAULT 'Free',
  `paypalemail` varchar(255) NOT NULL DEFAULT '',
  `features` text NOT NULL,
  `requirements` text NOT NULL,
  `homepagetitle` varchar(255) NOT NULL DEFAULT '',
  `forumid` int(11) NOT NULL DEFAULT '0',
  `limitations` varchar(255) NOT NULL DEFAULT '30 day trial',
  `versiontypes` varchar(255) NOT NULL DEFAULT 'None',
  `dhistory` text NOT NULL,
  `published` int(10) NOT NULL DEFAULT '0',
  `expired` int(10) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `offline` tinyint(1) NOT NULL DEFAULT '0',
  `summary` text NOT NULL,
  `description` text NOT NULL,
  `modifysubmitter` int(11) NOT NULL DEFAULT '0',
  `requestdate` int(11) NOT NULL DEFAULT '0',
  `screenshots` text NOT NULL,
  `dohtml` tinyint(1) NOT NULL DEFAULT '0',
  `dosmiley` tinyint(1) NOT NULL DEFAULT '1',
  `doxcode` tinyint(1) NOT NULL DEFAULT '1',
  `doimage` tinyint(1) NOT NULL DEFAULT '1',
  `dobr` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`requestid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_reviews`;
CREATE TABLE `xf3f_wfdownloads_reviews` (
  `review_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `review` text,
  `submit` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `rated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`review_id`),
  KEY `categoryid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_votedata`;
CREATE TABLE `xf3f_wfdownloads_votedata` (
  `ratingid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lid` int(11) unsigned NOT NULL DEFAULT '0',
  `ratinguser` int(11) NOT NULL DEFAULT '0',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ratinghostname` varchar(60) NOT NULL DEFAULT '',
  `ratingtimestamp` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ratingid`),
  KEY `ratinguser` (`ratinguser`),
  KEY `ratinghostname` (`ratinghostname`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2018-04-09 03:20:00
