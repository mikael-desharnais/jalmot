/*
SQLyog Ultimate v8.71 
MySQL - 5.5.16-log : Database - jalmot
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `media_directory` */

CREATE TABLE `media_directory` (
  `id_media_directory` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent_media_directory` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id_media_directory` (`id_media_directory`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `media_directory` */

insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (1,0,'>');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (2,1,'Photos');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (3,1,'PDF');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (4,1,'DOC');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (5,1,'Infos');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (6,2,'JPG');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (7,2,'BMP');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (8,2,'GIF');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (9,3,'Doc techniques');
insert  into `media_directory`(`id_media_directory`,`id_parent_media_directory`,`name`) values (10,3,'Fiche produits');

/*Table structure for table `media_file` */

CREATE TABLE `media_file` (
  `id_media_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_media_directory` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id_media_file` (`id_media_file`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `media_file` */

insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (1,6,'P1130211.JPG','Mon image avec un ');
insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (2,6,'image2.jpg','the JPG');

/*Table structure for table `test` */

CREATE TABLE `test` (
  `id_test` int(11) NOT NULL AUTO_INCREMENT,
  `valeur1` int(11) DEFAULT NULL,
  `valeur2` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_test`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `test` */

insert  into `test`(`id_test`,`valeur1`,`valeur2`,`code`) values (4,1,0,'Ce te');

/*Table structure for table `test_lang` */

CREATE TABLE `test_lang` (
  `id_test` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `short_description` text,
  PRIMARY KEY (`id_test`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `test_lang` */

insert  into `test_lang`(`id_test`,`id_lang`,`name`,`description`,`short_description`) values (4,1,'Nom du test','<p>Description en français<br>\r\n</p>\r\n','');
insert  into `test_lang`(`id_test`,`id_lang`,`name`,`description`,`short_description`) values (4,2,'','<p>Description in English<br>\r\n</p>\r\n','');
insert  into `test_lang`(`id_test`,`id_lang`,`name`,`description`,`short_description`) values (4,3,'','','');

/*Table structure for table `user_admin` */

CREATE TABLE `user_admin` (
  `id_user_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `user_admin` */

insert  into `user_admin`(`id_user_admin`,`username`,`password`,`firstname`,`lastname`) values (1,'mikael','ca7682df558c8d9e063bbca2b821295eb2e1876d','Desharnais','Mikael Stéphane');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
