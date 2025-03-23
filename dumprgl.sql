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
  `isdelete` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`Id_reservation`),
  KEY `idx_reservation_gymnase_sport` (`Id_Gymnase`,`Id_Sport`),
  KEY `fk_reservation_utilisateur` (`Id_Utilisateur`),
  KEY `fk_reservation_sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_gymnase` FOREIGN KEY (`Id_Gymnase`) REFERENCES `gymnase` (`Id_Gymnase`),
  CONSTRAINT `fk_reservation_gymnase_sport_unique` FOREIGN KEY (`Id_Gymnase`, `Id_Sport`) REFERENCES `gymnase_sport` (`Id_Gymnase`, `Id_Sport`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reservation_sport` FOREIGN KEY (`Id_Sport`) REFERENCES `sport` (`Id_Sport`),
  CONSTRAINT `fk_reservation_utilisateur` FOREIGN KEY (`Id_Utilisateur`) REFERENCES `utilisateur` (`Id_Utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES
(17,'2025-01-14 21:00:00','2025-01-16 21:00:00','dzdzq',1,5,1,0,0),
(47,'2024-03-01 10:00:00','2024-03-01 12:00:00','Réservation annulée',1,5,1,0,1),
(48,'2024-03-02 14:00:00','2024-03-02 16:00:00','Réservation supprimée',1,5,1,0,1),
(49,'2024-03-03 09:00:00','2024-03-03 11:00:00','Réservation supprimée',3,5,2,1,1),
(50,'2024-03-04 17:00:00','2024-03-04 19:00:00','Réservation annulée',1,5,1,0,1),
(52,'2024-03-06 11:00:00','2024-03-06 13:00:00','Annulation de dernière minute',1,5,1,4,1),
(53,'2024-03-07 15:00:00','2024-03-07 17:00:00','Suppression automatique',1,5,1,0,1),
(54,'2024-03-08 09:30:00','2024-03-08 11:30:00','Réservation annulée par admin',2,5,1,1,1),
(55,'2024-03-09 13:00:00','2024-03-09 15:00:00','Annulation utilisateur',1,5,1,2,1),
(56,'2024-03-10 16:00:00','2024-03-10 18:00:00','Réservation supprimée',1,5,1,0,1),
(57,'2024-03-11 07:00:00','2024-03-11 09:00:00','Erreur de réservation',1,5,1,1,1),
(58,'2024-03-12 18:00:00','2024-03-12 20:00:00','Suppression par utilisateur',1,5,1,2,1),
(59,'2024-03-13 12:00:00','2024-03-13 14:00:00','Annulation urgente',1,5,1,0,1),
(60,'2024-03-14 08:30:00','2024-03-14 10:30:00','Problème de planning',1,5,1,1,1),
(61,'2024-03-15 14:00:00','2024-03-15 16:00:00','Réservation obsolète',1,5,1,2,1),
(62,'2024-03-16 17:00:00','2024-03-16 19:00:00','Annulation confirmée',1,5,1,0,1),
(63,'2024-03-17 10:00:00','2024-03-17 12:00:00','Suppression admin',1,5,1,1,1),
(64,'2024-03-18 19:00:00','2024-03-18 21:00:00','Réservation annulée',1,5,1,2,1),
(65,'2024-03-19 08:00:00','2024-03-19 10:00:00','Suppression par erreur',1,5,1,0,1),
(66,'2024-03-20 15:00:00','2024-03-20 17:00:00','Demande de suppression',1,5,1,1,1),
(67,'2024-03-21 09:30:00','2024-03-21 11:30:00','Annulation demandée',1,5,1,2,1),
(68,'2024-03-22 12:00:00','2024-03-22 14:00:00','Réservation supprimée',1,5,1,0,1),
(69,'2024-03-23 16:00:00','2024-03-23 18:00:00','Suppression automatique',1,5,1,1,1),
(70,'2024-03-24 07:00:00','2024-03-24 09:00:00','Erreur utilisateur',1,5,1,2,1),
(71,'2024-03-25 18:00:00','2024-03-25 20:00:00','Réservation annulée',1,5,1,0,1),
(72,'2024-03-26 13:00:00','2024-03-26 15:00:00','Problème logistique',1,5,1,1,1),
(73,'2024-03-27 08:30:00','2024-03-27 10:30:00','Demande de suppression',1,5,1,4,1),
(74,'2024-03-28 14:00:00','2024-03-28 16:00:00','Annulation automatique',1,5,1,0,1),
(75,'2024-03-29 17:00:00','2024-03-29 19:00:00','Erreur d’horaire',1,5,1,0,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES
(4,'Ploquin','Juliann',NULL,NULL,'juliann.ploquin@gmail.com','Juliann2004','',0,1,NULL,NULL),
(5,'Rougeeeezz','Ludo','2025-03-19',668149759,'bibou@gmail.com','bibou','11 rue du jeu de paume',1,0,28700,'Sainvilleeeeezza'),
(6,'Le Morvan Idrac','Colombe','2025-03-11',668149759,'colombe@gmail.com','202cb962ac59075b964b07152d234b70','11 rue du jeu de paume',1,0,28700,'Sainville'),
(9,'Le Morvan Idrac','Colombe','2025-03-11',668149759,'colombe2@gmail.com','202cb962ac59075b964b07152d234b70','11 rue du jeu de paume',1,0,28700,'Sainville'),
(10,'Le Morvan Idrac','Colombe','2025-03-11',668149759,'colombe3@gmail.com','202cb962ac59075b964b07152d234b70','11 rue du jeu de paume',1,0,28700,'Sainville'),
(11,'Ploquin','Juliann','5111-05-06',668149759,'bibou5@gmail.com','fdf7ea0e85a96e3d524bec6da1917bfb','11 rue du jeu de paume',1,0,28700,'Sainville');
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

-- Dump completed on 2025-03-23 16:24:01
