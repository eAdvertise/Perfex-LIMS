-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: eadcrm_nutrilab
-- ------------------------------------------------------
-- Server version	10.3.39-MariaDB

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
-- Table structure for table `tblactivity_log`
--

DROP TABLE IF EXISTS `tblactivity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblactivity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `date` datetime NOT NULL,
  `staffid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staffid` (`staffid`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblactivity_log`
--

LOCK TABLES `tblactivity_log` WRITE;
/*!40000 ALTER TABLE `tblactivity_log` DISABLE KEYS */;
INSERT INTO `tblactivity_log` VALUES (1,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-01-28 09:55:53','Charalambos Isaias'),(2,'New Custom Field Added [Certificate]','2026-01-28 10:02:00','Charalambos Isaias'),(3,'New Leads Source Added [SourceID: 3, Name: Contacts]','2026-01-28 10:17:18','Charalambos Isaias'),(4,'New Tax Added [ID: 1, V.A.T. 19%]','2026-01-28 10:17:52','Charalambos Isaias'),(5,'Currency Deleted [1]','2026-01-28 10:18:01','Charalambos Isaias'),(6,'New Payment Mode Added [ID: 2, Name:Cash]','2026-01-28 10:18:19','Charalambos Isaias'),(7,'Payment Mode Updated [ID: 1, Name:Bank]','2026-01-28 10:18:29','Charalambos Isaias'),(8,'New Payment Mode Added [ID: 3, Name:Banknote]','2026-01-28 10:19:08','Charalambos Isaias'),(9,'Email Sent To [Email: andreas.c@nutrilab.com.cy, Template: New Staff Created (Welcome Email)]','2026-01-28 10:46:44','Charalambos Isaias'),(10,'New Staff Member Added [ID: 2, Andreas Christofi]','2026-01-28 10:46:44','Charalambos Isaias'),(11,'Email Sent To [Email: elena.c@nutrilab.com.cy, Template: New Staff Created (Welcome Email)]','2026-01-28 10:48:11','Charalambos Isaias'),(12,'New Staff Member Added [ID: 3, Elena Charalambous]','2026-01-28 10:48:11','Charalambos Isaias'),(13,'Email Sent To [Email: maria.c@nutrilab.com.cy, Template: New Staff Created (Welcome Email)]','2026-01-28 10:51:20','Charalambos Isaias'),(14,'New Staff Member Added [ID: 4, Maria Chrysostomou]','2026-01-28 10:51:20','Charalambos Isaias'),(15,'Staff Member Updated [ID: 3, Elena Charalambous]','2026-01-28 11:01:26','Charalambos Isaias'),(16,'Email Sent To [Email: elena.k@nutrilab.com.cy, Template: New Staff Created (Welcome Email)]','2026-01-28 12:39:10','Charalambos Isaias'),(17,'New Staff Member Added [ID: 5, Elena Kiprianou]','2026-01-28 12:39:10','Charalambos Isaias'),(18,'Email Sent To [Email: eleni.m@nutrilab.com.cy, Template: New Staff Created (Welcome Email)]','2026-01-28 12:40:44','Charalambos Isaias'),(19,'New Staff Member Added [ID: 6, Eleni Michael]','2026-01-28 12:40:44','Charalambos Isaias'),(20,'Staff Member Updated [ID: 1, Super Admin]','2026-01-28 12:41:17','Charalambos Isaias'),(21,'[paymentsonaccount] Installed my_invoicepdf.php to 1 theme(s).','2026-01-28 12:43:21','Super Admin'),(22,'[POA 006] No payments found. Nothing to regenerate.','2026-01-28 12:43:22','Super Admin'),(23,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-02-05 11:27:55','Super Admin'),(24,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-02-24 10:20:32','Super Admin'),(25,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-02-25 12:50:49','Super Admin'),(26,'User Successfully Logged In [User Id: 2, Is Staff Member: Yes, IP: 213.207.177.141]','2026-03-04 11:58:06','Andreas Christofi'),(27,'User Successfully Logged In [User Id: 6, Is Staff Member: Yes, IP: 213.207.177.141]','2026-03-04 12:11:30','Eleni Michael'),(28,'User Successfully Logged In [User Id: 2, Is Staff Member: Yes, IP: 213.207.177.141]','2026-03-26 14:38:02','Andreas Christofi'),(29,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-03-26 16:12:49','Super Admin'),(30,'Items Group Created [Name: Analysies]','2026-03-26 16:35:05','Andreas Christofi'),(31,'New Invoice Item Added [ID:1, TVC 37°C]','2026-03-26 16:35:05','Andreas Christofi'),(32,'New Invoice Item Added [ID:2, TVC 22°C]','2026-03-26 16:37:26','Andreas Christofi'),(33,'New Invoice Item Added [ID:3, Ε. coli]','2026-03-26 16:40:07','Andreas Christofi'),(34,'New Invoice Item Added [ID:4, Coliforms]','2026-03-26 16:41:30','Andreas Christofi'),(35,'Invoice Item Updated [ID: 1, TVC 37°C]','2026-03-26 16:42:20','Super Admin'),(36,'New Invoice Item Added [ID:5, Εντερόκοκκοι]','2026-03-26 16:42:28','Andreas Christofi'),(37,'New Invoice Item Added [ID:6, Pseudomonas aeruginosa]','2026-03-26 16:43:25','Andreas Christofi'),(38,'Items Group Created [Name: Panels]','2026-03-26 16:46:48','Andreas Christofi'),(39,'New Invoice Item Added [ID:7, Πόσιμο νερό]','2026-03-26 16:46:48','Andreas Christofi'),(40,'New Invoice Item Added [ID:8, Πόσιμο νερό]','2026-03-26 16:55:28','Andreas Christofi'),(41,'New Invoice Item Added [ID:9, Κολυμβητικές Δεξαμενές]','2026-03-26 16:56:43','Andreas Christofi'),(42,'New Invoice Item Added [ID:10, Coagulase positive staphylococci]','2026-03-26 16:59:35','Andreas Christofi'),(43,'New Client Created [ID: 1, From Staff: 2]','2026-03-26 17:05:38','Andreas Christofi'),(44,'Email Sent To [Email: test@test.com, Template: New Contact Added/Registered (Welcome Email)]','2026-03-26 17:05:39','Andreas Christofi'),(45,'Contact Created [ID: 1]','2026-03-26 17:05:39','Andreas Christofi'),(46,'New Client Created [ID: 2, From Staff: 2]','2026-03-26 17:05:39','Andreas Christofi'),(47,'Invoice Item Updated [ID: 7, Εμφιαλωμένο Νερό]','2026-03-26 17:11:33','Andreas Christofi'),(48,'LIMS save_step1 draft partner_id=0','2026-03-26 17:14:16','Andreas Christofi'),(49,'Email Sent To [Email: maria.c@nutrilab.com.cy, Template: New Task Assigned (Sent to Staff)]','2026-03-26 17:31:32','Andreas Christofi'),(50,'New Task Added [ID:1, Name: Appointment for Order #1 (Home)]','2026-03-26 17:31:32','Andreas Christofi'),(51,'Email Sent To [Email: maria.c@nutrilab.com.cy, Template: New Task Assigned (Sent to Staff)]','2026-03-26 17:31:33','Andreas Christofi'),(52,'New Task Added [ID:2, Name: Appointment for Order #1 (Home)]','2026-03-26 17:31:33','Andreas Christofi'),(53,'Currency Updated [EUR]','2026-03-26 17:34:33','Andreas Christofi'),(54,'Invoice Deleted [INV-DRAFT]','2026-03-26 17:35:40','Andreas Christofi'),(55,'Invoice Status Updated [Invoice Number: INV-2026/000000, From: ΑΝΕΞΟΦΛΗΤΑ To: ΕΞΟΦΛΗΜΕΝΑ]','2026-03-26 17:35:50','Andreas Christofi'),(56,'Invoice Deleted [INV-2026/000001]','2026-03-26 17:38:02','Andreas Christofi'),(57,'Payment Mode Updated [ID: 1, Name:Eurobank]','2026-03-26 17:41:01','Andreas Christofi'),(58,'Invoice Status Updated [Invoice Number: INV-2026/001001, From: ΠΡΟΣΧΕΔΙΑ To: ΑΝΕΞΟΦΛΗΤΑ]','2026-03-26 17:41:56','Andreas Christofi'),(59,'User Successfully Logged In [User Id: 2, Is Staff Member: Yes, IP: 213.207.177.141]','2026-03-27 07:24:55','Andreas Christofi'),(60,'User Successfully Logged In [User Id: 1, Is Staff Member: Yes, IP: 213.7.198.114]','2026-04-15 15:31:04','Super Admin'),(61,'New Client Created [ID: 3, From Staff: 1]','2026-04-15 15:34:59','Super Admin'),(62,'Email Sent To [Email: isaias@eadvertise.eu, Template: New Contact Added/Registered (Welcome Email)]','2026-04-15 15:35:00','Super Admin'),(63,'Contact Created [ID: 2]','2026-04-15 15:35:00','Super Admin'),(64,'New Client Created [ID: 4, From Staff: 1]','2026-04-15 15:35:00','Super Admin'),(65,'Client Deleted [ID: 4]','2026-04-15 15:47:42','Super Admin');
/*!40000 ALTER TABLE `tblactivity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblannouncements`
--

DROP TABLE IF EXISTS `tblannouncements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblannouncements` (
  `announcementid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `message` mediumtext DEFAULT NULL,
  `showtousers` int(11) NOT NULL,
  `showtostaff` int(11) NOT NULL,
  `showname` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `userid` varchar(100) NOT NULL,
  PRIMARY KEY (`announcementid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblannouncements`
--

LOCK TABLES `tblannouncements` WRITE;
/*!40000 ALTER TABLE `tblannouncements` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblannouncements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclients`
--

DROP TABLE IF EXISTS `tblclients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclients` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(191) DEFAULT NULL,
  `vat` varchar(50) DEFAULT NULL,
  `phonenumber` varchar(30) DEFAULT NULL,
  `country` int(11) NOT NULL DEFAULT 0,
  `city` varchar(100) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `leadid` int(11) DEFAULT NULL,
  `billing_street` varchar(200) DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(100) DEFAULT NULL,
  `billing_country` int(11) DEFAULT 0,
  `shipping_street` varchar(200) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(100) DEFAULT NULL,
  `shipping_country` int(11) DEFAULT 0,
  `longitude` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `default_language` varchar(40) DEFAULT NULL,
  `default_currency` int(11) NOT NULL DEFAULT 0,
  `show_primary_contact` int(11) NOT NULL DEFAULT 0,
  `stripe_id` varchar(40) DEFAULT NULL,
  `registration_confirmed` int(11) NOT NULL DEFAULT 1,
  `addedfrom` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`userid`),
  KEY `country` (`country`),
  KEY `leadid` (`leadid`),
  KEY `company` (`company`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclients`
--

LOCK TABLES `tblclients` WRITE;
/*!40000 ALTER TABLE `tblclients` DISABLE KEYS */;
INSERT INTO `tblclients` VALUES (1,'IONAS','','25222222',0,'','','','','','2026-03-26 17:05:38',1,NULL,'','','','',0,'','','','',0,NULL,NULL,NULL,0,0,NULL,1,2),(2,NULL,NULL,'25222222',0,NULL,NULL,NULL,NULL,NULL,'2026-03-26 17:05:38',1,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1,2),(3,'ddddd','','666666666',58,'paphos','8047','','xczxc','','2026-04-15 15:34:59',1,NULL,'xczxc','paphos','','8047',58,'xczxc','paphos','','8047',58,NULL,NULL,NULL,0,0,NULL,1,1);
/*!40000 ALTER TABLE `tblclients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblconsent_purposes`
--

DROP TABLE IF EXISTS `tblconsent_purposes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblconsent_purposes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblconsent_purposes`
--

LOCK TABLES `tblconsent_purposes` WRITE;
/*!40000 ALTER TABLE `tblconsent_purposes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblconsent_purposes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblconsents`
--

DROP TABLE IF EXISTS `tblconsents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblconsents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(10) NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(40) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `lead_id` int(11) NOT NULL DEFAULT 0,
  `description` mediumtext DEFAULT NULL,
  `opt_in_purpose_description` mediumtext DEFAULT NULL,
  `purpose_id` int(11) NOT NULL,
  `staff_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purpose_id` (`purpose_id`),
  KEY `contact_id` (`contact_id`),
  KEY `lead_id` (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblconsents`
--

LOCK TABLES `tblconsents` WRITE;
/*!40000 ALTER TABLE `tblconsents` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblconsents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontact_permissions`
--

DROP TABLE IF EXISTS `tblcontact_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontact_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontact_permissions`
--

LOCK TABLES `tblcontact_permissions` WRITE;
/*!40000 ALTER TABLE `tblcontact_permissions` DISABLE KEYS */;
INSERT INTO `tblcontact_permissions` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1);
/*!40000 ALTER TABLE `tblcontact_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontacts`
--

DROP TABLE IF EXISTS `tblcontacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `is_primary` int(11) NOT NULL DEFAULT 1,
  `firstname` varchar(191) NOT NULL,
  `lastname` varchar(191) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phonenumber` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `new_pass_key` varchar(32) DEFAULT NULL,
  `new_pass_key_requested` datetime DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `email_verification_key` varchar(32) DEFAULT NULL,
  `email_verification_sent_at` datetime DEFAULT NULL,
  `last_ip` varchar(40) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `profile_image` varchar(191) DEFAULT NULL,
  `direction` varchar(3) DEFAULT NULL,
  `invoice_emails` tinyint(1) NOT NULL DEFAULT 1,
  `estimate_emails` tinyint(1) NOT NULL DEFAULT 1,
  `credit_note_emails` tinyint(1) NOT NULL DEFAULT 1,
  `contract_emails` tinyint(1) NOT NULL DEFAULT 1,
  `task_emails` tinyint(1) NOT NULL DEFAULT 1,
  `project_emails` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_emails` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `email` (`email`),
  KEY `is_primary` (`is_primary`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontacts`
--

LOCK TABLES `tblcontacts` WRITE;
/*!40000 ALTER TABLE `tblcontacts` DISABLE KEYS */;
INSERT INTO `tblcontacts` VALUES (1,2,1,'IONAS','','test@test.com','25222222',NULL,'2026-03-26 17:05:38','$2a$08$R3s8SEZcIQ3lOuq7YXmIcubsmbqcibItrqFYG0Vgd1x7XyNLMVPD.',NULL,NULL,'2026-03-26 17:05:38',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1,1,1,1,1,1,1);
/*!40000 ALTER TABLE `tblcontacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontract_comments`
--

DROP TABLE IF EXISTS `tblcontract_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontract_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext DEFAULT NULL,
  `contract_id` int(11) NOT NULL,
  `staffid` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontract_comments`
--

LOCK TABLES `tblcontract_comments` WRITE;
/*!40000 ALTER TABLE `tblcontract_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcontract_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontract_renewals`
--

DROP TABLE IF EXISTS `tblcontract_renewals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontract_renewals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contractid` int(11) NOT NULL,
  `old_start_date` date NOT NULL,
  `new_start_date` date NOT NULL,
  `old_end_date` date DEFAULT NULL,
  `new_end_date` date DEFAULT NULL,
  `old_value` decimal(15,2) DEFAULT NULL,
  `new_value` decimal(15,2) DEFAULT NULL,
  `date_renewed` datetime NOT NULL,
  `renewed_by` varchar(100) NOT NULL,
  `renewed_by_staff_id` int(11) NOT NULL DEFAULT 0,
  `is_on_old_expiry_notified` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontract_renewals`
--

LOCK TABLES `tblcontract_renewals` WRITE;
/*!40000 ALTER TABLE `tblcontract_renewals` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcontract_renewals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontracts`
--

DROP TABLE IF EXISTS `tblcontracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `client` int(11) NOT NULL,
  `datestart` date DEFAULT NULL,
  `dateend` date DEFAULT NULL,
  `contract_type` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `isexpirynotified` int(11) NOT NULL DEFAULT 0,
  `contract_value` decimal(15,2) DEFAULT NULL,
  `trash` tinyint(1) DEFAULT 0,
  `not_visible_to_client` tinyint(1) NOT NULL DEFAULT 0,
  `hash` varchar(32) DEFAULT NULL,
  `signed` tinyint(1) NOT NULL DEFAULT 0,
  `signature` varchar(40) DEFAULT NULL,
  `marked_as_signed` tinyint(1) NOT NULL DEFAULT 0,
  `acceptance_firstname` varchar(50) DEFAULT NULL,
  `acceptance_lastname` varchar(50) DEFAULT NULL,
  `acceptance_email` varchar(100) DEFAULT NULL,
  `acceptance_date` datetime DEFAULT NULL,
  `acceptance_ip` varchar(40) DEFAULT NULL,
  `short_link` varchar(100) DEFAULT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  `contacts_sent_to` mediumtext DEFAULT NULL,
  `last_sign_reminder_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client`),
  KEY `contract_type` (`contract_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontracts`
--

LOCK TABLES `tblcontracts` WRITE;
/*!40000 ALTER TABLE `tblcontracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcontracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontracts_types`
--

DROP TABLE IF EXISTS `tblcontracts_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontracts_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontracts_types`
--

LOCK TABLES `tblcontracts_types` WRITE;
/*!40000 ALTER TABLE `tblcontracts_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcontracts_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcountries`
--

DROP TABLE IF EXISTS `tblcountries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcountries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcountries`
--

LOCK TABLES `tblcountries` WRITE;
/*!40000 ALTER TABLE `tblcountries` DISABLE KEYS */;
INSERT INTO `tblcountries` VALUES (1,'AF','Afghanistan','Islamic Republic of Afghanistan','AFG','004','yes','93','.af'),(2,'AX','Aland Islands','&Aring;land Islands','ALA','248','no','358','.ax'),(3,'AL','Albania','Republic of Albania','ALB','008','yes','355','.al'),(4,'DZ','Algeria','People\'s Democratic Republic of Algeria','DZA','012','yes','213','.dz'),(5,'AS','American Samoa','American Samoa','ASM','016','no','1+684','.as'),(6,'AD','Andorra','Principality of Andorra','AND','020','yes','376','.ad'),(7,'AO','Angola','Republic of Angola','AGO','024','yes','244','.ao'),(8,'AI','Anguilla','Anguilla','AIA','660','no','1+264','.ai'),(9,'AQ','Antarctica','Antarctica','ATA','010','no','672','.aq'),(10,'AG','Antigua and Barbuda','Antigua and Barbuda','ATG','028','yes','1+268','.ag'),(11,'AR','Argentina','Argentine Republic','ARG','032','yes','54','.ar'),(12,'AM','Armenia','Republic of Armenia','ARM','051','yes','374','.am'),(13,'AW','Aruba','Aruba','ABW','533','no','297','.aw'),(14,'AU','Australia','Commonwealth of Australia','AUS','036','yes','61','.au'),(15,'AT','Austria','Republic of Austria','AUT','040','yes','43','.at'),(16,'AZ','Azerbaijan','Republic of Azerbaijan','AZE','031','yes','994','.az'),(17,'BS','Bahamas','Commonwealth of The Bahamas','BHS','044','yes','1+242','.bs'),(18,'BH','Bahrain','Kingdom of Bahrain','BHR','048','yes','973','.bh'),(19,'BD','Bangladesh','People\'s Republic of Bangladesh','BGD','050','yes','880','.bd'),(20,'BB','Barbados','Barbados','BRB','052','yes','1+246','.bb'),(21,'BY','Belarus','Republic of Belarus','BLR','112','yes','375','.by'),(22,'BE','Belgium','Kingdom of Belgium','BEL','056','yes','32','.be'),(23,'BZ','Belize','Belize','BLZ','084','yes','501','.bz'),(24,'BJ','Benin','Republic of Benin','BEN','204','yes','229','.bj'),(25,'BM','Bermuda','Bermuda Islands','BMU','060','no','1+441','.bm'),(26,'BT','Bhutan','Kingdom of Bhutan','BTN','064','yes','975','.bt'),(27,'BO','Bolivia','Plurinational State of Bolivia','BOL','068','yes','591','.bo'),(28,'BQ','Bonaire, Sint Eustatius and Saba','Bonaire, Sint Eustatius and Saba','BES','535','no','599','.bq'),(29,'BA','Bosnia and Herzegovina','Bosnia and Herzegovina','BIH','070','yes','387','.ba'),(30,'BW','Botswana','Republic of Botswana','BWA','072','yes','267','.bw'),(31,'BV','Bouvet Island','Bouvet Island','BVT','074','no','NONE','.bv'),(32,'BR','Brazil','Federative Republic of Brazil','BRA','076','yes','55','.br'),(33,'IO','British Indian Ocean Territory','British Indian Ocean Territory','IOT','086','no','246','.io'),(34,'BN','Brunei','Brunei Darussalam','BRN','096','yes','673','.bn'),(35,'BG','Bulgaria','Republic of Bulgaria','BGR','100','yes','359','.bg'),(36,'BF','Burkina Faso','Burkina Faso','BFA','854','yes','226','.bf'),(37,'BI','Burundi','Republic of Burundi','BDI','108','yes','257','.bi'),(38,'KH','Cambodia','Kingdom of Cambodia','KHM','116','yes','855','.kh'),(39,'CM','Cameroon','Republic of Cameroon','CMR','120','yes','237','.cm'),(40,'CA','Canada','Canada','CAN','124','yes','1','.ca'),(41,'CV','Cape Verde','Republic of Cape Verde','CPV','132','yes','238','.cv'),(42,'KY','Cayman Islands','The Cayman Islands','CYM','136','no','1+345','.ky'),(43,'CF','Central African Republic','Central African Republic','CAF','140','yes','236','.cf'),(44,'TD','Chad','Republic of Chad','TCD','148','yes','235','.td'),(45,'CL','Chile','Republic of Chile','CHL','152','yes','56','.cl'),(46,'CN','China','People\'s Republic of China','CHN','156','yes','86','.cn'),(47,'CX','Christmas Island','Christmas Island','CXR','162','no','61','.cx'),(48,'CC','Cocos (Keeling) Islands','Cocos (Keeling) Islands','CCK','166','no','61','.cc'),(49,'CO','Colombia','Republic of Colombia','COL','170','yes','57','.co'),(50,'KM','Comoros','Union of the Comoros','COM','174','yes','269','.km'),(51,'CG','Congo','Republic of the Congo','COG','178','yes','242','.cg'),(52,'CK','Cook Islands','Cook Islands','COK','184','some','682','.ck'),(53,'CR','Costa Rica','Republic of Costa Rica','CRI','188','yes','506','.cr'),(54,'CI','Cote d\'ivoire (Ivory Coast)','Republic of C&ocirc;te D\'Ivoire (Ivory Coast)','CIV','384','yes','225','.ci'),(55,'HR','Croatia','Republic of Croatia','HRV','191','yes','385','.hr'),(56,'CU','Cuba','Republic of Cuba','CUB','192','yes','53','.cu'),(57,'CW','Curacao','Cura&ccedil;ao','CUW','531','no','599','.cw'),(58,'CY','Cyprus','Republic of Cyprus','CYP','196','yes','357','.cy'),(59,'CZ','Czech Republic','Czech Republic','CZE','203','yes','420','.cz'),(60,'CD','Democratic Republic of the Congo','Democratic Republic of the Congo','COD','180','yes','243','.cd'),(61,'DK','Denmark','Kingdom of Denmark','DNK','208','yes','45','.dk'),(62,'DJ','Djibouti','Republic of Djibouti','DJI','262','yes','253','.dj'),(63,'DM','Dominica','Commonwealth of Dominica','DMA','212','yes','1+767','.dm'),(64,'DO','Dominican Republic','Dominican Republic','DOM','214','yes','1+809, 8','.do'),(65,'EC','Ecuador','Republic of Ecuador','ECU','218','yes','593','.ec'),(66,'EG','Egypt','Arab Republic of Egypt','EGY','818','yes','20','.eg'),(67,'SV','El Salvador','Republic of El Salvador','SLV','222','yes','503','.sv'),(68,'GQ','Equatorial Guinea','Republic of Equatorial Guinea','GNQ','226','yes','240','.gq'),(69,'ER','Eritrea','State of Eritrea','ERI','232','yes','291','.er'),(70,'EE','Estonia','Republic of Estonia','EST','233','yes','372','.ee'),(71,'ET','Ethiopia','Federal Democratic Republic of Ethiopia','ETH','231','yes','251','.et'),(72,'FK','Falkland Islands (Malvinas)','The Falkland Islands (Malvinas)','FLK','238','no','500','.fk'),(73,'FO','Faroe Islands','The Faroe Islands','FRO','234','no','298','.fo'),(74,'FJ','Fiji','Republic of Fiji','FJI','242','yes','679','.fj'),(75,'FI','Finland','Republic of Finland','FIN','246','yes','358','.fi'),(76,'FR','France','French Republic','FRA','250','yes','33','.fr'),(77,'GF','French Guiana','French Guiana','GUF','254','no','594','.gf'),(78,'PF','French Polynesia','French Polynesia','PYF','258','no','689','.pf'),(79,'TF','French Southern Territories','French Southern Territories','ATF','260','no',NULL,'.tf'),(80,'GA','Gabon','Gabonese Republic','GAB','266','yes','241','.ga'),(81,'GM','Gambia','Republic of The Gambia','GMB','270','yes','220','.gm'),(82,'GE','Georgia','Georgia','GEO','268','yes','995','.ge'),(83,'DE','Germany','Federal Republic of Germany','DEU','276','yes','49','.de'),(84,'GH','Ghana','Republic of Ghana','GHA','288','yes','233','.gh'),(85,'GI','Gibraltar','Gibraltar','GIB','292','no','350','.gi'),(86,'GR','Greece','Hellenic Republic','GRC','300','yes','30','.gr'),(87,'GL','Greenland','Greenland','GRL','304','no','299','.gl'),(88,'GD','Grenada','Grenada','GRD','308','yes','1+473','.gd'),(89,'GP','Guadaloupe','Guadeloupe','GLP','312','no','590','.gp'),(90,'GU','Guam','Guam','GUM','316','no','1+671','.gu'),(91,'GT','Guatemala','Republic of Guatemala','GTM','320','yes','502','.gt'),(92,'GG','Guernsey','Guernsey','GGY','831','no','44','.gg'),(93,'GN','Guinea','Republic of Guinea','GIN','324','yes','224','.gn'),(94,'GW','Guinea-Bissau','Republic of Guinea-Bissau','GNB','624','yes','245','.gw'),(95,'GY','Guyana','Co-operative Republic of Guyana','GUY','328','yes','592','.gy'),(96,'HT','Haiti','Republic of Haiti','HTI','332','yes','509','.ht'),(97,'HM','Heard Island and McDonald Islands','Heard Island and McDonald Islands','HMD','334','no','NONE','.hm'),(98,'HN','Honduras','Republic of Honduras','HND','340','yes','504','.hn'),(99,'HK','Hong Kong','Hong Kong','HKG','344','no','852','.hk'),(100,'HU','Hungary','Hungary','HUN','348','yes','36','.hu'),(101,'IS','Iceland','Republic of Iceland','ISL','352','yes','354','.is'),(102,'IN','India','Republic of India','IND','356','yes','91','.in'),(103,'ID','Indonesia','Republic of Indonesia','IDN','360','yes','62','.id'),(104,'IR','Iran','Islamic Republic of Iran','IRN','364','yes','98','.ir'),(105,'IQ','Iraq','Republic of Iraq','IRQ','368','yes','964','.iq'),(106,'IE','Ireland','Ireland','IRL','372','yes','353','.ie'),(107,'IM','Isle of Man','Isle of Man','IMN','833','no','44','.im'),(108,'IL','Israel','State of Israel','ISR','376','yes','972','.il'),(109,'IT','Italy','Italian Republic','ITA','380','yes','39','.jm'),(110,'JM','Jamaica','Jamaica','JAM','388','yes','1+876','.jm'),(111,'JP','Japan','Japan','JPN','392','yes','81','.jp'),(112,'JE','Jersey','The Bailiwick of Jersey','JEY','832','no','44','.je'),(113,'JO','Jordan','Hashemite Kingdom of Jordan','JOR','400','yes','962','.jo'),(114,'KZ','Kazakhstan','Republic of Kazakhstan','KAZ','398','yes','7','.kz'),(115,'KE','Kenya','Republic of Kenya','KEN','404','yes','254','.ke'),(116,'KI','Kiribati','Republic of Kiribati','KIR','296','yes','686','.ki'),(117,'XK','Kosovo','Republic of Kosovo','---','---','some','381',''),(118,'KW','Kuwait','State of Kuwait','KWT','414','yes','965','.kw'),(119,'KG','Kyrgyzstan','Kyrgyz Republic','KGZ','417','yes','996','.kg'),(120,'LA','Laos','Lao People\'s Democratic Republic','LAO','418','yes','856','.la'),(121,'LV','Latvia','Republic of Latvia','LVA','428','yes','371','.lv'),(122,'LB','Lebanon','Republic of Lebanon','LBN','422','yes','961','.lb'),(123,'LS','Lesotho','Kingdom of Lesotho','LSO','426','yes','266','.ls'),(124,'LR','Liberia','Republic of Liberia','LBR','430','yes','231','.lr'),(125,'LY','Libya','Libya','LBY','434','yes','218','.ly'),(126,'LI','Liechtenstein','Principality of Liechtenstein','LIE','438','yes','423','.li'),(127,'LT','Lithuania','Republic of Lithuania','LTU','440','yes','370','.lt'),(128,'LU','Luxembourg','Grand Duchy of Luxembourg','LUX','442','yes','352','.lu'),(129,'MO','Macao','The Macao Special Administrative Region','MAC','446','no','853','.mo'),(130,'MK','North Macedonia','Republic of North Macedonia','MKD','807','yes','389','.mk'),(131,'MG','Madagascar','Republic of Madagascar','MDG','450','yes','261','.mg'),(132,'MW','Malawi','Republic of Malawi','MWI','454','yes','265','.mw'),(133,'MY','Malaysia','Malaysia','MYS','458','yes','60','.my'),(134,'MV','Maldives','Republic of Maldives','MDV','462','yes','960','.mv'),(135,'ML','Mali','Republic of Mali','MLI','466','yes','223','.ml'),(136,'MT','Malta','Republic of Malta','MLT','470','yes','356','.mt'),(137,'MH','Marshall Islands','Republic of the Marshall Islands','MHL','584','yes','692','.mh'),(138,'MQ','Martinique','Martinique','MTQ','474','no','596','.mq'),(139,'MR','Mauritania','Islamic Republic of Mauritania','MRT','478','yes','222','.mr'),(140,'MU','Mauritius','Republic of Mauritius','MUS','480','yes','230','.mu'),(141,'YT','Mayotte','Mayotte','MYT','175','no','262','.yt'),(142,'MX','Mexico','United Mexican States','MEX','484','yes','52','.mx'),(143,'FM','Micronesia','Federated States of Micronesia','FSM','583','yes','691','.fm'),(144,'MD','Moldava','Republic of Moldova','MDA','498','yes','373','.md'),(145,'MC','Monaco','Principality of Monaco','MCO','492','yes','377','.mc'),(146,'MN','Mongolia','Mongolia','MNG','496','yes','976','.mn'),(147,'ME','Montenegro','Montenegro','MNE','499','yes','382','.me'),(148,'MS','Montserrat','Montserrat','MSR','500','no','1+664','.ms'),(149,'MA','Morocco','Kingdom of Morocco','MAR','504','yes','212','.ma'),(150,'MZ','Mozambique','Republic of Mozambique','MOZ','508','yes','258','.mz'),(151,'MM','Myanmar (Burma)','Republic of the Union of Myanmar','MMR','104','yes','95','.mm'),(152,'NA','Namibia','Republic of Namibia','NAM','516','yes','264','.na'),(153,'NR','Nauru','Republic of Nauru','NRU','520','yes','674','.nr'),(154,'NP','Nepal','Federal Democratic Republic of Nepal','NPL','524','yes','977','.np'),(155,'NL','Netherlands','Kingdom of the Netherlands','NLD','528','yes','31','.nl'),(156,'NC','New Caledonia','New Caledonia','NCL','540','no','687','.nc'),(157,'NZ','New Zealand','New Zealand','NZL','554','yes','64','.nz'),(158,'NI','Nicaragua','Republic of Nicaragua','NIC','558','yes','505','.ni'),(159,'NE','Niger','Republic of Niger','NER','562','yes','227','.ne'),(160,'NG','Nigeria','Federal Republic of Nigeria','NGA','566','yes','234','.ng'),(161,'NU','Niue','Niue','NIU','570','some','683','.nu'),(162,'NF','Norfolk Island','Norfolk Island','NFK','574','no','672','.nf'),(163,'KP','North Korea','Democratic People\'s Republic of Korea','PRK','408','yes','850','.kp'),(164,'MP','Northern Mariana Islands','Northern Mariana Islands','MNP','580','no','1+670','.mp'),(165,'NO','Norway','Kingdom of Norway','NOR','578','yes','47','.no'),(166,'OM','Oman','Sultanate of Oman','OMN','512','yes','968','.om'),(167,'PK','Pakistan','Islamic Republic of Pakistan','PAK','586','yes','92','.pk'),(168,'PW','Palau','Republic of Palau','PLW','585','yes','680','.pw'),(169,'PS','Palestine','State of Palestine (or Occupied Palestinian Territory)','PSE','275','some','970','.ps'),(170,'PA','Panama','Republic of Panama','PAN','591','yes','507','.pa'),(171,'PG','Papua New Guinea','Independent State of Papua New Guinea','PNG','598','yes','675','.pg'),(172,'PY','Paraguay','Republic of Paraguay','PRY','600','yes','595','.py'),(173,'PE','Peru','Republic of Peru','PER','604','yes','51','.pe'),(174,'PH','Philippines','Republic of the Philippines','PHL','608','yes','63','.ph'),(175,'PN','Pitcairn','Pitcairn','PCN','612','no','NONE','.pn'),(176,'PL','Poland','Republic of Poland','POL','616','yes','48','.pl'),(177,'PT','Portugal','Portuguese Republic','PRT','620','yes','351','.pt'),(178,'PR','Puerto Rico','Commonwealth of Puerto Rico','PRI','630','no','1+939','.pr'),(179,'QA','Qatar','State of Qatar','QAT','634','yes','974','.qa'),(180,'RE','Reunion','R&eacute;union','REU','638','no','262','.re'),(181,'RO','Romania','Romania','ROU','642','yes','40','.ro'),(182,'RU','Russia','Russian Federation','RUS','643','yes','7','.ru'),(183,'RW','Rwanda','Republic of Rwanda','RWA','646','yes','250','.rw'),(184,'BL','Saint Barthelemy','Saint Barth&eacute;lemy','BLM','652','no','590','.bl'),(185,'SH','Saint Helena','Saint Helena, Ascension and Tristan da Cunha','SHN','654','no','290','.sh'),(186,'KN','Saint Kitts and Nevis','Federation of Saint Christopher and Nevis','KNA','659','yes','1+869','.kn'),(187,'LC','Saint Lucia','Saint Lucia','LCA','662','yes','1+758','.lc'),(188,'MF','Saint Martin','Saint Martin','MAF','663','no','590','.mf'),(189,'PM','Saint Pierre and Miquelon','Saint Pierre and Miquelon','SPM','666','no','508','.pm'),(190,'VC','Saint Vincent and the Grenadines','Saint Vincent and the Grenadines','VCT','670','yes','1+784','.vc'),(191,'WS','Samoa','Independent State of Samoa','WSM','882','yes','685','.ws'),(192,'SM','San Marino','Republic of San Marino','SMR','674','yes','378','.sm'),(193,'ST','Sao Tome and Principe','Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe','STP','678','yes','239','.st'),(194,'SA','Saudi Arabia','Kingdom of Saudi Arabia','SAU','682','yes','966','.sa'),(195,'SN','Senegal','Republic of Senegal','SEN','686','yes','221','.sn'),(196,'RS','Serbia','Republic of Serbia','SRB','688','yes','381','.rs'),(197,'SC','Seychelles','Republic of Seychelles','SYC','690','yes','248','.sc'),(198,'SL','Sierra Leone','Republic of Sierra Leone','SLE','694','yes','232','.sl'),(199,'SG','Singapore','Republic of Singapore','SGP','702','yes','65','.sg'),(200,'SX','Sint Maarten','Sint Maarten','SXM','534','no','1+721','.sx'),(201,'SK','Slovakia','Slovak Republic','SVK','703','yes','421','.sk'),(202,'SI','Slovenia','Republic of Slovenia','SVN','705','yes','386','.si'),(203,'SB','Solomon Islands','Solomon Islands','SLB','090','yes','677','.sb'),(204,'SO','Somalia','Somali Republic','SOM','706','yes','252','.so'),(205,'ZA','South Africa','Republic of South Africa','ZAF','710','yes','27','.za'),(206,'GS','South Georgia and the South Sandwich Islands','South Georgia and the South Sandwich Islands','SGS','239','no','500','.gs'),(207,'KR','South Korea','Republic of Korea','KOR','410','yes','82','.kr'),(208,'SS','South Sudan','Republic of South Sudan','SSD','728','yes','211','.ss'),(209,'ES','Spain','Kingdom of Spain','ESP','724','yes','34','.es'),(210,'LK','Sri Lanka','Democratic Socialist Republic of Sri Lanka','LKA','144','yes','94','.lk'),(211,'SD','Sudan','Republic of the Sudan','SDN','729','yes','249','.sd'),(212,'SR','Suriname','Republic of Suriname','SUR','740','yes','597','.sr'),(213,'SJ','Svalbard and Jan Mayen','Svalbard and Jan Mayen','SJM','744','no','47','.sj'),(214,'SZ','Swaziland','Kingdom of Swaziland','SWZ','748','yes','268','.sz'),(215,'SE','Sweden','Kingdom of Sweden','SWE','752','yes','46','.se'),(216,'CH','Switzerland','Swiss Confederation','CHE','756','yes','41','.ch'),(217,'SY','Syria','Syrian Arab Republic','SYR','760','yes','963','.sy'),(218,'TW','Taiwan','Republic of China (Taiwan)','TWN','158','former','886','.tw'),(219,'TJ','Tajikistan','Republic of Tajikistan','TJK','762','yes','992','.tj'),(220,'TZ','Tanzania','United Republic of Tanzania','TZA','834','yes','255','.tz'),(221,'TH','Thailand','Kingdom of Thailand','THA','764','yes','66','.th'),(222,'TL','Timor-Leste (East Timor)','Democratic Republic of Timor-Leste','TLS','626','yes','670','.tl'),(223,'TG','Togo','Togolese Republic','TGO','768','yes','228','.tg'),(224,'TK','Tokelau','Tokelau','TKL','772','no','690','.tk'),(225,'TO','Tonga','Kingdom of Tonga','TON','776','yes','676','.to'),(226,'TT','Trinidad and Tobago','Republic of Trinidad and Tobago','TTO','780','yes','1+868','.tt'),(227,'TN','Tunisia','Republic of Tunisia','TUN','788','yes','216','.tn'),(228,'TR','Turkey','Republic of Turkey','TUR','792','yes','90','.tr'),(229,'TM','Turkmenistan','Turkmenistan','TKM','795','yes','993','.tm'),(230,'TC','Turks and Caicos Islands','Turks and Caicos Islands','TCA','796','no','1+649','.tc'),(231,'TV','Tuvalu','Tuvalu','TUV','798','yes','688','.tv'),(232,'UG','Uganda','Republic of Uganda','UGA','800','yes','256','.ug'),(233,'UA','Ukraine','Ukraine','UKR','804','yes','380','.ua'),(234,'AE','United Arab Emirates','United Arab Emirates','ARE','784','yes','971','.ae'),(235,'GB','United Kingdom','United Kingdom of Great Britain and Nothern Ireland','GBR','826','yes','44','.uk'),(236,'US','United States','United States of America','USA','840','yes','1','.us'),(237,'UM','United States Minor Outlying Islands','United States Minor Outlying Islands','UMI','581','no','NONE','NONE'),(238,'UY','Uruguay','Eastern Republic of Uruguay','URY','858','yes','598','.uy'),(239,'UZ','Uzbekistan','Republic of Uzbekistan','UZB','860','yes','998','.uz'),(240,'VU','Vanuatu','Republic of Vanuatu','VUT','548','yes','678','.vu'),(241,'VA','Vatican City','State of the Vatican City','VAT','336','no','39','.va'),(242,'VE','Venezuela','Bolivarian Republic of Venezuela','VEN','862','yes','58','.ve'),(243,'VN','Vietnam','Socialist Republic of Vietnam','VNM','704','yes','84','.vn'),(244,'VG','Virgin Islands, British','British Virgin Islands','VGB','092','no','1+284','.vg'),(245,'VI','Virgin Islands, US','Virgin Islands of the United States','VIR','850','no','1+340','.vi'),(246,'WF','Wallis and Futuna','Wallis and Futuna','WLF','876','no','681','.wf'),(247,'EH','Western Sahara','Western Sahara','ESH','732','no','212','.eh'),(248,'YE','Yemen','Republic of Yemen','YEM','887','yes','967','.ye'),(249,'ZM','Zambia','Republic of Zambia','ZMB','894','yes','260','.zm'),(250,'ZW','Zimbabwe','Republic of Zimbabwe','ZWE','716','yes','263','.zw');
/*!40000 ALTER TABLE `tblcountries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcreditnote_refunds`
--

DROP TABLE IF EXISTS `tblcreditnote_refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcreditnote_refunds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_note_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `refunded_on` date NOT NULL,
  `payment_mode` varchar(40) NOT NULL,
  `note` mediumtext DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcreditnote_refunds`
--

LOCK TABLES `tblcreditnote_refunds` WRITE;
/*!40000 ALTER TABLE `tblcreditnote_refunds` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcreditnote_refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcreditnotes`
--

DROP TABLE IF EXISTS `tblcreditnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcreditnotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `deleted_customer_name` varchar(100) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `number_format` int(11) NOT NULL DEFAULT 1,
  `formatted_number` varchar(100) DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `date` date NOT NULL,
  `adminnote` mediumtext DEFAULT NULL,
  `terms` mediumtext DEFAULT NULL,
  `clientnote` mediumtext DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `adjustment` decimal(15,2) DEFAULT NULL,
  `addedfrom` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `discount_percent` decimal(15,2) DEFAULT 0.00,
  `discount_total` decimal(15,2) DEFAULT 0.00,
  `discount_type` varchar(30) NOT NULL,
  `billing_street` varchar(200) DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(100) DEFAULT NULL,
  `billing_country` int(11) DEFAULT NULL,
  `shipping_street` varchar(200) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(100) DEFAULT NULL,
  `shipping_country` int(11) DEFAULT NULL,
  `include_shipping` tinyint(1) NOT NULL,
  `show_shipping_on_credit_note` tinyint(1) NOT NULL DEFAULT 1,
  `show_quantity_as` int(11) NOT NULL DEFAULT 1,
  `reference_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currency` (`currency`),
  KEY `clientid` (`clientid`),
  KEY `project_id` (`project_id`),
  KEY `formatted_number` (`formatted_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcreditnotes`
--

LOCK TABLES `tblcreditnotes` WRITE;
/*!40000 ALTER TABLE `tblcreditnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcreditnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcredits`
--

DROP TABLE IF EXISTS `tblcredits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcredits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `credit_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_applied` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcredits`
--

LOCK TABLES `tblcredits` WRITE;
/*!40000 ALTER TABLE `tblcredits` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcredits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcurrencies`
--

DROP TABLE IF EXISTS `tblcurrencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcurrencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `decimal_separator` varchar(5) DEFAULT NULL,
  `thousand_separator` varchar(5) DEFAULT NULL,
  `placement` varchar(10) DEFAULT NULL,
  `isdefault` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcurrencies`
--

LOCK TABLES `tblcurrencies` WRITE;
/*!40000 ALTER TABLE `tblcurrencies` DISABLE KEYS */;
INSERT INTO `tblcurrencies` VALUES (2,'€','EUR',',','.','before',1);
/*!40000 ALTER TABLE `tblcurrencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomer_admins`
--

DROP TABLE IF EXISTS `tblcustomer_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomer_admins` (
  `staff_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_assigned` mediumtext NOT NULL,
  KEY `customer_id` (`customer_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomer_admins`
--

LOCK TABLES `tblcustomer_admins` WRITE;
/*!40000 ALTER TABLE `tblcustomer_admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcustomer_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomer_groups`
--

DROP TABLE IF EXISTS `tblcustomer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomer_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomer_groups`
--

LOCK TABLES `tblcustomer_groups` WRITE;
/*!40000 ALTER TABLE `tblcustomer_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcustomer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomers_groups`
--

DROP TABLE IF EXISTS `tblcustomers_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomers_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomers_groups`
--

LOCK TABLES `tblcustomers_groups` WRITE;
/*!40000 ALTER TABLE `tblcustomers_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcustomers_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfields`
--

DROP TABLE IF EXISTS `tblcustomfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldto` varchar(30) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL,
  `options` longtext DEFAULT NULL,
  `display_inline` tinyint(1) NOT NULL DEFAULT 0,
  `field_order` int(11) DEFAULT 0,
  `active` int(11) NOT NULL DEFAULT 1,
  `show_on_pdf` int(11) NOT NULL DEFAULT 0,
  `show_on_ticket_form` tinyint(1) NOT NULL DEFAULT 0,
  `only_admin` tinyint(1) NOT NULL DEFAULT 0,
  `show_on_table` tinyint(1) NOT NULL DEFAULT 0,
  `show_on_client_portal` int(11) NOT NULL DEFAULT 0,
  `disalow_client_to_edit` int(11) NOT NULL DEFAULT 0,
  `bs_column` int(11) NOT NULL DEFAULT 12,
  `default_value` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfields`
--

LOCK TABLES `tblcustomfields` WRITE;
/*!40000 ALTER TABLE `tblcustomfields` DISABLE KEYS */;
INSERT INTO `tblcustomfields` VALUES (1,'company','Certificate','company_certificate',0,'input','',0,0,1,1,0,0,1,1,0,12,'');
/*!40000 ALTER TABLE `tblcustomfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldsvalues`
--

DROP TABLE IF EXISTS `tblcustomfieldsvalues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `fieldto` varchar(15) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relid` (`relid`),
  KEY `fieldto` (`fieldto`),
  KEY `fieldid` (`fieldid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldsvalues`
--

LOCK TABLES `tblcustomfieldsvalues` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsvalues` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsvalues` VALUES (1,0,1,'company','ISO/EC 17025:2017');
/*!40000 ALTER TABLE `tblcustomfieldsvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldepartments`
--

DROP TABLE IF EXISTS `tbldepartments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldepartments` (
  `departmentid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `imap_username` varchar(191) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_from_header` tinyint(1) NOT NULL DEFAULT 0,
  `host` varchar(150) DEFAULT NULL,
  `password` longtext DEFAULT NULL,
  `encryption` varchar(3) DEFAULT NULL,
  `folder` varchar(191) NOT NULL DEFAULT 'INBOX',
  `delete_after_import` int(11) NOT NULL DEFAULT 0,
  `calendar_id` longtext DEFAULT NULL,
  `hidefromclient` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`departmentid`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldepartments`
--

LOCK TABLES `tbldepartments` WRITE;
/*!40000 ALTER TABLE `tbldepartments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldepartments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldismissed_announcements`
--

DROP TABLE IF EXISTS `tbldismissed_announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldismissed_announcements` (
  `dismissedannouncementid` int(11) NOT NULL AUTO_INCREMENT,
  `announcementid` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`dismissedannouncementid`),
  KEY `announcementid` (`announcementid`),
  KEY `staff` (`staff`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldismissed_announcements`
--

LOCK TABLES `tbldismissed_announcements` WRITE;
/*!40000 ALTER TABLE `tbldismissed_announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldismissed_announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblemaillists`
--

DROP TABLE IF EXISTS `tblemaillists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblemaillists` (
  `listid` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `creator` varchar(100) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY (`listid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblemaillists`
--

LOCK TABLES `tblemaillists` WRITE;
/*!40000 ALTER TABLE `tblemaillists` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblemaillists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblemailtemplates`
--

DROP TABLE IF EXISTS `tblemailtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblemailtemplates` (
  `emailtemplateid` int(11) NOT NULL AUTO_INCREMENT,
  `type` longtext NOT NULL,
  `slug` varchar(100) NOT NULL,
  `language` varchar(40) DEFAULT NULL,
  `name` longtext NOT NULL,
  `subject` longtext NOT NULL,
  `message` longtext NOT NULL,
  `fromname` longtext NOT NULL,
  `fromemail` varchar(100) DEFAULT NULL,
  `plaintext` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`emailtemplateid`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblemailtemplates`
--

LOCK TABLES `tblemailtemplates` WRITE;
/*!40000 ALTER TABLE `tblemailtemplates` DISABLE KEYS */;
INSERT INTO `tblemailtemplates` VALUES (1,'client','new-client-created','english','New Contact Added/Registered (Welcome Email)','Welcome aboard','Dear {contact_firstname} {contact_lastname}<br /><br />Thank you for registering on the <strong>{companyname}</strong> CRM System.<br /><br />We just wanted to say welcome.<br /><br />Please contact us if you need any help.<br /><br />Click here to view your profile: <a href=\"{crm_url}\">{crm_url}</a><br /><br />Kind Regards, <br />{email_signature}<br /><br />(This is an automated email, so please don\'t reply to this email address)','{companyname} | CRM','',0,1,0),(2,'invoice','invoice-send-to-client','english','Send Invoice to Customer','Invoice with number {invoice_number} created','<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">We have prepared the following invoice for you: <strong># {invoice_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Invoice status</strong>: {invoice_status}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the invoice on the following link: <a href=\"{invoice_link}\">{invoice_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(3,'ticket','new-ticket-opened-admin','english','New Ticket Opened (Opened by Staff, Sent to Customer)','New Support Ticket Opened','<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br><br><span style=\"font-size: 12pt;\">New support ticket has been opened.</span><br><br><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {ticket_subject}</span><br><span style=\"font-size: 12pt;\"><strong>Department:</strong> {ticket_department}</span><br><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {ticket_priority}<br></span><br><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br><span style=\"font-size: 12pt;\">{ticket_message}</span><br><br><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_public_url}\">#{ticket_id}</a><br><br>Kind Regards,</span><br><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(4,'ticket','ticket-reply','english','Ticket Reply (Sent to Customer)','New Ticket Reply','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">You have a new ticket reply to ticket <a href=\"{ticket_public_url}\">#{ticket_id}</a></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Ticket Subject:</strong> {ticket_subject}<br /></span><br /><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br /><span style=\"font-size: 12pt;\">{ticket_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_public_url}\">#{ticket_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(5,'ticket','ticket-autoresponse','english','New Ticket Opened - Autoresponse','New Support Ticket Opened','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Thank you for contacting our support team. A support ticket has now been opened for your request. You will be notified when a response is made by email.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {ticket_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {ticket_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {ticket_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br /><span style=\"font-size: 12pt;\">{ticket_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_public_url}\">#{ticket_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(6,'invoice','invoice-payment-recorded','english','Invoice Payment Recorded (Sent to Customer)','Invoice Payment Recorded','<span style=\"font-size: 12pt;\">Hello {contact_firstname}&nbsp;{contact_lastname}<br /><br /></span>Thank you for the payment. Find the payment details below:<br /><br />-------------------------------------------------<br /><br />Amount:&nbsp;<strong>{payment_total}<br /></strong>Date:&nbsp;<strong>{payment_date}</strong><br />Invoice number:&nbsp;<span style=\"font-size: 12pt;\"><strong># {invoice_number}<br /><br /></strong></span>-------------------------------------------------<br /><br />You can always view the invoice for this payment at the following link:&nbsp;<a href=\"{invoice_link}\"><span style=\"font-size: 12pt;\">{invoice_number}</span></a><br /><br />We are looking forward working with you.<br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(7,'invoice','invoice-overdue-notice','english','Invoice Overdue Notice','Invoice Overdue Notice - {invoice_number}','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">This is an overdue notice for invoice <strong># {invoice_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\">This invoice was due: {invoice_duedate}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the invoice on the following link: <a href=\"{invoice_link}\">{invoice_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(8,'invoice','invoice-already-send','english','Invoice Already Sent to Customer','Invoice # {invoice_number} ','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">At your request, here is the invoice with number <strong># {invoice_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\">You can view the invoice on the following link: <a href=\"{invoice_link}\">{invoice_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(9,'ticket','new-ticket-created-staff','english','New Ticket Created (Opened by Customer, Sent to Staff Members)','New Ticket Created','<p><span style=\"font-size: 12pt;\">A new support ticket has been opened.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {ticket_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {ticket_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {ticket_priority}<br /></span><br /><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br /><span style=\"font-size: 12pt;\">{ticket_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_url}\">#{ticket_id}</a></span><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(10,'estimate','estimate-send-to-client','english','Send Estimate to Customer','Estimate # {estimate_number} created','<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Please find the attached estimate <strong># {estimate_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Estimate status:</strong> {estimate_status}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the estimate on the following link: <a href=\"{estimate_link}\">{estimate_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">We look forward to your communication.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}<br /></span>','{companyname} | CRM','',0,1,0),(11,'ticket','ticket-reply-to-admin','english','Ticket Reply (Sent to Staff)','New Support Ticket Reply','<span style=\"font-size: 12pt;\">A new support ticket reply from {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {ticket_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {ticket_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {ticket_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br /><span style=\"font-size: 12pt;\">{ticket_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_url}\">#{ticket_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(12,'estimate','estimate-already-send','english','Estimate Already Sent to Customer','Estimate # {estimate_number} ','<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /> <br /><span style=\"font-size: 12pt;\">Thank you for your estimate request.</span><br /> <br /><span style=\"font-size: 12pt;\">You can view the estimate on the following link: <a href=\"{estimate_link}\">{estimate_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(13,'contract','contract-expiration','english','Contract Expiration Reminder (Sent to Customer Contacts)','Contract Expiration Reminder','<span style=\"font-size: 12pt;\">Dear {client_company}</span><br /><br /><span style=\"font-size: 12pt;\">This is a reminder that the following contract will expire soon:</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {contract_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Description:</strong> {contract_description}</span><br /><span style=\"font-size: 12pt;\"><strong>Date Start:</strong> {contract_datestart}</span><br /><span style=\"font-size: 12pt;\"><strong>Date End:</strong> {contract_dateend}</span><br /><br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(14,'tasks','task-assigned','english','New Task Assigned (Sent to Staff)','New Task Assigned to You - {task_name}','<span style=\"font-size: 12pt;\">Dear {staff_firstname}</span><br /><br /><span style=\"font-size: 12pt;\">You have been assigned to a new task:</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Name:</strong> {task_name}<br /></span><strong>Start Date:</strong> {task_startdate}<br /><span style=\"font-size: 12pt;\"><strong>Due date:</strong> {task_duedate}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {task_priority}<br /><br /></span><span style=\"font-size: 12pt;\"><span>You can view the task on the following link</span>: <a href=\"{task_link}\">{task_name}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(15,'tasks','task-added-as-follower','english','Staff Member Added as Follower on Task (Sent to Staff)','You are added as follower on task - {task_name}','<span style=\"font-size: 12pt;\">Hi {staff_firstname}<br /></span><br /><span style=\"font-size: 12pt;\">You have been added as follower on the following task:</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Name:</strong> {task_name}</span><br /><span style=\"font-size: 12pt;\"><strong>Start date:</strong> {task_startdate}</span><br /><br /><span>You can view the task on the following link</span><span>: </span><a href=\"{task_link}\">{task_name}</a><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(16,'tasks','task-commented','english','New Comment on Task (Sent to Staff)','New Comment on Task - {task_name}','Dear {staff_firstname}<br /><br />A comment has been made on the following task:<br /><br /><strong>Name:</strong> {task_name}<br /><strong>Comment:</strong> {task_comment}<br /><br />You can view the task on the following link: <a href=\"{task_link}\">{task_name}</a><br /><br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(17,'tasks','task-added-attachment','english','New Attachment(s) on Task (Sent to Staff)','New Attachment on Task - {task_name}','Hi {staff_firstname}<br /><br /><strong>{task_user_take_action}</strong> added an attachment on the following task:<br /><br /><strong>Name:</strong> {task_name}<br /><br />You can view the task on the following link: <a href=\"{task_link}\">{task_name}</a><br /><br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(18,'estimate','estimate-declined-to-staff','english','Estimate Declined (Sent to Staff)','Customer Declined Estimate','<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) declined estimate with number <strong># {estimate_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the estimate on the following link: <a href=\"{estimate_link}\">{estimate_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(19,'estimate','estimate-accepted-to-staff','english','Estimate Accepted (Sent to Staff)','Customer Accepted Estimate','<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) accepted estimate with number <strong># {estimate_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the estimate on the following link: <a href=\"{estimate_link}\">{estimate_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(20,'proposals','proposal-client-accepted','english','Customer Action - Accepted (Sent to Staff)','Customer Accepted Proposal','<div>Hi<br /> <br />Client <strong>{proposal_proposal_to}</strong> accepted the following proposal:<br /> <br /><strong>Number:</strong> {proposal_number}<br /><strong>Subject</strong>: {proposal_subject}<br /><strong>Total</strong>: {proposal_total}<br /> <br />View the proposal on the following link: <a href=\"{proposal_link}\">{proposal_number}</a><br /> <br />Kind Regards,<br />{email_signature}</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>','{companyname} | CRM','',0,1,0),(21,'proposals','proposal-send-to-customer','english','Send Proposal to Customer','Proposal With Number {proposal_number} Created','Dear {proposal_proposal_to}<br /><br />Please find our attached proposal.<br /><br />This proposal is valid until: {proposal_open_till}<br />You can view the proposal on the following link: <a href=\"{proposal_link}\">{proposal_number}</a><br /><br />Please don\'t hesitate to comment online if you have any questions.<br /><br />We look forward to your communication.<br /><br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(22,'proposals','proposal-client-declined','english','Customer Action - Declined (Sent to Staff)','Client Declined Proposal','Hi<br /> <br />Customer <strong>{proposal_proposal_to}</strong> declined the proposal <strong>{proposal_subject}</strong><br /> <br />View the proposal on the following link <a href=\"{proposal_link}\">{proposal_number}</a>&nbsp;or from the admin area.<br /> <br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(23,'proposals','proposal-client-thank-you','english','Thank You Email (Sent to Customer After Accept)','Thank for you accepting proposal','Dear {proposal_proposal_to}<br /> <br />Thank for for accepting the proposal.<br /> <br />We look forward to doing business with you.<br /> <br />We will contact you as soon as possible<br /> <br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(24,'proposals','proposal-comment-to-client','english','New Comment Â (Sent to Customer/Lead)','New Proposal Comment','Dear {proposal_proposal_to}<br /> <br />A new comment has been made on the following proposal: <strong>{proposal_number}</strong><br /> <br />You can view and reply to the comment on the following link: <a href=\"{proposal_link}\">{proposal_number}</a><br /> <br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(25,'proposals','proposal-comment-to-admin','english','New Comment (Sent to Staff) ','New Proposal Comment','Hi<br /> <br />A new comment has been made to the proposal <strong>{proposal_subject}</strong><br /> <br />You can view and reply to the comment on the following link: <a href=\"{proposal_link}\">{proposal_number}</a>&nbsp;or from the admin area.<br /> <br />{email_signature}','{companyname} | CRM','',0,1,0),(26,'estimate','estimate-thank-you-to-customer','english','Thank You Email (Sent to Customer After Accept)','Thank for you accepting estimate','<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /> <br /><span style=\"font-size: 12pt;\">Thank for for accepting the estimate.</span><br /> <br /><span style=\"font-size: 12pt;\">We look forward to doing business with you.</span><br /> <br /><span style=\"font-size: 12pt;\">We will contact you as soon as possible.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(27,'tasks','task-deadline-notification','english','Task Deadline Reminder - Sent to Assigned Members','Task Deadline Reminder','Hi {staff_firstname}&nbsp;{staff_lastname}<br /><br />This is an automated email from {companyname}.<br /><br />The task <strong>{task_name}</strong> deadline is on <strong>{task_duedate}</strong>. <br />This task is still not finished.<br /><br />You can view the task on the following link: <a href=\"{task_link}\">{task_name}</a><br /><br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(28,'contract','send-contract','english','Send Contract to Customer','Contract - {contract_subject}','<p><span style=\"font-size: 12pt;\">Hi&nbsp;{contact_firstname}&nbsp;{contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Please find the <a href=\"{contract_link}\">{contract_subject}</a> attached.<br /><br />Description: {contract_description}<br /><br /></span><span style=\"font-size: 12pt;\">Looking forward to hear from you.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(29,'invoice','invoice-payment-recorded-to-staff','english','Invoice Payment Recorded (Sent to Staff)','New Invoice Payment','<span style=\"font-size: 12pt;\">Hi</span><br /><br /><span style=\"font-size: 12pt;\">Customer recorded payment for invoice <strong># {invoice_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the invoice on the following link: <a href=\"{invoice_link}\">{invoice_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(30,'ticket','auto-close-ticket','english','Auto Close Ticket','Ticket Auto Closed','<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Ticket {ticket_subject} has been auto close due to inactivity.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Ticket #</strong>: <a href=\"{ticket_public_url}\">{ticket_id}</a></span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {ticket_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {ticket_priority}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(31,'project','new-project-discussion-created-to-staff','english','New Project Discussion (Sent to Project Members)','New Project Discussion Created - {project_name}','<p>Hi {staff_firstname}<br /><br />New project discussion created from <strong>{discussion_creator}</strong><br /><br /><strong>Subject:</strong> {discussion_subject}<br /><strong>Description:</strong> {discussion_description}<br /><br />You can view the discussion on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(32,'project','new-project-discussion-created-to-customer','english','New Project Discussion (Sent to Customer Contacts)','New Project Discussion Created - {project_name}','<p>Hello {contact_firstname} {contact_lastname}<br /><br />New project discussion created from <strong>{discussion_creator}</strong><br /><br /><strong>Subject:</strong> {discussion_subject}<br /><strong>Description:</strong> {discussion_description}<br /><br />You can view the discussion on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(33,'project','new-project-file-uploaded-to-customer','english','New Project File(s) Uploaded (Sent to Customer Contacts)','New Project File(s) Uploaded - {project_name}','<p>Hello {contact_firstname} {contact_lastname}<br /><br />New project file is uploaded on <strong>{project_name}</strong> from <strong>{file_creator}</strong><br /><br />You can view the project on the following link: <a href=\"{project_link}\">{project_name}</a><br /><br />To view the file in our CRM you can click on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(34,'project','new-project-file-uploaded-to-staff','english','New Project File(s) Uploaded (Sent to Project Members)','New Project File(s) Uploaded - {project_name}','<p>Hello&nbsp;{staff_firstname}</p>\r\n<p>New project&nbsp;file is uploaded on&nbsp;<strong>{project_name}</strong> from&nbsp;<strong>{file_creator}</strong></p>\r\n<p>You can view the project on the following link: <a href=\"{project_link}\">{project_name}<br /></a><br />To view&nbsp;the file you can click on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a></p>\r\n<p>Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(35,'project','new-project-discussion-comment-to-customer','english','New Discussion Comment  (Sent to Customer Contacts)','New Discussion Comment','<p><span style=\"font-size: 12pt;\">Hello {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">New discussion comment has been made on <strong>{discussion_subject}</strong> from <strong>{comment_creator}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Discussion subject:</strong> {discussion_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Comment</strong>: {discussion_comment}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the discussion on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(36,'project','new-project-discussion-comment-to-staff','english','New Discussion Comment (Sent to Project Members)','New Discussion Comment','<p>Hi {staff_firstname}<br /><br />New discussion comment has been made on <strong>{discussion_subject}</strong> from <strong>{comment_creator}</strong><br /><br /><strong>Discussion subject:</strong> {discussion_subject}<br /><strong>Comment:</strong> {discussion_comment}<br /><br />You can view the discussion on the following link: <a href=\"{discussion_link}\">{discussion_subject}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(37,'project','staff-added-as-project-member','english','Staff Added as Project Member','New project assigned to you','<p>Hi {staff_firstname}<br /><br />New project has been assigned to you.<br /><br />You can view the project on the following link <a href=\"{project_link}\">{project_name}</a><br /><br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(38,'estimate','estimate-expiry-reminder','english','Estimate Expiration Reminder','Estimate Expiration Reminder','<p><span style=\"font-size: 12pt;\">Hello {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">The estimate with <strong># {estimate_number}</strong> will expire on <strong>{estimate_expirydate}</strong></span><br /><br /><span style=\"font-size: 12pt;\">You can view the estimate on the following link: <a href=\"{estimate_link}\">{estimate_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(39,'proposals','proposal-expiry-reminder','english','Proposal Expiration Reminder','Proposal Expiration Reminder','<p>Hello {proposal_proposal_to}<br /><br />The proposal {proposal_number}&nbsp;will expire on <strong>{proposal_open_till}</strong><br /><br />You can view the proposal on the following link: <a href=\"{proposal_link}\">{proposal_number}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(40,'staff','new-staff-created','english','New Staff Created (Welcome Email)','You are added as staff member','Hi {staff_firstname}<br /><br />You are added as member on our CRM.<br /><br />Please use the following logic credentials:<br /><br /><strong>Email:</strong> {staff_email}<br /><strong>Password:</strong> {password}<br /><br />Click <a href=\"{admin_url}\">here </a>to login in the dashboard.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(41,'client','contact-forgot-password','english','Forgot Password','Create New Password','<h2>Create a new password</h2>\r\nForgot your password?<br /> To create a new password, just follow this link:<br /> <br /><a href=\"{reset_password_url}\">Reset Password</a><br /> <br /> You received this email, because it was requested by a {companyname}&nbsp;user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br /><br /> {email_signature}','{companyname} | CRM','',0,1,0),(42,'client','contact-password-reseted','english','Password Reset - Confirmation','Your password has been changed','<strong><span style=\"font-size: 14pt;\">You have changed your password.</span><br /></strong><br /> Please, keep it in your records so you don\'t forget it.<br /> <br /> Your email address for login is: {contact_email}<br /><br />If this wasnt you, please contact us.<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(43,'client','contact-set-password','english','Set New Password','Set new password on {companyname} ','<h2><span style=\"font-size: 14pt;\">Setup your new password on {companyname}</span></h2>\r\nPlease use the following link to set up your new password:<br /><br /><a href=\"{set_password_url}\">Set new password</a><br /><br />Keep it in your records so you don\'t forget it.<br /><br />Please set your new password in <strong>48 hours</strong>. After that, you won\'t be able to set your password because this link will expire.<br /><br />You can login at: <a href=\"{crm_url}\">{crm_url}</a><br />Your email address for login: {contact_email}<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(44,'staff','staff-forgot-password','english','Forgot Password','Create New Password','<h2><span style=\"font-size: 14pt;\">Create a new password</span></h2>\r\nForgot your password?<br /> To create a new password, just follow this link:<br /> <br /><a href=\"{reset_password_url}\">Reset Password</a><br /> <br /> You received this email, because it was requested by a <strong>{companyname}</strong>&nbsp;user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br /><br /> {email_signature}','{companyname} | CRM','',0,1,0),(45,'staff','staff-password-reseted','english','Password Reset - Confirmation','Your password has been changed','<span style=\"font-size: 14pt;\"><strong>You have changed your password.<br /></strong></span><br /> Please, keep it in your records so you don\'t forget it.<br /> <br /> Your email address for login is: {staff_email}<br /><br /> If this wasnt you, please contact us.<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(46,'project','assigned-to-project','english','New Project Created (Sent to Customer Contacts)','New Project Created','<p>Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}</p>\r\n<p>New project is assigned to your company.<br /><br /><strong>Project Name:</strong>&nbsp;{project_name}<br /><strong>Project Start Date:</strong>&nbsp;{project_start_date}</p>\r\n<p>You can view the project on the following link:&nbsp;<a href=\"{project_link}\">{project_name}</a></p>\r\n<p>We are looking forward hearing from you.<br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(47,'tasks','task-added-attachment-to-contacts','english','New Attachment(s) on Task (Sent to Customer Contacts)','New Attachment on Task - {task_name}','<span>Hi {contact_firstname} {contact_lastname}</span><br /><br /><strong>{task_user_take_action}</strong><span> added an attachment on the following task:</span><br /><br /><strong>Name:</strong><span> {task_name}</span><br /><br /><span>You can view the task on the following link: </span><a href=\"{task_link}\">{task_name}</a><br /><br /><span>Kind Regards,</span><br /><span>{email_signature}</span>','{companyname} | CRM','',0,1,0),(48,'tasks','task-commented-to-contacts','english','New Comment on Task (Sent to Customer Contacts)','New Comment on Task - {task_name}','<span>Dear {contact_firstname} {contact_lastname}</span><br /><br /><span>A comment has been made on the following task:</span><br /><br /><strong>Name:</strong><span> {task_name}</span><br /><strong>Comment:</strong><span> {task_comment}</span><br /><br /><span>You can view the task on the following link: </span><a href=\"{task_link}\">{task_name}</a><br /><br /><span>Kind Regards,</span><br /><span>{email_signature}</span>','{companyname} | CRM','',0,1,0),(49,'leads','new-lead-assigned','english','New Lead Assigned to Staff Member','New lead assigned to you','<p>Hello {lead_assigned}<br /><br />New lead is assigned to you.<br /><br /><strong>Lead Name:</strong>&nbsp;{lead_name}<br /><strong>Lead Email:</strong>&nbsp;{lead_email}<br /><br />You can view the lead on the following link: <a href=\"{lead_link}\">{lead_name}</a><br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(50,'client','client-statement','english','Statement - Account Summary','Account Statement from {statement_from} to {statement_to}','Dear {contact_firstname} {contact_lastname}, <br /><br />Its been a great experience working with you.<br /><br />Attached with this email is a list of all transactions for the period between {statement_from} to {statement_to}<br /><br />For your information your account balance due is total:&nbsp;{statement_balance_due}<br /><br />Please contact us if you need more information.<br /> <br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(51,'ticket','ticket-assigned-to-admin','english','New Ticket Assigned (Sent to Staff)','New support ticket has been assigned to you','<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new support ticket&nbsp;has been assigned to you.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {ticket_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {ticket_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {ticket_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Ticket message:</strong></span><br /><span style=\"font-size: 12pt;\">{ticket_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{ticket_url}\">#{ticket_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>','{companyname} | CRM','',0,1,0),(52,'client','new-client-registered-to-admin','english','New Customer Registration (Sent to admins)','New Customer Registration','Hello.<br /><br />New customer registration on your customer portal:<br /><br /><strong>Firstname:</strong>&nbsp;{contact_firstname}<br /><strong>Lastname:</strong>&nbsp;{contact_lastname}<br /><strong>Company:</strong>&nbsp;{client_company}<br /><strong>Email:</strong>&nbsp;{contact_email}<br /><br />Best Regards','{companyname} | CRM','',0,1,0),(53,'leads','new-web-to-lead-form-submitted','english','Web to lead form submitted - Sent to lead','{lead_name} - We Received Your Request','Hello {lead_name}.<br /><br /><strong>Your request has been received.</strong><br /><br />This email is to let you know that we received your request and we will get back to you as soon as possible with more information.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,0,0),(54,'staff','two-factor-authentication','english','Two Factor Authentication','Confirm Your Login','<p>Hi {staff_firstname}</p>\r\n<p style=\"text-align: left;\">You received this email because you have enabled two factor authentication in your account.<br />Use the following code to confirm your login:</p>\r\n<p style=\"text-align: left;\"><span style=\"font-size: 18pt;\"><strong>{two_factor_auth_code}<br /><br /></strong><span style=\"font-size: 12pt;\">{email_signature}</span><strong><br /><br /><br /><br /></strong></span></p>','{companyname} | CRM','',0,1,0),(55,'project','project-finished-to-customer','english','Project Marked as Finished (Sent to Customer Contacts)','Project Marked as Finished','<p>Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}</p>\r\n<p>You are receiving this email because project&nbsp;<strong>{project_name}</strong> has been marked as finished. This project is assigned under your company and we just wanted to keep you up to date.<br /><br />You can view the project on the following link:&nbsp;<a href=\"{project_link}\">{project_name}</a></p>\r\n<p>If you have any questions don\'t hesitate to contact us.<br /><br />Kind Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(56,'credit_note','credit-note-send-to-client','english','Send Credit Note To Email','Credit Note With Number #{credit_note_number} Created','Dear&nbsp;{contact_firstname}&nbsp;{contact_lastname}<br /><br />We have attached the credit note with number <strong>#{credit_note_number} </strong>for your reference.<br /><br /><strong>Date:</strong>&nbsp;{credit_note_date}<br /><strong>Total Amount:</strong>&nbsp;{credit_note_total}<br /><br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(57,'tasks','task-status-change-to-staff','english','Task Status Changed (Sent to Staff)','Task Status Changed','<span style=\"font-size: 12pt;\">Hi {staff_firstname}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>{task_user_take_action}</strong> marked task as <strong>{task_status}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Name:</strong> {task_name}</span><br /><span style=\"font-size: 12pt;\"><strong>Due date:</strong> {task_duedate}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the task on the following link: <a href=\"{task_link}\">{task_name}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(58,'tasks','task-status-change-to-contacts','english','Task Status Changed (Sent to Customer Contacts)','Task Status Changed','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>{task_user_take_action}</strong> marked task as <strong>{task_status}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Name:</strong> {task_name}</span><br /><span style=\"font-size: 12pt;\"><strong>Due date:</strong> {task_duedate}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the task on the following link: <a href=\"{task_link}\">{task_name}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(59,'staff','reminder-email-staff','english','Staff Reminder Email','You Have a New Reminder!','<p>Hello&nbsp;{staff_firstname}<br /><br /><strong>You have a new reminder&nbsp;linked to&nbsp;{staff_reminder_relation_name}!<br /><br />Reminder description:</strong><br />{staff_reminder_description}<br /><br />Click <a href=\"{staff_reminder_relation_link}\">here</a> to view&nbsp;<a href=\"{staff_reminder_relation_link}\">{staff_reminder_relation_name}</a><br /><br />Best Regards<br /><br /></p>','{companyname} | CRM','',0,1,0),(60,'contract','contract-comment-to-client','english','New Comment Â (Sent to Customer Contacts)','New Contract Comment','Dear {contact_firstname} {contact_lastname}<br /> <br />A new comment has been made on the following contract: <strong>{contract_subject}</strong><br /> <br />You can view and reply to the comment on the following link: <a href=\"{contract_link}\">{contract_subject}</a><br /> <br />Kind Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(61,'contract','contract-comment-to-admin','english','New Comment (Sent to Staff) ','New Contract Comment','Hi {staff_firstname}<br /><br />A new comment has been made to the contract&nbsp;<strong>{contract_subject}</strong><br /><br />You can view and reply to the comment on the following link: <a href=\"{contract_link}\">{contract_subject}</a>&nbsp;or from the admin area.<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(62,'subscriptions','send-subscription','english','Send Subscription to Customer','Subscription Created','Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}<br /><br />We have prepared the subscription&nbsp;<strong>{subscription_name}</strong> for your company.<br /><br />Click <a href=\"{subscription_link}\">here</a> to review the subscription and subscribe.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(63,'subscriptions','subscription-payment-failed','english','Subscription Payment Failed','Your most recent invoice payment failed','Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}<br /><br br=\"\" />Unfortunately, your most recent invoice payment for&nbsp;<strong>{subscription_name}</strong> was declined.<br /><br />This could be due to a change in your card number, your card expiring,<br />cancellation of your credit card, or the card issuer not recognizing the<br />payment and therefore taking action to prevent it.<br /><br />Please update your payment information as soon as possible by logging in here:<br /><a href=\"{crm_url}/login\">{crm_url}/login</a><br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(64,'subscriptions','subscription-canceled','english','Subscription Canceled (Sent to customer primary contact)','Your subscription has been canceled','Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}<br /><br />Your subscription&nbsp;<strong>{subscription_name} </strong>has been canceled, if you have any questions don\'t hesitate to contact us.<br /><br />It was a pleasure doing business with you.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(65,'subscriptions','subscription-payment-succeeded','english','Subscription Payment Succeeded (Sent to customer primary contact)','Subscription  Payment Receipt - {subscription_name}','Hello&nbsp;{contact_firstname}&nbsp;{contact_lastname}<br /><br />This email is to let you know that we received your payment for subscription&nbsp;<strong>{subscription_name}&nbsp;</strong>of&nbsp;<strong><span>{payment_total}<br /><br /></span></strong>The invoice associated with it is now with status&nbsp;<strong>{invoice_status}<br /></strong><br />Thank you for your confidence.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,1,0),(66,'contract','contract-expiration-to-staff','english','Contract Expiration Reminder (Sent to Staff)','Contract Expiration Reminder','Hi {staff_firstname}<br /><br /><span style=\"font-size: 12pt;\">This is a reminder that the following contract will expire soon:</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {contract_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Description:</strong> {contract_description}</span><br /><span style=\"font-size: 12pt;\"><strong>Date Start:</strong> {contract_datestart}</span><br /><span style=\"font-size: 12pt;\"><strong>Date End:</strong> {contract_dateend}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(67,'gdpr','gdpr-removal-request','english','Removal Request From Contact (Sent to administrators)','Data Removal Request Received','Hello&nbsp;{staff_firstname}&nbsp;{staff_lastname}<br /><br />Data removal has been requested by&nbsp;{contact_firstname} {contact_lastname}<br /><br />You can review this request and take proper actions directly from the admin area.','{companyname} | CRM','',0,1,0),(68,'gdpr','gdpr-removal-request-lead','english','Removal Request From Lead (Sent to administrators)','Data Removal Request Received','Hello&nbsp;{staff_firstname}&nbsp;{staff_lastname}<br /><br />Data removal has been requested by {lead_name}<br /><br />You can review this request and take proper actions directly from the admin area.<br /><br />To view the lead inside the admin area click here:&nbsp;<a href=\"{lead_link}\">{lead_link}</a>','{companyname} | CRM','',0,1,0),(69,'client','client-registration-confirmed','english','Customer Registration Confirmed','Your registration is confirmed','<p>Dear {contact_firstname} {contact_lastname}<br /><br />We just wanted to let you know that your registration at&nbsp;{companyname} is successfully confirmed and your account is now active.<br /><br />You can login at&nbsp;<a href=\"{crm_url}\">{crm_url}</a> with the email and password you provided during registration.<br /><br />Please contact us if you need any help.<br /><br />Kind Regards, <br />{email_signature}</p>\r\n<p><br />(This is an automated email, so please don\'t reply to this email address)</p>','{companyname} | CRM','',0,1,0),(70,'contract','contract-signed-to-staff','english','Contract Signed (Sent to Staff)','Customer Signed a Contract','Hi {staff_firstname}<br /><br />A contract with subject&nbsp;<strong>{contract_subject} </strong>has been successfully signed by the customer.<br /><br />You can view the contract at the following link: <a href=\"{contract_link}\">{contract_subject}</a>&nbsp;or from the admin area.<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(71,'subscriptions','customer-subscribed-to-staff','english','Customer Subscribed to a Subscription (Sent to administrators and subscription creator)','Customer Subscribed to a Subscription','The customer <strong>{client_company}</strong> subscribed to a subscription with name&nbsp;<strong>{subscription_name}</strong><br /><br /><strong>ID</strong>:&nbsp;{subscription_id}<br /><strong>Subscription name</strong>:&nbsp;{subscription_name}<br /><strong>Subscription description</strong>:&nbsp;{subscription_description}<br /><br />You can view the subscription by clicking <a href=\"{subscription_link}\">here</a><br />\r\n<div style=\"text-align: center;\"><span style=\"font-size: 10pt;\">&nbsp;</span></div>\r\nBest Regards,<br />{email_signature}<br /><br /><span style=\"font-size: 10pt;\"><span style=\"color: #999999;\">You are receiving this email because you are either administrator or you are creator of the subscription.</span></span>','{companyname} | CRM','',0,1,0),(72,'client','contact-verification-email','english','Email Verification (Sent to Contact After Registration)','Please Verify Your Email Address','<p>Hello&nbsp;{contact_firstname}<br /><br />Please click the button below to verify your email address.<br /><br /><a href=\"{email_verification_url}\">Verify Email Address</a><br /><br />If you did not create an account, no further action is required</p>\r\n<p><br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(73,'client','new-customer-profile-file-uploaded-to-staff','english','New Customer Profile File(s) Uploaded (Sent to Staff)','Customer Uploaded New File(s) in Profile','Hi!<br /><br />New file(s) is uploaded into the customer ({client_company}) profile by&nbsp;{contact_firstname}<br /><br />You can check the uploaded files into the admin area by clicking <a href=\"{customer_profile_files_admin_link}\">here</a> or at the following link:&nbsp;{customer_profile_files_admin_link}<br /><br />{email_signature}','{companyname} | CRM','',0,1,0),(74,'staff','event-notification-to-staff','english','Event Notification (Calendar)','Upcoming Event - {event_title}','Hi {staff_firstname}! <br /><br />This is a reminder for event <a href=\\\"{event_link}\\\">{event_title}</a> scheduled at {event_start_date}. <br /><br />Regards.','','',0,1,0),(75,'subscriptions','subscription-payment-requires-action','english','Credit Card Authorization Required - SCA','Important: Confirm your subscription {subscription_name} payment','<p>Hello {contact_firstname}</p>\r\n<p><strong>Your bank sometimes requires an additional step to make sure an online transaction was authorized.</strong><br /><br />Because of European regulation to protect consumers, many online payments now require two-factor authentication. Your bank ultimately decides when authentication is required to confirm a payment, but you may notice this step when you start paying for a service or when the cost changes.<br /><br />In order to pay the subscription <strong>{subscription_name}</strong>, you will need to&nbsp;confirm your payment by clicking on the follow link: <strong><a href=\"{subscription_authorize_payment_link}\">{subscription_authorize_payment_link}</a></strong><br /><br />To view the subscription, please click at the following link: <a href=\"{subscription_link}\"><span>{subscription_link}</span></a><br />or you can login in our dedicated area here: <a href=\"{crm_url}/login\">{crm_url}/login</a> in case you want to update your credit card or view the subscriptions you are subscribed.<br /><br />Best Regards,<br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(76,'invoice','invoice-due-notice','english','Invoice Due Notice','Your {invoice_number} will be due soon','<span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}<br /><br /></span>You invoice <span style=\"font-size: 12pt;\"><strong># {invoice_number} </strong>will be due on <strong>{invoice_duedate}</strong></span><br /><br /><span style=\"font-size: 12pt;\">You can view the invoice on the following link: <a href=\"{invoice_link}\">{invoice_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>','{companyname} | CRM','',0,1,0),(77,'estimate_request','estimate-request-submitted-to-staff','english','Estimate Request Submitted (Sent to Staff)','New Estimate Request Submitted','<span> Hello,&nbsp;</span><br /><br />{estimate_request_email} submitted an estimate request via the {estimate_request_form_name} form.<br /><br />You can view the request at the following link: <a href=\"{estimate_request_link}\">{estimate_request_link}</a><br /><br />==<br /><br />{estimate_request_submitted_data}<br /><br />Kind Regards,<br /><span>{email_signature}</span>','{companyname} | CRM','',0,1,0),(78,'estimate_request','estimate-request-assigned','english','Estimate Request Assigned (Sent to Staff)','New Estimate Request Assigned','<span> Hello {estimate_request_assigned},&nbsp;</span><br /><br />Estimate request #{estimate_request_id} has been assigned to you.<br /><br />You can view the request at the following link: <a href=\"{estimate_request_link}\">{estimate_request_link}</a><br /><br />Kind Regards,<br /><span>{email_signature}</span>','{companyname} | CRM','',0,1,0),(79,'estimate_request','estimate-request-received-to-user','english','Estimate Request Received (Sent to User)','Estimate Request Received','Hello,<br /><br /><strong>Your request has been received.</strong><br /><br />This email is to let you know that we received your request and we will get back to you as soon as possible with more information.<br /><br />Best Regards,<br />{email_signature}','{companyname} | CRM','',0,0,0),(80,'notifications','non-billed-tasks-reminder','english','Non-billed tasks reminder (sent to selected staff members)','Action required: Completed tasks are not billed','Hello {staff_firstname}<br><br>The following tasks are marked as complete but not yet billed:<br><br>{unbilled_tasks_list}<br><br>Kind Regards,<br><br>{email_signature}','{companyname} | CRM','',0,1,0),(81,'invoice','invoices-batch-payments','english','Invoices Payments Recorded in Batch (Sent to Customer)','We have received your payments','Hello {contact_firstname} {contact_lastname}<br><br>Thank you for the payments. Please find the payments details below:<br><br>{batch_payments_list}<br><br>We are looking forward working with you.<br><br>Kind Regards,<br><br>{email_signature}','{companyname} | CRM','',0,1,0),(82,'contract','contract-sign-reminder','english','Contract Sign Reminder (Sent to Customer)','Contract Sign Reminder','<p>Hello {contact_firstname} {contact_lastname}<br /><br />This is a reminder to review and sign the contract:<a href=\"{contract_link}\">{contract_subject}</a></p><p>You can view and sign by visiting: <a href=\"{contract_link}\">{contract_subject}</a></p><p><br />We are looking forward working with you.<br /><br />Kind Regards,<br /><br />{email_signature}</p>','{companyname} | CRM','',0,1,0),(83,'receipts','receipt-sent-to-customer','english','Receipt Sent to Customer','New Payment Receipt {receipt_number}','Dear {client_name},<br><br>Your payment receipt is attached.<br><br>Receipt Number: {receipt_number}<br>Total Paid: {total_amount}<br>Date: {receipt_date}<br><br>Thank you,<br>{companyname}','{companyname} | CRM',NULL,0,0,0);
/*!40000 ALTER TABLE `tblemailtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblestimate_request_forms`
--

DROP TABLE IF EXISTS `tblestimate_request_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblestimate_request_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_key` varchar(32) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(191) NOT NULL,
  `form_data` longtext DEFAULT NULL,
  `recaptcha` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `submit_btn_name` varchar(100) DEFAULT NULL,
  `submit_btn_bg_color` varchar(10) DEFAULT '#84c529',
  `submit_btn_text_color` varchar(10) DEFAULT '#ffffff',
  `success_submit_msg` mediumtext DEFAULT NULL,
  `submit_action` int(11) DEFAULT 0,
  `submit_redirect_url` longtext DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `dateadded` datetime DEFAULT NULL,
  `notify_type` varchar(100) DEFAULT NULL,
  `notify_ids` longtext DEFAULT NULL,
  `responsible` int(11) DEFAULT NULL,
  `notify_request_submitted` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblestimate_request_forms`
--

LOCK TABLES `tblestimate_request_forms` WRITE;
/*!40000 ALTER TABLE `tblestimate_request_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblestimate_request_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblestimate_request_status`
--

DROP TABLE IF EXISTS `tblestimate_request_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblestimate_request_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `statusorder` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `flag` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblestimate_request_status`
--

LOCK TABLES `tblestimate_request_status` WRITE;
/*!40000 ALTER TABLE `tblestimate_request_status` DISABLE KEYS */;
INSERT INTO `tblestimate_request_status` VALUES (1,'Cancelled',1,'#808080','cancelled'),(2,'Processing',2,'#007bff','processing'),(3,'Completed',3,'#28a745','completed');
/*!40000 ALTER TABLE `tblestimate_request_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblestimate_requests`
--

DROP TABLE IF EXISTS `tblestimate_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblestimate_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `submission` longtext NOT NULL,
  `last_status_change` datetime DEFAULT NULL,
  `date_estimated` datetime DEFAULT NULL,
  `from_form_id` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `default_language` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblestimate_requests`
--

LOCK TABLES `tblestimate_requests` WRITE;
/*!40000 ALTER TABLE `tblestimate_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblestimate_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblestimates`
--

DROP TABLE IF EXISTS `tblestimates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblestimates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `datesend` datetime DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `deleted_customer_name` varchar(100) DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `number` int(11) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `number_format` int(11) NOT NULL DEFAULT 0,
  `formatted_number` varchar(100) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `date` date NOT NULL,
  `expirydate` date DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `adjustment` decimal(15,2) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `clientnote` mediumtext DEFAULT NULL,
  `adminnote` mediumtext DEFAULT NULL,
  `discount_percent` decimal(15,2) DEFAULT 0.00,
  `discount_total` decimal(15,2) DEFAULT 0.00,
  `discount_type` varchar(30) DEFAULT NULL,
  `invoiceid` int(11) DEFAULT NULL,
  `invoiced_date` datetime DEFAULT NULL,
  `terms` mediumtext DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `sale_agent` int(11) NOT NULL DEFAULT 0,
  `billing_street` varchar(200) DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(100) DEFAULT NULL,
  `billing_country` int(11) DEFAULT NULL,
  `shipping_street` varchar(200) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(100) DEFAULT NULL,
  `shipping_country` int(11) DEFAULT NULL,
  `include_shipping` tinyint(1) NOT NULL,
  `show_shipping_on_estimate` tinyint(1) NOT NULL DEFAULT 1,
  `show_quantity_as` int(11) NOT NULL DEFAULT 1,
  `pipeline_order` int(11) DEFAULT 1,
  `is_expiry_notified` int(11) NOT NULL DEFAULT 0,
  `acceptance_firstname` varchar(50) DEFAULT NULL,
  `acceptance_lastname` varchar(50) DEFAULT NULL,
  `acceptance_email` varchar(100) DEFAULT NULL,
  `acceptance_date` datetime DEFAULT NULL,
  `acceptance_ip` varchar(40) DEFAULT NULL,
  `signature` varchar(40) DEFAULT NULL,
  `short_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `currency` (`currency`),
  KEY `project_id` (`project_id`),
  KEY `sale_agent` (`sale_agent`),
  KEY `status` (`status`),
  KEY `formatted_number` (`formatted_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblestimates`
--

LOCK TABLES `tblestimates` WRITE;
/*!40000 ALTER TABLE `tblestimates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblestimates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblevents`
--

DROP TABLE IF EXISTS `tblevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblevents` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `title` longtext NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `public` int(11) NOT NULL DEFAULT 0,
  `color` varchar(10) DEFAULT NULL,
  `isstartnotified` tinyint(1) NOT NULL DEFAULT 0,
  `reminder_before` int(11) NOT NULL DEFAULT 0,
  `reminder_before_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblevents`
--

LOCK TABLES `tblevents` WRITE;
/*!40000 ALTER TABLE `tblevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblexpenses`
--

DROP TABLE IF EXISTS `tblexpenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblexpenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tax` int(11) DEFAULT NULL,
  `tax2` int(11) NOT NULL DEFAULT 0,
  `reference_no` varchar(100) DEFAULT NULL,
  `note` mediumtext DEFAULT NULL,
  `expense_name` varchar(191) DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `billable` int(11) DEFAULT 0,
  `invoiceid` int(11) DEFAULT NULL,
  `paymentmode` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `recurring_type` varchar(10) DEFAULT NULL,
  `repeat_every` int(11) DEFAULT NULL,
  `recurring` int(11) NOT NULL DEFAULT 0,
  `cycles` int(11) NOT NULL DEFAULT 0,
  `total_cycles` int(11) NOT NULL DEFAULT 0,
  `custom_recurring` int(11) NOT NULL DEFAULT 0,
  `last_recurring_date` date DEFAULT NULL,
  `create_invoice_billable` tinyint(1) DEFAULT NULL,
  `send_invoice_to_customer` tinyint(1) NOT NULL,
  `recurring_from` int(11) DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  `addedfrom` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `project_id` (`project_id`),
  KEY `category` (`category`),
  KEY `currency` (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblexpenses`
--

LOCK TABLES `tblexpenses` WRITE;
/*!40000 ALTER TABLE `tblexpenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblexpenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblexpenses_categories`
--

DROP TABLE IF EXISTS `tblexpenses_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblexpenses_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblexpenses_categories`
--

LOCK TABLES `tblexpenses_categories` WRITE;
/*!40000 ALTER TABLE `tblexpenses_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblexpenses_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfiles`
--

DROP TABLE IF EXISTS `tblfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `filetype` varchar(40) DEFAULT NULL,
  `visible_to_customer` int(11) NOT NULL DEFAULT 0,
  `attachment_key` varchar(32) DEFAULT NULL,
  `external` varchar(40) DEFAULT NULL,
  `external_link` mediumtext DEFAULT NULL,
  `thumbnail_link` mediumtext DEFAULT NULL COMMENT 'For external usage',
  `staffid` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT 0,
  `task_comment_id` int(11) NOT NULL DEFAULT 0,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfiles`
--

LOCK TABLES `tblfiles` WRITE;
/*!40000 ALTER TABLE `tblfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfilter_defaults`
--

DROP TABLE IF EXISTS `tblfilter_defaults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblfilter_defaults` (
  `filter_id` int(10) unsigned NOT NULL,
  `staff_id` int(11) NOT NULL,
  `identifier` varchar(191) NOT NULL,
  `view` varchar(191) NOT NULL,
  KEY `filter_id` (`filter_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `tblfilter_defaults_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `tblfilters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblfilter_defaults_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `tblstaff` (`staffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfilter_defaults`
--

LOCK TABLES `tblfilter_defaults` WRITE;
/*!40000 ALTER TABLE `tblfilter_defaults` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblfilter_defaults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfilters`
--

DROP TABLE IF EXISTS `tblfilters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblfilters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `builder` mediumtext NOT NULL,
  `staff_id` int(10) unsigned NOT NULL,
  `identifier` varchar(191) NOT NULL,
  `is_shared` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfilters`
--

LOCK TABLES `tblfilters` WRITE;
/*!40000 ALTER TABLE `tblfilters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblfilters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblform_question_box`
--

DROP TABLE IF EXISTS `tblform_question_box`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblform_question_box` (
  `boxid` int(11) NOT NULL AUTO_INCREMENT,
  `boxtype` varchar(10) NOT NULL,
  `questionid` int(11) NOT NULL,
  PRIMARY KEY (`boxid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblform_question_box`
--

LOCK TABLES `tblform_question_box` WRITE;
/*!40000 ALTER TABLE `tblform_question_box` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblform_question_box` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblform_question_box_description`
--

DROP TABLE IF EXISTS `tblform_question_box_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblform_question_box_description` (
  `questionboxdescriptionid` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `boxid` longtext NOT NULL,
  `questionid` int(11) NOT NULL,
  PRIMARY KEY (`questionboxdescriptionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblform_question_box_description`
--

LOCK TABLES `tblform_question_box_description` WRITE;
/*!40000 ALTER TABLE `tblform_question_box_description` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblform_question_box_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblform_questions`
--

DROP TABLE IF EXISTS `tblform_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblform_questions` (
  `questionid` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) DEFAULT NULL,
  `question` longtext NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `question_order` int(11) NOT NULL,
  PRIMARY KEY (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblform_questions`
--

LOCK TABLES `tblform_questions` WRITE;
/*!40000 ALTER TABLE `tblform_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblform_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblform_results`
--

DROP TABLE IF EXISTS `tblform_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblform_results` (
  `resultid` int(11) NOT NULL AUTO_INCREMENT,
  `boxid` int(11) NOT NULL,
  `boxdescriptionid` int(11) DEFAULT NULL,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) DEFAULT NULL,
  `questionid` int(11) NOT NULL,
  `answer` mediumtext DEFAULT NULL,
  `resultsetid` int(11) NOT NULL,
  PRIMARY KEY (`resultid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblform_results`
--

LOCK TABLES `tblform_results` WRITE;
/*!40000 ALTER TABLE `tblform_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblform_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgdpr_requests`
--

DROP TABLE IF EXISTS `tblgdpr_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgdpr_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL DEFAULT 0,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `lead_id` int(11) NOT NULL DEFAULT 0,
  `request_type` varchar(191) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `request_date` datetime NOT NULL,
  `request_from` varchar(150) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgdpr_requests`
--

LOCK TABLES `tblgdpr_requests` WRITE;
/*!40000 ALTER TABLE `tblgdpr_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgdpr_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgoals`
--

DROP TABLE IF EXISTS `tblgoals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgoals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `goal_type` int(11) NOT NULL,
  `contract_type` int(11) NOT NULL DEFAULT 0,
  `achievement` int(11) NOT NULL,
  `notify_when_fail` tinyint(1) NOT NULL DEFAULT 1,
  `notify_when_achieve` tinyint(1) NOT NULL DEFAULT 1,
  `notified` int(11) NOT NULL DEFAULT 0,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgoals`
--

LOCK TABLES `tblgoals` WRITE;
/*!40000 ALTER TABLE `tblgoals` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgoals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblinvoicepaymentrecords`
--

DROP TABLE IF EXISTS `tblinvoicepaymentrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblinvoicepaymentrecords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paymentmode` varchar(40) DEFAULT NULL,
  `paymentmethod` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `daterecorded` datetime NOT NULL,
  `note` mediumtext DEFAULT NULL,
  `transactionid` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoiceid` (`invoiceid`),
  KEY `paymentmethod` (`paymentmethod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblinvoicepaymentrecords`
--

LOCK TABLES `tblinvoicepaymentrecords` WRITE;
/*!40000 ALTER TABLE `tblinvoicepaymentrecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblinvoicepaymentrecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblinvoices`
--

DROP TABLE IF EXISTS `tblinvoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblinvoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `datesend` datetime DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `deleted_customer_name` varchar(100) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `number_format` int(11) NOT NULL DEFAULT 0,
  `formatted_number` varchar(100) DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `date` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `adjustment` decimal(15,2) DEFAULT NULL,
  `addedfrom` int(11) DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `status` int(11) DEFAULT 1,
  `clientnote` mediumtext DEFAULT NULL,
  `adminnote` mediumtext DEFAULT NULL,
  `last_overdue_reminder` date DEFAULT NULL,
  `last_due_reminder` date DEFAULT NULL,
  `cancel_overdue_reminders` int(11) NOT NULL DEFAULT 0,
  `allowed_payment_modes` longtext DEFAULT NULL,
  `token` longtext DEFAULT NULL,
  `discount_percent` decimal(15,2) DEFAULT 0.00,
  `discount_total` decimal(15,2) DEFAULT 0.00,
  `discount_type` varchar(30) NOT NULL,
  `recurring` int(11) NOT NULL DEFAULT 0,
  `recurring_type` varchar(10) DEFAULT NULL,
  `custom_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `cycles` int(11) NOT NULL DEFAULT 0,
  `total_cycles` int(11) NOT NULL DEFAULT 0,
  `is_recurring_from` int(11) DEFAULT NULL,
  `last_recurring_date` date DEFAULT NULL,
  `terms` mediumtext DEFAULT NULL,
  `sale_agent` int(11) NOT NULL DEFAULT 0,
  `billing_street` varchar(200) DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(100) DEFAULT NULL,
  `billing_country` int(11) DEFAULT NULL,
  `shipping_street` varchar(200) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(100) DEFAULT NULL,
  `shipping_country` int(11) DEFAULT NULL,
  `include_shipping` tinyint(1) NOT NULL,
  `show_shipping_on_invoice` tinyint(1) NOT NULL DEFAULT 1,
  `show_quantity_as` int(11) NOT NULL DEFAULT 1,
  `project_id` int(11) DEFAULT 0,
  `subscription_id` int(11) NOT NULL DEFAULT 0,
  `short_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currency` (`currency`),
  KEY `clientid` (`clientid`),
  KEY `project_id` (`project_id`),
  KEY `sale_agent` (`sale_agent`),
  KEY `total` (`total`),
  KEY `status` (`status`),
  KEY `formatted_number` (`formatted_number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblinvoices`
--

LOCK TABLES `tblinvoices` WRITE;
/*!40000 ALTER TABLE `tblinvoices` DISABLE KEYS */;
INSERT INTO `tblinvoices` VALUES (3,1,'2026-03-26 17:41:56',1,NULL,1001,'INV-',2,'INV-2026/001001','2026-03-26 17:38:27','2026-03-26','2026-03-26',2,30.00,5.70,35.70,0.00,2,'2dbc010806e417c69e572da42900c93d',1,'','',NULL,NULL,0,'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}',NULL,0.00,0.00,'',0,NULL,0,0,0,NULL,NULL,'',0,'','','','',0,NULL,NULL,NULL,NULL,NULL,0,1,1,0,0,NULL);
/*!40000 ALTER TABLE `tblinvoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblitem_tax`
--

DROP TABLE IF EXISTS `tblitem_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblitem_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) NOT NULL,
  `taxrate` decimal(15,2) NOT NULL,
  `taxname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itemid` (`itemid`),
  KEY `rel_id` (`rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblitem_tax`
--

LOCK TABLES `tblitem_tax` WRITE;
/*!40000 ALTER TABLE `tblitem_tax` DISABLE KEYS */;
INSERT INTO `tblitem_tax` VALUES (3,3,3,'invoice',19.00,'V.A.T. 19%');
/*!40000 ALTER TABLE `tblitem_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblitemable`
--

DROP TABLE IF EXISTS `tblitemable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblitemable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(15) NOT NULL,
  `description` longtext NOT NULL,
  `long_description` longtext DEFAULT NULL,
  `qty` decimal(15,2) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `unit` varchar(40) DEFAULT NULL,
  `is_optional` tinyint(4) NOT NULL DEFAULT 0,
  `is_selected` tinyint(4) NOT NULL DEFAULT 1,
  `item_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`),
  KEY `qty` (`qty`),
  KEY `rate` (`rate`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblitemable`
--

LOCK TABLES `tblitemable` WRITE;
/*!40000 ALTER TABLE `tblitemable` DISABLE KEYS */;
INSERT INTO `tblitemable` VALUES (3,3,'invoice','Πόσιμο νερό','',1.00,30.00,'',0,1,1);
/*!40000 ALTER TABLE `tblitemable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblitems`
--

DROP TABLE IF EXISTS `tblitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `long_description` mediumtext DEFAULT NULL,
  `rate` decimal(15,2) NOT NULL,
  `tax` int(11) DEFAULT NULL,
  `tax2` int(11) DEFAULT NULL,
  `unit` varchar(40) DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `tax` (`tax`),
  KEY `tax2` (`tax2`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblitems`
--

LOCK TABLES `tblitems` WRITE;
/*!40000 ALTER TABLE `tblitems` DISABLE KEYS */;
INSERT INTO `tblitems` VALUES (1,'TVC 37°C','LIMS Analysis: TVC 37°C',7.00,1,NULL,NULL,1),(2,'TVC 22°C','LIMS Analysis: TVC 22°C',7.00,1,NULL,NULL,1),(3,'Ε. coli','LIMS Analysis: Ε. coli',7.00,1,NULL,NULL,1),(4,'Coliforms','LIMS Analysis: Coliforms',7.00,1,NULL,NULL,1),(5,'Εντερόκοκκοι','LIMS Analysis: Εντερόκοκκοι',7.00,1,NULL,NULL,1),(6,'Pseudomonas aeruginosa','LIMS Analysis: Pseudomonas aeruginosa',7.00,1,NULL,NULL,1),(7,'Εμφιαλωμένο Νερό','Analyses: \r\n -Coliforms \r\n -Pseudomonas aeruginosa \r\n -TVC 22°C \r\n -TVC 37°C \r\n -Ε. coli \r\n -Εντερόκοκκοι \r\n',34.45,1,NULL,'',38),(8,'Πόσιμο νερό','Analyses:\n -Coliforms\n -Pseudomonas aeruginosa\n -TVC 37°C\n -Ε. coli\n -Εντερόκοκκοι\n',34.45,1,NULL,NULL,2),(9,'Κολυμβητικές Δεξαμενές','Analyses:\n -Coliforms\n -Pseudomonas aeruginosa\n -TVC 37°C\n -Ε. coli\n',34.45,1,NULL,NULL,2),(10,'Coagulase positive staphylococci','LIMS Analysis: Coagulase positive staphylococci',7.00,1,NULL,NULL,1);
/*!40000 ALTER TABLE `tblitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblitems_groups`
--

DROP TABLE IF EXISTS `tblitems_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblitems_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblitems_groups`
--

LOCK TABLES `tblitems_groups` WRITE;
/*!40000 ALTER TABLE `tblitems_groups` DISABLE KEYS */;
INSERT INTO `tblitems_groups` VALUES (1,'Analysies'),(2,'Panels');
/*!40000 ALTER TABLE `tblitems_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowedge_base_article_feedback`
--

DROP TABLE IF EXISTS `tblknowedge_base_article_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowedge_base_article_feedback` (
  `articleanswerid` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`articleanswerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowedge_base_article_feedback`
--

LOCK TABLES `tblknowedge_base_article_feedback` WRITE;
/*!40000 ALTER TABLE `tblknowedge_base_article_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowedge_base_article_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowledge_base`
--

DROP TABLE IF EXISTS `tblknowledge_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowledge_base` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT,
  `articlegroup` int(11) NOT NULL,
  `subject` longtext NOT NULL,
  `description` mediumtext NOT NULL,
  `slug` longtext NOT NULL,
  `active` tinyint(4) NOT NULL,
  `datecreated` datetime NOT NULL,
  `article_order` int(11) NOT NULL DEFAULT 0,
  `staff_article` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`articleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowledge_base`
--

LOCK TABLES `tblknowledge_base` WRITE;
/*!40000 ALTER TABLE `tblknowledge_base` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledge_base` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowledge_base_groups`
--

DROP TABLE IF EXISTS `tblknowledge_base_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowledge_base_groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `group_slug` mediumtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `color` varchar(10) DEFAULT '#28B8DA',
  `group_order` int(11) DEFAULT 0,
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowledge_base_groups`
--

LOCK TABLES `tblknowledge_base_groups` WRITE;
/*!40000 ALTER TABLE `tblknowledge_base_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledge_base_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllead_activity_log`
--

DROP TABLE IF EXISTS `tbllead_activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllead_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leadid` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `additional_data` mediumtext DEFAULT NULL,
  `date` datetime NOT NULL,
  `staffid` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `custom_activity` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllead_activity_log`
--

LOCK TABLES `tbllead_activity_log` WRITE;
/*!40000 ALTER TABLE `tbllead_activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllead_activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllead_integration_emails`
--

DROP TABLE IF EXISTS `tbllead_integration_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllead_integration_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` longtext DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  `leadid` int(11) NOT NULL,
  `emailid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllead_integration_emails`
--

LOCK TABLES `tbllead_integration_emails` WRITE;
/*!40000 ALTER TABLE `tbllead_integration_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllead_integration_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblleads`
--

DROP TABLE IF EXISTS `tblleads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblleads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(65) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company` varchar(191) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `country` int(11) NOT NULL DEFAULT 0,
  `zip` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `assigned` int(11) NOT NULL DEFAULT 0,
  `dateadded` datetime NOT NULL,
  `from_form_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `source` int(11) NOT NULL,
  `lastcontact` datetime DEFAULT NULL,
  `dateassigned` date DEFAULT NULL,
  `last_status_change` datetime DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `leadorder` int(11) DEFAULT 1,
  `phonenumber` varchar(50) DEFAULT NULL,
  `date_converted` datetime DEFAULT NULL,
  `lost` tinyint(1) NOT NULL DEFAULT 0,
  `junk` int(11) NOT NULL DEFAULT 0,
  `last_lead_status` int(11) NOT NULL DEFAULT 0,
  `is_imported_from_email_integration` tinyint(1) NOT NULL DEFAULT 0,
  `email_integration_uid` varchar(30) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `default_language` varchar(40) DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `lead_value` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `company` (`company`),
  KEY `email` (`email`),
  KEY `assigned` (`assigned`),
  KEY `status` (`status`),
  KEY `source` (`source`),
  KEY `lastcontact` (`lastcontact`),
  KEY `dateadded` (`dateadded`),
  KEY `leadorder` (`leadorder`),
  KEY `from_form_id` (`from_form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblleads`
--

LOCK TABLES `tblleads` WRITE;
/*!40000 ALTER TABLE `tblleads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblleads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblleads_email_integration`
--

DROP TABLE IF EXISTS `tblleads_email_integration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblleads_email_integration` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'the ID always must be 1',
  `active` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `imap_server` varchar(100) NOT NULL,
  `password` longtext NOT NULL,
  `check_every` int(11) NOT NULL DEFAULT 5,
  `responsible` int(11) NOT NULL,
  `lead_source` int(11) NOT NULL,
  `lead_status` int(11) NOT NULL,
  `encryption` varchar(3) DEFAULT NULL,
  `folder` varchar(100) NOT NULL,
  `last_run` varchar(50) DEFAULT NULL,
  `notify_lead_imported` tinyint(1) NOT NULL DEFAULT 1,
  `notify_lead_contact_more_times` tinyint(1) NOT NULL DEFAULT 1,
  `notify_type` varchar(20) DEFAULT NULL,
  `notify_ids` longtext DEFAULT NULL,
  `mark_public` int(11) NOT NULL DEFAULT 0,
  `only_loop_on_unseen_emails` tinyint(1) NOT NULL DEFAULT 1,
  `delete_after_import` int(11) NOT NULL DEFAULT 0,
  `create_task_if_customer` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblleads_email_integration`
--

LOCK TABLES `tblleads_email_integration` WRITE;
/*!40000 ALTER TABLE `tblleads_email_integration` DISABLE KEYS */;
INSERT INTO `tblleads_email_integration` VALUES (1,0,'','','',10,0,0,0,'tls','INBOX','',1,1,'assigned','',0,1,0,1);
/*!40000 ALTER TABLE `tblleads_email_integration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblleads_sources`
--

DROP TABLE IF EXISTS `tblleads_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblleads_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblleads_sources`
--

LOCK TABLES `tblleads_sources` WRITE;
/*!40000 ALTER TABLE `tblleads_sources` DISABLE KEYS */;
INSERT INTO `tblleads_sources` VALUES (3,'Contacts'),(2,'Facebook'),(1,'Google');
/*!40000 ALTER TABLE `tblleads_sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblleads_status`
--

DROP TABLE IF EXISTS `tblleads_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblleads_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `statusorder` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT '#28B8DA',
  `isdefault` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblleads_status`
--

LOCK TABLES `tblleads_status` WRITE;
/*!40000 ALTER TABLE `tblleads_status` DISABLE KEYS */;
INSERT INTO `tblleads_status` VALUES (1,'Customer',1000,'#7cb342',1);
/*!40000 ALTER TABLE `tblleads_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_analyses`
--

DROP TABLE IF EXISTS `tbllims_analyses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_analyses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `department` varchar(128) DEFAULT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  `sample_type_id` int(10) unsigned DEFAULT NULL,
  `method` varchar(191) DEFAULT NULL,
  `tat_hours` int(10) unsigned DEFAULT NULL,
  `result_type` enum('numeric','text','select') NOT NULL DEFAULT 'numeric',
  `select_options` text DEFAULT NULL,
  `decimal_places` tinyint(3) unsigned DEFAULT NULL,
  `units_ucum` varchar(64) DEFAULT NULL,
  `loinc_code` varchar(64) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_analysis_dept` (`department_id`),
  KEY `idx_analysis_sampletype` (`sample_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_analyses`
--

LOCK TABLES `tbllims_analyses` WRITE;
/*!40000 ALTER TABLE `tbllims_analyses` DISABLE KEYS */;
INSERT INTO `tbllims_analyses` VALUES (1,'TVC 37°C','WA001',NULL,1,3,'ISO 6222:1999',72,'numeric',NULL,NULL,'cfu/ml','WA001',1,1,'2026-03-26 14:35:05'),(2,'TVC 22°C','WA002',NULL,1,3,'ISO 6222:1999',72,'numeric',NULL,NULL,'cfu/ml','WA002',2,1,'2026-03-26 14:37:26'),(3,'Ε. coli','WA003',NULL,1,3,'ISO 9308-1:2014',72,'numeric',NULL,NULL,'cfu/100ml','WA003',3,1,'2026-03-26 14:40:07'),(4,'Coliforms','WA004',NULL,1,3,'ISO 9308-1:2014',72,'numeric',NULL,NULL,'cfu/100ml','WA004',4,1,'2026-03-26 14:41:30'),(5,'Εντερόκοκκοι','WA005',NULL,1,3,'ISO 7899-2:2000',72,'numeric',NULL,NULL,'cfu/100ml','WA005',5,1,'2026-03-26 14:42:28'),(6,'Pseudomonas aeruginosa','WA006',NULL,1,3,'ISO 16266: 2008',72,'numeric',NULL,NULL,'cfu/100ml','WA006',6,1,'2026-03-26 14:43:25'),(7,'Coagulase positive staphylococci','WA007',NULL,1,4,'APHA 9213B:2005',48,'numeric',NULL,NULL,'cfu/100ml','WA007',10,1,'2026-03-26 14:59:35');
/*!40000 ALTER TABLE `tbllims_analyses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_analysis_specs`
--

DROP TABLE IF EXISTS `tbllims_analysis_specs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_analysis_specs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `analysis_id` int(10) unsigned NOT NULL,
  `sample_type_id` int(10) unsigned NOT NULL,
  `sex` enum('U','M','F') NOT NULL DEFAULT 'U',
  `age_min` decimal(8,2) DEFAULT NULL,
  `age_max` decimal(8,2) DEFAULT NULL,
  `ref_low` decimal(18,6) DEFAULT NULL,
  `ref_high` decimal(18,6) DEFAULT NULL,
  `critical_low` decimal(18,6) DEFAULT NULL,
  `critical_high` decimal(18,6) DEFAULT NULL,
  `unit_ucum` varchar(64) DEFAULT NULL,
  `version` int(10) unsigned DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_analysis_sampletype` (`analysis_id`,`sample_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_analysis_specs`
--

LOCK TABLES `tbllims_analysis_specs` WRITE;
/*!40000 ALTER TABLE `tbllims_analysis_specs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_analysis_specs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_appointments`
--

DROP TABLE IF EXISTS `tbllims_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_appointments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `appointment_at` datetime NOT NULL,
  `visit_type` enum('lab','home') DEFAULT 'lab',
  `location_text` varchar(255) DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','canceled','no_show') DEFAULT 'pending',
  `assigned_staff` int(10) unsigned DEFAULT NULL,
  `task_id` int(10) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `appointment_at` (`appointment_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_appointments`
--

LOCK TABLES `tbllims_appointments` WRITE;
/*!40000 ALTER TABLE `tbllims_appointments` DISABLE KEYS */;
INSERT INTO `tbllims_appointments` VALUES (1,1,NULL,1,'2026-03-26 17:31:31','home','Αρσινόης, Agios Dimitrios, Acropolis, Strovolos, Strovolos Municipality, Nicosia District, Cyprus, 2001, Cyprus',35.1564070,33.3665080,'confirmed',4,1,'δfdf','2026-03-26 16:31:31'),(2,1,NULL,1,'2026-03-26 17:31:32','home','Αρσινόης, Agios Dimitrios, Acropolis, Strovolos, Strovolos Municipality, Nicosia District, Cyprus, 2001, Cyprus',35.1564070,33.3665080,'confirmed',4,2,'δfdf','2026-03-26 16:31:32');
/*!40000 ALTER TABLE `tbllims_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_barcode_sequences`
--

DROP TABLE IF EXISTS `tbllims_barcode_sequences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_barcode_sequences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(10) NOT NULL,
  `next_number` int(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`prefix`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_barcode_sequences`
--

LOCK TABLES `tbllims_barcode_sequences` WRITE;
/*!40000 ALTER TABLE `tbllims_barcode_sequences` DISABLE KEYS */;
INSERT INTO `tbllims_barcode_sequences` VALUES (1,'ORD',4);
/*!40000 ALTER TABLE `tbllims_barcode_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_billing_links`
--

DROP TABLE IF EXISTS `tbllims_billing_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_billing_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `invoice_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_billing_links`
--

LOCK TABLES `tbllims_billing_links` WRITE;
/*!40000 ALTER TABLE `tbllims_billing_links` DISABLE KEYS */;
INSERT INTO `tbllims_billing_links` VALUES (3,1,3,'2026-03-26 16:38:27');
/*!40000 ALTER TABLE `tbllims_billing_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_contract_prices`
--

DROP TABLE IF EXISTS `tbllims_contract_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_contract_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `fixed_price` decimal(15,4) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`),
  KEY `item_id` (`item_id`),
  KEY `currency` (`currency`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_contract_prices`
--

LOCK TABLES `tbllims_contract_prices` WRITE;
/*!40000 ALTER TABLE `tbllims_contract_prices` DISABLE KEYS */;
INSERT INTO `tbllims_contract_prices` VALUES (1,1,7,30.0000,'EUR','2026-03-26 16:12:17'),(2,1,9,31.0000,'EUR','2026-03-26 16:12:17'),(3,1,8,30.0000,'EUR','2026-03-26 16:12:17'),(4,1,4,5.0000,'EUR','2026-03-26 16:12:17');
/*!40000 ALTER TABLE `tbllims_contract_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_contracts`
--

DROP TABLE IF EXISTS `tbllims_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_contracts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `discount_percent` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `priority` int(11) DEFAULT 0,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `active` (`active`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_contracts`
--

LOCK TABLES `tbllims_contracts` WRITE;
/*!40000 ALTER TABLE `tbllims_contracts` DISABLE KEYS */;
INSERT INTO `tbllims_contracts` VALUES (1,1,'ΙΟΝΑΣ','10001',NULL,1,0,NULL,NULL,NULL,'2026-03-26 15:09:34');
/*!40000 ALTER TABLE `tbllims_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_culture_option_links`
--

DROP TABLE IF EXISTS `tbllims_culture_option_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_culture_option_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `culture_id` int(10) unsigned NOT NULL,
  `set_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_culture` (`culture_id`),
  KEY `idx_set` (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_culture_option_links`
--

LOCK TABLES `tbllims_culture_option_links` WRITE;
/*!40000 ALTER TABLE `tbllims_culture_option_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_culture_option_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_culture_option_sets`
--

DROP TABLE IF EXISTS `tbllims_culture_option_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_culture_option_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_code` (`code`),
  KEY `idx_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_culture_option_sets`
--

LOCK TABLES `tbllims_culture_option_sets` WRITE;
/*!40000 ALTER TABLE `tbllims_culture_option_sets` DISABLE KEYS */;
INSERT INTO `tbllims_culture_option_sets` VALUES (1,'Culture Result','CULTURE_RESULT','High-level culture result such as No growth, Normal flora, Pathogen isolated.',1,'2026-01-28 08:28:54'),(2,'Semi-quantitative growth','SEMI_QUANT','Semi-quantitative growth scale (e.g. 1+, 2+, 3+, 4+).',1,'2026-01-28 08:28:54'),(3,'Organism significance','ORG_SIGNIF','Significance of isolated organism (pathogen, coloniser, etc.).',1,'2026-01-28 08:28:54');
/*!40000 ALTER TABLE `tbllims_culture_option_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_culture_option_values`
--

DROP TABLE IF EXISTS `tbllims_culture_option_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_culture_option_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `value` varchar(64) NOT NULL,
  `label` varchar(191) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_set` (`set_id`),
  CONSTRAINT `fk_lims_copt_vals_set` FOREIGN KEY (`set_id`) REFERENCES `tbllims_culture_option_sets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_culture_option_values`
--

LOCK TABLES `tbllims_culture_option_values` WRITE;
/*!40000 ALTER TABLE `tbllims_culture_option_values` DISABLE KEYS */;
INSERT INTO `tbllims_culture_option_values` VALUES (1,1,'NO_GROWTH','No growth',10,1,'2026-01-28 08:28:54'),(2,1,'NORMAL_FLORA','Normal flora',20,1,'2026-01-28 08:28:54'),(3,1,'PATHOGEN_ISOLATED','Pathogen isolated',30,1,'2026-01-28 08:28:54'),(4,1,'POSSIBLE_CONTAM','Probable contamination',40,1,'2026-01-28 08:28:54'),(5,1,'RESULT_PENDING','Result pending',50,1,'2026-01-28 08:28:54'),(6,2,'SCANT','Scant growth',10,1,'2026-01-28 08:28:54'),(7,2,'1_PLUS','1+',20,1,'2026-01-28 08:28:54'),(8,2,'2_PLUS','2+',30,1,'2026-01-28 08:28:54'),(9,2,'3_PLUS','3+',40,1,'2026-01-28 08:28:54'),(10,2,'4_PLUS','4+',50,1,'2026-01-28 08:28:54'),(11,2,'HEAVY','Heavy growth',60,1,'2026-01-28 08:28:54'),(12,3,'PATHOGEN','Pathogen',10,1,'2026-01-28 08:28:54'),(13,3,'POSSIBLE_PATHOGEN','Possible pathogen',20,1,'2026-01-28 08:28:54'),(14,3,'COLONISER','Coloniser/normal flora',30,1,'2026-01-28 08:28:54');
/*!40000 ALTER TABLE `tbllims_culture_option_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_culture_results`
--

DROP TABLE IF EXISTS `tbllims_culture_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_culture_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `sample_id` int(10) unsigned NOT NULL,
  `culture_id` int(10) unsigned NOT NULL,
  `result_text` text DEFAULT NULL,
  `options_json` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sample_culture` (`sample_id`,`culture_id`),
  KEY `idx_order_sample_culture` (`order_id`,`sample_id`,`culture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_culture_results`
--

LOCK TABLES `tbllims_culture_results` WRITE;
/*!40000 ALTER TABLE `tbllims_culture_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_culture_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_culture_types`
--

DROP TABLE IF EXISTS `tbllims_culture_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_culture_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_culturetype_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_culture_types`
--

LOCK TABLES `tbllims_culture_types` WRITE;
/*!40000 ALTER TABLE `tbllims_culture_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_culture_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_cultures`
--

DROP TABLE IF EXISTS `tbllims_cultures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_cultures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `culture_type_id` int(10) unsigned DEFAULT NULL,
  `sample_type_id` int(10) unsigned DEFAULT NULL,
  `method` varchar(191) DEFAULT NULL,
  `tat_hours` int(11) DEFAULT NULL,
  `incubation_temp` varchar(64) DEFAULT NULL,
  `incubation_time` varchar(64) DEFAULT NULL,
  `loinc_code` varchar(64) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_culture_type` (`culture_type_id`),
  KEY `idx_sample_type` (`sample_type_id`),
  KEY `idx_item` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_cultures`
--

LOCK TABLES `tbllims_cultures` WRITE;
/*!40000 ALTER TABLE `tbllims_cultures` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_cultures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_departments`
--

DROP TABLE IF EXISTS `tbllims_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lims_dept_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_departments`
--

LOCK TABLES `tbllims_departments` WRITE;
/*!40000 ALTER TABLE `tbllims_departments` DISABLE KEYS */;
INSERT INTO `tbllims_departments` VALUES (1,'Μικροβιολογικό','D001',1,'2026-03-26 14:28:38'),(2,'Χημικό','D002',1,'2026-03-26 14:48:47');
/*!40000 ALTER TABLE `tbllims_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_item_rates`
--

DROP TABLE IF EXISTS `tbllims_item_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_item_rates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `currency` varchar(32) NOT NULL,
  `rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_item_currency` (`item_id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_item_rates`
--

LOCK TABLES `tbllims_item_rates` WRITE;
/*!40000 ALTER TABLE `tbllims_item_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_item_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_order_activity`
--

DROP TABLE IF EXISTS `tbllims_order_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_order_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `action` varchar(64) NOT NULL,
  `message` text DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_action` (`action`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_order_activity`
--

LOCK TABLES `tbllims_order_activity` WRITE;
/*!40000 ALTER TABLE `tbllims_order_activity` DISABLE KEYS */;
INSERT INTO `tbllims_order_activity` VALUES (1,1,'sample_collected','Sample marked as collected','{\"sample_id\":1}',2,'2026-03-26 17:17:11'),(2,1,'appointment_created','Appointment created','{\"appointment_id\":1}',2,'2026-03-26 17:31:31'),(3,1,'status_changed','Status changed to Appointment','{\"new_status\":\"appointment\"}',2,'2026-03-26 17:31:32'),(4,1,'appointment_created','Appointment created','{\"appointment_id\":2}',2,'2026-03-26 17:31:32'),(5,1,'status_changed','Status changed to Appointment','{\"new_status\":\"appointment\"}',2,'2026-03-26 17:31:33'),(6,1,'invoice_created','Δημιουργία τιμολογίου','{\"invoice_id\":1,\"mode\":\"draft\"}',2,'2026-03-26 17:33:30'),(7,1,'contract_updated','Contract set to 1','{\"contract_id\":1}',2,'2026-03-26 17:35:28'),(8,1,'invoice_unlinked','Αφαιρέθηκε σύνδεση τιμολογίου (ID: 1)','{\"invoice_id\":1}',2,'2026-03-26 17:35:40'),(9,1,'invoice_created','Δημιουργία τιμολογίου','{\"invoice_id\":2,\"mode\":\"normal\"}',2,'2026-03-26 17:35:50'),(10,1,'invoice_unlinked','Αφαιρέθηκε σύνδεση τιμολογίου (ID: 2)','{\"invoice_id\":2}',2,'2026-03-26 17:38:02'),(11,1,'invoice_created','Δημιουργία τιμολογίου','{\"invoice_id\":3,\"mode\":\"draft\"}',2,'2026-03-26 17:38:27'),(12,3,'items_updated','Οι αλλαγές αποθηκεύτηκαν.',NULL,2,'2026-03-27 12:05:55'),(13,3,'items_updated','Οι αλλαγές αποθηκεύτηκαν.',NULL,2,'2026-03-27 12:08:12'),(14,3,'items_updated','Οι αλλαγές αποθηκεύτηκαν.',NULL,2,'2026-03-27 12:09:28'),(15,3,'items_updated','Οι αλλαγές αποθηκεύτηκαν.',NULL,2,'2026-03-27 12:11:52'),(16,3,'sample_uncollected','Sample marked as pending','{\"sample_id\":3}',2,'2026-03-27 12:12:17'),(17,3,'sample_uncollected','Sample marked as pending','{\"sample_id\":3}',2,'2026-03-27 12:16:17');
/*!40000 ALTER TABLE `tbllims_order_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_order_items`
--

DROP TABLE IF EXISTS `tbllims_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `source_type` enum('panel','analysis','culture') NOT NULL,
  `source_id` int(10) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `qty` decimal(15,4) NOT NULL DEFAULT 1.0000,
  `unit` varchar(50) DEFAULT NULL,
  `currency_id` int(10) unsigned NOT NULL,
  `unit_price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `tax1_id` int(10) unsigned DEFAULT NULL,
  `tax2_id` int(10) unsigned DEFAULT NULL,
  `from_contract_id` int(10) unsigned DEFAULT NULL,
  `discount_percent` decimal(10,2) DEFAULT NULL,
  `fixed_price_applied` tinyint(1) NOT NULL DEFAULT 0,
  `referred_partner_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_source` (`source_type`,`source_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_order_items`
--

LOCK TABLES `tbllims_order_items` WRITE;
/*!40000 ALTER TABLE `tbllims_order_items` DISABLE KEYS */;
INSERT INTO `tbllims_order_items` VALUES (1,1,8,'panel',2,'Πόσιμο νερό',1.0000,'',2,30.0000,1,NULL,1,NULL,1,NULL,'2026-03-26 16:15:16'),(9,3,8,'panel',2,'Πόσιμο νερό',1.0000,'',2,34.4500,1,NULL,NULL,NULL,0,NULL,'2026-03-27 11:11:52');
/*!40000 ALTER TABLE `tbllims_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_orders`
--

DROP TABLE IF EXISTS `tbllims_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_uid` char(36) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `contract_id` int(10) unsigned DEFAULT NULL,
  `partner_id` int(10) unsigned DEFAULT NULL,
  `partner_direction` enum('outbound','inbound') DEFAULT NULL,
  `partner_last_sync_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `partner_sync_status` varchar(32) DEFAULT NULL,
  `partner_sync_error` text DEFAULT NULL,
  `external_ref` varchar(100) DEFAULT NULL,
  `status` enum('draft','submitted','accessioned','testing','verified','approved','reported') DEFAULT 'draft',
  `priority` tinyint(4) DEFAULT 0,
  `received_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `due_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_barcode` varchar(32) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_order_uid` (`order_uid`),
  KEY `client_id` (`client_id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_orders`
--

LOCK TABLES `tbllims_orders` WRITE;
/*!40000 ALTER TABLE `tbllims_orders` DISABLE KEYS */;
INSERT INTO `tbllims_orders` VALUES (1,'4a0a00a7-9ccc-4db5-8599-705440474e50',1,1,1,NULL,NULL,'2026-03-26 15:35:28',NULL,NULL,NULL,'',0,'2026-03-26 15:35:28','2026-03-26 15:35:28','ORD00000001',NULL,2,'2026-03-26 16:15:16','2026-03-26 15:35:28'),(2,'47461b4b-ba70-80b5-4afc-fee6e4579e90',1,NULL,NULL,NULL,NULL,'2026-03-27 09:58:13',NULL,NULL,NULL,'',0,'2026-03-27 09:58:13','2026-03-27 09:58:13','ORD00000002','',2,'2026-03-27 10:58:13','2026-03-27 10:58:13'),(3,'867478e3-4e68-c5bd-3d19-543a7943914c',1,NULL,NULL,NULL,NULL,'2026-03-27 10:02:02',NULL,NULL,NULL,'',0,'2026-03-27 10:02:02','2026-03-27 10:02:02','ORD00000003','',2,'2026-03-27 11:02:02','2026-03-27 11:02:02');
/*!40000 ALTER TABLE `tbllims_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_panel_items`
--

DROP TABLE IF EXISTS `tbllims_panel_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_panel_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `panel_id` int(10) unsigned NOT NULL,
  `analysis_id` int(10) unsigned NOT NULL,
  `culture_id` int(10) unsigned DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_panel` (`panel_id`),
  KEY `idx_analysis` (`analysis_id`),
  KEY `idx_panel_culture` (`panel_id`,`culture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_panel_items`
--

LOCK TABLES `tbllims_panel_items` WRITE;
/*!40000 ALTER TABLE `tbllims_panel_items` DISABLE KEYS */;
INSERT INTO `tbllims_panel_items` VALUES (7,1,4,NULL,1,0),(8,1,6,NULL,1,1),(9,1,2,NULL,1,2),(10,1,1,NULL,1,3),(11,1,3,NULL,1,4),(12,1,5,NULL,1,5),(13,2,4,NULL,1,0),(14,2,6,NULL,1,1),(15,2,1,NULL,1,2),(16,2,3,NULL,1,3),(17,2,5,NULL,1,4),(22,3,7,NULL,1,0),(23,3,4,NULL,1,1),(24,3,6,NULL,1,2),(25,3,1,NULL,1,3),(26,3,3,NULL,1,4);
/*!40000 ALTER TABLE `tbllims_panel_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_panels`
--

DROP TABLE IF EXISTS `tbllims_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_panels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_panel_dept` (`department_id`),
  KEY `idx_panel_item` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_panels`
--

LOCK TABLES `tbllims_panels` WRITE;
/*!40000 ALTER TABLE `tbllims_panels` DISABLE KEYS */;
INSERT INTO `tbllims_panels` VALUES (1,'Εμφιαλωμένο Νερό','WP001',1,7,1,'2026-03-26 14:46:48'),(2,'Πόσιμο νερό','WP002',1,8,1,'2026-03-26 14:55:28'),(3,'Κολυμβητικές Δεξαμενές','WP003',1,9,1,'2026-03-26 14:56:43');
/*!40000 ALTER TABLE `tbllims_panels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_partners`
--

DROP TABLE IF EXISTS `tbllims_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `api_key` varchar(128) DEFAULT NULL,
  `api_base_url` varchar(255) DEFAULT NULL,
  `api_secret` varchar(255) DEFAULT NULL,
  `sync_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `last_seen_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_error` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_customer` (`customer_id`),
  KEY `idx_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_partners`
--

LOCK TABLES `tbllims_partners` WRITE;
/*!40000 ALTER TABLE `tbllims_partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_report_notes`
--

DROP TABLE IF EXISTS `tbllims_report_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_report_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) DEFAULT NULL,
  `note_el` text NOT NULL,
  `note_en` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`active`,`sort_order`),
  KEY `idx_position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_report_notes`
--

LOCK TABLES `tbllims_report_notes` WRITE;
/*!40000 ALTER TABLE `tbllims_report_notes` DISABLE KEYS */;
INSERT INTO `tbllims_report_notes` VALUES (1,'Samples Note','Τα αποτελέσματα αφορούν μόνο τα δείγματα που έχουν εξεταστεί.','The results apply only to the samples that have been tested.',1,0,1,'2025-12-17 18:27:36','2025-12-17 18:27:36'),(2,'Δήλωση Συμμόρφωσης','Δήλωση Συμμόρφωσης: Συνάδει με τους περί της Ποιότητας του Νερού Ανθρώπινης Κατανάλωσης Νόμος του 2023 (Ν.46(Ι)/2023).','Statement of Compliance: In accordance with the Quality of Water Intended for Human Consumption Law of 2023  (Ν.46(Ι)/2023).',1,0,2,'2025-12-17 18:30:13','2025-12-17 18:30:13');
/*!40000 ALTER TABLE `tbllims_report_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_results`
--

DROP TABLE IF EXISTS `tbllims_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_id` int(10) unsigned NOT NULL,
  `value_numeric` decimal(15,6) DEFAULT NULL,
  `value_text` varchar(255) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `flag` enum('L','H','LL','HH','A') DEFAULT NULL,
  `measured_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_results`
--

LOCK TABLES `tbllims_results` WRITE;
/*!40000 ALTER TABLE `tbllims_results` DISABLE KEYS */;
INSERT INTO `tbllims_results` VALUES (1,1,0.000000,NULL,'cfu/100ml',NULL,'2026-03-26 16:20:00','2026-03-26 16:20:54'),(2,1,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:21:30'),(3,1,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:22:26'),(4,1,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:23:03'),(5,1,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:01'),(6,2,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:01'),(7,3,0.000000,NULL,'cfu/ml','H','2026-03-26 16:20:00','2026-03-26 16:25:01'),(8,4,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:01'),(9,5,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:01'),(10,1,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:12'),(11,2,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:12'),(12,3,0.000000,NULL,'cfu/ml','H','2026-03-26 16:20:00','2026-03-26 16:25:12'),(13,4,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:12'),(14,5,0.000000,NULL,'cfu/100ml','L','2026-03-26 16:20:00','2026-03-26 16:25:12');
/*!40000 ALTER TABLE `tbllims_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_sample_cultures`
--

DROP TABLE IF EXISTS `tbllims_sample_cultures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_sample_cultures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `sample_id` int(10) unsigned NOT NULL,
  `culture_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sample_culture` (`sample_id`,`culture_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_sample_id` (`sample_id`),
  KEY `idx_culture_id` (`culture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_sample_cultures`
--

LOCK TABLES `tbllims_sample_cultures` WRITE;
/*!40000 ALTER TABLE `tbllims_sample_cultures` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_sample_cultures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_sample_types`
--

DROP TABLE IF EXISTS `tbllims_sample_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_sample_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `snomed_specimen_code` varchar(64) DEFAULT NULL,
  `min_volume` varchar(64) DEFAULT NULL,
  `container` varchar(128) DEFAULT NULL,
  `stability_hours` int(10) unsigned DEFAULT NULL,
  `storage_temp` varchar(64) DEFAULT NULL,
  `collection_instructions` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sampletype_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_sample_types`
--

LOCK TABLES `tbllims_sample_types` WRITE;
/*!40000 ALTER TABLE `tbllims_sample_types` DISABLE KEYS */;
INSERT INTO `tbllims_sample_types` VALUES (2,'Νερό','WS1001','WS1001','500ml','',NULL,'','',1,'2026-03-26 14:17:29'),(3,'Εμφιαλωμένο Νερό','WS1002','WS1002','1000ml','',NULL,'','',1,'2026-03-26 14:18:10'),(4,'Νέρό Κ. Δεξαμενής','WS1003','WS1003','500ml','',NULL,'','',1,'2026-03-26 14:19:30'),(5,'Χημική Νερού','WS1004','WS1004','1.5L','',NULL,'','',1,'2026-03-26 14:21:21');
/*!40000 ALTER TABLE `tbllims_sample_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_samples`
--

DROP TABLE IF EXISTS `tbllims_samples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_samples` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `appointment_id` int(10) unsigned DEFAULT NULL,
  `sample_uid` varchar(50) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `sample_type_id` int(10) unsigned DEFAULT NULL,
  `collected_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `received_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `sample_type_id` (`sample_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_samples`
--

LOCK TABLES `tbllims_samples` WRITE;
/*!40000 ALTER TABLE `tbllims_samples` DISABLE KEYS */;
INSERT INTO `tbllims_samples` VALUES (1,1,1,NULL,'S1-001','ORD00000001',2,'2026-03-26 16:17:11','2026-03-26 15:17:11','collected','δφγδφγδφ','2026-03-26 16:15:16','2026-03-26 16:17:11'),(2,2,NULL,NULL,'S2-01','ORD00000002',3,'2026-03-27 10:55:00','2026-03-27 10:55:00','draft','300ml','2026-03-27 10:58:13','2026-03-27 09:58:13'),(3,3,NULL,NULL,'S3-01','ORD00000003',2,NULL,'2026-03-27 10:16:17','pending','','2026-03-27 11:02:02','2026-03-27 11:16:17');
/*!40000 ALTER TABLE `tbllims_samples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_subjects`
--

DROP TABLE IF EXISTS `tbllims_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `primary_contact_id` int(11) DEFAULT NULL,
  `subject_type` varchar(30) DEFAULT 'patient',
  `subject_name` varchar(191) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `internal_code` varchar(100) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `social_insurance_no` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(30) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `primary_contact_id` (`primary_contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_subjects`
--

LOCK TABLES `tbllims_subjects` WRITE;
/*!40000 ALTER TABLE `tbllims_subjects` DISABLE KEYS */;
INSERT INTO `tbllims_subjects` VALUES (1,1,2,'farm','YPOSTATIKO 1 DROMOLAXIA','','','SUB-01001','','','','',NULL,'greek','25222222','andreas-c2014@hotmail.com','','','','',NULL,'',1,'2026-03-26 16:05:39','2026-03-26 16:05:39'),(2,3,NULL,'farm','ddddd','IONAS','','SUB-01002','888888','Cypriot','','',NULL,'english','666666666','isaias@eadvertise.eu','xczxc','paphos','','8047',58,'',1,'2026-04-15 12:47:20','2026-04-15 13:47:20');
/*!40000 ALTER TABLE `tbllims_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_sync_inbox`
--

DROP TABLE IF EXISTS `tbllims_sync_inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_sync_inbox` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` int(10) unsigned NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `idempotency_key` varchar(128) NOT NULL,
  `payload_hash` char(64) DEFAULT NULL,
  `received_at` datetime NOT NULL,
  `processed_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(32) NOT NULL DEFAULT 'received',
  `last_error` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_inbox` (`partner_id`,`idempotency_key`),
  KEY `idx_partner_received` (`partner_id`,`received_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_sync_inbox`
--

LOCK TABLES `tbllims_sync_inbox` WRITE;
/*!40000 ALTER TABLE `tbllims_sync_inbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_sync_inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_sync_outbox`
--

DROP TABLE IF EXISTS `tbllims_sync_outbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_sync_outbox` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `order_uid` char(36) DEFAULT NULL,
  `event_type` varchar(64) NOT NULL,
  `idempotency_key` varchar(128) NOT NULL,
  `payload_json` longtext NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `next_retry_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_error` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `sent_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_idem` (`partner_id`,`idempotency_key`),
  KEY `idx_partner_next_retry` (`partner_id`,`next_retry_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_sync_outbox`
--

LOCK TABLES `tbllims_sync_outbox` WRITE;
/*!40000 ALTER TABLE `tbllims_sync_outbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllims_sync_outbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_test_audit`
--

DROP TABLE IF EXISTS `tbllims_test_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_test_audit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_id` int(10) unsigned NOT NULL,
  `action` varchar(64) NOT NULL,
  `old_status` varchar(64) DEFAULT NULL,
  `new_status` varchar(64) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_test` (`test_id`),
  KEY `idx_action` (`action`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_test_audit`
--

LOCK TABLES `tbllims_test_audit` WRITE;
/*!40000 ALTER TABLE `tbllims_test_audit` DISABLE KEYS */;
INSERT INTO `tbllims_test_audit` VALUES (1,1,'result_entered','pending','complete',NULL,'{\"test_id\":1,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:01\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:01'),(2,2,'result_entered','pending','complete',NULL,'{\"test_id\":2,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:01\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:01'),(3,3,'result_entered','pending','complete',NULL,'{\"test_id\":3,\"unit\":\"cfu\\/ml\",\"flag\":\"H\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:01\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:01'),(4,4,'result_entered','pending','complete',NULL,'{\"test_id\":4,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:01\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:01'),(5,5,'result_entered','pending','complete',NULL,'{\"test_id\":5,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:01\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:01'),(6,1,'result_entered','','complete',NULL,'{\"test_id\":1,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:12\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:12'),(7,2,'result_entered','','complete',NULL,'{\"test_id\":2,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:12\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:12'),(8,3,'result_entered','','complete',NULL,'{\"test_id\":3,\"unit\":\"cfu\\/ml\",\"flag\":\"H\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:12\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:12'),(9,4,'result_entered','','complete',NULL,'{\"test_id\":4,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:12\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:12'),(10,5,'result_entered','','complete',NULL,'{\"test_id\":5,\"unit\":\"cfu\\/100ml\",\"flag\":\"L\",\"measured_at\":\"2026-03-26 17:20:00\",\"created_at\":\"2026-03-26 17:25:12\",\"value_numeric\":0}',NULL,2,'2026-03-26 17:25:12');
/*!40000 ALTER TABLE `tbllims_test_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_test_statuses`
--

DROP TABLE IF EXISTS `tbllims_test_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_test_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(64) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_terminal` tinyint(1) NOT NULL DEFAULT 0,
  `requires_result` tinyint(1) NOT NULL DEFAULT 0,
  `requires_verification` tinyint(1) NOT NULL DEFAULT 0,
  `requires_approval` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code` (`code`),
  KEY `idx_active` (`active`),
  KEY `idx_position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_test_statuses`
--

LOCK TABLES `tbllims_test_statuses` WRITE;
/*!40000 ALTER TABLE `tbllims_test_statuses` DISABLE KEYS */;
INSERT INTO `tbllims_test_statuses` VALUES (1,'Pending','pending','#999999',1,1,0,0,0,0,1,'2026-01-28 08:24:27'),(2,'In Progress','in_progress','#3a87ad',2,0,0,0,0,0,1,'2026-01-28 08:24:27'),(3,'Completed','completed','#5cb85c',3,0,0,1,0,0,1,'2026-01-28 08:24:27'),(4,'Verified','verified','#8e44ad',4,0,0,1,1,0,1,'2026-01-28 08:24:27'),(5,'Approved','approved','#2ecc71',5,0,0,1,1,1,1,'2026-01-28 08:24:27'),(6,'Reported','reported','#34495e',6,0,1,1,0,0,1,'2026-01-28 08:24:27');
/*!40000 ALTER TABLE `tbllims_test_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllims_tests`
--

DROP TABLE IF EXISTS `tbllims_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllims_tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sample_id` int(10) unsigned NOT NULL,
  `analysis_id` int(10) unsigned DEFAULT NULL,
  `status` enum('pending','in_progress','completed','verified') DEFAULT 'pending',
  `status_code` varchar(64) DEFAULT NULL,
  `assigned_staff` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `unit_price` decimal(15,4) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_by` int(10) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_by` int(10) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reported_by` int(10) unsigned DEFAULT NULL,
  `reported_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sample_id` (`sample_id`),
  KEY `analysis_id` (`analysis_id`),
  KEY `item_id` (`item_id`),
  KEY `assigned_staff` (`assigned_staff`),
  KEY `status_code` (`status_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllims_tests`
--

LOCK TABLES `tbllims_tests` WRITE;
/*!40000 ALTER TABLE `tbllims_tests` DISABLE KEYS */;
INSERT INTO `tbllims_tests` VALUES (1,1,4,'','complete',NULL,NULL,NULL,NULL,'2026-03-26 15:25:01','2026-03-26 16:20:00',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01','2026-03-26 16:15:16'),(2,1,6,'','complete',NULL,NULL,NULL,NULL,'2026-03-26 15:25:01','2026-03-26 16:20:00',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01','2026-03-26 16:15:16'),(3,1,1,'','complete',NULL,NULL,NULL,NULL,'2026-03-26 15:25:01','2026-03-26 16:20:00',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01','2026-03-26 16:15:16'),(4,1,3,'','complete',NULL,NULL,NULL,NULL,'2026-03-26 15:25:01','2026-03-26 16:20:00',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01','2026-03-26 16:15:16'),(5,1,5,'','complete',NULL,NULL,NULL,NULL,'2026-03-26 15:25:01','2026-03-26 16:20:00',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01',NULL,'2026-03-26 15:25:01','2026-03-26 16:15:16');
/*!40000 ALTER TABLE `tbllims_tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbllistemails`
--

DROP TABLE IF EXISTS `tbllistemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbllistemails` (
  `emailid` int(11) NOT NULL AUTO_INCREMENT,
  `listid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbllistemails`
--

LOCK TABLES `tbllistemails` WRITE;
/*!40000 ALTER TABLE `tbllistemails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbllistemails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmail_queue`
--

DROP TABLE IF EXISTS `tblmail_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmail_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine` varchar(40) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `cc` mediumtext DEFAULT NULL,
  `bcc` mediumtext DEFAULT NULL,
  `message` longtext NOT NULL,
  `alt_message` longtext DEFAULT NULL,
  `status` enum('pending','sending','sent','failed') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `headers` mediumtext DEFAULT NULL,
  `attachments` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmail_queue`
--

LOCK TABLES `tblmail_queue` WRITE;
/*!40000 ALTER TABLE `tblmail_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmail_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmaillistscustomfields`
--

DROP TABLE IF EXISTS `tblmaillistscustomfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmaillistscustomfields` (
  `customfieldid` int(11) NOT NULL AUTO_INCREMENT,
  `listid` int(11) NOT NULL,
  `fieldname` varchar(150) NOT NULL,
  `fieldslug` varchar(100) NOT NULL,
  PRIMARY KEY (`customfieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmaillistscustomfields`
--

LOCK TABLES `tblmaillistscustomfields` WRITE;
/*!40000 ALTER TABLE `tblmaillistscustomfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmaillistscustomfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmaillistscustomfieldvalues`
--

DROP TABLE IF EXISTS `tblmaillistscustomfieldvalues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmaillistscustomfieldvalues` (
  `customfieldvalueid` int(11) NOT NULL AUTO_INCREMENT,
  `listid` int(11) NOT NULL,
  `customfieldid` int(11) NOT NULL,
  `emailid` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`customfieldvalueid`),
  KEY `listid` (`listid`),
  KEY `customfieldid` (`customfieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmaillistscustomfieldvalues`
--

LOCK TABLES `tblmaillistscustomfieldvalues` WRITE;
/*!40000 ALTER TABLE `tblmaillistscustomfieldvalues` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmaillistscustomfieldvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmigrations`
--

DROP TABLE IF EXISTS `tblmigrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmigrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmigrations`
--

LOCK TABLES `tblmigrations` WRITE;
/*!40000 ALTER TABLE `tblmigrations` DISABLE KEYS */;
INSERT INTO `tblmigrations` VALUES (340);
/*!40000 ALTER TABLE `tblmigrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmilestones`
--

DROP TABLE IF EXISTS `tblmilestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmilestones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `description_visible_to_customer` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `project_id` int(11) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `milestone_order` int(11) NOT NULL DEFAULT 0,
  `datecreated` date NOT NULL,
  `hide_from_customer` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmilestones`
--

LOCK TABLES `tblmilestones` WRITE;
/*!40000 ALTER TABLE `tblmilestones` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmilestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmodules`
--

DROP TABLE IF EXISTS `tblmodules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmodules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(55) NOT NULL,
  `installed_version` varchar(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmodules`
--

LOCK TABLES `tblmodules` WRITE;
/*!40000 ALTER TABLE `tblmodules` DISABLE KEYS */;
INSERT INTO `tblmodules` VALUES (1,'backup','2.3.0',1),(2,'exports','1.0.0',1),(3,'menu_setup','2.3.0',1),(4,'surveys','2.3.0',1),(5,'theme_style','2.3.0',1),(6,'goals','2.3.0',1),(7,'lims','3.0.3',1),(9,'contactsplus','1.0.1',1),(10,'paymentsonaccount','2.0.0',1);
/*!40000 ALTER TABLE `tblmodules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnewsfeed_comment_likes`
--

DROP TABLE IF EXISTS `tblnewsfeed_comment_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnewsfeed_comment_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `dateliked` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnewsfeed_comment_likes`
--

LOCK TABLES `tblnewsfeed_comment_likes` WRITE;
/*!40000 ALTER TABLE `tblnewsfeed_comment_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnewsfeed_comment_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnewsfeed_post_comments`
--

DROP TABLE IF EXISTS `tblnewsfeed_post_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnewsfeed_post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnewsfeed_post_comments`
--

LOCK TABLES `tblnewsfeed_post_comments` WRITE;
/*!40000 ALTER TABLE `tblnewsfeed_post_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnewsfeed_post_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnewsfeed_post_likes`
--

DROP TABLE IF EXISTS `tblnewsfeed_post_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnewsfeed_post_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `dateliked` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnewsfeed_post_likes`
--

LOCK TABLES `tblnewsfeed_post_likes` WRITE;
/*!40000 ALTER TABLE `tblnewsfeed_post_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnewsfeed_post_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnewsfeed_posts`
--

DROP TABLE IF EXISTS `tblnewsfeed_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnewsfeed_posts` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `visibility` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `pinned` int(11) NOT NULL,
  `datepinned` datetime DEFAULT NULL,
  PRIMARY KEY (`postid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnewsfeed_posts`
--

LOCK TABLES `tblnewsfeed_posts` WRITE;
/*!40000 ALTER TABLE `tblnewsfeed_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnewsfeed_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnotes`
--

DROP TABLE IF EXISTS `tblnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `date_contacted` datetime DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnotes`
--

LOCK TABLES `tblnotes` WRITE;
/*!40000 ALTER TABLE `tblnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnotifications`
--

DROP TABLE IF EXISTS `tblnotifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isread` int(11) NOT NULL DEFAULT 0,
  `isread_inline` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `description` mediumtext NOT NULL,
  `fromuserid` int(11) NOT NULL,
  `fromclientid` int(11) NOT NULL DEFAULT 0,
  `from_fullname` varchar(100) NOT NULL,
  `touserid` int(11) NOT NULL,
  `fromcompany` int(11) DEFAULT NULL,
  `link` longtext DEFAULT NULL,
  `additional_data` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnotifications`
--

LOCK TABLES `tblnotifications` WRITE;
/*!40000 ALTER TABLE `tblnotifications` DISABLE KEYS */;
INSERT INTO `tblnotifications` VALUES (1,0,0,'2026-03-26 17:31:31','not_task_assigned_to_you',2,0,'Andreas Christofi',4,NULL,'#taskid=1','a:1:{i:0;s:31:\"Appointment for Order #1 (Home)\";}'),(2,0,0,'2026-03-26 17:31:32','not_task_assigned_to_you',2,0,'Andreas Christofi',4,NULL,'#taskid=2','a:1:{i:0;s:31:\"Appointment for Order #1 (Home)\";}');
/*!40000 ALTER TABLE `tblnotifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbloptions`
--

DROP TABLE IF EXISTS `tbloptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbloptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `value` longtext NOT NULL,
  `autoload` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbloptions`
--

LOCK TABLES `tbloptions` WRITE;
/*!40000 ALTER TABLE `tbloptions` DISABLE KEYS */;
INSERT INTO `tbloptions` VALUES (1,'dateformat','d/m/Y|%d/%m/%Y',1),(2,'companyname','D.A.K. NutriLab LTD',1),(3,'services','0',1),(4,'maximum_allowed_ticket_attachments','4',1),(5,'ticket_attachments_file_extensions','.jpg,.jpeg,.png,.pdf,.doc,.zip,.rar',1),(6,'staff_access_only_assigned_departments','1',1),(7,'use_knowledge_base','0',1),(8,'smtp_email','noreply@nutrilab.com.cy',1),(9,'smtp_password','7fd34ad5e5eb6de0691ab9302c88726468f624e6ed56d1400170337afe51a103d250ba8b28856180e1896fe592fcdfc62b68bd25d84fed488d7302995921cda49fFxAE/nZ9JznezTambg5h02xYJOLpgnShtOxSunrc8=',1),(10,'company_info_format','{company_name}<br />\r\n      {address}<br />\r\n      {city} {state}<br />\r\n      {country_code} {zip_code}<br />\r\n      {cf_1}',0),(11,'smtp_port','587',1),(12,'smtp_host','mail.nutrilab.com.cy',1),(13,'smtp_email_charset','utf-8',1),(14,'default_timezone','Asia/Nicosia',1),(15,'clients_default_theme','perfex',1),(16,'company_logo','e7ff8ed6ee64380b3e18fb89c90bc1c6.png',1),(17,'tables_pagination_limit','25',1),(18,'main_domain','www.nutrilab.com.cy',1),(19,'allow_registration','0',1),(20,'knowledge_base_without_registration','0',1),(21,'email_signature','',1),(22,'default_staff_role','1',1),(23,'newsfeed_maximum_files_upload','10',1),(24,'contract_expiration_before','4',1),(25,'invoice_prefix','INV-',1),(26,'decimal_separator','.',1),(27,'thousand_separator',',',1),(28,'invoice_company_name','D.A.K. NutriLab LTD',1),(29,'invoice_company_address','12 Omirou Ave, Office 103',1),(30,'invoice_company_city','Larnaca',1),(31,'invoice_company_country_code','CY',1),(32,'invoice_company_postal_code','7102',1),(33,'invoice_company_phonenumber','24813812',1),(34,'view_invoice_only_logged_in','0',1),(35,'invoice_number_format','2',1),(36,'next_invoice_number','1002',0),(37,'active_language','english',1),(38,'invoice_number_decrement_on_delete','1',1),(39,'automatically_send_invoice_overdue_reminder_after','1',1),(40,'automatically_resend_invoice_overdue_reminder_after','3',1),(41,'expenses_auto_operations_hour','9',1),(42,'delete_only_on_last_invoice','1',1),(43,'delete_only_on_last_estimate','1',1),(44,'create_invoice_from_recurring_only_on_paid_invoices','0',1),(45,'allow_payment_amount_to_be_modified','1',1),(46,'rtl_support_client','0',1),(47,'limit_top_search_bar_results_to','10',1),(48,'estimate_prefix','EST-',1),(49,'next_estimate_number','1001',0),(50,'estimate_number_decrement_on_delete','1',1),(51,'estimate_number_format','2',1),(52,'estimate_auto_convert_to_invoice_on_client_accept','1',1),(53,'exclude_estimate_from_client_area_with_draft_status','1',1),(54,'rtl_support_admin','0',1),(55,'last_cron_run','',1),(56,'show_sale_agent_on_estimates','1',1),(57,'show_sale_agent_on_invoices','1',1),(58,'predefined_terms_invoice','',1),(59,'predefined_terms_estimate','',1),(60,'default_task_priority','2',1),(61,'dropbox_app_key','',1),(62,'show_expense_reminders_on_calendar','1',1),(63,'only_show_contact_tickets','1',1),(64,'predefined_clientnote_invoice','',1),(65,'predefined_clientnote_estimate','',1),(66,'custom_pdf_logo_image_url','',1),(67,'favicon','favicon.png',1),(68,'invoice_due_after','30',1),(69,'google_api_key','',1),(70,'google_calendar_main_calendar','',1),(71,'default_tax','a:0:{}',1),(72,'show_invoices_on_calendar','1',1),(73,'show_estimates_on_calendar','1',1),(74,'show_contracts_on_calendar','1',1),(75,'show_tasks_on_calendar','1',1),(76,'show_customer_reminders_on_calendar','1',1),(77,'output_client_pdfs_from_admin_area_in_client_language','1',1),(78,'show_lead_reminders_on_calendar','1',1),(79,'send_estimate_expiry_reminder_before','4',1),(80,'leads_default_source','',1),(81,'leads_default_status','',1),(82,'proposal_expiry_reminder_enabled','1',1),(83,'send_proposal_expiry_reminder_before','4',1),(84,'default_contact_permissions','a:6:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";}',1),(85,'pdf_logo_width','150',1),(86,'access_tickets_to_none_staff_members','0',1),(87,'customer_default_country','58',1),(88,'view_estimate_only_logged_in','0',1),(89,'show_status_on_pdf_ei','1',1),(90,'email_piping_only_replies','0',1),(91,'email_piping_only_registered','0',1),(92,'default_view_calendar','dayGridMonth',1),(93,'email_piping_default_priority','2',1),(94,'total_to_words_lowercase','1',1),(95,'show_tax_per_item','1',1),(96,'total_to_words_enabled','1',1),(97,'receive_notification_on_new_ticket','1',0),(98,'autoclose_tickets_after','0',1),(99,'media_max_file_size_upload','10',1),(100,'client_staff_add_edit_delete_task_comments_first_hour','0',1),(101,'show_projects_on_calendar','1',1),(102,'leads_kanban_limit','50',1),(103,'tasks_reminder_notification_before','2',1),(104,'pdf_font','freesans',1),(105,'pdf_table_heading_color','#e5e7eb',1),(106,'pdf_table_heading_text_color','#030712',1),(107,'pdf_font_size','10',1),(108,'default_leads_kanban_sort','dateadded',1),(109,'default_leads_kanban_sort_type','asc',1),(110,'allowed_files','.png,.jpg,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt',1),(111,'show_all_tasks_for_project_member','1',1),(112,'email_protocol','smtp',1),(113,'calendar_first_day','0',1),(114,'recaptcha_secret_key','',1),(115,'show_help_on_setup_menu','0',1),(116,'show_proposals_on_calendar','1',1),(117,'smtp_encryption','tls',1),(118,'recaptcha_site_key','',1),(119,'smtp_username','noreply@nutrilab.com.cy',1),(120,'auto_stop_tasks_timers_on_new_timer','1',1),(121,'notification_when_customer_pay_invoice','1',1),(122,'calendar_invoice_color','#FF6F00',1),(123,'calendar_estimate_color','#FF6F00',1),(124,'calendar_proposal_color','#84c529',1),(125,'new_task_auto_assign_current_member','1',1),(126,'calendar_reminder_color','#03A9F4',1),(127,'calendar_contract_color','#B72974',1),(128,'calendar_project_color','#B72974',1),(129,'update_info_message','',1),(130,'show_estimate_reminders_on_calendar','1',1),(131,'show_invoice_reminders_on_calendar','1',1),(132,'show_proposal_reminders_on_calendar','1',1),(133,'proposal_due_after','30',1),(134,'allow_customer_to_change_ticket_status','0',1),(135,'lead_lock_after_convert_to_customer','0',1),(136,'default_proposals_pipeline_sort','datecreated',1),(137,'default_proposals_pipeline_sort_type','asc',1),(138,'default_estimates_pipeline_sort','pipeline_order',1),(139,'default_estimates_pipeline_sort_type','asc',1),(140,'use_recaptcha_customers_area','0',1),(141,'remove_decimals_on_zero','0',1),(142,'remove_tax_name_from_item_table','1',1),(143,'pdf_format_invoice','A4-PORTRAIT',1),(144,'pdf_format_estimate','A4-PORTRAIT',1),(145,'pdf_format_proposal','A4-PORTRAIT',1),(146,'pdf_format_payment','A4-PORTRAIT',1),(147,'pdf_format_contract','A4-PORTRAIT',1),(148,'swap_pdf_info','0',1),(149,'exclude_invoice_from_client_area_with_draft_status','1',1),(150,'cron_has_run_from_cli','0',1),(151,'hide_cron_is_required_message','0',0),(152,'auto_assign_customer_admin_after_lead_convert','1',1),(153,'show_transactions_on_invoice_pdf','1',1),(154,'show_pay_link_to_invoice_pdf','1',1),(155,'tasks_kanban_limit','50',1),(156,'purchase_key','',1),(157,'estimates_pipeline_limit','50',1),(158,'proposals_pipeline_limit','50',1),(159,'proposal_number_prefix','PRO-',1),(160,'number_padding_prefixes','6',1),(161,'show_page_number_on_pdf','0',1),(162,'calendar_events_limit','4',1),(163,'show_setup_menu_item_only_on_hover','0',1),(164,'company_requires_vat_number_field','1',1),(165,'company_is_required','1',1),(166,'allow_contact_to_delete_files','1',1),(167,'company_vat','',1),(168,'di','1769586927',1),(169,'invoice_auto_operations_hour','21',1),(170,'use_minified_files','1',1),(171,'only_own_files_contacts','1',1),(172,'allow_primary_contact_to_view_edit_billing_and_shipping','1',1),(173,'estimate_due_after','30',1),(174,'staff_members_open_tickets_to_all_contacts','1',1),(175,'time_format','24',1),(176,'delete_activity_log_older_then','1',1),(177,'disable_language','0',1),(178,'company_state','Aradippou',1),(179,'email_header','<!doctype html>\r\n      <html>\r\n      <head>\r\n      <meta name=\"viewport\" content=\"width=device-width\" />\r\n      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n      <style>\r\n      body {\r\n        background-color: #f6f6f6;\r\n        font-family: sans-serif;\r\n        -webkit-font-smoothing: antialiased;\r\n        font-size: 14px;\r\n        line-height: 1.4;\r\n        margin: 0;\r\n        padding: 0;\r\n        -ms-text-size-adjust: 100%;\r\n        -webkit-text-size-adjust: 100%;\r\n      }\r\n      table {\r\n        border-collapse: separate;\r\n        mso-table-lspace: 0pt;\r\n        mso-table-rspace: 0pt;\r\n        width: 100%;\r\n      }\r\n      table td {\r\n        font-family: sans-serif;\r\n        font-size: 14px;\r\n        vertical-align: top;\r\n      }\r\n      /* -------------------------------------\r\n      BODY & CONTAINER\r\n      ------------------------------------- */\r\n      .body {\r\n        background-color: #f6f6f6;\r\n        width: 100%;\r\n      }\r\n      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */\r\n      \r\n      .container {\r\n        display: block;\r\n        margin: 0 auto !important;\r\n        /* makes it centered */\r\n        max-width: 680px;\r\n        padding: 10px;\r\n        width: 680px;\r\n      }\r\n      /* This should also be a block element, so that it will fill 100% of the .container */\r\n      \r\n      .content {\r\n        box-sizing: border-box;\r\n        display: block;\r\n        margin: 0 auto;\r\n        max-width: 680px;\r\n        padding: 10px;\r\n      }\r\n      /* -------------------------------------\r\n      HEADER, FOOTER, MAIN\r\n      ------------------------------------- */\r\n      \r\n      .main {\r\n        background: #fff;\r\n        border-radius: 3px;\r\n        width: 100%;\r\n      }\r\n      .wrapper {\r\n        box-sizing: border-box;\r\n        padding: 20px;\r\n      }\r\n      .footer {\r\n        clear: both;\r\n        padding-top: 10px;\r\n        text-align: center;\r\n        width: 100%;\r\n      }\r\n      .footer td,\r\n      .footer p,\r\n      .footer span,\r\n      .footer a {\r\n        color: #999999;\r\n        font-size: 12px;\r\n        text-align: center;\r\n      }\r\n      hr {\r\n        border: 0;\r\n        border-bottom: 1px solid #f6f6f6;\r\n        margin: 20px 0;\r\n      }\r\n      /* -------------------------------------\r\n      RESPONSIVE AND MOBILE FRIENDLY STYLES\r\n      ------------------------------------- */\r\n      \r\n      @media only screen and (max-width: 620px) {\r\n        table[class=body] .content {\r\n          padding: 0 !important;\r\n        }\r\n        table[class=body] .container {\r\n          padding: 0 !important;\r\n          width: 100% !important;\r\n        }\r\n        table[class=body] .main {\r\n          border-left-width: 0 !important;\r\n          border-radius: 0 !important;\r\n          border-right-width: 0 !important;\r\n        }\r\n      }\r\n      </style>\r\n      </head>\r\n      <body class=\"\">\r\n      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\r\n      <tr>\r\n      <td>&nbsp;</td>\r\n      <td class=\"container\">\r\n      <div class=\"content\">\r\n      <!-- START CENTERED WHITE CONTAINER -->\r\n      <table class=\"main\">\r\n      <!-- START MAIN CONTENT AREA -->\r\n      <tr>\r\n      <td class=\"wrapper\">\r\n      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n      <tr>\r\n      <td>',1),(180,'show_pdf_signature_invoice','1',0),(181,'show_pdf_signature_estimate','1',0),(182,'signature_image','',0),(183,'email_footer','</td>\r\n      </tr>\r\n      </table>\r\n      </td>\r\n      </tr>\r\n      <!-- END MAIN CONTENT AREA -->\r\n      </table>\r\n      <!-- START FOOTER -->\r\n      <div class=\"footer\">\r\n      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n      <tr>\r\n      <td class=\"content-block\">\r\n      <span>{companyname}</span>\r\n      </td>\r\n      </tr>\r\n      </table>\r\n      </div>\r\n      <!-- END FOOTER -->\r\n      <!-- END CENTERED WHITE CONTAINER -->\r\n      </div>\r\n      </td>\r\n      <td>&nbsp;</td>\r\n      </tr>\r\n      </table>\r\n      </body>\r\n      </html>',1),(184,'exclude_proposal_from_client_area_with_draft_status','1',1),(185,'pusher_app_key','9e587bebd078415f0c8f',1),(186,'pusher_app_secret','d3e2fd0c28ce555eb2bf',1),(187,'pusher_app_id','2107868',1),(188,'pusher_realtime_notifications','1',1),(189,'pdf_format_statement','A4-PORTRAIT',1),(190,'pusher_cluster','eu',1),(191,'show_table_export_button','to_all',1),(192,'allow_staff_view_proposals_assigned','1',1),(193,'show_cloudflare_notice','1',0),(194,'task_modal_class','modal-lg',1),(195,'lead_modal_class','modal-lg',1),(196,'show_timesheets_overview_all_members_notice_admins','0',1),(197,'desktop_notifications','1',1),(198,'hide_notified_reminders_from_calendar','1',0),(199,'customer_info_format','{company_name}<br />\r\n      {street}<br />\r\n      {city} {state}<br />\r\n      {country_code} {zip_code}<br />\r\n      {vat_number_with_label}',0),(200,'timer_started_change_status_in_progress','1',0),(201,'default_ticket_reply_status','3',1),(202,'default_task_status','auto',1),(203,'email_queue_skip_with_attachments','1',1),(204,'email_queue_enabled','0',1),(205,'last_email_queue_retry','',1),(206,'auto_dismiss_desktop_notifications_after','0',1),(207,'proposal_info_format','{proposal_to}<br />\r\n      {address}<br />\r\n      {city} {state}<br />\r\n      {country_code} {zip_code}<br />\r\n      {phone}<br />\r\n      {email}',0),(208,'ticket_replies_order','desc',1),(209,'new_recurring_invoice_action','generate_and_send',0),(210,'bcc_emails','',0),(211,'email_templates_language_checks','',0),(212,'proposal_accept_identity_confirmation','1',0),(213,'estimate_accept_identity_confirmation','1',0),(214,'new_task_auto_follower_current_member','0',1),(215,'task_biillable_checked_on_creation','1',1),(216,'predefined_clientnote_credit_note','',1),(217,'predefined_terms_credit_note','',1),(218,'next_credit_note_number','1001',1),(219,'credit_note_prefix','CN-',1),(220,'credit_note_number_decrement_on_delete','1',1),(221,'pdf_format_credit_note','A4-PORTRAIT',1),(222,'show_pdf_signature_credit_note','1',0),(223,'show_credit_note_reminders_on_calendar','1',1),(224,'show_amount_due_on_invoice','1',1),(225,'show_total_paid_on_invoice','1',1),(226,'show_credits_applied_on_invoice','1',1),(227,'staff_members_create_inline_lead_status','1',1),(228,'staff_members_create_inline_customer_groups','1',1),(229,'staff_members_create_inline_ticket_services','1',1),(230,'staff_members_save_tickets_predefined_replies','1',1),(231,'staff_members_create_inline_contract_types','1',1),(232,'staff_members_create_inline_expense_categories','1',1),(233,'show_project_on_credit_note','1',1),(234,'proposals_auto_operations_hour','9',1),(235,'estimates_auto_operations_hour','9',1),(236,'contracts_auto_operations_hour','9',1),(237,'credit_note_number_format','2',1),(238,'allow_non_admin_members_to_import_leads','1',1),(239,'e_sign_legal_text','By clicking on \"Sign\", I consent to be legally bound by this electronic representation of my signature.',1),(240,'show_pdf_signature_contract','1',1),(241,'view_contract_only_logged_in','0',1),(242,'show_subscriptions_in_customers_area','0',1),(243,'calendar_only_assigned_tasks','0',1),(244,'after_subscription_payment_captured','send_invoice_and_receipt',1),(245,'mail_engine','phpmailer',1),(246,'gdpr_enable_terms_and_conditions','0',1),(247,'privacy_policy','',1),(248,'terms_and_conditions','',1),(249,'gdpr_enable_terms_and_conditions_lead_form','0',1),(250,'gdpr_enable_terms_and_conditions_ticket_form','0',1),(251,'gdpr_contact_enable_right_to_be_forgotten','0',1),(252,'show_gdpr_in_customers_menu','1',1),(253,'show_gdpr_link_in_footer','1',1),(254,'enable_gdpr','0',1),(255,'gdpr_on_forgotten_remove_invoices_credit_notes','0',1),(256,'gdpr_on_forgotten_remove_estimates','0',1),(257,'gdpr_enable_consent_for_contacts','0',1),(258,'gdpr_consent_public_page_top_block','',1),(259,'gdpr_page_top_information_block','',1),(260,'gdpr_enable_lead_public_form','0',1),(261,'gdpr_show_lead_custom_fields_on_public_form','0',1),(262,'gdpr_lead_attachments_on_public_form','0',1),(263,'gdpr_enable_consent_for_leads','0',1),(264,'gdpr_lead_enable_right_to_be_forgotten','0',1),(265,'allow_staff_view_invoices_assigned','1',1),(266,'gdpr_data_portability_leads','0',1),(267,'gdpr_lead_data_portability_allowed','',1),(268,'gdpr_contact_data_portability_allowed','',1),(269,'gdpr_data_portability_contacts','0',1),(270,'allow_staff_view_estimates_assigned','1',1),(271,'gdpr_after_lead_converted_delete','0',1),(272,'gdpr_show_terms_and_conditions_in_footer','0',1),(273,'save_last_order_for_tables','0',1),(274,'company_logo_dark','9f6d952b4f5126342fcd61c9c4d552e2.png',1),(275,'customers_register_require_confirmation','1',1),(276,'allow_non_admin_staff_to_delete_ticket_attachments','0',1),(277,'receive_notification_on_new_ticket_replies','1',0),(278,'google_client_id','',1),(279,'enable_google_picker','1',1),(280,'show_ticket_reminders_on_calendar','1',1),(281,'ticket_import_reply_only','0',1),(282,'visible_customer_profile_tabs','all',0),(283,'show_project_on_invoice','1',1),(284,'show_project_on_estimate','1',1),(285,'staff_members_create_inline_lead_source','1',1),(286,'lead_unique_validation','[\"email\"]',1),(287,'last_upgrade_copy_data','',1),(288,'custom_js_admin_scripts','',1),(289,'custom_js_customer_scripts','0',1),(290,'stripe_webhook_id','',1),(291,'stripe_webhook_signing_secret','',1),(292,'stripe_ideal_webhook_id','',1),(293,'stripe_ideal_webhook_signing_secret','',1),(294,'show_php_version_notice','1',0),(295,'recaptcha_ignore_ips','',1),(296,'show_task_reminders_on_calendar','1',1),(297,'customer_settings','true',1),(298,'tasks_reminder_notification_hour','9',1),(299,'allow_primary_contact_to_manage_other_contacts','1',1),(300,'items_table_amounts_exclude_currency_symbol','1',1),(301,'round_off_task_timer_option','0',1),(302,'round_off_task_timer_time','5',1),(303,'bitly_access_token','',1),(304,'enable_support_menu_badges','0',1),(305,'attach_invoice_to_payment_receipt_email','1',1),(306,'invoice_due_notice_before','2',1),(307,'invoice_due_notice_resend_after','0',1),(308,'_leads_settings','true',1),(309,'show_estimate_request_in_customers_area','0',1),(310,'gdpr_enable_terms_and_conditions_estimate_request_form','0',1),(311,'identification_key','56660796817695869536979c10977bdb',1),(312,'automatically_stop_task_timer_after_hours','8',1),(313,'automatically_assign_ticket_to_first_staff_responding','0',1),(314,'reminder_for_completed_but_not_billed_tasks','0',1),(315,'staff_notify_completed_but_not_billed_tasks','',1),(316,'reminder_for_completed_but_not_billed_tasks_days','',1),(317,'tasks_reminder_notification_last_notified_day','',1),(318,'staff_related_ticket_notification_to_assignee_only','0',1),(319,'show_pdf_signature_proposal','1',1),(320,'enable_honeypot_spam_validation','1',1),(321,'microsoft_mail_client_id','',1),(322,'microsoft_mail_client_secret','',1),(323,'microsoft_mail_azure_tenant_id','',1),(324,'google_mail_client_id','',1),(325,'google_mail_client_secret','',1),(326,'google_mail_refresh_token','',1),(327,'automatically_set_logged_in_staff_sales_agent','1',1),(328,'contract_sign_reminder_every_days','0',1),(329,'last_updated_date','',1),(330,'v310_incompatible_tables','[]',1),(331,'microsoft_mail_refresh_token','',1),(332,'required_register_fields','[]',0),(333,'allow_non_admin_members_to_delete_tickets_and_replies','1',1),(334,'proposal_auto_convert_to_invoice_on_client_accept','0',1),(335,'show_project_on_proposal','1',1),(336,'disable_ticket_public_url','0',1),(337,'openai_max_token','500',1),(338,'ai_provider','',1),(339,'ai_enable_ticket_summarization','0',0),(340,'ai_enable_ticket_reply_suggestions','0',0),(341,'openai_use_fine_tuning','0',1),(342,'openai_fine_tuning_base_model','',1),(343,'openai_fine_tuning_last_job_id','',1),(344,'openai_fine_tuned_model','',1),(345,'openai_our_fine_tuned_model','',1),(346,'openai_last_fine_tuning_file_id','',1),(347,'ai_system_prompt','You are a support representative',1),(348,'upgraded_from_version','',0),(349,'einvoice_send_as_invoice_email_attachment','0',1),(350,'einvoice_default_credit_note_email_template','0',1),(351,'einvoice_default_invoice_template','',1),(352,'einvoice_default_credit_note_template','',1),(353,'einvoice_send_as_credit_note_email_attachment','0',1),(354,'enabled_languages','[\"english\",\"greek\"]',1),(355,'sms_clickatell_api_key','',1),(356,'sms_clickatell_active','0',1),(357,'sms_clickatell_initialized','1',1),(358,'sms_msg91_sender_id','',1),(359,'sms_msg91_api_type','api',1),(360,'sms_msg91_auth_key','',1),(361,'sms_msg91_active','0',1),(362,'sms_msg91_initialized','1',1),(363,'sms_twilio_account_sid','',1),(364,'sms_twilio_auth_token','',1),(365,'sms_twilio_phone_number','',1),(366,'sms_twilio_sender_id','',1),(367,'sms_twilio_active','0',1),(368,'sms_twilio_initialized','1',1),(369,'paymentmethod_authorize_acceptjs_active','0',1),(370,'paymentmethod_authorize_acceptjs_label','Authorize.net Accept.js',1),(371,'paymentmethod_authorize_acceptjs_public_key','',0),(372,'paymentmethod_authorize_acceptjs_api_login_id','',0),(373,'paymentmethod_authorize_acceptjs_api_transaction_key','',0),(374,'paymentmethod_authorize_acceptjs_description_dashboard','Payment for Invoice {invoice_number}',0),(375,'paymentmethod_authorize_acceptjs_currencies','USD',0),(376,'paymentmethod_authorize_acceptjs_test_mode_enabled','0',0),(377,'paymentmethod_authorize_acceptjs_default_selected','1',1),(378,'paymentmethod_authorize_acceptjs_initialized','1',1),(379,'paymentmethod_instamojo_active','0',1),(380,'paymentmethod_instamojo_label','Instamojo',1),(381,'paymentmethod_instamojo_fee_fixed','0',0),(382,'paymentmethod_instamojo_fee_percent','0',0),(383,'paymentmethod_instamojo_api_key','',0),(384,'paymentmethod_instamojo_auth_token','',0),(385,'paymentmethod_instamojo_description_dashboard','Payment for Invoice {invoice_number}',0),(386,'paymentmethod_instamojo_currencies','INR',0),(387,'paymentmethod_instamojo_test_mode_enabled','1',0),(388,'paymentmethod_instamojo_default_selected','1',1),(389,'paymentmethod_instamojo_initialized','1',1),(390,'paymentmethod_mollie_active','0',1),(391,'paymentmethod_mollie_label','Mollie',1),(392,'paymentmethod_mollie_api_key','',0),(393,'paymentmethod_mollie_description_dashboard','Payment for Invoice {invoice_number}',0),(394,'paymentmethod_mollie_currencies','EUR',0),(395,'paymentmethod_mollie_test_mode_enabled','1',0),(396,'paymentmethod_mollie_default_selected','1',1),(397,'paymentmethod_mollie_initialized','1',1),(398,'paymentmethod_paypal_braintree_active','0',1),(399,'paymentmethod_paypal_braintree_label','Braintree',1),(400,'paymentmethod_paypal_braintree_merchant_id','',0),(401,'paymentmethod_paypal_braintree_api_public_key','',0),(402,'paymentmethod_paypal_braintree_api_private_key','',0),(403,'paymentmethod_paypal_braintree_currencies','USD',0),(404,'paymentmethod_paypal_braintree_paypal_enabled','1',0),(405,'paymentmethod_paypal_braintree_test_mode_enabled','1',0),(406,'paymentmethod_paypal_braintree_default_selected','1',1),(407,'paymentmethod_paypal_braintree_initialized','1',1),(408,'paymentmethod_paypal_checkout_active','0',1),(409,'paymentmethod_paypal_checkout_label','Paypal Smart Checkout',1),(410,'paymentmethod_paypal_checkout_fee_fixed','0',0),(411,'paymentmethod_paypal_checkout_fee_percent','0',0),(412,'paymentmethod_paypal_checkout_client_id','',0),(413,'paymentmethod_paypal_checkout_secret','',0),(414,'paymentmethod_paypal_checkout_payment_description','Payment for Invoice {invoice_number}',0),(415,'paymentmethod_paypal_checkout_currencies','USD,CAD,EUR',0),(416,'paymentmethod_paypal_checkout_test_mode_enabled','1',0),(417,'paymentmethod_paypal_checkout_default_selected','1',1),(418,'paymentmethod_paypal_checkout_initialized','1',1),(419,'paymentmethod_paypal_active','0',1),(420,'paymentmethod_paypal_label','Paypal',1),(421,'paymentmethod_paypal_fee_fixed','0',0),(422,'paymentmethod_paypal_fee_percent','0',0),(423,'paymentmethod_paypal_username','',0),(424,'paymentmethod_paypal_password','',0),(425,'paymentmethod_paypal_signature','',0),(426,'paymentmethod_paypal_description_dashboard','Payment for Invoice {invoice_number}',0),(427,'paymentmethod_paypal_currencies','EUR,USD',0),(428,'paymentmethod_paypal_test_mode_enabled','1',0),(429,'paymentmethod_paypal_default_selected','1',1),(430,'paymentmethod_paypal_initialized','1',1),(431,'paymentmethod_payu_money_active','0',1),(432,'paymentmethod_payu_money_label','PayU Money',1),(433,'paymentmethod_payu_money_fee_fixed','0',0),(434,'paymentmethod_payu_money_fee_percent','0',0),(435,'paymentmethod_payu_money_key','',0),(436,'paymentmethod_payu_money_salt','',0),(437,'paymentmethod_payu_money_description_dashboard','Payment for Invoice {invoice_number}',0),(438,'paymentmethod_payu_money_currencies','INR',0),(439,'paymentmethod_payu_money_test_mode_enabled','1',0),(440,'paymentmethod_payu_money_default_selected','1',1),(441,'paymentmethod_payu_money_initialized','1',1),(442,'paymentmethod_stripe_active','0',1),(443,'paymentmethod_stripe_label','Stripe Checkout',1),(444,'paymentmethod_stripe_fee_fixed','0',0),(445,'paymentmethod_stripe_fee_percent','0',0),(446,'paymentmethod_stripe_api_publishable_key','',0),(447,'paymentmethod_stripe_api_secret_key','',0),(448,'paymentmethod_stripe_description_dashboard','Payment for Invoice {invoice_number}',0),(449,'paymentmethod_stripe_currencies','USD,CAD',0),(450,'paymentmethod_stripe_allow_primary_contact_to_update_credit_card','1',0),(451,'paymentmethod_stripe_default_selected','1',1),(452,'paymentmethod_stripe_initialized','1',1),(453,'paymentmethod_stripe_ideal_active','0',1),(454,'paymentmethod_stripe_ideal_label','Stripe iDEAL',1),(455,'paymentmethod_stripe_ideal_api_secret_key','',0),(456,'paymentmethod_stripe_ideal_api_publishable_key','',0),(457,'paymentmethod_stripe_ideal_description_dashboard','Payment for Invoice {invoice_number}',0),(458,'paymentmethod_stripe_ideal_statement_descriptor','Payment for Invoice {invoice_number}',0),(459,'paymentmethod_stripe_ideal_currencies','EUR',0),(460,'paymentmethod_stripe_ideal_default_selected','1',1),(461,'paymentmethod_stripe_ideal_initialized','1',1),(462,'paymentmethod_two_checkout_active','0',1),(463,'paymentmethod_two_checkout_label','2Checkout',1),(464,'paymentmethod_two_checkout_fee_fixed','0',0),(465,'paymentmethod_two_checkout_fee_percent','0',0),(466,'paymentmethod_two_checkout_merchant_code','',0),(467,'paymentmethod_two_checkout_secret_key','',0),(468,'paymentmethod_two_checkout_description','Payment for Invoice {invoice_number}',0),(469,'paymentmethod_two_checkout_currencies','USD, EUR, GBP',0),(470,'paymentmethod_two_checkout_test_mode_enabled','1',0),(471,'paymentmethod_two_checkout_default_selected','1',1),(472,'paymentmethod_two_checkout_initialized','1',1),(473,'localization_settings','true',1),(474,'auto_backup_enabled','0',1),(475,'auto_backup_every','7',1),(476,'last_auto_backup','',1),(477,'delete_backups_older_then','0',1),(478,'auto_backup_hour','6',1),(479,'aside_menu_active','{\"dashboard\":{\"id\":\"dashboard\",\"icon\":\"\",\"disabled\":\"false\",\"position\":1},\"customers\":{\"id\":\"customers\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"10\"},\"sales\":{\"id\":\"sales\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"15\",\"children\":{\"proposals\":{\"disabled\":\"false\",\"id\":\"proposals\",\"icon\":\"\",\"position\":\"5\"},\"estimates\":{\"disabled\":\"false\",\"id\":\"estimates\",\"icon\":\"\",\"position\":\"10\"},\"invoices\":{\"disabled\":\"false\",\"id\":\"invoices\",\"icon\":\"\",\"position\":\"15\"},\"payments\":{\"id\":\"payments\",\"disabled\":\"true\",\"icon\":\"\",\"position\":\"20\"},\"credit_notes\":{\"disabled\":\"false\",\"id\":\"credit_notes\",\"icon\":\"\",\"position\":\"25\"},\"items\":{\"disabled\":\"false\",\"id\":\"items\",\"icon\":\"\",\"position\":\"30\"},\"paymentsonaccount\":{\"disabled\":\"false\",\"id\":\"paymentsonaccount\",\"icon\":\"\",\"position\":\"35\"}}},\"subscriptions\":{\"id\":\"subscriptions\",\"icon\":\"\",\"disabled\":\"true\",\"position\":\"20\"},\"lims\":{\"id\":\"lims\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"25\",\"children\":{\"lims-subjects\":{\"disabled\":\"false\",\"id\":\"lims-subjects\",\"icon\":\"\",\"position\":\"5\"},\"lims-orders\":{\"disabled\":\"false\",\"id\":\"lims-orders\",\"icon\":\"\",\"position\":\"10\"},\"lims-contracts\":{\"disabled\":\"false\",\"id\":\"lims-contracts\",\"icon\":\"\",\"position\":\"15\"},\"lims-appointments\":{\"disabled\":\"false\",\"id\":\"lims-appointments\",\"icon\":\"\",\"position\":\"20\"},\"lims-tests-queue\":{\"disabled\":\"false\",\"id\":\"lims-tests-queue\",\"icon\":\"\",\"position\":\"25\"},\"lims-samples\":{\"disabled\":\"false\",\"id\":\"lims-samples\",\"icon\":\"\",\"position\":\"30\"}}},\"expenses\":{\"id\":\"expenses\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"30\"},\"contracts\":{\"id\":\"contracts\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"35\"},\"projects\":{\"id\":\"projects\",\"icon\":\"\",\"disabled\":\"true\",\"position\":\"40\"},\"tasks\":{\"id\":\"tasks\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"45\"},\"support\":{\"id\":\"support\",\"icon\":\"\",\"disabled\":\"true\",\"position\":\"50\"},\"leads\":{\"id\":\"leads\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"55\"},\"estimate_request\":{\"id\":\"estimate_request\",\"icon\":\"\",\"disabled\":\"true\",\"position\":\"60\"},\"knowledge-base\":{\"id\":\"knowledge-base\",\"icon\":\"\",\"disabled\":\"true\",\"position\":\"65\"},\"utilities\":{\"id\":\"utilities\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"70\",\"children\":{\"media\":{\"disabled\":\"false\",\"id\":\"media\",\"icon\":\"\",\"position\":\"5\"},\"bulk-pdf-exporter\":{\"disabled\":\"false\",\"id\":\"bulk-pdf-exporter\",\"icon\":\"\",\"position\":\"10\"},\"csv-export\":{\"disabled\":\"false\",\"id\":\"csv-export\",\"icon\":\"\",\"position\":\"15\"},\"calendar\":{\"disabled\":\"false\",\"id\":\"calendar\",\"icon\":\"\",\"position\":\"20\"},\"announcements\":{\"disabled\":\"false\",\"id\":\"announcements\",\"icon\":\"\",\"position\":\"25\"},\"goals-tracking\":{\"disabled\":\"false\",\"id\":\"goals-tracking\",\"icon\":\"\",\"position\":\"30\"},\"activity-log\":{\"disabled\":\"true\",\"id\":\"activity-log\",\"icon\":\"\",\"position\":\"35\"},\"surveys\":{\"disabled\":\"false\",\"id\":\"surveys\",\"icon\":\"\",\"position\":\"40\"},\"utility_backup\":{\"disabled\":\"false\",\"id\":\"utility_backup\",\"icon\":\"\",\"position\":\"45\"},\"ticket-pipe-log\":{\"disabled\":\"true\",\"id\":\"ticket-pipe-log\",\"icon\":\"\",\"position\":\"50\"}}},\"reports\":{\"id\":\"reports\",\"icon\":\"\",\"disabled\":\"false\",\"position\":\"75\",\"children\":{\"sales-reports\":{\"disabled\":\"false\",\"id\":\"sales-reports\",\"icon\":\"\",\"position\":\"5\"},\"expenses-reports\":{\"disabled\":\"false\",\"id\":\"expenses-reports\",\"icon\":\"\",\"position\":\"10\"},\"expenses-vs-income-reports\":{\"disabled\":\"false\",\"id\":\"expenses-vs-income-reports\",\"icon\":\"\",\"position\":\"15\"},\"leads-reports\":{\"disabled\":\"false\",\"id\":\"leads-reports\",\"icon\":\"\",\"position\":\"20\"},\"timesheets-reports\":{\"disabled\":\"true\",\"id\":\"timesheets-reports\",\"icon\":\"\",\"position\":\"25\"},\"knowledge-base-reports\":{\"disabled\":\"true\",\"id\":\"knowledge-base-reports\",\"icon\":\"\",\"position\":\"30\"},\"client-credit-balances\":{\"disabled\":\"false\",\"id\":\"client-credit-balances\",\"icon\":\"\",\"position\":\"35\"},\"reports-receipts\":{\"disabled\":\"false\",\"id\":\"reports-receipts\",\"icon\":\"\",\"position\":\"40\"}}}}',1),(480,'setup_menu_active','[]',1),(481,'survey_send_emails_per_cron_run','100',1),(482,'last_survey_send_cron','',1),(483,'theme_style','[]',1),(484,'theme_style_custom_admin_area','',1),(485,'theme_style_custom_clients_area','',1),(486,'theme_style_custom_clients_and_admin_area','',1),(487,'lims_barcode_prefix','SMP',1),(488,'lims_default_department','',1),(489,'lims_enable_contracts','1',1),(490,'lims_label_page_width_mm','210',1),(491,'lims_label_page_height_mm','297',1),(492,'lims_label_columns','3',1),(493,'lims_label_rows','8',1),(494,'lims_label_width_mm','70',1),(495,'lims_label_height_mm','35',1),(496,'lims_label_hgap_mm','5',1),(497,'lims_label_vgap_mm','5',1),(498,'lims_label_left_margin_mm','10',1),(499,'lims_label_top_margin_mm','10',1),(500,'lims_label_font_size','8',1),(501,'lims_label_barcode_height','12',1),(502,'lims_label_show_received','1',1),(503,'lims_label_show_sampletype','1',1),(504,'lims_label_show_analysis','1',1),(505,'lims_report_logo_max_w','80',1),(506,'lims_report_show_ranges','1',1),(507,'lims_report_show_flags','1',1),(508,'lims_report_footer','',1),(509,'lims_module_version','3.0.2',1),(510,'lims_report_font_family','dejavuserif',1),(511,'lims_report_font_size','10.5',1),(512,'lims_report_background_image','lims_6979ca51d40259.32617833.jpg',1),(513,'lims_report_footer_image','lims_6979ca1ac00d30.09164527.jpg',1),(514,'lims_report_footer_text','12 Omirou Ave, Office 103, Aradippou 7102, Larnaca, Tel: 24813812, Fax: 24531599, E-,ail: nutrilab@cytanet.com.cy',1),(515,'lims_report_show_signature','1',1),(516,'lims_report_signature_width_mm','42',1),(517,'lims_report_language_from_subject','1',1),(518,'lims_report_default_language','greek',1),(519,'lims_report_header_subtitle_el','ΧΗΜΙΚΟ - ΜΙΚΡΟΒΙΟΛΟΓΙΚΟ ΕΡΓΑΣΤΗΡΙΟ ΤΡΟΦΙΜΩΝ & ΠΟΤΩΝ',1),(520,'lims_report_header_subtitle_en','CHEMICAL AND MICROBIOLOGICAL LABORATORY FOR FOOD & BEVERAGES',1),(521,'lims_report_heading_el','ΕΚΘΕΣΗ ΕΡΓΑΣΤΗΡΙΟΥ',1),(522,'lims_report_heading_en','LABORATORY REPORT',1),(523,'lims_report_topright_line1','ΕΣΔ-15/1',1),(524,'lims_report_topright_line2','ΑΝΑΘ. 00',1),(525,'lims_report_footer_img_y','235',1),(526,'lims_report_footer_img_w','20',1),(527,'lims_report_logo_x','',1),(528,'lims_report_pdf_logo_y','',1),(529,'lims_report_footer_gap_mm','10',1),(530,'lims_report_footer_bottom_margin_mm','3',1),(531,'lims_report_footer_line_thickness_mm','0.4',1),(532,'lims_report_footer_line_x1_mm','20',1),(533,'lims_report_footer_line_x2_mm','190',1),(534,'lims_report_footer_line_offset_mm','2',1),(535,'lims_subject_prefix','SUB-',1),(536,'lims_subject_next_number','1003',1),(537,'lims_report_logo','lims_6979ca3d5dd113.50591466.png',1),(538,'lims_report_logo_width','90',1),(539,'lims_report_logo_y','8',1),(540,'lims_report_footer_img_x','100',1),(541,'lims_report_pre_footer_note_greek','*Οι σημειώσεις δέν εμπίπτουν στο Πεδίο Διαπίστευσης.',1),(542,'lims_report_footer_text_greek','Απαγορεύεται η μερική αναπαραγωγή της Εκθεσης. Η ολική αναπαραγωγή είναι δυνατή μετά από έγκριση του Εργαστηρίου',1),(543,'lims_report_pre_footer_note_english','',1),(544,'lims_report_footer_text_english','',1),(545,'lims_report_footer_line_color','#009600',1),(546,'receipt_number_prefix','RCPT-',1),(547,'next_receipt_number','3',1),(548,'receipt_auto_send_email','0',1),(549,'poa_receipts_regenerated','1',1),(550,'contactsplus_module_version','1.0.1',1);
/*!40000 ALTER TABLE `tbloptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpayment_attempts`
--

DROP TABLE IF EXISTS `tblpayment_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpayment_attempts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `fee` double NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpayment_attempts`
--

LOCK TABLES `tblpayment_attempts` WRITE;
/*!40000 ALTER TABLE `tblpayment_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblpayment_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpayment_modes`
--

DROP TABLE IF EXISTS `tblpayment_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpayment_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `show_on_pdf` int(11) NOT NULL DEFAULT 0,
  `invoices_only` int(11) NOT NULL DEFAULT 0,
  `expenses_only` int(11) NOT NULL DEFAULT 0,
  `selected_by_default` int(11) NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpayment_modes`
--

LOCK TABLES `tblpayment_modes` WRITE;
/*!40000 ALTER TABLE `tblpayment_modes` DISABLE KEYS */;
INSERT INTO `tblpayment_modes` VALUES (1,'Eurobank','IBAN : CY900050034900034901A6432102<br />\r\nSWIFT: HEBACY2N',1,0,0,1,1),(2,'Cash','',0,0,0,1,1),(3,'Banknote','',0,0,0,1,1);
/*!40000 ALTER TABLE `tblpayment_modes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpinned_projects`
--

DROP TABLE IF EXISTS `tblpinned_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpinned_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpinned_projects`
--

LOCK TABLES `tblpinned_projects` WRITE;
/*!40000 ALTER TABLE `tblpinned_projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblpinned_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpmc_contact_company`
--

DROP TABLE IF EXISTS `tblpmc_contact_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpmc_contact_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `billing` tinyint(1) NOT NULL DEFAULT 0,
  `notifications` tinyint(1) NOT NULL DEFAULT 1,
  `perms_json` text DEFAULT NULL,
  `email_notif_json` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_contact_client` (`contact_id`,`client_id`),
  KEY `idx_contact_id` (`contact_id`),
  KEY `idx_client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpmc_contact_company`
--

LOCK TABLES `tblpmc_contact_company` WRITE;
/*!40000 ALTER TABLE `tblpmc_contact_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblpmc_contact_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpmc_contacts`
--

DROP TABLE IF EXISTS `tblpmc_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpmc_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `has_portal_access` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpmc_contacts`
--

LOCK TABLES `tblpmc_contacts` WRITE;
/*!40000 ALTER TABLE `tblpmc_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblpmc_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpmc_contacts_bridge`
--

DROP TABLE IF EXISTS `tblpmc_contacts_bridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpmc_contacts_bridge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tblcontact_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `pmc_contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_core_client` (`tblcontact_id`,`client_id`),
  KEY `idx_pmc_contact` (`pmc_contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpmc_contacts_bridge`
--

LOCK TABLES `tblpmc_contacts_bridge` WRITE;
/*!40000 ALTER TABLE `tblpmc_contacts_bridge` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblpmc_contacts_bridge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproject_activity`
--

DROP TABLE IF EXISTS `tblproject_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproject_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `fullname` varchar(100) DEFAULT NULL,
  `visible_to_customer` int(11) NOT NULL DEFAULT 0,
  `description_key` varchar(191) NOT NULL COMMENT 'Language file key',
  `additional_data` mediumtext DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproject_activity`
--

LOCK TABLES `tblproject_activity` WRITE;
/*!40000 ALTER TABLE `tblproject_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproject_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproject_files`
--

DROP TABLE IF EXISTS `tblproject_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproject_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(191) NOT NULL,
  `original_file_name` longtext DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `visible_to_customer` tinyint(1) DEFAULT 0,
  `staffid` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `external` varchar(40) DEFAULT NULL,
  `external_link` mediumtext DEFAULT NULL,
  `thumbnail_link` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproject_files`
--

LOCK TABLES `tblproject_files` WRITE;
/*!40000 ALTER TABLE `tblproject_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproject_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproject_members`
--

DROP TABLE IF EXISTS `tblproject_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproject_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproject_members`
--

LOCK TABLES `tblproject_members` WRITE;
/*!40000 ALTER TABLE `tblproject_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproject_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproject_notes`
--

DROP TABLE IF EXISTS `tblproject_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproject_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `staff_id` int(11) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproject_notes`
--

LOCK TABLES `tblproject_notes` WRITE;
/*!40000 ALTER TABLE `tblproject_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproject_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproject_settings`
--

DROP TABLE IF EXISTS `tblproject_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproject_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproject_settings`
--

LOCK TABLES `tblproject_settings` WRITE;
/*!40000 ALTER TABLE `tblproject_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproject_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblprojectdiscussioncomments`
--

DROP TABLE IF EXISTS `tblprojectdiscussioncomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblprojectdiscussioncomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) NOT NULL,
  `discussion_type` varchar(10) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `staff_id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT 0,
  `fullname` varchar(191) DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `file_mime_type` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblprojectdiscussioncomments`
--

LOCK TABLES `tblprojectdiscussioncomments` WRITE;
/*!40000 ALTER TABLE `tblprojectdiscussioncomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblprojectdiscussioncomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblprojectdiscussions`
--

DROP TABLE IF EXISTS `tblprojectdiscussions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblprojectdiscussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` mediumtext NOT NULL,
  `show_to_customer` tinyint(1) NOT NULL DEFAULT 0,
  `datecreated` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblprojectdiscussions`
--

LOCK TABLES `tblprojectdiscussions` WRITE;
/*!40000 ALTER TABLE `tblprojectdiscussions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblprojectdiscussions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblprojects`
--

DROP TABLE IF EXISTS `tblprojects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblprojects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `clientid` int(11) NOT NULL,
  `billing_type` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `project_created` date NOT NULL,
  `date_finished` datetime DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `progress_from_tasks` int(11) NOT NULL DEFAULT 1,
  `project_cost` decimal(15,2) DEFAULT NULL,
  `project_rate_per_hour` decimal(15,2) DEFAULT NULL,
  `estimated_hours` decimal(15,2) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `contact_notification` int(11) DEFAULT 1,
  `notify_contacts` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblprojects`
--

LOCK TABLES `tblprojects` WRITE;
/*!40000 ALTER TABLE `tblprojects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblprojects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproposal_comments`
--

DROP TABLE IF EXISTS `tblproposal_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproposal_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext DEFAULT NULL,
  `proposalid` int(11) NOT NULL,
  `staffid` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproposal_comments`
--

LOCK TABLES `tblproposal_comments` WRITE;
/*!40000 ALTER TABLE `tblproposal_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproposal_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblproposals`
--

DROP TABLE IF EXISTS `tblproposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblproposals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `total_tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `adjustment` decimal(15,2) DEFAULT NULL,
  `discount_percent` decimal(15,2) NOT NULL,
  `discount_total` decimal(15,2) NOT NULL,
  `discount_type` varchar(30) DEFAULT NULL,
  `show_quantity_as` int(11) NOT NULL DEFAULT 1,
  `currency` int(11) NOT NULL,
  `open_till` date DEFAULT NULL,
  `date` date NOT NULL,
  `rel_id` int(11) DEFAULT NULL,
  `rel_type` varchar(40) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `proposal_to` varchar(191) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `country` int(11) NOT NULL DEFAULT 0,
  `zip` varchar(50) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL,
  `estimate_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `date_converted` datetime DEFAULT NULL,
  `pipeline_order` int(11) DEFAULT 1,
  `is_expiry_notified` int(11) NOT NULL DEFAULT 0,
  `acceptance_firstname` varchar(50) DEFAULT NULL,
  `acceptance_lastname` varchar(50) DEFAULT NULL,
  `acceptance_email` varchar(100) DEFAULT NULL,
  `acceptance_date` datetime DEFAULT NULL,
  `acceptance_ip` varchar(40) DEFAULT NULL,
  `signature` varchar(40) DEFAULT NULL,
  `short_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblproposals`
--

LOCK TABLES `tblproposals` WRITE;
/*!40000 ALTER TABLE `tblproposals` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblproposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblreceipt_invoice_applications`
--

DROP TABLE IF EXISTS `tblreceipt_invoice_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblreceipt_invoice_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_record_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `receipt_id` (`receipt_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_record_id` (`payment_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblreceipt_invoice_applications`
--

LOCK TABLES `tblreceipt_invoice_applications` WRITE;
/*!40000 ALTER TABLE `tblreceipt_invoice_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblreceipt_invoice_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblreceiptemaillogs`
--

DROP TABLE IF EXISTS `tblreceiptemaillogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblreceiptemaillogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `recipient` varchar(255) NOT NULL,
  `date_sent` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblreceiptemaillogs`
--

LOCK TABLES `tblreceiptemaillogs` WRITE;
/*!40000 ALTER TABLE `tblreceiptemaillogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblreceiptemaillogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblreceipts`
--

DROP TABLE IF EXISTS `tblreceipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblreceipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_payment_id` int(11) DEFAULT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `date_created` datetime NOT NULL,
  `invoices_applied` text DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_source_payment_id` (`source_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblreceipts`
--

LOCK TABLES `tblreceipts` WRITE;
/*!40000 ALTER TABLE `tblreceipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblreceipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblrelated_items`
--

DROP TABLE IF EXISTS `tblrelated_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblrelated_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(30) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblrelated_items`
--

LOCK TABLES `tblrelated_items` WRITE;
/*!40000 ALTER TABLE `tblrelated_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblrelated_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblreminders`
--

DROP TABLE IF EXISTS `tblreminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblreminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` mediumtext DEFAULT NULL,
  `date` datetime NOT NULL,
  `isnotified` int(11) NOT NULL DEFAULT 0,
  `rel_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `rel_type` varchar(40) NOT NULL,
  `notify_by_email` int(11) NOT NULL DEFAULT 1,
  `creator` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`),
  KEY `staff` (`staff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblreminders`
--

LOCK TABLES `tblreminders` WRITE;
/*!40000 ALTER TABLE `tblreminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblreminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblroles`
--

DROP TABLE IF EXISTS `tblroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblroles` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `permissions` longtext DEFAULT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblroles`
--

LOCK TABLES `tblroles` WRITE;
/*!40000 ALTER TABLE `tblroles` DISABLE KEYS */;
INSERT INTO `tblroles` VALUES (1,'Employee',NULL);
/*!40000 ALTER TABLE `tblroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsales_activity`
--

DROP TABLE IF EXISTS `tblsales_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsales_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_type` varchar(20) DEFAULT NULL,
  `rel_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `additional_data` mediumtext DEFAULT NULL,
  `staffid` varchar(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsales_activity`
--

LOCK TABLES `tblsales_activity` WRITE;
/*!40000 ALTER TABLE `tblsales_activity` DISABLE KEYS */;
INSERT INTO `tblsales_activity` VALUES (5,'invoice',3,'invoice_activity_created','','2','Andreas Christofi','2026-03-26 17:38:27'),(6,'invoice',3,'invoice_activity_status_updated','a:2:{i:0;s:36:\"<original_status>6</original_status>\";i:1;s:26:\"<new_status>1</new_status>\";}','2','Andreas Christofi','2026-03-26 17:41:56'),(7,'invoice',3,'invoice_activity_marked_as_sent','a:0:{}','2','Andreas Christofi','2026-03-26 17:41:56');
/*!40000 ALTER TABLE `tblsales_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblscheduled_emails`
--

DROP TABLE IF EXISTS `tblscheduled_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblscheduled_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(15) NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `contacts` varchar(197) NOT NULL,
  `cc` mediumtext DEFAULT NULL,
  `attach_pdf` tinyint(1) NOT NULL DEFAULT 1,
  `template` varchar(197) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblscheduled_emails`
--

LOCK TABLES `tblscheduled_emails` WRITE;
/*!40000 ALTER TABLE `tblscheduled_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblscheduled_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservices`
--

DROP TABLE IF EXISTS `tblservices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservices` (
  `serviceid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`serviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservices`
--

LOCK TABLES `tblservices` WRITE;
/*!40000 ALTER TABLE `tblservices` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsessions`
--

DROP TABLE IF EXISTS `tblsessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsessions`
--

LOCK TABLES `tblsessions` WRITE;
/*!40000 ALTER TABLE `tblsessions` DISABLE KEYS */;
INSERT INTO `tblsessions` VALUES ('048fee337d7698606bdb904c7842b476','213.207.177.141',1772628341,'__ci_last_regenerate|i:1772628341;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('051b9a9b20297eecf077f573e3ccbfe4','213.7.198.114',1776257289,'__ci_last_regenerate|i:1776257283;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";_prev_url|s:69:\"https://crm.nutrilab.com.cy/admin/clients/client/3?group=contactsplus\";'),('056dd17af2b94c549fc9a0a3d5231944','213.207.177.141',1774607791,'__ci_last_regenerate|i:1774607791;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/orders/view/3\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('05e4c20677b64a82ff505bd06c68c662','213.207.177.141',1774535705,'__ci_last_regenerate|i:1774535705;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('0a32ec763d80a5bc1d37ce8f3318c921','213.207.177.141',1772619353,'__ci_last_regenerate|i:1772619353;_prev_url|s:41:\"https://crm.nutrilab.com.cy/admin/clients\";staff_user_id|s:1:\"6\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('0a73fdb206894a690450ede20667aab8','213.7.198.114',1769587570,'__ci_last_regenerate|i:1769587570;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/settings?group=email\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('0b218d51f49efc679611ec2f7d2ef456','213.7.198.114',1770283693,'__ci_last_regenerate|i:1770283675;staff_user_id|s:1:\"1\";staff_logged_in|b:1;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:57:\"https://crm.nutrilab.com.cy/admin/lims/sampletypes/create\";setup-menu-open|b:1;'),('0e6ec250ee70fd53bca77df3f8e9e26a','213.207.177.141',1774607793,'__ci_last_regenerate|i:1774607791;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('0f62eba6e94d633c30e07cbac62c7674','213.7.198.114',1774536768,'__ci_last_regenerate|i:1774536768;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;_prev_url|s:50:\"https://crm.nutrilab.com.cy/admin/lims/departments\";'),('184f05da42375b29338375b83101c7e6','213.7.198.114',1769588930,'__ci_last_regenerate|i:1769588930;_prev_url|s:41:\"https://crm.nutrilab.com.cy/admin/modules\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('1888dfc0a769a8e9577dec289e138634','213.7.198.114',1769587908,'__ci_last_regenerate|i:1769587908;_prev_url|s:57:\"https://crm.nutrilab.com.cy/admin/settings?group=invoices\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('199efc93bb398acd4455a2f0f40c027b','213.207.177.141',1772622336,'__ci_last_regenerate|i:1772622334;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"6\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('2151b9c1aa9f94cba9518c519fdee800','213.207.177.141',1774534472,'__ci_last_regenerate|i:1774534472;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('2a754a18c8dc9f1d1ecd149991e18a03','213.207.177.141',1774605269,'__ci_last_regenerate|i:1774605269;_prev_url|s:45:\"https://crm.nutrilab.com.cy/admin/lims/orders\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('2b2887e59b93aaf888870dc939380ca1','213.7.198.114',1769596843,'__ci_last_regenerate|i:1769596843;_prev_url|s:46:\"https://crm.nutrilab.com.cy/admin/staff/member\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('2bbeba981d6c2a724bf790733868e860','213.207.177.141',1774604965,'__ci_last_regenerate|i:1774604965;_prev_url|s:41:\"https://crm.nutrilab.com.cy/admin/clients\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('2ebcb997009d09259246bea99df1cb0a','167.172.18.148',1774007863,'__ci_last_regenerate|i:1774007860;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('30d705a703067a1a6cbba6cf9f0c121d','213.207.177.141',1774538894,'__ci_last_regenerate|i:1774538894;_prev_url|s:44:\"https://crm.nutrilab.com.cy/admin/lims/tests\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('311ad1b9afd3312285d3d93c4d30a7e2','213.207.177.141',1774606206,'__ci_last_regenerate|i:1774606206;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/tests/order/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('333dcba00663a8dc21a3ae27e7c9c811','213.7.198.114',1769589938,'__ci_last_regenerate|i:1769589938;_prev_url|s:57:\"https://crm.nutrilab.com.cy/admin/lims/sampletypes/create\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('33c6592b7e890a514e417321d473de47','18.202.56.225',1776256672,'__ci_last_regenerate|i:1776256672;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('365df4a01ce3d56a47b33f0d23f869d8','213.7.198.114',1774536427,'__ci_last_regenerate|i:1774536427;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;_prev_url|s:47:\"https://crm.nutrilab.com.cy/admin/lims/analyses\";message-success|s:14:\"Changes saved.\";__ci_vars|a:1:{s:15:\"message-success\";s:3:\"old\";}'),('3af6dd355e7cec70b0472b0ddda9c4b5','213.207.177.141',1772628342,'__ci_last_regenerate|i:1772628342;_prev_url|s:48:\"https://crm.nutrilab.com.cy/admin/authentication\";'),('3c9694fa11f10db8b3ac0708a6a2488e','138.197.190.243',1770353745,'__ci_last_regenerate|i:1770353745;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('421662ed7b8f52e01ef4ac9bbd628c1f','213.207.177.141',1772618541,'__ci_last_regenerate|i:1772618541;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('46f4730571e9f18ddf0a93ef7ab439db','44.248.197.209',1773275787,'__ci_last_regenerate|i:1773275786;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('4b82a9f07efb298f57da8682fd96e0ab','213.7.198.114',1776257283,'__ci_last_regenerate|i:1776257283;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";_prev_url|s:70:\"https://crm.nutrilab.com.cy/admin/clients/client/3?group=poa_statement\";'),('4bb9fdb3f9e6ffefddfa837e3c770f60','213.207.177.141',1774606508,'__ci_last_regenerate|i:1774606508;_prev_url|s:45:\"https://crm.nutrilab.com.cy/admin/lims/orders\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('51d0a454a2848ba914e0eaa2f9975ee5','213.7.198.114',1769596524,'__ci_last_regenerate|i:1769596524;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/subjects/create\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('53e7eb2b02922df8d1e1a7352fa00d66','213.7.198.114',1769590280,'__ci_last_regenerate|i:1769590280;_prev_url|s:39:\"https://crm.nutrilab.com.cy/admin/staff\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('57bf2ba014d6c718fc400c81dd83d745','213.7.198.114',1769592059,'__ci_last_regenerate|i:1769592059;_prev_url|s:64:\"https://crm.nutrilab.com.cy/admin/settings?group=lims-report-pdf\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('59469c670713f4e7ea7fa757457ff463','213.207.177.141',1774537011,'__ci_last_regenerate|i:1774537011;_prev_url|s:45:\"https://crm.nutrilab.com.cy/admin/lims/panels\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;message-success|s:47:\"Οι αλλαγές αποθηκεύτηκαν.\";__ci_vars|a:1:{s:15:\"message-success\";s:3:\"old\";}'),('70c357ea7d862d5e0372e0bdb2e0894c','213.207.177.141',1774540235,'__ci_last_regenerate|i:1774540235;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('7475f98deb0c31c274198841f94b0ea2','213.207.177.141',1774605594,'__ci_last_regenerate|i:1774605594;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/tests/order/2\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('74b0f3dfaa92219c1d7713492853c468','213.7.198.114',1772016653,'__ci_last_regenerate|i:1772016649;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";'),('760de07e215ab0a02467bed1463bad09','213.207.177.141',1774540241,'__ci_last_regenerate|i:1774540235;_prev_url|s:45:\"https://crm.nutrilab.com.cy/admin/lims/panels\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('7c859bc1921f2d6832d432046fa3bcc5','213.207.177.141',1774536408,'__ci_last_regenerate|i:1774536408;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/panels/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('7e8f824284dddd28ce4a027c83c2057a','213.207.177.141',1774605904,'__ci_last_regenerate|i:1774605904;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/tests/order/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('813e0e5a5533de5e70413a53c09841dd','213.207.177.141',1774601048,'__ci_last_regenerate|i:1774601048;_prev_url|s:56:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('8537a847bc42e9f13764b6e121cab1c2','213.207.177.141',1774539861,'__ci_last_regenerate|i:1774539861;_prev_url|s:58:\"https://crm.nutrilab.com.cy/admin/invoices/list_invoices/3\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('85acedce0da180810aa68500baf1df6e','213.7.198.114',1769588597,'__ci_last_regenerate|i:1769588597;_prev_url|s:55:\"https://crm.nutrilab.com.cy/admin/settings?group=pusher\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('8613b15170438d79fed7e58330324f2d','213.207.177.141',1774604303,'__ci_last_regenerate|i:1774604303;_prev_url|s:56:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('8bdac5babefd601013f33e581697d40d','213.207.177.141',1774537538,'__ci_last_regenerate|i:1774537538;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/subjects/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('929a24ed9ae678a0a9c92fc5276720bb','213.7.198.114',1769587248,'__ci_last_regenerate|i:1769587248;_prev_url|s:56:\"https://crm.nutrilab.com.cy/admin/settings?group=company\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('9509d8d2c997d5665bc8627a7f7e247e','213.207.177.141',1774600687,'__ci_last_regenerate|i:1774600687;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/panels/create/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('979dada98872aac885079439b4f8fade','213.7.198.114',1769590860,'__ci_last_regenerate|i:1769590860;_prev_url|s:39:\"https://crm.nutrilab.com.cy/admin/staff\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('98b1cf89691919bcfe3ecc3fb9c19729','34.241.181.59',1774538539,'__ci_last_regenerate|i:1774538539;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('98d0ecf2fcd408a5176158a08f3ea4b9','213.207.177.141',1774589091,'__ci_last_regenerate|i:1774589091;red_url|s:33:\"https://crm.nutrilab.com.cy/admin\";'),('99e049fed977fb80fd61c771c6071b4f','213.7.198.114',1774535899,'__ci_last_regenerate|i:1774535899;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";'),('9c4689a6029da02a529948f14bd20e65','44.228.129.119',1775692322,'__ci_last_regenerate|i:1775692322;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('9cb36819dff28806cf08eea730ca3303','213.207.177.141',1774538262,'__ci_last_regenerate|i:1774538262;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/orders/view/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('9d0d578a56749ea1a42736bf814d31b4','213.207.177.141',1774600269,'__ci_last_regenerate|i:1774600269;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('9fc790f321cea651c10df40e447673e7','213.7.198.114',1769589274,'__ci_last_regenerate|i:1769589274;_prev_url|s:64:\"https://crm.nutrilab.com.cy/admin/settings?group=lims-report-pdf\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('a3c19316089d56b9fc1b4b77d5bb43e1','134.122.47.232',1772811254,'__ci_last_regenerate|i:1772811251;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('a3e0af548585d5dbb8e78688ef8a525e','213.207.177.141',1774590610,'__ci_last_regenerate|i:1774590610;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/staff/edit_profile\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('a8ce44cc1f708f58f38a7f7243e9a727','157.245.106.121',1775232450,'__ci_last_regenerate|i:1775232445;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";'),('ab4da0554a3352177228697514b0d344','213.7.198.114',1769588210,'__ci_last_regenerate|i:1769588210;_prev_url|s:53:\"https://crm.nutrilab.com.cy/admin/settings?group=tags\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('b09b14666cc2ba36594769aad62e4813','213.207.177.141',1774534776,'__ci_last_regenerate|i:1774534776;_prev_url|s:50:\"https://crm.nutrilab.com.cy/admin/lims/sampletypes\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;message-success|s:47:\"Οι αλλαγές αποθηκεύτηκαν.\";__ci_vars|a:1:{s:15:\"message-success\";s:3:\"old\";}'),('b3306ce598853a8ef3c86200f697b023','213.207.177.141',1774538572,'__ci_last_regenerate|i:1774538572;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/tests/order/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('b33c84401b8ddc8b4ce98f1bee5981b2','213.7.198.114',1776256980,'__ci_last_regenerate|i:1776256980;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/subjects/view/2\";message-success|s:20:\" added successfully.\";__ci_vars|a:1:{s:15:\"message-success\";s:3:\"old\";}'),('ba692186c2e86db0fbb87fe446c86631','213.7.198.114',1771921302,'__ci_last_regenerate|i:1771921232;staff_user_id|s:1:\"1\";staff_logged_in|b:1;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:39:\"https://crm.nutrilab.com.cy/admin/staff\";setup-menu-open|b:1;'),('c6382ede62553e7a9bc811d14e61c332','213.207.177.141',1774604655,'__ci_last_regenerate|i:1774604655;_prev_url|s:47:\"https://crm.nutrilab.com.cy/admin/lims/analyses\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('c6ba371bedac406131028c8262671463','213.207.177.141',1772622334,'__ci_last_regenerate|i:1772622334;_prev_url|s:48:\"https://crm.nutrilab.com.cy/admin/lims/contracts\";staff_user_id|s:1:\"6\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('cdf6d251f5466cd8ef95b003db58d79f','213.207.177.141',1774537847,'__ci_last_regenerate|i:1774537847;_prev_url|s:45:\"https://crm.nutrilab.com.cy/admin/lims/panels\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('d4148017c33be2cf68de3c427811d277','213.207.177.141',1774535272,'__ci_last_regenerate|i:1774535272;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('d42916e8df259d6cf1d85addb2373a8e','213.207.177.141',1774539210,'__ci_last_regenerate|i:1774539210;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/orders/view/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('d9d132cf35dfcda08f1ff90e6dc87650','213.7.198.114',1769591297,'__ci_last_regenerate|i:1769591297;_prev_url|s:39:\"https://crm.nutrilab.com.cy/admin/staff\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;'),('e1fe4530d88d854436a077e08699040d','213.207.177.141',1774536710,'__ci_last_regenerate|i:1774536710;_prev_url|s:47:\"https://crm.nutrilab.com.cy/admin/lims/subjects\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('e4eceacd456b46ffb569e5f79b6ee184','213.207.177.141',1774539511,'__ci_last_regenerate|i:1774539511;_prev_url|s:52:\"https://crm.nutrilab.com.cy/admin/lims/orders/view/1\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";message-success|s:67:\"Τιμολόγιο δημιουργήθηκε με επιτυχία\";__ci_vars|a:1:{s:15:\"message-success\";s:3:\"old\";}'),('ec8add14014d0070a63d6de6257b4797','213.7.198.114',1774536797,'__ci_last_regenerate|i:1774536768;staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|b:1;_prev_url|s:47:\"https://crm.nutrilab.com.cy/admin/lims/partners\";'),('ef010411bbcc521617b3a9628c924221','213.207.177.141',1774536007,'__ci_last_regenerate|i:1774536007;_prev_url|s:54:\"https://crm.nutrilab.com.cy/admin/lims/analyses/create\";staff_user_id|s:1:\"2\";staff_logged_in|b:1;setup-menu-open|b:1;'),('f4e2232dff9153af1e662cd81c6b52ac','213.7.198.114',1769597096,'__ci_last_regenerate|i:1769596843;_prev_url|s:33:\"https://crm.nutrilab.com.cy/admin\";staff_user_id|s:1:\"1\";staff_logged_in|b:1;setup-menu-open|s:0:\"\";'),('f9d4e47c5c7a4713e6adf54531194e1c','46.101.185.52',1771597166,'__ci_last_regenerate|i:1771597165;red_url|s:28:\"https://crm.nutrilab.com.cy/\";_prev_url|s:48:\"https://crm.nutrilab.com.cy/authentication/login\";');
/*!40000 ALTER TABLE `tblsessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblshared_customer_files`
--

DROP TABLE IF EXISTS `tblshared_customer_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblshared_customer_files` (
  `file_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblshared_customer_files`
--

LOCK TABLES `tblshared_customer_files` WRITE;
/*!40000 ALTER TABLE `tblshared_customer_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblshared_customer_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblspam_filters`
--

DROP TABLE IF EXISTS `tblspam_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblspam_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) NOT NULL,
  `rel_type` varchar(10) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblspam_filters`
--

LOCK TABLES `tblspam_filters` WRITE;
/*!40000 ALTER TABLE `tblspam_filters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblspam_filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstaff`
--

DROP TABLE IF EXISTS `tblstaff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstaff` (
  `staffid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `facebook` longtext DEFAULT NULL,
  `linkedin` longtext DEFAULT NULL,
  `phonenumber` varchar(30) DEFAULT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `datecreated` datetime NOT NULL,
  `profile_image` varchar(191) DEFAULT NULL,
  `last_ip` varchar(40) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `new_pass_key` varchar(32) DEFAULT NULL,
  `new_pass_key_requested` datetime DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT 0,
  `role` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `default_language` varchar(40) DEFAULT NULL,
  `direction` varchar(3) DEFAULT NULL,
  `media_path_slug` varchar(191) DEFAULT NULL,
  `is_not_staff` int(11) NOT NULL DEFAULT 0,
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `two_factor_auth_enabled` tinyint(1) DEFAULT 0,
  `two_factor_auth_code` varchar(100) DEFAULT NULL,
  `two_factor_auth_code_requested` datetime DEFAULT NULL,
  `email_signature` mediumtext DEFAULT NULL,
  `google_auth_secret` mediumtext DEFAULT NULL,
  `lims_signature` longtext DEFAULT NULL,
  PRIMARY KEY (`staffid`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstaff`
--

LOCK TABLES `tblstaff` WRITE;
/*!40000 ALTER TABLE `tblstaff` DISABLE KEYS */;
INSERT INTO `tblstaff` VALUES (1,'isaias@eadvertise.eu','Super','Admin','','','','','$2a$08$fj4sMtXVbXT1dh4NXeo/seiUzPf3WxeI0pH3GZ2qWmlpO3/Nh96Yy','2026-01-28 07:55:27',NULL,'213.7.198.114','2026-04-15 15:31:04','2026-04-15 15:48:09',NULL,NULL,NULL,1,0,1,'','',NULL,0,0.00,0,NULL,NULL,'',NULL,NULL),(2,'andreas.c@nutrilab.com.cy','Andreas','Christofi','','','+35799086908','','$2a$08$idkPDkgZimQM.tra.7WvtOGdFQK8edhzH//qoyAr7v5f3s295Ykg.','2026-01-28 10:46:44',NULL,'213.207.177.141','2026-03-27 07:24:55','2026-03-27 12:36:33',NULL,NULL,NULL,1,1,1,'greek','','andreas-christofi',0,0.00,0,NULL,NULL,'',NULL,NULL),(3,'elena.c@nutrilab.com.cy','Elena','Charalambous','','','','','$2a$08$0kdVwDwL9mR1V1QQ0VPk6uO/6bnTAsnCAvSJGviknNMo5nFfjZffy','2026-01-28 10:48:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'english','','elena-charalambous',0,0.00,0,NULL,NULL,'',NULL,NULL),(4,'maria.c@nutrilab.com.cy','Maria','Chrysostomou','','','+35796394405','','$2a$08$ILFz5jSmGJyE8tzo84eZxuE46G30RKlSnuuSISqE1orNEFEszDsBK','2026-01-28 10:51:20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,1,'','','maria-chrysostomou',0,0.00,0,NULL,NULL,'',NULL,NULL),(5,'elena.k@nutrilab.com.cy','Elena','Kiprianou','','','+35795519976','','$2a$08$TCx0oB3f4b7loRXDVWxEm.cp18wY3mVgmaT2S3PFVt1.D5g7/wLlK','2026-01-28 12:39:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,1,'','','elena-kiprianou',0,0.00,0,NULL,NULL,'',NULL,NULL),(6,'eleni.m@nutrilab.com.cy','Eleni','Michael','','','','','$2a$08$SDlgVeWTPDCre2lc2rz1qeLHF9nuXn/JeuaKVkBrnU45p4sn4toki','2026-01-28 12:40:43',NULL,'213.207.177.141','2026-03-04 12:11:30','2026-03-04 13:05:36',NULL,NULL,NULL,1,1,1,'','','eleni-michael',0,0.00,0,NULL,NULL,'',NULL,NULL);
/*!40000 ALTER TABLE `tblstaff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstaff_departments`
--

DROP TABLE IF EXISTS `tblstaff_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstaff_departments` (
  `staffdepartmentid` int(11) NOT NULL AUTO_INCREMENT,
  `staffid` int(11) NOT NULL,
  `departmentid` int(11) NOT NULL,
  PRIMARY KEY (`staffdepartmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstaff_departments`
--

LOCK TABLES `tblstaff_departments` WRITE;
/*!40000 ALTER TABLE `tblstaff_departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblstaff_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstaff_permissions`
--

DROP TABLE IF EXISTS `tblstaff_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstaff_permissions` (
  `staff_id` int(11) NOT NULL,
  `feature` varchar(40) NOT NULL,
  `capability` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstaff_permissions`
--

LOCK TABLES `tblstaff_permissions` WRITE;
/*!40000 ALTER TABLE `tblstaff_permissions` DISABLE KEYS */;
INSERT INTO `tblstaff_permissions` VALUES (4,'items','view'),(4,'staff','view'),(4,'checklist_templates','create'),(4,'checklist_templates','delete'),(4,'leads','view'),(4,'lims','view'),(4,'lims','manage_orders'),(4,'lims','manage_samples'),(4,'lims','enter_results'),(4,'lims','verify'),(4,'lims','approve'),(4,'lims','qc_manage'),(4,'lims','appointments'),(4,'lims','admin'),(5,'credit_notes','view_own'),(5,'credit_notes','create'),(5,'credit_notes','edit'),(5,'credit_notes','delete'),(5,'customers','view'),(5,'customers','create'),(5,'customers','edit'),(5,'customers','delete'),(5,'estimates','view'),(5,'estimates','create'),(5,'estimates','edit'),(5,'estimates','delete'),(5,'expenses','view'),(5,'expenses','create'),(5,'expenses','edit'),(5,'expenses','delete'),(5,'invoices','view'),(5,'invoices','create'),(5,'invoices','edit'),(5,'invoices','delete'),(5,'items','view'),(5,'items','create'),(5,'items','edit'),(5,'items','delete'),(5,'payments','view'),(5,'payments','create'),(5,'payments','edit'),(5,'payments','delete'),(5,'proposals','view'),(5,'proposals','create'),(5,'proposals','edit'),(5,'proposals','delete'),(5,'proposals','view_all_templates'),(5,'reports','view'),(5,'reports','view-timesheets'),(5,'roles','view'),(5,'roles','create'),(5,'roles','edit'),(5,'roles','delete'),(5,'settings','view'),(5,'settings','edit'),(5,'staff','view'),(5,'staff','create'),(5,'staff','edit'),(5,'staff','delete'),(5,'tasks','view'),(5,'tasks','create'),(5,'tasks','edit'),(5,'tasks','delete'),(5,'tasks','edit_timesheet'),(5,'tasks','edit_own_timesheet'),(5,'tasks','delete_timesheet'),(5,'tasks','delete_own_timesheet'),(5,'checklist_templates','create'),(5,'checklist_templates','delete'),(5,'leads','view'),(5,'leads','delete'),(5,'lims','view'),(5,'lims','manage_orders'),(5,'lims','manage_samples'),(5,'lims','enter_results'),(5,'lims','verify'),(5,'lims','approve'),(5,'lims','billing'),(5,'lims','appointments'),(5,'lims','admin'),(5,'goals','view'),(5,'goals','create'),(5,'goals','edit'),(5,'goals','delete'),(5,'surveys','view'),(5,'surveys','create'),(5,'surveys','edit'),(5,'surveys','delete');
/*!40000 ALTER TABLE `tblstaff_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsubscriptions`
--

DROP TABLE IF EXISTS `tblsubscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsubscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `description_in_item` tinyint(1) NOT NULL DEFAULT 0,
  `clientid` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `terms` mediumtext DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `stripe_tax_id` varchar(50) DEFAULT NULL,
  `tax_id_2` int(11) NOT NULL DEFAULT 0,
  `stripe_tax_id_2` varchar(50) DEFAULT NULL,
  `stripe_plan_id` mediumtext DEFAULT NULL,
  `stripe_subscription_id` mediumtext NOT NULL,
  `next_billing_cycle` bigint(20) DEFAULT NULL,
  `ends_at` bigint(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `hash` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `created_from` int(11) NOT NULL,
  `date_subscribed` datetime DEFAULT NULL,
  `in_test_environment` int(11) DEFAULT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `currency` (`currency`),
  KEY `tax_id` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsubscriptions`
--

LOCK TABLES `tblsubscriptions` WRITE;
/*!40000 ALTER TABLE `tblsubscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblsubscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsurveyresultsets`
--

DROP TABLE IF EXISTS `tblsurveyresultsets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsurveyresultsets` (
  `resultsetid` int(11) NOT NULL AUTO_INCREMENT,
  `surveyid` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `useragent` varchar(150) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`resultsetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsurveyresultsets`
--

LOCK TABLES `tblsurveyresultsets` WRITE;
/*!40000 ALTER TABLE `tblsurveyresultsets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblsurveyresultsets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsurveys`
--

DROP TABLE IF EXISTS `tblsurveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsurveys` (
  `surveyid` int(11) NOT NULL AUTO_INCREMENT,
  `subject` mediumtext NOT NULL,
  `slug` mediumtext NOT NULL,
  `description` text NOT NULL,
  `viewdescription` text DEFAULT NULL,
  `datecreated` datetime NOT NULL,
  `redirect_url` varchar(100) DEFAULT NULL,
  `send` tinyint(1) NOT NULL DEFAULT 0,
  `onlyforloggedin` int(11) DEFAULT 0,
  `fromname` varchar(100) DEFAULT NULL,
  `iprestrict` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `hash` varchar(32) NOT NULL,
  PRIMARY KEY (`surveyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsurveys`
--

LOCK TABLES `tblsurveys` WRITE;
/*!40000 ALTER TABLE `tblsurveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblsurveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsurveysemailsendcron`
--

DROP TABLE IF EXISTS `tblsurveysemailsendcron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsurveysemailsendcron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surveyid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `emailid` int(11) DEFAULT NULL,
  `listid` varchar(11) DEFAULT NULL,
  `log_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsurveysemailsendcron`
--

LOCK TABLES `tblsurveysemailsendcron` WRITE;
/*!40000 ALTER TABLE `tblsurveysemailsendcron` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblsurveysemailsendcron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsurveysendlog`
--

DROP TABLE IF EXISTS `tblsurveysendlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsurveysendlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surveyid` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `iscronfinished` int(11) NOT NULL DEFAULT 0,
  `send_to_mail_lists` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsurveysendlog`
--

LOCK TABLES `tblsurveysendlog` WRITE;
/*!40000 ALTER TABLE `tblsurveysendlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblsurveysendlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltaggables`
--

DROP TABLE IF EXISTS `tbltaggables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltaggables` (
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(20) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `tag_order` int(11) NOT NULL DEFAULT 0,
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltaggables`
--

LOCK TABLES `tbltaggables` WRITE;
/*!40000 ALTER TABLE `tbltaggables` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltaggables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltags`
--

DROP TABLE IF EXISTS `tbltags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltags`
--

LOCK TABLES `tbltags` WRITE;
/*!40000 ALTER TABLE `tbltags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltask_assigned`
--

DROP TABLE IF EXISTS `tbltask_assigned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltask_assigned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staffid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `assigned_from` int(11) NOT NULL DEFAULT 0,
  `is_assigned_from_contact` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `taskid` (`taskid`),
  KEY `staffid` (`staffid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltask_assigned`
--

LOCK TABLES `tbltask_assigned` WRITE;
/*!40000 ALTER TABLE `tbltask_assigned` DISABLE KEYS */;
INSERT INTO `tbltask_assigned` VALUES (1,4,1,2,0),(2,4,2,2,0);
/*!40000 ALTER TABLE `tbltask_assigned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltask_checklist_items`
--

DROP TABLE IF EXISTS `tbltask_checklist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltask_checklist_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskid` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `finished` int(11) NOT NULL DEFAULT 0,
  `dateadded` datetime NOT NULL,
  `addedfrom` int(11) NOT NULL,
  `finished_from` int(11) DEFAULT 0,
  `list_order` int(11) NOT NULL DEFAULT 0,
  `assigned` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltask_checklist_items`
--

LOCK TABLES `tbltask_checklist_items` WRITE;
/*!40000 ALTER TABLE `tbltask_checklist_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltask_checklist_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltask_comments`
--

DROP TABLE IF EXISTS `tbltask_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltask_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext NOT NULL,
  `taskid` int(11) NOT NULL,
  `staffid` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `file_id` int(11) NOT NULL DEFAULT 0,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltask_comments`
--

LOCK TABLES `tbltask_comments` WRITE;
/*!40000 ALTER TABLE `tbltask_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltask_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltask_followers`
--

DROP TABLE IF EXISTS `tbltask_followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltask_followers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staffid` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltask_followers`
--

LOCK TABLES `tbltask_followers` WRITE;
/*!40000 ALTER TABLE `tbltask_followers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltask_followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltasks`
--

DROP TABLE IF EXISTS `tbltasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  `startdate` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `datefinished` datetime DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `is_added_from_contact` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `recurring_type` varchar(10) DEFAULT NULL,
  `repeat_every` int(11) DEFAULT NULL,
  `recurring` int(11) NOT NULL DEFAULT 0,
  `is_recurring_from` int(11) DEFAULT NULL,
  `cycles` int(11) NOT NULL DEFAULT 0,
  `total_cycles` int(11) NOT NULL DEFAULT 0,
  `custom_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `last_recurring_date` date DEFAULT NULL,
  `rel_id` int(11) DEFAULT NULL,
  `rel_type` varchar(30) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `billable` tinyint(1) NOT NULL DEFAULT 0,
  `billed` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_id` int(11) NOT NULL DEFAULT 0,
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `milestone` int(11) DEFAULT 0,
  `kanban_order` int(11) DEFAULT 1,
  `milestone_order` int(11) NOT NULL DEFAULT 0,
  `visible_to_client` tinyint(1) NOT NULL DEFAULT 0,
  `deadline_notified` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `rel_id` (`rel_id`),
  KEY `rel_type` (`rel_type`),
  KEY `milestone` (`milestone`),
  KEY `kanban_order` (`kanban_order`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltasks`
--

LOCK TABLES `tbltasks` WRITE;
/*!40000 ALTER TABLE `tbltasks` DISABLE KEYS */;
INSERT INTO `tbltasks` VALUES (1,'Appointment for Order #1 (Home)','δfdf',2,'2026-03-26 17:31:31','2026-03-26','2026-03-26',NULL,2,0,4,NULL,NULL,0,NULL,0,0,0,NULL,1,'customer',1,1,0,0,0.00,0,1,0,0,0),(2,'Appointment for Order #1 (Home)','δfdf',2,'2026-03-26 17:31:32','2026-03-26','2026-03-26',NULL,2,0,4,NULL,NULL,0,NULL,0,0,0,NULL,1,'customer',1,1,0,0,0.00,0,1,0,0,0);
/*!40000 ALTER TABLE `tbltasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltasks_checklist_templates`
--

DROP TABLE IF EXISTS `tbltasks_checklist_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltasks_checklist_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltasks_checklist_templates`
--

LOCK TABLES `tbltasks_checklist_templates` WRITE;
/*!40000 ALTER TABLE `tbltasks_checklist_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltasks_checklist_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltaskstimers`
--

DROP TABLE IF EXISTS `tbltaskstimers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltaskstimers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `start_time` varchar(64) NOT NULL,
  `end_time` varchar(64) DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `note` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltaskstimers`
--

LOCK TABLES `tbltaskstimers` WRITE;
/*!40000 ALTER TABLE `tbltaskstimers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltaskstimers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltaxes`
--

DROP TABLE IF EXISTS `tbltaxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltaxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `taxrate` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltaxes`
--

LOCK TABLES `tbltaxes` WRITE;
/*!40000 ALTER TABLE `tbltaxes` DISABLE KEYS */;
INSERT INTO `tbltaxes` VALUES (1,'V.A.T. 19%',19.00);
/*!40000 ALTER TABLE `tbltaxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltemplates`
--

DROP TABLE IF EXISTS `tbltemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltemplates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `addedfrom` int(11) NOT NULL,
  `content` longtext DEFAULT NULL,
  `content_type` varchar(20) NOT NULL DEFAULT 'html',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltemplates`
--

LOCK TABLES `tbltemplates` WRITE;
/*!40000 ALTER TABLE `tbltemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticket_attachments`
--

DROP TABLE IF EXISTS `tblticket_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticket_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `replyid` int(11) DEFAULT NULL,
  `file_name` varchar(191) NOT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticket_attachments`
--

LOCK TABLES `tblticket_attachments` WRITE;
/*!40000 ALTER TABLE `tblticket_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticket_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticket_replies`
--

DROP TABLE IF EXISTS `tblticket_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `contactid` int(11) NOT NULL DEFAULT 0,
  `name` mediumtext DEFAULT NULL,
  `email` mediumtext DEFAULT NULL,
  `date` datetime NOT NULL,
  `message` mediumtext DEFAULT NULL,
  `attachment` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticket_replies`
--

LOCK TABLES `tblticket_replies` WRITE;
/*!40000 ALTER TABLE `tblticket_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticket_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets`
--

DROP TABLE IF EXISTS `tbltickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets` (
  `ticketid` int(11) NOT NULL AUTO_INCREMENT,
  `adminreplying` int(11) NOT NULL DEFAULT 0,
  `userid` int(11) NOT NULL,
  `contactid` int(11) NOT NULL DEFAULT 0,
  `merged_ticket_id` int(11) DEFAULT NULL,
  `email` mediumtext DEFAULT NULL,
  `name` mediumtext DEFAULT NULL,
  `department` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `service` int(11) DEFAULT NULL,
  `ticketkey` varchar(32) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `message` mediumtext DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `lastreply` datetime DEFAULT NULL,
  `clientread` int(11) NOT NULL DEFAULT 0,
  `adminread` int(11) NOT NULL DEFAULT 0,
  `assigned` int(11) NOT NULL DEFAULT 0,
  `staff_id_replying` int(11) DEFAULT NULL,
  `cc` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`ticketid`),
  KEY `service` (`service`),
  KEY `department` (`department`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `priority` (`priority`),
  KEY `project_id` (`project_id`),
  KEY `contactid` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets`
--

LOCK TABLES `tbltickets` WRITE;
/*!40000 ALTER TABLE `tbltickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets_pipe_log`
--

DROP TABLE IF EXISTS `tbltickets_pipe_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets_pipe_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `email_to` varchar(100) NOT NULL,
  `name` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `message` longtext NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets_pipe_log`
--

LOCK TABLES `tbltickets_pipe_log` WRITE;
/*!40000 ALTER TABLE `tbltickets_pipe_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltickets_pipe_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets_predefined_replies`
--

DROP TABLE IF EXISTS `tbltickets_predefined_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets_predefined_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets_predefined_replies`
--

LOCK TABLES `tbltickets_predefined_replies` WRITE;
/*!40000 ALTER TABLE `tbltickets_predefined_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltickets_predefined_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets_priorities`
--

DROP TABLE IF EXISTS `tbltickets_priorities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets_priorities` (
  `priorityid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`priorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets_priorities`
--

LOCK TABLES `tbltickets_priorities` WRITE;
/*!40000 ALTER TABLE `tbltickets_priorities` DISABLE KEYS */;
INSERT INTO `tbltickets_priorities` VALUES (1,'Low'),(2,'Medium'),(3,'High');
/*!40000 ALTER TABLE `tbltickets_priorities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets_status`
--

DROP TABLE IF EXISTS `tbltickets_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets_status` (
  `ticketstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `isdefault` int(11) NOT NULL DEFAULT 0,
  `statuscolor` varchar(7) DEFAULT NULL,
  `statusorder` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets_status`
--

LOCK TABLES `tbltickets_status` WRITE;
/*!40000 ALTER TABLE `tbltickets_status` DISABLE KEYS */;
INSERT INTO `tbltickets_status` VALUES (1,'Open',1,'#ff2d42',1),(2,'In progress',1,'#22c55e',2),(3,'Answered',1,'#2563eb',3),(4,'On Hold',1,'#64748b',4),(5,'Closed',1,'#03a9f4',5);
/*!40000 ALTER TABLE `tbltickets_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltodos`
--

DROP TABLE IF EXISTS `tbltodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltodos` (
  `todoid` int(11) NOT NULL AUTO_INCREMENT,
  `description` mediumtext NOT NULL,
  `staffid` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `finished` tinyint(1) NOT NULL,
  `datefinished` datetime DEFAULT NULL,
  `item_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`todoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltodos`
--

LOCK TABLES `tbltodos` WRITE;
/*!40000 ALTER TABLE `tbltodos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltodos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltracked_mails`
--

DROP TABLE IF EXISTS `tbltracked_mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltracked_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(40) NOT NULL,
  `date` datetime NOT NULL,
  `email` varchar(100) NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT 0,
  `date_opened` datetime DEFAULT NULL,
  `subject` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltracked_mails`
--

LOCK TABLES `tbltracked_mails` WRITE;
/*!40000 ALTER TABLE `tbltracked_mails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltracked_mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltwocheckout_log`
--

DROP TABLE IF EXISTS `tbltwocheckout_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltwocheckout_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(64) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` varchar(25) NOT NULL,
  `created_at` datetime NOT NULL,
  `attempt_reference` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `tbltwocheckout_log_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `tblinvoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltwocheckout_log`
--

LOCK TABLES `tbltwocheckout_log` WRITE;
/*!40000 ALTER TABLE `tbltwocheckout_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltwocheckout_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluser_auto_login`
--

DROP TABLE IF EXISTS `tbluser_auto_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluser_auto_login` (
  `key_id` char(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_agent` varchar(150) NOT NULL,
  `last_ip` varchar(40) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `staff` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluser_auto_login`
--

LOCK TABLES `tbluser_auto_login` WRITE;
/*!40000 ALTER TABLE `tbluser_auto_login` DISABLE KEYS */;
INSERT INTO `tbluser_auto_login` VALUES ('3c635156564d13f322ee0e9e2585b8b2',1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','213.7.198.114','2026-01-28 07:55:53',1),('f78eb8776969384049494807b2857199',6,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','213.207.177.141','2026-03-04 10:11:30',1);
/*!40000 ALTER TABLE `tbluser_auto_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluser_meta`
--

DROP TABLE IF EXISTS `tbluser_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluser_meta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `client_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `contact_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(191) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`umeta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluser_meta`
--

LOCK TABLES `tbluser_meta` WRITE;
/*!40000 ALTER TABLE `tbluser_meta` DISABLE KEYS */;
INSERT INTO `tbluser_meta` VALUES (1,1,0,0,'dashboard_widgets_visibility','a:11:{i:0;a:2:{s:2:\"id\";s:9:\"top_stats\";s:7:\"visible\";s:1:\"1\";}i:1;a:2:{s:2:\"id\";s:16:\"finance_overview\";s:7:\"visible\";s:1:\"1\";}i:2;a:2:{s:2:\"id\";s:9:\"user_data\";s:7:\"visible\";s:1:\"1\";}i:3;a:2:{s:2:\"id\";s:8:\"calendar\";s:7:\"visible\";s:1:\"1\";}i:4;a:2:{s:2:\"id\";s:14:\"payments_chart\";s:7:\"visible\";s:1:\"1\";}i:5;a:2:{s:2:\"id\";s:18:\"contracts_expiring\";s:7:\"visible\";s:1:\"0\";}i:6;a:2:{s:2:\"id\";s:14:\"tickets_report\";s:7:\"visible\";s:1:\"0\";}i:7;a:2:{s:2:\"id\";s:5:\"todos\";s:7:\"visible\";s:1:\"1\";}i:8;a:2:{s:2:\"id\";s:11:\"leads_chart\";s:7:\"visible\";s:1:\"1\";}i:9;a:2:{s:2:\"id\";s:14:\"projects_chart\";s:7:\"visible\";s:1:\"0\";}i:10;a:2:{s:2:\"id\";s:17:\"projects_activity\";s:7:\"visible\";s:1:\"0\";}}'),(2,0,0,1,'consent_key','f19f02687b08a094a289af462bee7d85-ef731a931038206654fa6cb604a3879f');
/*!40000 ALTER TABLE `tbluser_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblvault`
--

DROP TABLE IF EXISTS `tblvault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblvault` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `server_address` varchar(191) NOT NULL,
  `port` int(11) DEFAULT NULL,
  `username` varchar(191) NOT NULL,
  `password` mediumtext NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `creator` int(11) NOT NULL,
  `creator_name` varchar(100) DEFAULT NULL,
  `visibility` tinyint(1) NOT NULL DEFAULT 1,
  `share_in_projects` tinyint(1) NOT NULL DEFAULT 0,
  `last_updated` datetime DEFAULT NULL,
  `last_updated_from` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblvault`
--

LOCK TABLES `tblvault` WRITE;
/*!40000 ALTER TABLE `tblvault` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblvault` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblviews_tracking`
--

DROP TABLE IF EXISTS `tblviews_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblviews_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_id` int(11) NOT NULL,
  `rel_type` varchar(40) NOT NULL,
  `date` datetime NOT NULL,
  `view_ip` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblviews_tracking`
--

LOCK TABLES `tblviews_tracking` WRITE;
/*!40000 ALTER TABLE `tblviews_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblviews_tracking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblweb_to_lead`
--

DROP TABLE IF EXISTS `tblweb_to_lead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblweb_to_lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_key` varchar(32) NOT NULL,
  `lead_source` int(11) NOT NULL,
  `lead_status` int(11) NOT NULL,
  `notify_lead_imported` int(11) NOT NULL DEFAULT 1,
  `notify_type` varchar(20) DEFAULT NULL,
  `notify_ids` longtext DEFAULT NULL,
  `responsible` int(11) NOT NULL DEFAULT 0,
  `name` varchar(191) NOT NULL,
  `form_data` longtext DEFAULT NULL,
  `recaptcha` int(11) NOT NULL DEFAULT 0,
  `submit_btn_name` varchar(40) DEFAULT NULL,
  `submit_btn_text_color` varchar(10) DEFAULT '#ffffff',
  `submit_btn_bg_color` varchar(10) DEFAULT '#84c529',
  `success_submit_msg` mediumtext DEFAULT NULL,
  `submit_action` int(11) DEFAULT 0,
  `lead_name_prefix` varchar(255) DEFAULT NULL,
  `submit_redirect_url` longtext DEFAULT NULL,
  `language` varchar(40) DEFAULT NULL,
  `allow_duplicate` int(11) NOT NULL DEFAULT 1,
  `mark_public` int(11) NOT NULL DEFAULT 0,
  `track_duplicate_field` varchar(20) DEFAULT NULL,
  `track_duplicate_field_and` varchar(20) DEFAULT NULL,
  `create_task_on_duplicate` int(11) NOT NULL DEFAULT 0,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblweb_to_lead`
--

LOCK TABLES `tblweb_to_lead` WRITE;
/*!40000 ALTER TABLE `tblweb_to_lead` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblweb_to_lead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'eadcrm_nutrilab'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-15 14:57:15
