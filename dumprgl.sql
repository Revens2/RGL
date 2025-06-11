-- MariaDB dump 10.19-11.3.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: rgl
-- ------------------------------------------------------
-- Server version	11.3.2-MariaDB-log

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
  `Coordonnees_latitude` text DEFAULT NULL,
  `Coordonnees_longitude` text DEFAULT NULL,
  PRIMARY KEY (`Id_Gymnase`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gymnase`
--

LOCK TABLES `gymnase` WRITE;
/*!40000 ALTER TABLE `gymnase` DISABLE KEYS */;
INSERT INTO `gymnase` VALUES
(1,'Gymnase Jean Jaurès','54000','Nancy','10 Rue Jean Jaurès','48.6921','6.1844'),
(2,'Gymnase du Saulcy','57000','Metz','Île du Saulcy','49.1193','6.1727'),
(3,'Complexe Sportif de la Rotonde','88000','Épinal','Rue de la Rotonde','48.1832','6.4544'),
(4,'Gymnase Blaise Pascal','57100','Thionville','5 Rue Blaise Pascal','49.3558','6.1681'),
(5,'Gymnase des Roses','57600','Forbach','Rue des Roses','49.1919','6.8987'),
(6,'Gymnase Marcel Cerdan','54300','Lunéville','Rue Marcel Cerdan','48.5946','6.5009'),
(7,'Gymnase Louis Armand','88100','Saint-Dié-des-Vosges','Avenue Louis Armand','48.2847','6.9492'),
(8,'Gymnase Victor Hugo','55000','Bar-le-Duc','Rue Victor Hugo','48.7723','5.1607'),
(9,'Gymnase du Haut Clocher','57200','Sarreguemines','Rue du Haut Clocher','49.1078','7.0716'),
(10,'Gymnase de la Paix','54400','Longwy','Boulevard de la Paix','49.5187','5.7661');
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
(4,1),
(1,2),
(5,2),
(2,3),
(6,3),
(1,4),
(3,4),
(6,4),
(2,5),
(7,5),
(1,6),
(2,6),
(3,6),
(4,6),
(7,6),
(3,7),
(8,7),
(3,8),
(8,8),
(4,9),
(9,9),
(4,10),
(9,10),
(5,11),
(9,11),
(5,12),
(10,12),
(6,13),
(10,13),
(7,14),
(10,14),
(8,15),
(10,15);
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
  `isdelete` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`Id_reservation`),
  KEY `idx_reservation_gymnase_sport` (`Id_Gymnase`,`Id_Sport`),
  KEY `fk_reservation_utilisateur` (`Id_Utilisateur`),
  KEY `fk_reservation_sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_gymnase` FOREIGN KEY (`Id_Gymnase`) REFERENCES `gymnase` (`Id_Gymnase`),
  CONSTRAINT `fk_reservation_gymnase_sport_unique` FOREIGN KEY (`Id_Gymnase`, `Id_Sport`) REFERENCES `gymnase_sport` (`Id_Gymnase`, `Id_Sport`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reservation_sport` FOREIGN KEY (`Id_Sport`) REFERENCES `sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_utilisateur` FOREIGN KEY (`Id_Utilisateur`) REFERENCES `utilisateur` (`Id_Utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES
(86,'2025-05-23 16:02:00','2025-05-25 16:02:00','',4,1,6,3,0),
(87,'2025-05-23 16:26:00','2025-05-25 16:26:00','',2,2,1,2,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sport`
--

LOCK TABLES `sport` WRITE;
/*!40000 ALTER TABLE `sport` DISABLE KEYS */;
INSERT INTO `sport` VALUES
(1,'Basketball',1),
(2,'Handball',1),
(3,'Volley-ball',1),
(4,'Badminton',1),
(5,'Tennis de table',1),
(6,'Gymnastique',0),
(7,'Escrime',1),
(8,'Judo',1),
(9,'Boxe',1),
(10,'Danse',0),
(11,'Football en salle',1),
(12,'Yoga',0),
(13,'CrossFit',0),
(14,'Haltérophilie',0),
(15,'Escalade intérieure',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES
(1,'Ploquin','Juliann',NULL,NULL,'admin@gmail.com ','Admin123  ','',0,1,NULL,NULL),
(2,'Rouge','Ludo','2025-03-19',668149759,'juliann.ploquin@gmail.com','Juliann123','11 rue du jeu de paume',1,0,28700,'Sainville');
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

-- Dump completed on 2025-05-27 12:20:16
