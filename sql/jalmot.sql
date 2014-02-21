/*
SQLyog Enterprise v10.5 
MySQL - 5.5.24-log : Database - jalmot
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `media_directory` */

DROP TABLE IF EXISTS `media_directory`;

CREATE TABLE `media_directory` (
  `id_media_directory` int(11) NOT NULL AUTO_INCREMENT,
  `id_media_directory_parent` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id_media_directory` (`id_media_directory`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `media_directory` */

insert  into `media_directory`(`id_media_directory`,`id_media_directory_parent`,`name`) values (1,0,'>'),(2,1,'Photos'),(3,1,'PDF'),(4,1,'DOC'),(5,1,'Infos'),(6,2,'JPG'),(7,2,'BMP'),(8,2,'GIF'),(9,3,'Doc techniques'),(10,3,'Fiche produits');

/*Table structure for table `media_file` */

DROP TABLE IF EXISTS `media_file`;

CREATE TABLE `media_file` (
  `id_media_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_media_directory` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id_media_file` (`id_media_file`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

/*Data for the table `media_file` */

insert  into `media_file`(`id_media_file`,`id_media_directory`,`file`,`name`) values (1,6,'P1130211.JPG','Mon image avec'),(2,6,'image2.jpg','the JPG'),(51,6,'dsc03048.jpg','dsc03048.jpg'),(52,6,'P1100587.JPG','P1100587.JPG'),(53,6,'produit1.jpg','produit1.jpg');

/*Table structure for table `test` */

DROP TABLE IF EXISTS `test`;

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

DROP TABLE IF EXISTS `test_lang`;

CREATE TABLE `test_lang` (
  `id_test` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `short_description` text,
  PRIMARY KEY (`id_test`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `test_lang` */

insert  into `test_lang`(`id_test`,`id_lang`,`name`,`description`,`short_description`) values (4,1,'Nom du test','<p>Description en français<br>\r\n</p>\r\n',''),(4,2,'','<p>Description in English<br>\r\n</p>\r\n',''),(4,3,'','','');

/*Table structure for table `text_category` */

DROP TABLE IF EXISTS `text_category`;

CREATE TABLE `text_category` (
  `id_text_category` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_text_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_category` */

/*Table structure for table `text_category_lang` */

DROP TABLE IF EXISTS `text_category_lang`;

CREATE TABLE `text_category_lang` (
  `id_text_category` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_text_category`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_category_lang` */

/*Table structure for table `text_content` */

DROP TABLE IF EXISTS `text_content`;

CREATE TABLE `text_content` (
  `id_text_content` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_text_content`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `text_content` */

insert  into `text_content`(`id_text_content`) values (11),(12);

/*Table structure for table `text_content_lang` */

DROP TABLE IF EXISTS `text_content_lang`;

CREATE TABLE `text_content_lang` (
  `id_text_content` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id_text_content`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_content_lang` */

insert  into `text_content_lang`(`id_text_content`,`id_lang`,`title`,`description`) values (11,1,'Lorem ipsum dolor sit amet','<p>Le <strong>Lorem Ipsum</strong> est simplement du faux texte employé \r\ndans la composition et la mise en page avant impression. Le Lorem Ipsum \r\nest le faux texte standard de l\'imprimerie depuis les années 1500, quand\r\n un peintre anonyme assembla ensemble des morceaux de texte pour \r\nréaliser un livre spécimen de polices de texte. Il n\'a pas fait que \r\nsurvivre cinq siècles, mais s\'est aussi adapté à la bureautique \r\ninformatique, sans que son contenu n\'en soit modifié. Il a été \r\npopularisé dans les années 1960 grâce à la vente de feuilles Letraset \r\ncontenant des passages du Lorem Ipsum, et, plus récemment, par son \r\ninclusion dans des applications de mise en page de texte, comme Aldus \r\nPageMaker.<span id=\"pastemarkerend\">&nbsp;</span></p>\r\n'),(11,2,'',''),(11,3,'',''),(12,1,'pariatur. Excepteur','<p>On sait depuis longtemps que travailler avec du texte lisible et \r\ncontenant du sens est source de distractions, et empêche de se \r\nconcentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur \r\nun texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il \r\npossède une distribution de lettres plus ou moins normale, et en tout \r\ncas comparable avec celle du français standard. De nombreuses suites \r\nlogicielles de mise en page ou éditeurs de sites Web ont fait du Lorem \r\nIpsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' \r\nvous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur \r\nphase de construction. Plusieurs versions sont apparues avec le temps, \r\nparfois par accident, souvent intentionnellement (histoire d\'y rajouter \r\nde petits clins d\'oeil, voire des phrases embarassantes).<span id=\"pastemarkerend\">&nbsp;</span></p>\r\n'),(12,2,'',''),(12,3,'','');

/*Table structure for table `text_content_media_file` */

DROP TABLE IF EXISTS `text_content_media_file`;

CREATE TABLE `text_content_media_file` (
  `id_text_content` int(11) NOT NULL,
  `id_media_file` int(11) NOT NULL,
  PRIMARY KEY (`id_text_content`,`id_media_file`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `text_content_media_file` */

/*Table structure for table `user_admin` */

DROP TABLE IF EXISTS `user_admin`;

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
