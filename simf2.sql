-- MariaDB dump 10.19  Distrib 10.6.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: simf2
-- ------------------------------------------------------
-- Server version	10.6.8-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20220817112716','2022-08-17 14:37:22',45),('DoctrineMigrations\\Version20220817124303','2022-08-17 15:43:13',38),('DoctrineMigrations\\Version20220819082826','2022-08-19 11:29:07',56),('DoctrineMigrations\\Version20220819095513','2022-08-19 12:55:37',99),('DoctrineMigrations\\Version20220819101658','2022-08-19 13:17:22',67),('DoctrineMigrations\\Version20220822115145','2022-08-22 14:52:33',112),('DoctrineMigrations\\Version20220823114134','2022-08-23 14:42:02',51),('DoctrineMigrations\\Version20220830104125','2022-08-30 13:41:47',92),('DoctrineMigrations\\Version20220830134819','2022-08-30 16:48:47',29),('DoctrineMigrations\\Version20220901071937','2022-09-01 10:20:17',55),('DoctrineMigrations\\Version20220901072254','2022-09-01 10:23:00',63),('DoctrineMigrations\\Version20220901102254','2022-09-01 13:23:21',54),('DoctrineMigrations\\Version20220920140808','2022-09-20 17:09:25',25),('DoctrineMigrations\\Version20220920143000','2022-09-20 17:30:27',90),('DoctrineMigrations\\Version20220920143206','2022-09-20 17:32:27',38),('DoctrineMigrations\\Version20221007111116','2022-10-07 14:13:26',40),('DoctrineMigrations\\Version20221007111510','2022-10-07 14:15:17',73);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Я тут','2022-08-30 13:59:19'),(2,'Я тут','2022-08-30 14:49:01'),(3,'Я тут','2022-08-30 14:50:01'),(4,'Я тут','2022-08-30 14:51:01'),(5,'Я тут','2022-08-30 14:52:01'),(6,'Я тут','2022-08-30 14:53:02'),(7,'Я тут','2022-08-30 14:54:01'),(8,'Я тут','2022-08-30 14:55:01'),(9,'Я тут','2022-08-30 14:56:01'),(10,'Я тут','2022-08-30 14:57:01'),(11,'Я тут','2022-08-30 14:58:02'),(12,'Я тут','2022-08-30 14:59:01'),(13,'Я тут','2022-08-30 15:00:01'),(14,'Я тут','2022-08-30 15:01:01'),(15,'Я тут','2022-08-30 15:02:01'),(16,'Я тут','2022-08-30 15:03:01'),(17,'Я тут','2022-08-30 15:04:01'),(18,'Я тут','2022-08-30 15:05:01'),(19,'Я тут','2022-08-30 15:06:01'),(20,'Я тут','2022-08-30 15:07:01'),(21,'Я тут','2022-08-30 15:08:01'),(22,'Я тут','2022-08-30 15:09:01'),(23,'Я тут','2022-08-30 15:10:01'),(24,'Я тут','2022-08-30 15:11:01'),(25,'Я тут','2022-08-30 15:12:01'),(26,'Я тут','2022-08-30 15:13:01'),(27,'Я тут','2022-08-30 15:14:01'),(28,'Я тут','2022-08-30 15:15:01'),(29,'Я тут','2022-08-30 15:16:01'),(30,'Я тут','2022-08-30 15:17:02'),(31,'Я тут','2022-08-30 15:18:01'),(32,'Я тут','2022-08-30 15:19:01'),(33,'Я тут','2022-08-30 15:20:01'),(34,'Я тут','2022-08-30 15:21:02'),(35,'Я тут','2022-08-30 15:22:01'),(36,'Я тут','2022-08-30 15:23:01'),(37,'Я тут','2022-08-30 15:24:01'),(38,'Я тут','2022-08-30 15:25:01'),(39,'Я тут','2022-08-30 15:26:02'),(40,'Я тут','2022-08-30 15:27:01'),(41,'Я тут','2022-08-30 15:28:01'),(42,'Я тут','2022-08-30 15:29:01'),(43,'Я тут','2022-08-30 15:30:01'),(44,'Я тут','2022-08-30 15:31:02'),(45,'Я тут','2022-08-30 15:32:01'),(46,'Я тут','2022-08-30 15:33:01'),(47,'Я тут','2022-08-30 15:34:01'),(48,'Я тут','2022-08-30 15:35:01'),(49,'Я тут','2022-08-30 15:36:02'),(50,'Я тут','2022-08-30 15:37:01'),(51,'Я тут','2022-08-30 15:38:01'),(52,'Я тут','2022-08-30 15:39:01'),(53,'Я тут','2022-08-30 15:40:01'),(54,'Я тут','2022-08-30 15:41:01'),(55,'Я тут','2022-08-30 15:42:01'),(56,'Я тут','2022-08-30 16:51:01');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
  `user_id` int(11) NOT NULL,
  `intro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A8A6C8DA76ED395` (`user_id`),
  CONSTRAINT `FK_5A8A6C8DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'Статья 1','Текст статьи 1\r\nЛучшая статья!!!','2022-08-17 10:19:03',5,'Текст статьи 1\nЛучшая статья!!!'),(2,'Статья 2','Текст статьи 2 \r\n\r\nХа-ха и хи-хи\r\nОпять хи-хи','2022-08-17 13:19:03',4,'Текст статьи 2 \r\n\r\nХа-ха и хи-хи'),(4,'123','123 123\r\n32 32...!','2022-08-18 13:19:03',5,'123 123\n32 32...!'),(6,'zzz','xcxc\r\nxcxc\r\nzzz фыфы sss','2022-08-19 13:21:09',6,'xcxc\nxcxc\nzzz фыфы sss'),(7,'pp',';;\r\nll ssssss','2022-08-22 10:55:47',5,';;\r\nll ssssss'),(8,'cvb cvb','ghj ghj','2022-10-03 11:21:37',5,'ghj ghj'),(9,'qqq','qweqwe \r\nqwe vvv','2022-10-04 17:49:42',5,'qweqwe \nqwe vvv');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'333@mail.ru','[\"ROLE_USER\"]','$2y$13$Xnu21cPm1.cQ8MHk52fNpeSsLR846qSk.dU0ZGIghWQDgGqGn5CXG',0),(5,'555@mail.ru','[\"ROLE_USER\", \"ROLE_ADMIN\"]','$2y$13$Gn9IiTviuMkaD.59NtSJYOCIgtjRVOXJ1NWG4WMtpuk2IAMpz.j9i',1),(6,'444@mail.ru','[\"ROLE_USER\"]','$2y$13$W4WuZKRdaytxtkRgzE/Ba.HOxO0FZS.Kn3y3gtvHJB3JXpvaYW9ay',1),(7,'23','[\"ROLE_USER\"]','$2y$13$9J1raQYjpZzmOW2k5wlvsuNi8yPfcMxVHZAsqc8GuVBS1csP4Vbo.',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-11 11:53:27
