/*
* @Author: jsy135135
* @Date:   2017-10-14 19:42:17
* @Last Modified by:   jsy135135
* @Last Modified time: 2017-10-14 19:42:29
*/
CREATE DATABASE `curl`;
use curl;
CREATE TABLE `zhilian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `companyName` varchar(200) NOT NULL,
  `salary` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL,
  `jobType` varchar(200) NOT NULL,
  `experience` varchar(200) NOT NULL,
  `education` varchar(200) NOT NULL,
  `nums` varchar(200) NOT NULL,
  `jobCategory` varchar(200) NOT NULL,
  `jobInfo` text NOT NULL,
  `address` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,PRIMARY KEY (`id`))
ENGINE=MyISAM CHARSET=utf8