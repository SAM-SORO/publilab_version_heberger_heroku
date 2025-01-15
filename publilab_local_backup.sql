-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: publilab_bd
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'soro','soro@gmail.com',NULL,'$2y$12$59.VnkU99mKCONzHn3TCAuvudPQ6BIR3M5L.WLuJ7JYLouTTlLeIS',NULL,'2024-12-09 10:19:32','2024-12-09 10:19:32');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_revue`
--

DROP TABLE IF EXISTS `article_revue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article_revue` (
  `idArticle` bigint unsigned NOT NULL,
  `idRevue` bigint unsigned NOT NULL,
  `datePubArt` date DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `volume` int DEFAULT NULL,
  `pageDebut` int DEFAULT NULL,
  `pageFin` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idArticle`,`idRevue`),
  KEY `article_revue_idrevue_foreign` (`idRevue`),
  CONSTRAINT `article_revue_idarticle_foreign` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`idArticle`),
  CONSTRAINT `article_revue_idrevue_foreign` FOREIGN KEY (`idRevue`) REFERENCES `revues` (`idRevue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_revue`
--

LOCK TABLES `article_revue` WRITE;
/*!40000 ALTER TABLE `article_revue` DISABLE KEYS */;
INSERT INTO `article_revue` VALUES (2,8,'2024-08-02',7,19,92,103,NULL,NULL),(3,2,'2023-02-13',13,2,1896,1909,NULL,NULL),(4,3,'2023-02-05',1,28,31,40,NULL,NULL),(5,4,'2023-07-08',NULL,11,343,355,NULL,NULL),(6,4,'2022-08-05',NULL,10,202,2012,NULL,NULL),(7,4,'2022-08-05',NULL,10,202,2012,NULL,NULL),(8,5,'2020-06-16',NULL,NULL,791,795,NULL,NULL),(9,10,'2019-04-05',1,9,105,115,NULL,NULL),(11,9,'2018-08-01',1,150,14,27,NULL,NULL),(12,9,'2018-08-03',1,150,21,27,NULL,NULL),(13,6,'2018-05-12',12,9,27,35,NULL,NULL);
/*!40000 ALTER TABLE `article_revue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `idArticle` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titreArticle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumeArticle` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idArticle`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (2,'AAAA84585','Hybrid Approach Using Multi-Relational Weighted Matrix Factorization (WMRMF) and Cohen’s Kappa (Sk) to Refine Educational Items Clustering','Cet document fait référence à une méthode avancée qui combine plusieurs techniques pour améliorer le regroupement ou la classification d\'éléments dans un contexte éducatif','2024-12-09 13:21:15','2024-12-09 13:21:15'),(3,NULL,'An Information Content and Set of Common Superconcepts-Based Algorithm to Estimate Similarity between Concepts of Ontologies','ce document permet d\'estimer la similarité entre des concepts d\'ontologies en mesurant leur proximité sémantique','2024-12-09 13:47:28','2024-12-09 13:47:28'),(4,NULL,'Efficient Criteria Based Method for Selection of Relevant Ontologies in Transport Domain',NULL,'2024-12-09 13:54:07','2024-12-09 13:54:07'),(5,NULL,'VALIDATION OPTIMIZATION ENVIRONMENT FOR IMPROVED SELECTION OF SOFTWARE COMPONENTS : CONCEPTUAL MODELING AND ARCHITECTURE',NULL,'2024-12-09 14:09:26','2024-12-09 14:09:26'),(6,NULL,'HYBRID MODEL FOR THE CLASSIFICATION OF QUESTIONS EXPRESSED IN NATURAL LANGUAGE',NULL,'2024-12-09 14:23:26','2024-12-09 14:23:26'),(7,NULL,'HYBRID MODEL FOR THE CLASSIFICATION OF QUESTIONS EXPRESSED IN NATURAL LANGUAGE',NULL,'2024-12-09 14:23:54','2024-12-09 14:23:54'),(8,'10.3233/SHTI200269','Visual Representation of African Traditional Medicine Recipes Using Icons and a Formal Ontology, ontoMEDTRAD',NULL,'2024-12-09 16:40:43','2024-12-09 16:40:43'),(9,NULL,'A New Extraction Optimization Approch To Frequent 2 Itemsets',NULL,'2024-12-09 16:58:55','2024-12-09 16:58:55'),(10,NULL,'CLASSIFICATION DES ESPECES VEGETALES PAR FAMILLE',NULL,'2024-12-13 09:30:26','2024-12-13 09:30:26'),(11,NULL,'Data Extraction in the Warehouse: A Quality–Based Approach',NULL,'2024-12-13 09:42:27','2024-12-13 09:42:27'),(12,NULL,'User Profile of the Data Warehouse: Overview of the State of the Art and Definition of an Evaluation Model',NULL,'2024-12-13 09:45:02','2024-12-13 09:45:02'),(13,NULL,'Efficient Reduction of Overgeneration Errors for Automatic Controlled Indexing with an Application to the Biomedical Domain',NULL,'2024-12-13 10:57:23','2024-12-13 10:57:23');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axe_recherches`
--

DROP TABLE IF EXISTS `axe_recherches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `axe_recherches` (
  `idAxeRech` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titreAxeRech` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descAxeRech` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idAxeRech`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axe_recherches`
--

LOCK TABLES `axe_recherches` WRITE;
/*!40000 ALTER TABLE `axe_recherches` DISABLE KEYS */;
INSERT INTO `axe_recherches` VALUES (1,'Science de donnee et IA','science des donnee (big data et IA)','2024-12-09 18:01:19','2024-12-25 08:11:25'),(2,'Web semantique','recherche d\'information pertinente sur le web','2024-12-09 18:02:18','2024-12-09 18:02:18');
/*!40000 ALTER TABLE `axe_recherches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bd_indexations`
--

DROP TABLE IF EXISTS `bd_indexations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bd_indexations` (
  `idBDIndex` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomBDInd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idBDIndex`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bd_indexations`
--

LOCK TABLES `bd_indexations` WRITE;
/*!40000 ALTER TABLE `bd_indexations` DISABLE KEYS */;
INSERT INTO `bd_indexations` VALUES (5,'Indexation à voir','2024-12-09 10:45:09','2024-12-09 10:45:09'),(6,'Indexé dans Scopus, ISI Thomson Reuter, Web of Science (WOS)','2024-12-09 11:26:26','2024-12-09 11:26:26'),(7,'Springer','2024-12-09 11:31:39','2024-12-09 11:31:39'),(8,'Comite de lecture','2024-12-19 13:20:59','2024-12-19 13:20:59'),(10,'gveer','2024-12-25 19:30:53','2024-12-25 19:30:53');
/*!40000 ALTER TABLE `bd_indexations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bdindexation_revue`
--

DROP TABLE IF EXISTS `bdindexation_revue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bdindexation_revue` (
  `idBDInd` bigint unsigned NOT NULL,
  `idRevue` bigint unsigned NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  PRIMARY KEY (`idBDInd`,`idRevue`),
  KEY `bdindexation_revue_idrevue_foreign` (`idRevue`),
  CONSTRAINT `bdindexation_revue_idbdind_foreign` FOREIGN KEY (`idBDInd`) REFERENCES `bd_indexations` (`idBDIndex`),
  CONSTRAINT `bdindexation_revue_idrevue_foreign` FOREIGN KEY (`idRevue`) REFERENCES `revues` (`idRevue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bdindexation_revue`
--

LOCK TABLES `bdindexation_revue` WRITE;
/*!40000 ALTER TABLE `bdindexation_revue` DISABLE KEYS */;
INSERT INTO `bdindexation_revue` VALUES (5,9,'2016-01-01','2018-05-04'),(5,10,'2017-02-05','2019-03-05'),(6,6,'2016-05-05','2018-04-05'),(7,7,'2015-06-05','2017-08-08');
/*!40000 ALTER TABLE `bdindexation_revue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chercheur_article`
--

DROP TABLE IF EXISTS `chercheur_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chercheur_article` (
  `idCherch` bigint unsigned NOT NULL,
  `idArticle` bigint unsigned NOT NULL,
  PRIMARY KEY (`idCherch`,`idArticle`),
  KEY `chercheur_article_idarticle_foreign` (`idArticle`),
  CONSTRAINT `chercheur_article_idarticle_foreign` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`idArticle`),
  CONSTRAINT `chercheur_article_idcherch_foreign` FOREIGN KEY (`idCherch`) REFERENCES `chercheurs` (`idCherch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chercheur_article`
--

LOCK TABLES `chercheur_article` WRITE;
/*!40000 ALTER TABLE `chercheur_article` DISABLE KEYS */;
INSERT INTO `chercheur_article` VALUES (3,2),(3,3),(5,3),(6,3),(3,4),(5,4),(7,4),(3,5),(4,5),(8,5),(9,5),(3,6),(4,6),(10,6),(11,6),(3,7),(4,7),(10,7),(11,7),(3,8),(4,8),(12,8),(13,8),(3,9),(10,9),(14,9),(3,10),(15,10),(16,10),(3,11),(17,11),(18,11),(3,12),(17,12),(18,12),(3,13),(10,13),(19,13),(20,13);
/*!40000 ALTER TABLE `chercheur_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chercheur_grade`
--

DROP TABLE IF EXISTS `chercheur_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chercheur_grade` (
  `idCherch` bigint unsigned NOT NULL,
  `idGrade` bigint unsigned NOT NULL,
  `dateGrade` date DEFAULT NULL,
  PRIMARY KEY (`idCherch`,`idGrade`),
  KEY `chercheur_grade_idgrade_foreign` (`idGrade`),
  CONSTRAINT `chercheur_grade_idcherch_foreign` FOREIGN KEY (`idCherch`) REFERENCES `chercheurs` (`idCherch`),
  CONSTRAINT `chercheur_grade_idgrade_foreign` FOREIGN KEY (`idGrade`) REFERENCES `grades` (`idGrade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chercheur_grade`
--

LOCK TABLES `chercheur_grade` WRITE;
/*!40000 ALTER TABLE `chercheur_grade` DISABLE KEYS */;
INSERT INTO `chercheur_grade` VALUES (3,1,'2000-09-08'),(4,1,'1996-08-05'),(5,1,'2011-05-04'),(6,1,'1994-07-08'),(7,1,'2020-08-05'),(8,1,'2004-08-25'),(9,1,'1995-05-04'),(10,1,'1995-08-05'),(11,1,'1998-02-18'),(12,1,'2024-04-05'),(13,1,'1996-04-05'),(14,1,'2000-12-05'),(15,1,'2012-05-04'),(16,2,'1998-05-02'),(17,2,'2009-08-20'),(18,2,'2009-02-01'),(19,2,'1999-07-12'),(20,4,'1995-02-10');
/*!40000 ALTER TABLE `chercheur_grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chercheurs`
--

DROP TABLE IF EXISTS `chercheurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chercheurs` (
  `idCherch` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomCherch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenomCherch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telCherch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailCherch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idLabo` bigint unsigned NOT NULL,
  `dateArrivee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCherch`),
  UNIQUE KEY `chercheurs_emailcherch_unique` (`emailCherch`),
  KEY `chercheurs_idlabo_foreign` (`idLabo`),
  CONSTRAINT `chercheurs_idlabo_foreign` FOREIGN KEY (`idLabo`) REFERENCES `laboratoires` (`idLabo`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chercheurs`
--

LOCK TABLES `chercheurs` WRITE;
/*!40000 ALTER TABLE `chercheurs` DISABLE KEYS */;
INSERT INTO `chercheurs` VALUES (1,'soro','samuel','123 INPHB-CENTRES','0546829308','samuel.soro@inphb.ci','$2y$12$K3cR07r.R7OU4eVc/UByYeGXb8rYktsd9yOnN6lDwbjx6n9Ick3T.','Biologie',1,'2024-12-09','2024-12-09 10:19:33','2024-12-27 10:59:39'),(3,'Brou','Konan','YAMOUSSOUKRO','0708583000','marcellin.brou@inphb.ci','$2y$12$D8gXORPBbAGc0bbrpUEBMujSR6AkHLRai./Lk/w5sLXmS2ImBEyGi','Technologies du web',1,'2003-08-18','2024-12-09 12:33:13','2024-12-09 12:33:13'),(4,'KOUAME','APPOH','YAMOUSSOUKRO','0707930589','kgerappoh@gmail.com','$2y$12$e.qatGZcIICVipObzdz0YeYuzcmkWAFylb0DZn8tyYUyEvpSQok4y','Technologies du web',1,'1998-08-07','2024-12-09 13:27:01','2024-12-09 13:27:01'),(5,'Gbame','Gbede Sylvain','YAMOUSSOUKRO','0708965412','gbede.sylvain@inphb.ci','$2y$12$uKCQ.opjqoo8aW3HkIPQ5uuws.4T9lTSR24f1PJrn3yqFODLzH.ti','Technologies du web',1,'2014-08-05','2024-12-09 13:32:17','2024-12-09 13:32:17'),(6,'Morie','Maho Wielfrid','ABIDJAN','0709562415','maho.morie@gmail.com','$2y$12$qjkJaXF6xo6GaR3FFPm6ouisIPeY/fnD2.WFswKf2IMy.6Jtm84ka','Technologies du web',1,'1996-05-08','2024-12-09 13:38:47','2024-12-09 13:38:47'),(7,'SAHA','BERNARD','YAMOUSSOUKRO','0142630325','bernard.saha@gmail.com','$2y$12$a8wqdwZksojPZ7T8rQwgq.aSQaBFkOhYBcdZK8b92ojF0Lv8R4Xxu','Technologies du web',1,'2000-08-05','2024-12-09 13:52:12','2024-12-09 13:52:12'),(8,'Koffi','Kouakou Ive Arsene','ABIDJAN','0503985412','arsene.koffi@inphb.ci','$2y$12$3QVJo6cERPfRpYxXwcRow.wmOk5CC5AJ13eHcNvtG5wJTRjs0xclW','Technologies du web',1,'2010-05-25','2024-12-09 13:59:54','2024-12-09 13:59:54'),(9,'Kouamé','Abel Assiélou','YAMOUSSOUKRO','0757879587','abel.kouame@inphb.ci','$2y$12$22sWbwovgcTRVk12hFWRDeKyzgLkLtoMY8N.a3SY/XRF0IMn5caEq','Intelligence Artificielle',1,'2000-08-05','2024-12-09 14:04:18','2024-12-09 14:04:18'),(10,'KIMOU','KOUADIO PROSPER','yamoussoukro','0707105326','kouadio.kimou@inphb.ci','$2y$12$Aqg0zdD7yKfq2ryVIJpjze.NQ80/aQR3jn496gtM8iIDMOjCN8VEW','Intelligence Artificielle',1,'1993-08-08','2024-12-09 14:14:45','2024-12-09 14:14:45'),(11,'SANGARE','SEYDOU','YAMOUSSOUKRO','0509040520','seydou.sangare@inphb.ci','$2y$12$j2AQiUDba6LKt7QRin5NO.XUn2N3qgjGIYIMfxiJf3X8tbx7iolRu','Technologies du web',1,'2018-05-08','2024-12-09 14:20:00','2024-12-09 14:20:00'),(12,'LO','MOUSSA','ABIDJAN','0767995863','moussa.lo@gmail.com','$2y$12$VonjmUuM5E3EA61Pnha5Ne7ppcBwDzzgcaDh0y4zv/BNs5SjdePC2','Technologies du web',1,'2020-02-05','2024-12-09 15:10:22','2024-12-09 15:10:22'),(13,'LAMY','Jean Baptiste','YAMOUSSOUKRO','0767998521','jean.lamy@inphb.ci','$2y$12$aA9Q4YNhrzQvqYCuVkKxJ.hEOkp./JC5APrFIo6llpiMcUwaDOogC','Technologies du web',1,'2000-05-05','2024-12-09 16:34:09','2024-12-09 16:34:09'),(14,'Nombre','Claude Issa1','YAMOUSSOUKRO','0142630351','claude.nombre@inphb.ci','$2y$12$bRihtVTIex27O88Dq7R8/eMR4glq8BlEhErLwBx8BNw5aJvC0C9Xe',NULL,1,NULL,'2024-12-09 16:54:44','2024-12-09 16:54:44'),(15,'Trey','Zacrada Françoise Odile',NULL,'010154863','odile.trey@inphb.ci','$2y$12$fdR83VX/pkpLZQ.1xZ/jj.1T5Dr4oTHclMnLPc.HTL639kVJTjwzm','base de donnees et IA',1,'2014-02-05','2024-12-09 17:04:27','2024-12-09 17:04:27'),(16,'E.','Olajubu','yamoussoukro','0145896320','Olajubu@gmail.com','$2y$12$eb7hIJ1QMVYqVmYNUpSQuuBcayo6v//olVJQdTmuOV0HAv0xS6njO','INFORMATIQUE',1,'2005-05-05','2024-12-13 09:25:59','2024-12-13 09:25:59'),(17,'ANASSIN','Mireille Chiatsè','ABIDJAN','0708945007','Mireille@gmail.com','$2y$12$KEg.IdK46nS4ra6xr4HACe7VF88eS6a.X5kZTu3aoqKZ9kOa4W1/O','INFORMATIQUE',1,'2014-04-05','2024-12-13 09:37:03','2024-12-13 09:37:03'),(18,'AKA','Boko','ABIDJAN','0708945089','Boko@gmail.com','$2y$12$qqaD1/.zg3Nq/rVzE3AOp.2WQ8eKCYMpfkU5p6DIuYTL/iQRN.i06','INFORMATIQUE',1,'2014-12-10','2024-12-13 09:39:38','2024-12-13 09:39:38'),(19,'Samassi','Adama','yamoussoukro','0589632054','adamasamassi@gmail.com','$2y$12$b3BBTcQ/4PnrOirJ.4vryOzKF0yIo0avd8PL3bo6K.1Eg/hAlef4.','INFORMATIQUE',1,'2008-12-12','2024-12-13 09:51:56','2024-12-13 09:51:56'),(20,'Gooré','Bi Tra','yamoussoukro','0708563210','bitra@inphb.ci','$2y$12$LYnv6ElvYv4BeUQX50pWce6YAR0ct0JUmpTW61WSWHFu1tNdluUMW','INFORMATIQUE',1,'1998-04-12','2024-12-13 10:08:31','2024-12-13 10:08:31');
/*!40000 ALTER TABLE `chercheurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctorant_article_chercheur`
--

DROP TABLE IF EXISTS `doctorant_article_chercheur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctorant_article_chercheur` (
  `idDoc` bigint unsigned NOT NULL,
  `idCherch` bigint unsigned NOT NULL,
  `idArticle` bigint unsigned NOT NULL,
  PRIMARY KEY (`idDoc`,`idCherch`,`idArticle`),
  KEY `doctorant_article_chercheur_idcherch_foreign` (`idCherch`),
  KEY `doctorant_article_chercheur_idarticle_foreign` (`idArticle`),
  CONSTRAINT `doctorant_article_chercheur_idarticle_foreign` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`idArticle`),
  CONSTRAINT `doctorant_article_chercheur_idcherch_foreign` FOREIGN KEY (`idCherch`) REFERENCES `chercheurs` (`idCherch`),
  CONSTRAINT `doctorant_article_chercheur_iddoc_foreign` FOREIGN KEY (`idDoc`) REFERENCES `doctorants` (`idDoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctorant_article_chercheur`
--

LOCK TABLES `doctorant_article_chercheur` WRITE;
/*!40000 ALTER TABLE `doctorant_article_chercheur` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctorant_article_chercheur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctorant_chercheur`
--

DROP TABLE IF EXISTS `doctorant_chercheur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctorant_chercheur` (
  `idDoc` bigint unsigned NOT NULL,
  `idCherch` bigint unsigned NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  PRIMARY KEY (`idDoc`,`idCherch`),
  KEY `doctorant_chercheur_idcherch_foreign` (`idCherch`),
  CONSTRAINT `doctorant_chercheur_idcherch_foreign` FOREIGN KEY (`idCherch`) REFERENCES `chercheurs` (`idCherch`),
  CONSTRAINT `doctorant_chercheur_iddoc_foreign` FOREIGN KEY (`idDoc`) REFERENCES `doctorants` (`idDoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctorant_chercheur`
--

LOCK TABLES `doctorant_chercheur` WRITE;
/*!40000 ALTER TABLE `doctorant_chercheur` DISABLE KEYS */;
INSERT INTO `doctorant_chercheur` VALUES (1,3,'2019-01-01',NULL),(2,12,'1999-01-01',NULL);
/*!40000 ALTER TABLE `doctorant_chercheur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctorants`
--

DROP TABLE IF EXISTS `doctorants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctorants` (
  `idDoc` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomDoc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenomDoc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idTheme` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idDoc`),
  KEY `doctorants_idtheme_foreign` (`idTheme`),
  CONSTRAINT `doctorants_idtheme_foreign` FOREIGN KEY (`idTheme`) REFERENCES `themes` (`idTheme`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctorants`
--

LOCK TABLES `doctorants` WRITE;
/*!40000 ALTER TABLE `doctorants` DISABLE KEYS */;
INSERT INTO `doctorants` VALUES (1,'Koua','hortence',1,'2024-12-09 18:10:09','2024-12-25 08:02:49'),(2,'samassi','adama',2,'2024-12-09 18:15:17','2024-12-09 18:15:17');
/*!40000 ALTER TABLE `doctorants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edps`
--

DROP TABLE IF EXISTS `edps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edps` (
  `idEDP` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomEDP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localisationEDP` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `WhatsAppUMI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailUMI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idEDP`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edps`
--

LOCK TABLES `edps` WRITE;
/*!40000 ALTER TABLE `edps` DISABLE KEYS */;
INSERT INTO `edps` VALUES (1,'CAC','INPHB','123456789','edp@exemple.com','2024-12-09 10:19:33','2024-12-25 08:52:12'),(2,'EDP STI','INP-Nord',NULL,'edp.sti@inphb.ci','2024-12-09 17:52:36','2024-12-09 17:52:36');
/*!40000 ALTER TABLE `edps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `idGrade` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sigleGrade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomGrade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idGrade`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
INSERT INTO `grades` VALUES (1,'Dr','Docteur','2024-12-09 12:25:18','2024-12-25 10:52:42'),(2,'A','Assistant','2024-12-09 18:19:01','2024-12-09 18:19:01'),(3,'MA','Maitre Assistant','2024-12-09 18:19:33','2024-12-09 18:19:33'),(4,'MC','Maitre de Conférences','2024-12-09 18:20:17','2024-12-09 18:20:17'),(5,'PT','Professeur Titulaire','2024-12-09 18:20:46','2024-12-09 18:20:46');
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laboratoire_axe_recherche`
--

DROP TABLE IF EXISTS `laboratoire_axe_recherche`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laboratoire_axe_recherche` (
  `idLabo` bigint unsigned NOT NULL,
  `idAxeRech` bigint unsigned NOT NULL,
  PRIMARY KEY (`idLabo`,`idAxeRech`),
  KEY `laboratoire_axe_recherche_idaxerech_foreign` (`idAxeRech`),
  CONSTRAINT `laboratoire_axe_recherche_idaxerech_foreign` FOREIGN KEY (`idAxeRech`) REFERENCES `axe_recherches` (`idAxeRech`),
  CONSTRAINT `laboratoire_axe_recherche_idlabo_foreign` FOREIGN KEY (`idLabo`) REFERENCES `laboratoires` (`idLabo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laboratoire_axe_recherche`
--

LOCK TABLES `laboratoire_axe_recherche` WRITE;
/*!40000 ALTER TABLE `laboratoire_axe_recherche` DISABLE KEYS */;
/*!40000 ALTER TABLE `laboratoire_axe_recherche` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laboratoires`
--

DROP TABLE IF EXISTS `laboratoires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laboratoires` (
  `idLabo` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomLabo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anneeCreation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localisationLabo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresseLabo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telLabo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faxLabo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailLabo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descLabo` text COLLATE utf8mb4_unicode_ci,
  `idUMRI` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idLabo`),
  KEY `laboratoires_idumri_foreign` (`idUMRI`),
  CONSTRAINT `laboratoires_idumri_foreign` FOREIGN KEY (`idUMRI`) REFERENCES `umris` (`idUMRI`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laboratoires`
--

LOCK TABLES `laboratoires` WRITE;
/*!40000 ALTER TABLE `laboratoires` DISABLE KEYS */;
INSERT INTO `laboratoires` VALUES (1,'INFO-lab','2020','Abidjan','332 INPHB-CENTRE','0123456789','0123456789','info-lab@inphb.ci','Laboratoire informatique de l\'INPHB',1,'2024-12-09 10:19:33','2024-12-09 10:19:33'),(2,'LARIT','2024','INP-Centre','BP-1090',NULL,NULL,'labo.larit@inphb.ci',NULL,2,'2024-12-09 17:58:42','2024-12-09 17:58:42'),(3,'LASDIA','2024','INP-Centre','BP-1090',NULL,NULL,'labo.lasdia@inphb.ci',NULL,2,'2024-12-09 17:59:56','2024-12-09 17:59:56');
/*!40000 ALTER TABLE `laboratoires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2024_02_22_211350_create_visiteurs_table',1),(7,'2024_06_08_001542_create_admins_table',1),(8,'2024_11_01_194942_create_bd_indexations_table',1),(9,'2024_11_01_195411_create_grades_table',1),(10,'2024_11_03_165831_create_e_d_p_s_table',1),(11,'2024_11_03_170129_create_u_m_r_i_s_table',1),(12,'2024_11_03_172740_create_laboratoires_table',1),(13,'2024_11_03_172741_create_chercheurs_table',1),(14,'2024_11_03_175014_create_revues_table',1),(15,'2024_11_03_175230_create_articles_table',1),(16,'2024_11_03_175418_create_axe_recherches_table',1),(17,'2024_11_03_175623_create_themes_table',1),(18,'2024_11_03_192904_create_doctorants_table',1),(19,'2024_11_03_194312_create_chercheur_grades_table',1),(20,'2024_11_03_194502_create_chercheur_articles_table',1),(21,'2024_11_03_194647_create_laboratoire_axe_recherches_table',1),(22,'2024_11_03_194727_create_doctorant_chercheurs_table',1),(23,'2024_11_03_194957_create_doctorant_article_chercheurs_table',1),(24,'2024_11_03_195121_create_bd_indexation_revues_table',1),(25,'2024_11_03_195328_create_article_revues_table',1),(26,'2024_12_13_110420_create_article_revue_deux_table',2),(27,'2024_12_14_125615_update_themes_table',3),(28,'2024_12_14_144936_update_doctorants_table',4),(29,'2024_12_14_145122_add_datefin_to_doctorant_chercheur',4),(30,'2024_12_14_194848_update_chercheurs_table_nullable_fields',5),(31,'2024_12_16_235439_update_chercheur_grade_table',5),(32,'2024_11_03_195328_update_article_revues_table',6),(33,'2024_12_13_110420_update_article_revue_deux_table',6),(34,'2024_12_25_160759_update_revues_fields',6),(35,'2024_12_25_160840_update_bdindexation_revue_dates',6),(36,'2024_12_25_204405_update_doctorant_chercheur',7),(37,'2024_12_13_110420_update_articles_revue_deux_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revues`
--

DROP TABLE IF EXISTS `revues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revues` (
  `idRevue` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ISSN` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomRevue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descRevue` text COLLATE utf8mb4_unicode_ci,
  `typeRevue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idRevue`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revues`
--

LOCK TABLES `revues` WRITE;
/*!40000 ALTER TABLE `revues` DISABLE KEYS */;
INSERT INTO `revues` VALUES (1,'1234-5678','Journal of Programming','Une revue scientifique sur la programmations.','Scientifique','2024-12-09 10:19:33','2024-12-25 08:02:02'),(2,'1024-2003','Open Journal of Applied Sciences (OJAppS)',NULL,'Scientifique','2024-12-09 11:08:19','2024-12-09 11:08:19'),(3,'5066-9865','Ingénierie des Systèmes d’Information (ISI)',NULL,'Scientifique','2024-12-09 11:10:17','2024-12-09 11:10:17'),(4,'0912-2024','International Journal of Advanced Research (IJAR)',NULL,'Scientifique','2024-12-09 11:12:40','2024-12-09 11:12:40'),(5,'1512-2014','Stud Health Technol Inform, (IJSET)',NULL,'Scientifique','2024-12-09 11:16:23','2024-12-09 11:16:23'),(6,'5252-8954','International Journal of Advanced Computer Science and Applications (IJACSA)',NULL,'Scientifique','2024-12-09 11:29:11','2024-12-09 11:29:11'),(7,'2525-7896','Lecture Notes of the Institute for Computer Sciences, Social Informatics and Telecommunications Engineering (LNICST)',NULL,'Scientifique','2024-12-09 11:55:39','2024-12-09 11:55:39'),(8,'8545-9874','International Journal of Emerging Technologies in Learning (iJET)',NULL,'Scientifique','2024-12-09 12:02:29','2024-12-09 12:02:29'),(9,'1450-216X / 1450-202X','European Journal of Scientific Research (EJSR)',NULL,'Scientifique','2024-12-09 12:07:07','2024-12-09 12:07:07'),(10,'7998-9845','International Journal on Computational Science & Applications (IJCSA)',NULL,'Scientifique','2024-12-09 12:13:21','2024-12-09 12:13:21');
/*!40000 ALTER TABLE `revues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `themes` (
  `idTheme` bigint unsigned NOT NULL AUTO_INCREMENT,
  `intituleTheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descTheme` text COLLATE utf8mb4_unicode_ci,
  `idAxeRech` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idTheme`),
  KEY `themes_idaxerech_foreign` (`idAxeRech`),
  CONSTRAINT `themes_idaxerech_foreign` FOREIGN KEY (`idAxeRech`) REFERENCES `axe_recherches` (`idAxeRech`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'des','Recherche d\'information',2,'2024-12-09 18:06:00','2024-12-25 07:44:03'),(2,'','anthologie',2,'2024-12-09 18:07:16','2024-12-09 18:07:16'),(3,'test','test',1,'2024-12-14 13:10:59','2024-12-14 13:10:59');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umris`
--

DROP TABLE IF EXISTS `umris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `umris` (
  `idUMRI` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomUMRI` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localisationUMI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `WhatsAppUMRI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailUMRI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idEDP` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUMRI`),
  KEY `umris_idedp_foreign` (`idEDP`),
  CONSTRAINT `umris_idedp_foreign` FOREIGN KEY (`idEDP`) REFERENCES `edps` (`idEDP`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umris`
--

LOCK TABLES `umris` WRITE;
/*!40000 ALTER TABLE `umris` DISABLE KEYS */;
INSERT INTO `umris` VALUES (1,'Nom de l\'UMRI','Localisation de l\'UMRIs','0987654321','email@exemple.com',1,'2024-12-09 10:19:33','2024-12-25 08:36:38'),(2,'umri msn','Abidjan',NULL,'umris.msn@inphb.ci',2,'2024-12-09 17:54:35','2024-12-09 17:54:35'),(3,'umri sti','INP-Centre',NULL,'umri.sti@inphb.ci',2,'2024-12-09 17:55:45','2024-12-09 17:55:45');
/*!40000 ALTER TABLE `umris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visiteurs`
--

DROP TABLE IF EXISTS `visiteurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visiteurs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `visiteurs_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visiteurs`
--

LOCK TABLES `visiteurs` WRITE;
/*!40000 ALTER TABLE `visiteurs` DISABLE KEYS */;
INSERT INTO `visiteurs` VALUES (1,'silue','samuelCaleb@gmai.com','$2y$12$aM9b7qTOVHqUDVewYvTp0eAQwQj2JX4kwQNcB06rgj7x.FNAQiy/i',NULL,NULL,'2024-12-09 10:19:33','2024-12-09 10:19:33');
/*!40000 ALTER TABLE `visiteurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-29 21:03:20
