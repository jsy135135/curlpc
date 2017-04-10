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
  `jobInfo` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,PRIMARY KEY (`id`))
ENGINE=MyISAM AUTO_INCREMENT=337 DEFAULT CHARSET=utf8