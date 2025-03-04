-- MariaDB dump 10.19-11.3.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: rgl
-- ------------------------------------------------------
-- Server version	11.3.2-MariaDB

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
-- Current Database: `rgl`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `rgl` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `rgl`;

--
-- Table structure for table `gymnase`
--

DROP TABLE IF EXISTS `gymnase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gymnase` (
  `Id_Gymnase` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) DEFAULT NULL,
  `Zip` varchar(50) DEFAULT NULL,
  `Ville` varchar(50) DEFAULT NULL,
  `Adresse` varchar(50) DEFAULT NULL,
  `Horraire_ouverture` datetime DEFAULT NULL,
  `Horraire_fermeture` datetime DEFAULT NULL,
  `Coordonnees_latitude` text DEFAULT NULL,
  `Coordonnees_longitude` text DEFAULT NULL,
  PRIMARY KEY (`Id_Gymnase`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gymnase`
--

LOCK TABLES `gymnase` WRITE;
/*!40000 ALTER TABLE `gymnase` DISABLE KEYS */;
INSERT INTO `gymnase` VALUES
(1,'gymcentraleee','75001','Paris','123 Rue de la République','2023-01-01 08:00:00','2023-01-01 22:00:00','48.85668','2.3522'),
(2,'Gymnase Esttt','69001','Lyon','456 Avenue de la Liberté','2023-01-01 07:00:00','2023-01-01 21:00:00','45.764','4.835699'),
(3,'Gymnase Sud','13001','Marseille','789 Boulevard du Port','2023-01-01 06:00:00','2023-01-01 23:00:00','43.2965','5.3698');
/*!40000 ALTER TABLE `gymnase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gymnase_sport`
--

DROP TABLE IF EXISTS `gymnase_sport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gymnase_sport` (
  `Id_Gymnase` int(11) NOT NULL,
  `Id_Sport` int(11) NOT NULL,
  PRIMARY KEY (`Id_Gymnase`,`Id_Sport`),
  KEY `fk_sport` (`Id_Sport`),
  CONSTRAINT `fk_gymnase` FOREIGN KEY (`Id_Gymnase`) REFERENCES `gymnase` (`Id_Gymnase`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sport` FOREIGN KEY (`Id_Sport`) REFERENCES `sport` (`Id_Sport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gymnase_sport`
--

LOCK TABLES `gymnase_sport` WRITE;
/*!40000 ALTER TABLE `gymnase_sport` DISABLE KEYS */;
INSERT INTO `gymnase_sport` VALUES
(1,1),
(2,1),
(1,2),
(3,2),
(1,3),
(2,3),
(3,3),
(2,4),
(3,5);
/*!40000 ALTER TABLE `gymnase_sport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation` (
  `Id_reservation` int(11) NOT NULL AUTO_INCREMENT,
  `Date_debut` datetime DEFAULT NULL,
  `Date_fin` datetime DEFAULT NULL,
  `Commentaire` varchar(5000) DEFAULT NULL,
  `Id_Gymnase` int(11) NOT NULL,
  `Id_Utilisateur` int(11) NOT NULL,
  `Id_Sport` int(11) NOT NULL,
  `statut` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_reservation`),
  KEY `idx_reservation_gymnase_sport` (`Id_Gymnase`,`Id_Sport`),
  KEY `fk_reservation_utilisateur` (`Id_Utilisateur`),
  KEY `fk_reservation_sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_gymnase` FOREIGN KEY (`Id_Gymnase`) REFERENCES `gymnase` (`Id_Gymnase`),
  CONSTRAINT `fk_reservation_gymnase_sport_unique` FOREIGN KEY (`Id_Gymnase`, `Id_Sport`) REFERENCES `gymnase_sport` (`Id_Gymnase`, `Id_Sport`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reservation_sport` FOREIGN KEY (`Id_Sport`) REFERENCES `sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_utilisateur` FOREIGN KEY (`Id_Utilisateur`) REFERENCES `utilisateur` (`Id_Utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES
(17,'2025-01-14 21:00:00','2025-01-16 21:00:00','dzdzq',1,5,1,1);
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sport`
--

DROP TABLE IF EXISTS `sport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sport` (
  `Id_Sport` int(11) NOT NULL AUTO_INCREMENT,
  `Nom_du_sport` varchar(50) DEFAULT NULL,
  `Collectif` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id_Sport`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sport`
--

LOCK TABLES `sport` WRITE;
/*!40000 ALTER TABLE `sport` DISABLE KEYS */;
INSERT INTO `sport` VALUES
(1,'Basketball',1),
(2,'Tennis',0),
(3,'Football',1),
(4,'Natation',0),
(5,'Gymnastique',0),
(12,'dezqdzq',0),
(13,'dzdq',0),
(14,'ceceà',0),
(15,'éé',0);
/*!40000 ALTER TABLE `sport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `Id_Utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) DEFAULT NULL,
  `Prenom` varchar(50) DEFAULT NULL,
  `Date_de_naissance` date DEFAULT NULL,
  `Numero_de_telephone` int(11) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Mot_de_passe` varchar(500) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `isClient` tinyint(1) DEFAULT 0,
  `isAdmin` tinyint(1) DEFAULT 0,
  `Zip` int(11) DEFAULT NULL,
  `Ville` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id_Utilisateur`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES
(4,'Ploquin','Juliann',NULL,NULL,'juliann.ploquin@gmail.com','6f1d545cc5fa57a65fbe8e6fc84182e8','',0,1,NULL,NULL),
(5,'Rouge ','Ludo',NULL,NULL,'bibou@gmail.com','fdf7ea0e85a96e3d524bec6da1917bfb',NULL,1,0,NULL,NULL);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'rgl'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-04  9:55:01
