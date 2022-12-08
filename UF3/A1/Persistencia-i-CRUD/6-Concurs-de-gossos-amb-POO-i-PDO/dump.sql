CREATE DATABASE  IF NOT EXISTS `concursgossos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `concursgossos`;
-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: concursgossos
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
-- Table structure for table `dog`
--

DROP TABLE IF EXISTS `dog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dog` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `image` varchar(45) NOT NULL,
  `owner` varchar(45) NOT NULL,
  `breed` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dog`
--

LOCK TABLES `dog` WRITE;
/*!40000 ALTER TABLE `dog` DISABLE KEYS */;
INSERT INTO `dog` VALUES (455,'lisy1','../img/lisy1-carSvg.png','owner1','breed1'),(456,'lisy2','../img/g2.png','owner2','breed2'),(457,'lisy3','../img/g3.png','owner3','breed3'),(458,'lisy4','../img/g4.png','owner4','breed4'),(459,'lisy5','../img/g5.png','owner5','breed5'),(460,'lisy6','../img/g6.png','owner6','breed6'),(461,'lisy7','../img/g7.png','owner7','breed7'),(462,'lisy8','../img/g8.png','owner8','breed8'),(463,'lisy9','../img/g9.png','owner9','breed9');
/*!40000 ALTER TABLE `dog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phase`
--

DROP TABLE IF EXISTS `phase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phase` (
  `id` int NOT NULL AUTO_INCREMENT,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `phaseNumber` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phase`
--

LOCK TABLES `phase` WRITE;
/*!40000 ALTER TABLE `phase` DISABLE KEYS */;
INSERT INTO `phase` VALUES (1,'2023-01-01','2023-02-01',1),(2,'2023-02-02','2023-03-02',2),(3,'2023-03-03','2023-04-03',3),(4,'2023-04-04','2023-05-04',4),(5,'2023-05-05','2023-06-05',5),(6,'2023-06-06','2023-07-06',6),(7,'2023-07-07','2023-08-07',7),(8,'2023-08-08','2023-09-08',8);
/*!40000 ALTER TABLE `phase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phasecontestants`
--

DROP TABLE IF EXISTS `phasecontestants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phasecontestants` (
  `idDog` int NOT NULL,
  `idPhase` int NOT NULL,
  PRIMARY KEY (`idDog`,`idPhase`),
  KEY `idphase_idx` (`idPhase`),
  CONSTRAINT `iddog` FOREIGN KEY (`idDog`) REFERENCES `dog` (`id`),
  CONSTRAINT `idphase` FOREIGN KEY (`idPhase`) REFERENCES `phase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phasecontestants`
--

LOCK TABLES `phasecontestants` WRITE;
/*!40000 ALTER TABLE `phasecontestants` DISABLE KEYS */;
/*!40000 ALTER TABLE `phasecontestants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec'),(2,'alex','4c7af5fd4ef4354f53d2ec38a681da8082b66cec721ac1ea3721ef71ebb07f6d4c604675a601026fa9c5e73751de13014e161242a2c7671e7e4833a0a7c372ea');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vote` (
  `idDog` int NOT NULL,
  `idPhase` int NOT NULL,
  `sessionID` varchar(64) NOT NULL,
  PRIMARY KEY (`idPhase`,`sessionID`),
  KEY `idPhaseFK_idx` (`idPhase`),
  KEY `idDogFK` (`idDog`),
  CONSTRAINT `idDogFK` FOREIGN KEY (`idDog`) REFERENCES `dog` (`id`),
  CONSTRAINT `idPhaseFK` FOREIGN KEY (`idPhase`) REFERENCES `phase` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_votsperfase`
--

DROP TABLE IF EXISTS `vw_votsperfase`;
/*!50001 DROP VIEW IF EXISTS `vw_votsperfase`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_votsperfase` AS SELECT 
 1 AS `dogName`,
 1 AS `dogImage`,
 1 AS `phaseNumber`,
 1 AS `votePercentage`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_votsperfase`
--

/*!50001 DROP VIEW IF EXISTS `vw_votsperfase`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_votsperfase` AS select `dog`.`name` AS `dogName`,`dog`.`image` AS `dogImage`,`phase`.`phaseNumber` AS `phaseNumber`,(count(`dog`.`id`) / (select count(0) from (`vote` join `phase` on((`vote`.`idPhase` = `phase`.`id`))) where (`phase`.`phaseNumber` = 5))) AS `votePercentage` from ((`vote` join `phase` on((`phase`.`id` = `vote`.`idPhase`))) join `dog` on((`vote`.`idDog` = `dog`.`id`))) group by `dog`.`id`,`phase`.`phaseNumber` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-08 11:18:53