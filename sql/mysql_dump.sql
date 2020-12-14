-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `xf3f_wfdownloads_broken`;
CREATE TABLE `xf3f_wfdownloads_broken` (
    `reportid`     INT(5)      NOT NULL AUTO_INCREMENT,
    `lid`          INT(11)     NOT NULL DEFAULT '0',
    `sender`       INT(11)     NOT NULL DEFAULT '0',
    `ip`           VARCHAR(20) NOT NULL DEFAULT '',
    `date`         VARCHAR(11) NOT NULL DEFAULT '0',
    `confirmed`    TINYINT(1)  NOT NULL DEFAULT '0',
    `acknowledged` TINYINT(1)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`reportid`),
    KEY `lid` (`lid`),
    KEY `sender` (`sender`),
    KEY `ip` (`ip`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_cat`;
CREATE TABLE `xf3f_wfdownloads_cat` (
    `cid`           INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `pid`           INT(5) UNSIGNED NOT NULL DEFAULT '0',
    `title`         VARCHAR(255)    NOT NULL DEFAULT '',
    `imgurl`        VARCHAR(255)    NOT NULL DEFAULT '',
    `description`   TEXT            NOT NULL,
    `total`         INT(11)         NOT NULL DEFAULT '0',
    `summary`       TEXT            NOT NULL,
    `spotlighttop`  INT(11)         NOT NULL DEFAULT '0',
    `spotlighthis`  INT(11)         NOT NULL DEFAULT '0',
    `dohtml`        TINYINT(1)      NOT NULL DEFAULT '0',
    `dosmiley`      TINYINT(1)      NOT NULL DEFAULT '1',
    `doxcode`       TINYINT(1)      NOT NULL DEFAULT '1',
    `doimage`       TINYINT(1)      NOT NULL DEFAULT '1',
    `dobr`          TINYINT(1)      NOT NULL DEFAULT '1',
    `weight`        INT(11)         NOT NULL DEFAULT '0',
    `formulize_fid` INT(5)          NOT NULL DEFAULT '0',
    PRIMARY KEY (`cid`),
    KEY `pid` (`pid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_downloads`;
CREATE TABLE `xf3f_wfdownloads_downloads` (
    `lid`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `cid`             INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    `title`           VARCHAR(255)     NOT NULL DEFAULT '',
    `url`             VARCHAR(255)     NOT NULL DEFAULT '',
    `filename`        VARCHAR(150)     NOT NULL DEFAULT '',
    `filetype`        VARCHAR(100)     NOT NULL DEFAULT '',
    `homepage`        VARCHAR(100)     NOT NULL DEFAULT '',
    `version`         VARCHAR(20)      NOT NULL DEFAULT '',
    `size`            INT(8)           NOT NULL DEFAULT '0',
    `platform`        VARCHAR(50)      NOT NULL DEFAULT '',
    `screenshot`      VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot2`     VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot3`     VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot4`     VARCHAR(255)     NOT NULL DEFAULT '',
    `submitter`       INT(11)          NOT NULL DEFAULT '0',
    `publisher`       VARCHAR(255)     NOT NULL DEFAULT '',
    `status`          TINYINT(2)       NOT NULL DEFAULT '0',
    `date`            INT(10)          NOT NULL DEFAULT '0',
    `hits`            INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `rating`          DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    `votes`           INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `comments`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `license`         VARCHAR(255)     NOT NULL DEFAULT '',
    `mirror`          VARCHAR(255)     NOT NULL DEFAULT '',
    `price`           VARCHAR(10)      NOT NULL DEFAULT 'Free',
    `paypalemail`     VARCHAR(255)     NOT NULL DEFAULT '',
    `features`        TEXT             NOT NULL,
    `requirements`    TEXT             NOT NULL,
    `homepagetitle`   VARCHAR(255)     NOT NULL DEFAULT '',
    `forumid`         INT(11)          NOT NULL DEFAULT '0',
    `limitations`     VARCHAR(255)     NOT NULL DEFAULT '30 day trial',
    `versiontypes`    VARCHAR(255)     NOT NULL DEFAULT 'None',
    `dhistory`        TEXT             NOT NULL,
    `published`       INT(11)          NOT NULL DEFAULT '1089662528',
    `expired`         INT(10)          NOT NULL DEFAULT '0',
    `updated`         INT(11)          NOT NULL DEFAULT '0',
    `offline`         TINYINT(1)       NOT NULL DEFAULT '0',
    `summary`         TEXT             NOT NULL,
    `description`     TEXT             NOT NULL,
    `ipaddress`       VARCHAR(120)     NOT NULL DEFAULT '0',
    `notifypub`       INT(1)           NOT NULL DEFAULT '0',
    `formulize_idreq` INT(5)           NOT NULL DEFAULT '0',
    `screenshots`     TEXT             NOT NULL,
    `dohtml`          TINYINT(1)       NOT NULL DEFAULT '0',
    `dosmiley`        TINYINT(1)       NOT NULL DEFAULT '1',
    `doxcode`         TINYINT(1)       NOT NULL DEFAULT '1',
    `doimage`         TINYINT(1)       NOT NULL DEFAULT '1',
    `dobr`            TINYINT(1)       NOT NULL DEFAULT '1',
    PRIMARY KEY (`lid`),
    KEY `cid` (`cid`),
    KEY `status` (`status`),
    KEY `title` (`title`(40))
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_indexpage`;
CREATE TABLE `xf3f_wfdownloads_indexpage` (
    `indeximage`       VARCHAR(255) NOT NULL DEFAULT 'blank.png',
    `indexheading`     VARCHAR(255) NOT NULL DEFAULT 'Wfdownloads',
    `indexheader`      TEXT         NOT NULL,
    `indexfooter`      TEXT         NOT NULL,
    `nohtml`           TINYINT(8)   NOT NULL DEFAULT '1',
    `nosmiley`         TINYINT(8)   NOT NULL DEFAULT '1',
    `noxcodes`         TINYINT(8)   NOT NULL DEFAULT '1',
    `noimages`         TINYINT(8)   NOT NULL DEFAULT '1',
    `nobreak`          TINYINT(4)   NOT NULL DEFAULT '1',
    `indexheaderalign` VARCHAR(25)  NOT NULL DEFAULT 'left',
    `indexfooteralign` VARCHAR(25)  NOT NULL DEFAULT 'center',
    FULLTEXT KEY `indexheading` (`indexheading`),
    FULLTEXT KEY `indexheader` (`indexheader`),
    FULLTEXT KEY `indexfooter` (`indexfooter`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_ip_log`;
CREATE TABLE `xf3f_wfdownloads_ip_log` (
    `ip_logid`   INT(11)     NOT NULL AUTO_INCREMENT,
    `lid`        INT(11)     NOT NULL DEFAULT '0',
    `uid`        INT(11)     NOT NULL DEFAULT '0',
    `date`       INT(11)     NOT NULL DEFAULT '0',
    `ip_address` VARCHAR(45) NOT NULL DEFAULT '',
    PRIMARY KEY (`ip_logid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_meta`;
CREATE TABLE `xf3f_wfdownloads_meta` (
    `metakey`   VARCHAR(50)  NOT NULL DEFAULT '',
    `metavalue` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`metakey`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mimetypes`;
CREATE TABLE `xf3f_wfdownloads_mimetypes` (
    `mime_id`    INT(11)      NOT NULL AUTO_INCREMENT,
    `mime_ext`   VARCHAR(60)  NOT NULL DEFAULT '',
    `mime_types` TEXT         NOT NULL,
    `mime_name`  VARCHAR(255) NOT NULL DEFAULT '',
    `mime_admin` INT(1)       NOT NULL DEFAULT '1',
    `mime_user`  INT(1)       NOT NULL DEFAULT '0',
    KEY `mime_id` (`mime_id`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mirrors`;
CREATE TABLE `xf3f_wfdownloads_mirrors` (
    `mirror_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `lid`       INT(11)          NOT NULL DEFAULT '0',
    `title`     VARCHAR(255)     NOT NULL DEFAULT '',
    `homeurl`   VARCHAR(100)     NOT NULL DEFAULT '',
    `location`  VARCHAR(255)     NOT NULL DEFAULT '',
    `continent` VARCHAR(255)     NOT NULL DEFAULT '',
    `downurl`   VARCHAR(255)     NOT NULL DEFAULT '',
    `submit`    INT(11)          NOT NULL DEFAULT '0',
    `date`      INT(11)          NOT NULL DEFAULT '0',
    `uid`       INT(10)          NOT NULL DEFAULT '0',
    PRIMARY KEY (`mirror_id`),
    KEY `categoryid` (`lid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_mod`;
CREATE TABLE `xf3f_wfdownloads_mod` (
    `requestid`       INT(11)          NOT NULL AUTO_INCREMENT,
    `lid`             INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `cid`             INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    `title`           VARCHAR(255)     NOT NULL DEFAULT '',
    `url`             VARCHAR(255)     NOT NULL DEFAULT '',
    `filename`        VARCHAR(150)     NOT NULL DEFAULT '',
    `filetype`        VARCHAR(100)     NOT NULL DEFAULT '',
    `homepage`        VARCHAR(255)     NOT NULL DEFAULT '',
    `version`         VARCHAR(20)      NOT NULL DEFAULT '',
    `size`            INT(8)           NOT NULL DEFAULT '0',
    `platform`        VARCHAR(50)      NOT NULL DEFAULT '',
    `screenshot`      VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot2`     VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot3`     VARCHAR(255)     NOT NULL DEFAULT '',
    `screenshot4`     VARCHAR(255)     NOT NULL DEFAULT '',
    `submitter`       INT(11)          NOT NULL DEFAULT '0',
    `publisher`       TEXT             NOT NULL,
    `status`          TINYINT(2)       NOT NULL DEFAULT '0',
    `date`            INT(10)          NOT NULL DEFAULT '0',
    `hits`            INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `rating`          DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    `votes`           INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `comments`        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `license`         VARCHAR(255)     NOT NULL DEFAULT '',
    `mirror`          VARCHAR(255)     NOT NULL DEFAULT '',
    `price`           VARCHAR(10)      NOT NULL DEFAULT 'Free',
    `paypalemail`     VARCHAR(255)     NOT NULL DEFAULT '',
    `features`        TEXT             NOT NULL,
    `requirements`    TEXT             NOT NULL,
    `homepagetitle`   VARCHAR(255)     NOT NULL DEFAULT '',
    `forumid`         INT(11)          NOT NULL DEFAULT '0',
    `limitations`     VARCHAR(255)     NOT NULL DEFAULT '30 day trial',
    `versiontypes`    VARCHAR(255)     NOT NULL DEFAULT 'None',
    `dhistory`        TEXT             NOT NULL,
    `published`       INT(10)          NOT NULL DEFAULT '0',
    `expired`         INT(10)          NOT NULL DEFAULT '0',
    `updated`         INT(11)          NOT NULL DEFAULT '0',
    `offline`         TINYINT(1)       NOT NULL DEFAULT '0',
    `summary`         TEXT             NOT NULL,
    `description`     TEXT             NOT NULL,
    `modifysubmitter` INT(11)          NOT NULL DEFAULT '0',
    `requestdate`     INT(11)          NOT NULL DEFAULT '0',
    `screenshots`     TEXT             NOT NULL,
    `dohtml`          TINYINT(1)       NOT NULL DEFAULT '0',
    `dosmiley`        TINYINT(1)       NOT NULL DEFAULT '1',
    `doxcode`         TINYINT(1)       NOT NULL DEFAULT '1',
    `doimage`         TINYINT(1)       NOT NULL DEFAULT '1',
    `dobr`            TINYINT(1)       NOT NULL DEFAULT '1',
    PRIMARY KEY (`requestid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_reviews`;
CREATE TABLE `xf3f_wfdownloads_reviews` (
    `review_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `lid`       INT(11)          NOT NULL DEFAULT '0',
    `title`     VARCHAR(255)              DEFAULT NULL,
    `review`    TEXT,
    `submit`    INT(11)          NOT NULL DEFAULT '0',
    `date`      INT(11)          NOT NULL DEFAULT '0',
    `uid`       INT(10)          NOT NULL DEFAULT '0',
    `rated`     INT(11)          NOT NULL DEFAULT '0',
    PRIMARY KEY (`review_id`),
    KEY `categoryid` (`lid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `xf3f_wfdownloads_votedata`;
CREATE TABLE `xf3f_wfdownloads_votedata` (
    `ratingid`        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `lid`             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `ratinguser`      INT(11)             NOT NULL DEFAULT '0',
    `rating`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `ratinghostname`  VARCHAR(60)         NOT NULL DEFAULT '',
    `ratingtimestamp` INT(10)             NOT NULL DEFAULT '0',
    PRIMARY KEY (`ratingid`),
    KEY `ratinguser` (`ratinguser`),
    KEY `ratinghostname` (`ratinghostname`),
    KEY `lid` (`lid`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;


-- 2018-04-09 03:20:00
