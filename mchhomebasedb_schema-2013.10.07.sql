# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.32-MariaDB)
# Database: mchhomebasedb
# Generation Time: 2013-10-07 23:28:07 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table dbApplicantScreenings
# ------------------------------------------------------------

CREATE TABLE `dbApplicantScreenings` (
  `type` text NOT NULL,
  `creator` text,
  `steps` text,
  `status` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbDataExport
# ------------------------------------------------------------

CREATE TABLE `dbDataExport` (
  `export_date` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `gender` text,
  `type` text,
  `notes` text,
  `address` text,
  `city` text,
  `state` text,
  `zip` text,
  `county` text,
  `phone1` varchar(12) NOT NULL,
  `phone2` varchar(12) DEFAULT NULL,
  `email` text,
  `employer` text,
  `status` text,
  `hours_worked` text,
  `day_of_week` text,
  `month` text,
  `start_date` text,
  `id` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbDates
# ------------------------------------------------------------

CREATE TABLE `dbDates` (
  `id` char(8) NOT NULL,
  `shifts` text,
  `mgr_notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbLog
# ------------------------------------------------------------

CREATE TABLE `dbLog` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `time` text,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbMasterSchedule
# ------------------------------------------------------------

CREATE TABLE `dbMasterSchedule` (
  `group` text NOT NULL,
  `day` text NOT NULL,
  `week_no` text NOT NULL,
  `slots` int(11) DEFAULT NULL,
  `persons` text,
  `notes` text,
  `id` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbMonths
# ------------------------------------------------------------

CREATE TABLE `dbMonths` (
  `id` text NOT NULL,
  `dates` text,
  `group` text,
  `status` text,
  `end_of_month_timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbPersons
# ------------------------------------------------------------

CREATE TABLE `dbPersons` (
  `id` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text,
  `address` text,
  `city` text,
  `state` varchar(2) DEFAULT NULL,
  `zip` text,
  `phone1` varchar(12) NOT NULL,
  `phone2` varchar(12) DEFAULT NULL,
  `email` text,
  `type` text,
  `group` text,
  `role` text,
  `status` text,
  `availability` text,
  `schedule` text,
  `birthday` text,
  `start_date` text,
  `notes` text,
  `password` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbSCL
# ------------------------------------------------------------

CREATE TABLE `dbSCL` (
  `id` char(17) NOT NULL,
  `persons` text,
  `status` text,
  `vacancies` text,
  `time` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbShifts
# ------------------------------------------------------------

CREATE TABLE `dbShifts` (
  `id` char(20) NOT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `venue` text,
  `vacancies` int(11) DEFAULT NULL,
  `persons` text,
  `sub_call_list` text,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dbWeeks
# ------------------------------------------------------------

CREATE TABLE `dbWeeks` (
  `id` char(8) NOT NULL,
  `dates` text,
  `weekday_group` text,
  `weekend_group` text,
  `status` text,
  `name` text,
  `end` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
