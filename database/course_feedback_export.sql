-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: course_feedback
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Current Database: `course_feedback`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `course_feedback` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `course_feedback`;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(100) NOT NULL,
  `matric_no` char(8) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `rating_content` tinyint(3) unsigned NOT NULL,
  `rating_teaching` tinyint(3) unsigned NOT NULL,
  `rating_assessment` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_course` (`course_code`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`rating_content` between 1 and 5),
  CONSTRAINT `CONSTRAINT_2` CHECK (`rating_teaching` between 1 and 5),
  CONSTRAINT `CONSTRAINT_3` CHECK (`rating_assessment` between 1 and 5)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,'Nur Aisyah binti Rahman','AI240112','BIC21203',5,4,5,'Clear explanations and useful labs.','2026-11-07 10:19:49'),(2,'Lim Wei Jie','AI240087','BIC21203',4,5,4,NULL,'2026-11-07 10:19:52'),(3,'Muhammad Hafiz bin Omar','CI240033','BIC21203',5,5,4,'More PHP examples please.','2026-11-07 10:19:53'),(4,'Ahmad Zaki bin Yusof','AI240061','BIC21404',4,3,3,NULL,'2026-11-07 10:19:54');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
