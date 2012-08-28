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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

/*Data for the table `media_file` */

insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (1,6,'P1130211.JPG','Mon image avec un ');
insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (2,6,'image2.jpg','the JPG');
insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (51,6,'dsc03048.jpg','dsc03048.jpg');
insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (52,6,'P1100587.JPG','P1100587.JPG');
insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (53,6,'produit1.jpg','produit1.jpg');

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

/*Table structure for table `text_category` */

CREATE TABLE `text_category` (
  `id_text_category` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_text_category`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `text_category` */

insert  into `text_category`(`id_text_category`) values (1);
insert  into `text_category`(`id_text_category`) values (2);

/*Table structure for table `text_category_lang` */

CREATE TABLE `text_category_lang` (
  `id_text_category` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_text_category`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_category_lang` */

insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (1,1,'Bibi');
insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (1,2,'');
insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (1,3,'');
insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (2,1,'Information');
insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (2,2,'');
insert  into `text_category_lang`(`id_text_category`,`id_lang`,`title`) values (2,3,'');

/*Table structure for table `text_content` */

CREATE TABLE `text_content` (
  `id_text_content` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `id_text_category` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_text_content`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `text_content` */

insert  into `text_content`(`id_text_content`,`name`,`id_text_category`) values (1,'Texte du footer',2);
insert  into `text_content`(`id_text_content`,`name`,`id_text_category`) values (2,'Texte de la page d\'accueil',1);
insert  into `text_content`(`id_text_content`,`name`,`id_text_category`) values (10,'Précisions',1);

/*Table structure for table `text_content_lang` */

CREATE TABLE `text_content_lang` (
  `id_text_content` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id_text_content`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_content_lang` */

insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (1,1,'Texte du footer','<p>Propulsé par JalmotPHP<br>\r\n</p>\r\n');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (1,2,'','');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (1,3,'','');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (2,1,'Bienvenue','<p>JalmotPHP est un framework simple et puissant.</p>\r\n<p>Ceci est un test de cache<br>\r\n\r\n</p>\r\n');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (2,2,'','');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (2,3,'','');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (10,1,'Dudulle','<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book. It has survived not \r\nonly five centuries, but also the leap into electronic typesetting, \r\nremaining essentially unchanged. It was popularised in the 1960s with \r\nthe release of Letraset sheets containing Lorem Ipsum passages, and more\r\n recently with desktop publishing software like Aldus PageMaker \r\nincluding versions of Lorem Ipsum.<span id=\"pastemarkerend\">&nbsp;</span></p>\r\n');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (10,2,'','');
insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (10,3,'','');

/*Table structure for table `text_content_media_file` */

CREATE TABLE `text_content_media_file` (
  `id_text_content` int(11) NOT NULL,
  `id_media_file` int(11) NOT NULL,
  PRIMARY KEY (`id_text_content`,`id_media_file`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_content_media_file` */

insert  into `text_content_media_file`(`id_text_content`,`id_media_file`) values (2,1);
insert  into `text_content_media_file`(`id_text_content`,`id_media_file`) values (2,53);
insert  into `text_content_media_file`(`id_text_content`,`id_media_file`) values (10,51);
insert  into `text_content_media_file`(`id_text_content`,`id_media_file`) values (10,53);

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
