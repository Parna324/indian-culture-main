-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: indian_culture
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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `heritage_sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `heritage_sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `heritage_sites`
--

DROP TABLE IF EXISTS `heritage_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `heritage_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heritage_sites`
--

LOCK TABLES `heritage_sites` WRITE;
/*!40000 ALTER TABLE `heritage_sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `heritage_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `email_index` (`email`),
  KEY `user_id_index` (`user_id`),
  KEY `idx_token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (1,5,'parnaghosh628@gmail.com','86e7644fe7ed4dc9ea5cc244bb54f40309947d3df421e90049737088753398a8af5798914d1a175ef9a2b0c331d1bd488aa1','2025-04-22 07:36:28',0),(2,5,'parnaghosh628@gmail.com','04a04a70a5f7dd65db83df0fa32c1daddee6a2e8bfdf8d763a302707b14fe2b6aec96fd71af4f3a330f0a869bb965fcbb167','2025-04-22 08:15:46',0),(3,5,'parnaghosh628@gmail.com','ebe15af5c837c2c66b9be5c4b8e4bdf5f4b303eeec2e9fafaee2989e56550b5997fc84e620f34dc0d7eed726696ff893f835','2025-04-22 12:11:37',0),(4,5,'parnaghosh628@gmail.com','31f0beb5bc9d059b12429bfc2af5bdc202f3fa025edea6a5894b828c92e4447ce5a07d919b87bb6e7dee6debb1b6d10049e3','2025-04-22 12:36:07',0),(5,5,'parnaghosh628@gmail.com','24c1ca4d20673a8e5c9a95b8014441adc9ce2daba1db7763b822922642c08a9d8c21e80592cc3b676ede13742dbe43b62faa','2025-04-22 12:40:11',0),(6,5,'parnaghosh628@gmail.com','ef1ca441cde50a794c789cdbbea6203a489a55abe92248e73e14e4da47308d121b5adac846ed4d82cedbd07e815119f1f4e7','2025-04-22 12:40:14',0),(7,5,'parnaghosh628@gmail.com','f5e66438f7f09ec889d7fc6fb4738905d0fd50d5ab82207fd72815a07d096a113676d37cc3b8259eae84e955e539ae0c9f45','2025-04-22 12:44:49',0),(8,5,'parnaghosh628@gmail.com','d449f7d9e0ef8f1543ba9a6c766476ca09e31671d38ee1777e1a6f18e1af6d78e51e0e92fd73c9f8d24ff1810f28bc03ee9b','2025-04-22 12:46:43',0),(9,5,'parnaghosh628@gmail.com','2114d91dc82a32836625fbae809006390c62d0f8b842628902502378b9bf942f0f7fb272201529042498d0c53cf7562bfeb7','2025-04-23 08:17:00',0),(10,5,'parnaghosh628@gmail.com','28a31128d74447f4eb0b6841053f5db6c8a5e3bfee5a2fbbcdbb02288c73e4265268feba38450b6ee8b7da553c5f388d0522','2025-04-23 08:19:37',0),(11,5,'parnaghosh628@gmail.com','f69bdd88d5de24107d777e56fad68bfb9760094b8ce0c8ad16f796fed8a3ef43a55725c4e483cfe919b534aa3a7134e41220','2025-04-23 08:23:26',0),(12,5,'parnaghosh628@gmail.com','9f52d3a34e1005889d4ec7b3736ebcfee787940d7a212231cf428f83f9a3fcfadb6a0842c74ee868964ec88787794577edbc','2025-04-23 08:26:45',0),(13,5,'parnaghosh628@gmail.com','a9e15791fb809ff5de27888a2529a49478605a3de24278e43625edf1a5b3aea55a2195fa89098f0462dfefba524a04347b45','2025-04-23 18:48:36',0),(14,5,'parnaghosh628@gmail.com','7f05407674b09c3b97b2d9a263bbbc392880cf803ae9382cc128becaa5f0fb179769a096041ada57c69ca374cd927e063440','2025-04-23 18:49:38',0),(15,5,'parnaghosh628@gmail.com','007bbbf436fc50826d51236be894da335c77ecda60021cf55a7c16a247ca05cde7549db977180135e3d6ff51b87d4a3ce62f','2025-04-23 18:50:06',0),(16,5,'parnaghosh628@gmail.com','ce4fe199e222fde8620121b8c114289c7d9763b850ccacf2fb60efcc5922503b01ff5465d7953de35d43ee648a6045b0dabb','2025-04-23 18:57:52',0),(17,5,'parnaghosh628@gmail.com','8ab2322b0ca385ca8a3ab6b037a785f438dec5c3f650aa473e0d5eb3e41df582ed50f10a24cb8a1aa566a7299f683739adb2','2025-04-25 12:07:04',0);
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state_visits`
--

DROP TABLE IF EXISTS `state_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL,
  `best_time` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `culture` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `state_name` (`state_name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state_visits`
--

LOCK TABLES `state_visits` WRITE;
/*!40000 ALTER TABLE `state_visits` DISABLE KEYS */;
INSERT INTO `state_visits` VALUES (1,'Delhi','October to March (Winter)','Pleasant cool weather, ideal for exploring historical monuments, markets, and parks.','Major festivals like Diwali (Oct/Nov) and Republic Day parade (Jan). Avoid extreme summer heat (Apr-Jun) and heavy fog (Dec-Jan).'),(2,'Haryana','September to March (Autumn/Winter)','Comfortable weather for visiting places like Kurukshetra and Sultanpur Bird Sanctuary.','Lohri (Jan) is celebrated. Good time for exploring agricultural landscapes.'),(3,'Himachal Pradesh','March to June (Summer - hills pleasant), September to November (Autumn - clear views)','Ideal for trekking, visiting Shimla, Manali, Dharamshala. Summer offers escape from plains\' heat; Autumn has crisp air.','Various local fairs. Dussehra in Kullu (Oct) is famous. Avoid peak monsoon (Jul-Aug) and heavy winter snow (Dec-Feb) in higher reaches.'),(4,'Jammu and Kashmir','April to June (Summer - Srinagar, Gulmarg pleasant), September to October (Autumn - mild)','Summer is ideal for Srinagar\'s Dal Lake, Gulmarg meadows. Autumn offers pleasant weather before winter.','Shikara rides, Tulip Garden (Spring). Winter (Dec-Feb) for skiing in Gulmarg but heavy snow elsewhere.'),(5,'Ladakh','May to September (Summer)','Roads are open, sunny days. Ideal for Pangong Lake, Nubra Valley, monasteries. High altitude region.','Monastery festivals like Hemis Tsechu (Jun/Jul). Requires acclimatization. Winter is extremely harsh.'),(6,'Punjab','October to March (Winter)','Pleasant weather for visiting the Golden Temple in Amritsar and exploring cities.','Baisakhi (Apr) and Lohri (Jan) are major festivals. Best time to experience Punjabi culture and cuisine.'),(7,'Rajasthan','October to March (Winter)','Cool days and chilly nights, perfect for exploring forts (Jaipur, Jodhpur, Jaisalmer), palaces, and deserts without the extreme heat.','Peak tourist season. Many cultural festivals like Pushkar Camel Fair (Nov), Jaipur Literature Festival (Jan), and Desert Festival (Feb) take place.'),(8,'Uttarakhand','March to June (Summer - hills pleasant), September to November (Autumn)','Good for trekking, yoga retreats (Rishikesh), wildlife (Jim Corbett), and hill stations (Nainital, Mussoorie).','Char Dham Yatra season opens (Apr/May). Ganga Aarti in Haridwar/Rishikesh year-round. Avoid heavy monsoons.'),(9,'Uttar Pradesh','October to March (Winter)','Cool and pleasant weather, perfect for visiting the Taj Mahal in Agra, exploring Lucknow, and experiencing Varanasi\'s ghats.','Ideal time for religious tourism in Varanasi and Mathura. Major festivals like Diwali (Oct/Nov) and Holi (Mar) are celebrated grandly.'),(10,'Arunachal Pradesh','October to April (Winter/Spring)','Clear skies and pleasant weather for exploring Tawang monastery and scenic landscapes. Requires permits.','Various tribal festivals like Losar (Feb/Mar), Ziro Music Festival (Sep). Diverse indigenous cultures.'),(11,'Assam','October to April (Winter/Spring)','Pleasant weather, ideal for Kaziranga National Park (rhino sightings), Majuli island, and tea gardens.','Bihu festivals (especially Rongali Bihu in Apr) are major highlights. Rich tribal culture and handicrafts.'),(12,'Bihar','October to March (Winter)','Comfortable weather for exploring historical sites like Bodh Gaya, Nalanda, and Patna.','Chhath Puja (Oct/Nov) is a major festival. The weather is suitable for visiting pilgrimage sites comfortably.'),(13,'Jharkhand','October to February (Winter)','Pleasant climate for visiting waterfalls, hills (Netarhat), and tribal villages.','Rich tribal culture, Sarhul festival (Spring). Known for forests and mineral resources.'),(14,'Manipur','October to April (Winter/Spring)','Pleasant weather for visiting Loktak Lake and exploring Imphal. Requires permits for some areas.','Sangai Festival (Nov), Lai Haraoba (Apr/May). Known for classical dance and unique traditions.'),(15,'Meghalaya','October to June (Avoid peak monsoon Jul-Sep)','Known as \'Abode of Clouds\'. Best for waterfalls (Cherrapunji, Mawsynram), living root bridges, caves. Pleasant weather most times except heavy rain.','Wangala festival (Nov), Shad Suk Mynsiem (Apr). Matrilineal societies (Khasi, Garo).'),(16,'Mizoram','October to March (Winter)','Pleasant weather for exploring rolling hills, lakes, and Aizawl city. Requires permits.','Chapchar Kut (March) is a major festival celebrating spring. Bamboo dance (Cheraw) is famous.'),(17,'Nagaland','October to May (Avoid monsoon Jun-Sep)','Ideal for exploring tribal villages, scenic beauty. Requires permits.','Hornbill Festival (Dec 1-10) is a major attraction showcasing diverse Naga tribes and culture.'),(18,'Odisha','October to March (Winter)','Pleasant weather for visiting temples (Konark, Puri, Bhubaneswar), beaches, and Chilika Lake.','Rath Yatra in Puri (Jun/Jul), Konark Dance Festival (Dec). Rich temple architecture and classical dance (Odissi).'),(19,'Sikkim','March to June (Spring/Summer), October to December (Autumn)','Spring offers blooming rhododendrons. Autumn has clear mountain views (Kanchenjunga). Ideal for Gangtok, monasteries, trekking.','Losar (Feb/Mar), Saga Dawa (May/Jun). Beautiful monasteries and Buddhist culture. Avoid heavy monsoon.'),(20,'Tripura','October to March (Winter)','Pleasant weather for visiting palaces (Ujjayanta), temples, and exploring Agartala.','Garia Puja (Apr), Kharchi Puja (Jul). Mix of Bengali and tribal cultures.'),(21,'West Bengal','October to March (Autumn/Winter)','Pleasant weather, ideal for sightseeing in Kolkata, exploring Darjeeling\'s hills, and visiting the Sundarbans.','Major festivals like Durga Puja (Oct) and Kali Puja occur. Enjoy vibrant cultural events and comfortable travel conditions.'),(22,'Chhattisgarh','October to March (Winter)','Pleasant weather for exploring waterfalls (Chitrakote), caves, national parks, and tribal areas.','Rich tribal heritage, Bastar Dussehra (Sep/Oct) is unique. Known for forests and indigenous crafts.'),(23,'Madhya Pradesh','October to March (Winter)','Ideal weather for wildlife safaris (Kanha, Bandhavgarh), historical sites (Khajuraho, Sanchi, Mandu), and cities.','Khajuraho Dance Festival (Feb). Known as the \'Heart of India\' with diverse attractions.'),(24,'Goa','November to February (Winter)','Peak season with pleasant weather, ideal for beaches, nightlife, water sports.','Christmas and New Year celebrations are vibrant. Carnival (Feb/Mar). Monsoon (Jun-Sep) is off-season but lush green.'),(25,'Gujarat','October to March (Winter)','Pleasant weather for visiting Rann of Kutch, Gir Forest (Asiatic Lions), Ahmedabad, Somnath, Dwarka.','Navratri (Sep/Oct) is celebrated grandly. Rann Utsav (Dec-Feb). Kite Festival (Jan).'),(26,'Maharashtra','October to March (Winter)','Pleasant weather for exploring Mumbai, Pune, Ajanta & Ellora caves, hill stations (Mahabaleshwar), and beaches.','Ganesh Chaturthi (Aug/Sep) and Diwali (Oct/Nov) are major festivals. Rich Maratha history and forts.'),(27,'Andhra Pradesh','October to March (Winter)','Comfortable weather for visiting Tirupati temple, beaches (Vizag), and historical sites.','Major festivals like Sankranti (Jan). Rich classical music (Carnatic) and dance (Kuchipudi) traditions.'),(28,'Karnataka','September to February (Post-Monsoon/Winter)','Pleasant weather for Bangalore, Mysore, Hampi ruins, Coorg hills, and coastal areas.','Mysore Dasara (Sep/Oct) is spectacular. Hampi Utsav (Jan/Feb). Rich history and diverse landscapes.'),(29,'Kerala','September to March (Winter/Post-Monsoon)','Pleasant weather, lush greenery after monsoons. Ideal for backwaters, beaches (Kovalam, Varkala), and hill stations (Munnar).','Onam (Aug/Sep) celebrations might extend. Theyyam performances begin. Peak season for Ayurveda treatments.'),(30,'Tamil Nadu','November to February (Winter)','Avoids extreme heat and monsoon rains. Ideal for temple towns (Madurai, Thanjavur), beaches (Mahabalipuram), hill stations (Ooty).','Pongal harvest festival (Jan) is significant. Music and dance festival in Chennai (Dec-Jan). Rich Dravidian culture and architecture.'),(31,'Telangana','October to March (Winter)','Pleasant weather for exploring Hyderabad (Charminar, Golconda Fort) and other historical sites.','Bathukamma festival (Sep/Oct), Bonalu (Jul/Aug). Rich Nizam heritage and cuisine.'),(32,'Andaman and Nicobar Islands','October to May (Avoid monsoon Jun-Sep)','Ideal weather for beaches (Havelock, Neil), water sports (snorkeling, diving), and Cellular Jail visit.','Mix of indigenous tribes and settlers. Island Tourism Festival (Jan).'),(33,'Puducherry','October to March (Winter)','Pleasant weather, less humidity. Ideal for exploring French Quarter, Auroville, beaches.','Unique Franco-Tamil culture. Sri Aurobindo Ashram attracts many visitors.');
/*!40000 ALTER TABLE `state_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'parna','parnaghosh628@gmail.com','$2y$10$G1c2y485pnYcJfWBjGcwNOuYdCebrDiXM61ms4fq66e9qS.byyAYe','2025-04-16 13:54:23'),(10,'amisha','singh23@gmail.com','$2y$10$SkaVO3Bez9WrJJ71F1Nt8OXMkSCEXVIt/N0Hd0gmHhLUzNX6j4zfO','2025-04-23 21:25:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visit_feedback`
--

DROP TABLE IF EXISTS `visit_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visit_feedback`
--

LOCK TABLES `visit_feedback` WRITE;
/*!40000 ALTER TABLE `visit_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `visit_feedback` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-25 14:43:58
