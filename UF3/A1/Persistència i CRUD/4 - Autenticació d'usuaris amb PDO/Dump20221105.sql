CREATE DATABASE IF NOT EXISTS `dwes-alexcalvo-autpdo`;
USE `dwes-alexcalvo-autpdo`;
-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: dwes-alexcalvo-autpdo
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `connections`
--

DROP TABLE IF EXISTS `connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `connections` (
  `ip` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `time` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connections`
--

LOCK TABLES `connections` WRITE;
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
INSERT INTO `connections` VALUES ('::1','prova@gmail.com','2022-11-03 18:56:27','signup_success'),('::1','prova@gmail.com','2022-11-03 19:01:46','signup_exist_error'),('::1','prova@gmail.com','2022-11-03 19:01:49','signup_exist_error'),('::1','prova@gmail.com','2022-11-03 19:02:12','signup_exist_error'),('::1','prov2a@gmail.com','2022-11-03 19:03:23','signup_success'),('::1','pro34va@gmail.com','2022-11-03 19:04:11','signup_success'),('::1','prova222@gmail.com','2022-11-03 19:05:25','signup_success'),('::1','prova@gmail.com','2022-11-03 19:16:02','signin_success'),('::1','prova@gmail.com','2022-11-03 19:16:10','signin_success'),('::1','prova@gmail.com','2022-11-03 19:16:13','signin_success'),('::1','prov44a@gmail.com','2022-11-03 19:16:21','signup_success'),('::1','prova@gmail.com','2022-11-03 19:16:32','signin_success'),('::1','pro13313va@gmail.com','2022-11-03 19:34:11','signup_success'),('::1','prova@gmail.com','2022-11-03 19:35:46','signin_success'),('::1','prova@gmail.com','2022-11-03 19:35:51','signin_success'),('::1','prova@gmail.com','2022-11-03 19:35:55','signin_success'),('::1','prova@gmail.com','2022-11-04 15:07:05','signin_success'),('::1','prova@gmail.com','2022-11-05 19:47:43','signin_success'),('::1','prova@gmail.com','2022-11-05 19:49:19','signin_success'),('::1','prova@gmail.com','2022-11-05 19:49:23','signin_success'),('::1','prova@gmail.com','2022-11-05 19:50:38','signin_success'),('::1','prova@gmail.com','2022-11-05 19:50:57','signin_success'),('::1','prova@gmail.com','2022-11-05 19:51:35','signin_success'),('::1','prova@gmail.com','2022-11-05 19:53:14','signup_exist_error'),('::1','prov2313131a@gmail.com','2022-11-05 19:53:19','signup_success'),('::1','prova@gmail.com','2022-11-05 19:53:50','signin_success'),('::1','prov4444a@gmail.com','2022-11-05 19:54:46','signup_success'),('::1','prova@gmail.com','2022-11-05 19:54:49','signin_success'),('::1','prova@gmail.com','2022-11-05 19:55:01','signin_success'),('::1','prova@gmail.com','2022-11-05 19:56:10','signin_success'),('::1','prov4444a@gmail.com','2022-11-05 20:00:13','signup_exist_error'),('::1','prova41414141@gmail.com','2022-11-05 20:00:23','signup_success'),('::1','prova@gmail.com','2022-11-05 20:02:59','signin_success'),('::1','prova@gmail.com','2022-11-05 20:03:15','signin_password_error'),('::1','prova@gmail.com','2022-11-05 20:03:17','signin_password_error'),('::1','prova@gmail.com1313131','2022-11-05 20:03:19','signin_email_error'),('::1','prova@gmail.com','2022-11-05 20:03:21','signin_password_error'),('::1','prova@gmail.com','2022-11-05 20:03:23','signin_password_error'),('::1','prova@gmail.com','2022-11-05 20:03:24','signin_success'),('::1','prova@gmail.com','2022-11-05 20:25:06','signin_success'),('::1','prova@gmail.com','2022-11-05 20:25:13','signup_exist_error'),('::1','prova@gmail.com','2022-11-05 20:25:18','signup_exist_error'),('::1','a@gmail.com','2022-11-05 20:25:32','signup_success'),('::1','prova@gmail.com','2022-11-05 20:25:41','signin_success'),('::1','prova@gmail.com','2022-11-05 20:25:47','signin_success');
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `name` varchar(45) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('k','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','a@gmail.com'),('11313','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','pro13313va@gmail.com'),('aaa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','pro34va@gmail.com'),('aa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prov2313131a@gmail.com'),('aaa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prov2a@gmail.com'),('aa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prov4444a@gmail.com'),('aa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prov44a@gmail.com'),('aa','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prova@gmail.com'),('333','d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db','prova222@gmail.com'),('41414141','a49ed49ddd4b6af3cb8551decf9ac5fef128054e4e4749c4e1bb07f9dd51fda0ab5c151d77fd65c95eac7f50a7992a132885bcbda770ecc1579f871fb8fe5476','prova41414141@gmail.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-05 20:37:13
