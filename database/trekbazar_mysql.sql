-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: trekbazar
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
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `booking_reference` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_nationality` varchar(255) DEFAULT NULL,
  `travel_date` date NOT NULL,
  `group_size` smallint(5) unsigned NOT NULL DEFAULT 1,
  `special_requests` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_reference_unique` (`booking_reference`),
  KEY `bookings_package_id_foreign` (`package_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_travel_date_index` (`travel_date`),
  KEY `bookings_customer_email_index` (`customer_email`),
  CONSTRAINT `bookings_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Backpacks','backpacks',NULL,NULL,1,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(2,'Boots & Footwear','boots',NULL,NULL,2,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(3,'Tents & Shelter','tents',NULL,NULL,3,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(4,'Clothing & Layers','clothing',NULL,NULL,4,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(5,'Sleeping Gear','sleeping',NULL,NULL,5,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(6,'Safety & First Aid','safety',NULL,NULL,6,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(7,'Navigation','navigation',NULL,NULL,7,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(8,'Accessories','accessories',NULL,NULL,8,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(9,'Trekking Gear','trekking-gear','Essential gear for trekkers and hikers.',NULL,1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') NOT NULL DEFAULT 'unread',
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (2,'Ram Prasad','ramprasad@gmail.com','9864253541','Booking & Payment','dsfasdfsdf  what is this','read','127.0.0.1','2026-04-28 14:11:16','2026-04-28 14:12:10'),(3,'Seed Test User','seed@pathtosnow.com','+977-9800000099','Enquiry about the Golden Triangle Tour','Hello, I am interested in the Golden Triangle Cultural Tour. Could you please send me more details about the itinerary and pricing options for a group of 4?','read','127.0.0.1','2026-05-04 10:47:02','2026-05-04 10:49:03');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Thailand','thailand',NULL,NULL,1,'2026-04-29 11:39:28','2026-04-29 11:39:28'),(2,'Bhutan','bhutan',NULL,NULL,1,'2026-04-29 11:47:02','2026-04-29 11:47:02'),(3,'UAE','uae',NULL,NULL,1,'2026-04-29 11:47:11','2026-04-29 11:47:11'),(4,'India','india',NULL,'Discover the spiritual heartland of South Asia — from the Himalayas to ancient temples.',1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_albums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (3,'kathmandu visit','kathmandu-visit',NULL,'/storage/gallery_covers/lJ2DLEBL5dQ2lQdrQV7XXPQMYSgepkCVay99BNPM.jpg',1,'2026-04-29 09:39:18','2026-04-29 09:39:18'),(4,'Test — Nepal Landscapes','test-nepal-landscapes','A stunning collection of Nepal landscape photographs from our trekking expeditions.',NULL,1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_album_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_images_gallery_album_id_foreign` (`gallery_album_id`),
  CONSTRAINT `gallery_images_gallery_album_id_foreign` FOREIGN KEY (`gallery_album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,4,'/images/placeholder-gallery.jpg','Annapurna Base Camp at dawn — 4,130m',1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itinerary_days`
--

DROP TABLE IF EXISTS `itinerary_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itinerary_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `day_number` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `accommodation` varchar(255) DEFAULT NULL,
  `meals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meals`)),
  `distance_km` int(10) unsigned DEFAULT NULL,
  `altitude_m` int(10) unsigned DEFAULT NULL,
  `elevation_gain_m` int(10) unsigned DEFAULT NULL,
  `elevation_loss_m` int(10) unsigned DEFAULT NULL,
  `place_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `itinerary_days_package_id_day_number_unique` (`package_id`,`day_number`),
  KEY `itinerary_days_package_id_index` (`package_id`),
  CONSTRAINT `itinerary_days_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itinerary_days`
--

LOCK TABLES `itinerary_days` WRITE;
/*!40000 ALTER TABLE `itinerary_days` DISABLE KEYS */;
INSERT INTO `itinerary_days` VALUES (1,4,1,'Arrival in Pokhara','Fly or drive from Kathmandu to Pokhara. Rest and trek briefing.','Hotel','[\"Dinner\"]',NULL,820,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(2,4,2,'Nayapul to Tikhedhunga','Drive to Nayapul (1.5hr), begin trek through Birethanti village.','Guesthouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',13,1540,720,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(3,4,3,'Tikhedhunga to Ghorepani','Steep climb through rhododendron forest. Sunrise views of Dhaulagiri.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',12,2874,1334,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(4,4,4,'Poon Hill Sunrise & Tadapani','Pre-dawn climb to Poon Hill (3,210m) for panoramic sunrise. Trek to Tadapani.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',10,2630,336,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(5,4,5,'Tadapani to Chhomrong','Trek through oak forests to the Gurung village of Chhomrong.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',9,2170,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(6,4,6,'Chhomrong to Dovan','Enter the Annapurna Sanctuary. Bamboo and rhododendron forest.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',11,2600,430,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(7,4,7,'Dovan to Deurali','Trek through Himalaya Hotel and Hinko Cave area. Glacier views.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',7,3230,630,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(8,4,8,'Deurali to Annapurna Base Camp','Final push to the glacier amphitheatre at 4,130m. Incredible 360° views.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',9,4130,900,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(9,4,9,'ABC to Bamboo via Jhinu Hot Springs','Descend fast, stop at natural hot springs at Jhinu for a soak.','Guesthouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',16,2310,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(10,4,10,'Bamboo to Chhomrong to Sinuwa','Continue descent through familiar terrain.','Teahouse','[\"Breakfast\",\"Lunch\",\"Dinner\"]',10,2360,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(11,4,11,'Sinuwa to Nayapul','Long descent day. Drive to Pokhara on arrival.','Hotel','[\"Breakfast\",\"Lunch\"]',18,820,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(12,4,12,'Pokhara free day','Rest, explore Pokhara lakeside, last shopping.','Hotel','[\"Breakfast\"]',NULL,820,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(13,4,13,'Return to Kathmandu','Fly or drive back to Kathmandu. Tour ends.',NULL,'[\"Breakfast\"]',NULL,1400,NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(14,17,1,'Arrival in Delhi','Arrive at Indira Gandhi International Airport. Transfer to hotel. Evening walk at Connaught Place.','Hotel in Delhi','[\"dinner\"]',20,216,0,0,'New Delhi','Wear comfortable walking shoes.','2026-05-04 10:47:02','2026-05-04 10:47:02'),(15,17,2,'Old and New Delhi Tour','Visit Qutb Minar, Red Fort, Jama Masjid, and India Gate. Street food walk in Chandni Chowk.','Hotel in Delhi','[\"breakfast\",\"lunch\"]',45,216,0,0,'Delhi','Keep valuables secure in crowded markets.','2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `itinerary_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_users_table',1),(2,'2024_01_01_000002_create_packages_table',1),(3,'2024_01_01_000003_create_itinerary_days_table',1),(4,'2024_01_01_000004_create_bookings_table',1),(5,'2024_01_01_000005_create_posts_table',1),(6,'2024_01_01_000006_create_shop_tables',1),(7,'2026_04_26_154840_create_gallery_albums_table',1),(8,'2026_04_26_154841_create_gallery_images_table',1),(9,'2026_04_26_170734_create_slides_table',1),(10,'2026_04_27_035547_add_payment_status_to_orders_table',1),(11,'2026_04_27_042633_create_static_pages_table',1),(12,'2026_04_27_043913_create_contact_messages_table',1),(13,'2026_04_27_050911_create_package_types_table',1),(14,'2026_04_27_050911_create_post_types_table',1),(15,'2026_04_27_054354_add_hero_image_to_post_types_table',1),(16,'2026_04_29_164606_create_countries_table',1),(17,'2026_04_29_164612_add_country_id_to_packages_table',1),(18,'2026_05_04_165505_add_price_nrs_to_packages_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` smallint(5) unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,6,'Test — Carbon Fibre Trekking Poles',NULL,1,2999.00,2999.00,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(255) DEFAULT NULL,
  `shipping_country` varchar(255) DEFAULT NULL,
  `shipping_postal_code` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,7,'TB-SHOP-CNYJ9R','Test Traveller','testuser@pathtosnow.com','+977-9800000001','pending','unpaid','cash_on_delivery',NULL,2999.00,150.00,3149.00,'NPR','Test Traveller','Thamel, Ward 26','Kathmandu','Nepal','44600','Test order — seeded by AdminTestDataSeeder.',NULL,NULL,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_types`
--

DROP TABLE IF EXISTS `package_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type_key` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon_emoji` varchar(255) NOT NULL DEFAULT '?',
  `hero_image_url` varchar(255) DEFAULT NULL,
  `gradient` varchar(255) NOT NULL DEFAULT 'from-slate-700 to-slate-800',
  `badge_class` varchar(255) NOT NULL DEFAULT 'bg-slate-100 text-slate-700',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT 99,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_types_slug_unique` (`slug`),
  UNIQUE KEY `package_types_type_key_unique` (`type_key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_types`
--

LOCK TABLES `package_types` WRITE;
/*!40000 ALTER TABLE `package_types` DISABLE KEYS */;
INSERT INTO `package_types` VALUES (1,'Adventure','adventure','adventure','Bungee jumping, white-water rafting, paragliding & extreme Nepal experiences','⚡','https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=1600&q=80&auto=format&fit=crop','from-red-700 to-orange-700','bg-red-100 text-red-700',1,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(2,'Trekking','trekking','trekking','Annapurna, Everest Base Camp, Langtang & Nepal\'s greatest trails','🏔','https://images.unsplash.com/photo-1467887913518-98ca7ce13ede?w=1600&q=80&auto=format&fit=crop','from-emerald-800 to-green-700','bg-emerald-100 text-emerald-800',2,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(3,'Valley Visit','valley-visit','valley_visit','Kathmandu, Pokhara, Bhaktapur & Nepal\'s UNESCO World Heritage Sites','🏛','https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1600&q=80&auto=format&fit=crop','from-violet-800 to-purple-700','bg-violet-100 text-violet-700',3,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(4,'National Park','national-park','national_park','Chitwan, Sagarmatha, Langtang & Nepal\'s protected natural wonders','🌿','https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1600&q=80&auto=format&fit=crop','from-green-800 to-teal-700','bg-green-100 text-green-800',4,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(6,'Lakes','lake','lake','Rara, Phewa, Begnas & Nepal\'s stunning high-altitude mountain lakes','🏞','https://images.unsplash.com/photo-1606210695818-fbe9f66e7df0?w=1600&q=80&auto=format&fit=crop','from-sky-800 to-cyan-700','bg-sky-100 text-sky-700',6,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(7,'Chardham Package','chardham-package','chardham_package',NULL,'📦',NULL,'from-slate-700 to-slate-800','bg-slate-100 text-slate-700',7,1,'2026-04-28 13:37:32','2026-04-28 13:38:41'),(10,'Cultural Tour','cultural-tour','cultural_tour','Immersive cultural tours across Nepal and the Himalayas.','🏛️',NULL,'from-rose-600 to-pink-700','bg-rose-100 text-rose-700',9,1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `package_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `short_description` text NOT NULL,
  `description` longtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `price_per_person` decimal(10,2) NOT NULL,
  `price_nrs` decimal(10,2) DEFAULT NULL,
  `price_group` decimal(10,2) DEFAULT NULL,
  `duration_days` int(11) NOT NULL,
  `duration_nights` int(11) DEFAULT NULL,
  `min_group_size` int(11) NOT NULL DEFAULT 1,
  `max_group_size` int(11) NOT NULL DEFAULT 15,
  `difficulty` enum('easy','moderate','challenging','strenuous') DEFAULT NULL,
  `max_altitude_m` int(11) DEFAULT NULL,
  `best_season` varchar(255) DEFAULT NULL,
  `start_point` varchar(255) DEFAULT NULL,
  `end_point` varchar(255) DEFAULT NULL,
  `highlights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`highlights`)),
  `included` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`included`)),
  `excluded` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded`)),
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `faqs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`faqs`)),
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_slug_unique` (`slug`),
  KEY `packages_type_index` (`type`),
  KEY `packages_featured_index` (`featured`),
  KEY `packages_active_index` (`active`),
  KEY `packages_country_id_foreign` (`country_id`),
  CONSTRAINT `packages_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'pilgrimage_tour','Muktinath Tour and Pilgrimage','bungee-jump-last-resort','Jomsom, Muktinath','Gandaki Province','Pilgrimage site in 4000m — one of Asia\'s best pilgrimage for Hindu and Buddhist','<p>Best Pilgrimage Site in Nepal</p>',NULL,NULL,120.00,NULL,NULL,1,0,1,15,'easy',NULL,'Year-round',NULL,NULL,'[\"Transport from Kathmandu\"]','[\"Return transport KTM\",\"Harness & equipment\",\"Safety briefing\"]','[\"Personal insurance\",\"Meals\",\"Accommodation\"]',NULL,NULL,1,1,7,NULL,NULL,'2026-04-26 08:53:49','2026-05-04 11:17:10',NULL),(2,'adventure','White Water Rafting — Trishuli River','rafting-trishuli-river','Trishuli, Nuwakot','Bagmati Province','Grade 3–4 rapids on Nepal\'s most popular rafting river. Full day from Kathmandu.','<p>The Trishuli River offers the perfect introduction to white water rafting. Grade 3–4 rapids provide genuine excitement while remaining accessible to beginners. The river cuts through green hills with views of distant Himalayan peaks.</p>',NULL,NULL,55.00,NULL,NULL,1,0,1,15,'moderate',NULL,'Oct-May',NULL,NULL,'[\"Grade 3\\u20134 rapids\",\"Himalayan views\",\"Professional guides\",\"All equipment included\"]','[\"Transport from KTM\",\"Rafting equipment\",\"Guide\",\"Lunch by the river\"]','[\"Personal insurance\",\"Alcohol\",\"Tips\"]',NULL,NULL,1,1,8,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 22:47:28',NULL),(3,'adventure','Paragliding — Pokhara','paragliding-pokhara','Sarangkot, Pokhara','Gandaki Province','Soar over Phewa Lake with Annapurna panorama. Tandem flight from Sarangkot.','<p>Pokhara is one of the world\'s best paragliding destinations. Your tandem flight launches from Sarangkot hill and glides over the turquoise Phewa Lake with Annapurna, Machhapuchhre, and Dhaulagiri filling the horizon. No experience required — your certified pilot does all the work.</p>',NULL,NULL,90.00,NULL,NULL,1,0,1,15,'easy',NULL,'Sep-May',NULL,NULL,'[\"Annapurna panorama\",\"Fewa Lake views\",\"Tandem with certified pilot\",\"GoPro footage available\"]','[\"Equipment\",\"Certified pilot\",\"Transport to Sarangkot\"]','[\"Video package (extra)\",\"Hotel pickup\"]',NULL,NULL,0,1,4,NULL,NULL,'2026-04-26 08:53:49','2026-05-04 11:21:44',NULL),(4,'trekking','Annapurna Base Camp Trek','annapurna-base-camp-trek','Annapurna Region, Kaski','Gandaki Province','13-day classic trek to ABC at 4,130m. Rhododendron forests, glacier amphitheatre, mountain views.','<p>The Annapurna Base Camp (ABC) trek is one of Nepal\'s most popular routes for good reason. You walk through Gurung villages, dense rhododendron forests that blaze red and pink in spring, past hot springs at Jhinu, and up to a dramatic 360° amphitheatre of peaks including Annapurna I (8,091m), Machhapuchhre, and Hiunchuli.</p><p>This is a moderate trek — challenging but achievable for anyone with reasonable fitness and proper acclimatisation.</p>',NULL,NULL,950.00,NULL,NULL,13,12,1,15,'moderate',4130,'Mar-May, Sep-Dec','Nayapul','Nayapul','[\"4,130m base camp\",\"Annapurna panorama\",\"Rhododendron forests\",\"Hot springs at Jhinu\",\"Gurung villages\"]','[\"Licensed guide\",\"Porter\",\"Teahouse accommodation\",\"All meals (B\\/L\\/D)\",\"ACAP permit\",\"TIMS card\"]','[\"International flights\",\"Travel insurance\",\"Personal gear\",\"Tips\"]',NULL,NULL,1,1,9,NULL,NULL,'2026-04-26 08:53:49','2026-05-14 21:55:52',NULL),(5,'trekking','Langtang Valley Trek','langtang-valley-trek','Langtang National Park, Rasuwa','Bagmati Province','10-day trek through Tamang culture and high-altitude yak pastures near Tibet.','<p>The Langtang Valley is one of Nepal\'s most accessible yet underrated treks. Just 68km north of Kathmandu, the valley offers stunning scenery, Tamang Buddhist culture, and views of Langtang Lirung (7,227m) without the crowds of the Annapurna or Everest regions.</p>',NULL,NULL,750.00,NULL,NULL,10,9,1,15,'moderate',3870,'Mar-May, Oct-Nov','Syabrubesi','Syabrubesi','[\"Tamang culture\",\"Yak pastures\",\"Kyanjin Gompa\",\"Cheese factory visit\",\"Glacier views\"]','[\"Guide\",\"Porter\",\"Accommodation\",\"All meals\",\"National park permits\"]','[\"Flights to\\/from Kathmandu\",\"Insurance\",\"Personal expenses\"]',NULL,NULL,1,1,7,NULL,NULL,'2026-04-26 08:53:49','2026-05-14 21:52:58',NULL),(6,'valley_visit','Kathmandu Durbar Squares Tour','kathmandu-durbar-squares-tour','Kathmandu Valley','Bagmati Province','2-day private guided tour of all three UNESCO-listed Durbar Squares.','<p>Explore the heart of Nepal\'s ancient civilisation across three UNESCO World Heritage Durbar Squares — Kathmandu, Patan (Lalitpur), and Bhaktapur. Each square is a living museum of Newari architecture, medieval temples, royal palaces, and traditional craftwork still practiced today.</p>',NULL,NULL,120.00,NULL,NULL,2,1,1,15,'easy',NULL,'Year-round',NULL,NULL,'[\"3 UNESCO sites\",\"Expert local guide\",\"Kumari Living Goddess courtyard\",\"Pashupatinath Temple\",\"Boudhanath Stupa\"]','[\"Private guide\",\"Entry fees\",\"Transport between sites\",\"Lunch at Bhaktapur\"]','[\"Hotel accommodation\",\"Dinner\",\"Personal shopping\"]',NULL,NULL,1,1,3,NULL,NULL,'2026-04-26 08:53:49','2026-04-29 12:30:13',NULL),(7,'national_park','Chitwan Jungle Safari — 3 Days','chitwan-jungle-safari-3-days','Chitwan National Park, Nawalpur','Bagmati / Madhesh Province','Jeep safari, elephant breeding centre, canoe ride, and Tharu culture in Nepal\'s most famous national park.','<p>Chitwan National Park is a UNESCO World Heritage Site and home to one-horned rhinoceroses, Bengal tigers, gharial crocodiles, and over 600 bird species. Our 3-day package includes jeep safaris at dawn and dusk (best for wildlife), a canoe ride on the Rapti River, cultural visits to Tharu villages, and a visit to the elephant breeding centre.</p>',NULL,NULL,350.00,NULL,NULL,3,2,1,15,'easy',NULL,'Oct-Mar',NULL,NULL,'[\"One-horned rhino sightings\",\"Tiger territory jeep safari\",\"Canoe river ride\",\"Tharu cultural program\",\"Bird watching\"]','[\"Accommodation at jungle lodge\",\"All meals\",\"Jeep safaris\",\"Park entry fees\",\"Naturalist guide\"]','[\"Transport to\\/from Chitwan\",\"Alcohol\",\"Tips\",\"Personal insurance\"]',NULL,NULL,1,1,1,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 11:05:04',NULL),(8,'wildlife_reserve','Bardia Wildlife Reserve — Tiger Trek','bardia-wildlife-tiger-trek','Bardia National Park, Bardiya','Lumbini Province','5-day deep wilderness safari in Nepal\'s least-visited tiger reserve. Better odds than Chitwan.','<p>Bardia National Park is Nepal\'s largest and most pristine wildlife reserve, with significantly higher tiger density than Chitwan. Because it receives far fewer visitors, wildlife encounters here feel genuinely wild. Walk with a naturalist through tall elephant grass, riverine forest, and open savannahs. Most visitors see rhino, deer, and monkeys. Tiger sightings are rare but real.</p>',NULL,NULL,580.00,NULL,NULL,5,4,1,15,'easy',NULL,'Oct-Apr',NULL,NULL,'[\"Best tiger chances in Nepal\",\"Pristine wilderness\",\"Walking safaris\",\"Karnali River rafting option\",\"Dolphin spotting\"]','[\"Eco-lodge accommodation\",\"All meals\",\"Walking safaris\",\"Park fees\",\"Expert naturalist\"]','[\"Flights to Nepalgunj\",\"Personal insurance\",\"Alcohol\"]',NULL,NULL,0,1,0,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49',NULL),(9,'lake','Rara Lake Trek — Nepal\'s Hidden Gem','rara-lake-trek','Rara National Park, Mugu','Karnali Province','12-day expedition to Nepal\'s largest and most remote lake at 2,990m. True wilderness.','<p>Rara Lake is Nepal\'s largest and most remote lake — a jewel of deep blue surrounded by conifer forests and snow-capped peaks in the far northwest. Because of its remoteness (flights from Kathmandu to Jumla, then trek), very few tourists ever reach it. Those who do are rewarded with absolute silence, pristine nature, and one of the most beautiful lake settings on earth.</p>',NULL,NULL,1650.00,NULL,NULL,12,11,1,15,'challenging',3731,'Apr-Jun, Sep-Nov','Jumla','Jumla','[\"Nepal\'s largest lake\",\"Rara National Park\",\"Snow leopard habitat\",\"Remote villages\",\"Untouched wilderness\"]','[\"Domestic flights KTM-Jumla-KTM\",\"Guide & porter\",\"Accommodation\",\"All meals\",\"Park permits\"]','[\"International flights\",\"Travel insurance\",\"Personal gear\",\"Tips\"]',NULL,NULL,1,1,2,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 09:53:58',NULL),(10,'lake','Fewa Lake Pokhara — Kayaking & Reflection','fewa-lake-pokhara-kayaking','Phewa Lake, Pokhara','Gandaki Province','2-day Pokhara experience: kayaking on Fewa, Barahi Island temple, Annapurna reflections.','<p>Fewa Lake is one of Nepal\'s most beautiful bodies of water — a 4.4 km² lake with perfect reflections of the Annapurna massif on calm mornings. Kayak at sunrise, visit the Tal Barahi temple on the island, cycle the lakeside, and watch the mountains from the shore at dusk.</p>',NULL,NULL,85.00,NULL,NULL,2,1,1,15,'easy',NULL,'Sep-May',NULL,NULL,'[\"Annapurna reflections\",\"Sunrise kayaking\",\"Island temple visit\",\"Lakeside cycling\",\"Mountain photography\"]','[\"Kayak hire\",\"Guide\",\"Lakeside hotel 1 night\",\"Breakfast\"]','[\"Lunch\\/dinner\",\"Paragliding (can be added)\",\"Personal transport\"]',NULL,NULL,0,1,0,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49',NULL),(15,'adventure','dcsdfdf','dcsdfdf','dsfsdf','dsf sd','dfsf sdf sdfsdf','dsf sdf sdfasdfasdfsadfad',NULL,NULL,122.95,NULL,NULL,1,0,1,15,NULL,NULL,NULL,NULL,NULL,'[\"sd fsd fsadf\"]','[\"sd fsd fasd fasdf\"]','[\"sd fsd fsad fsad\"]',NULL,NULL,0,1,10,'sadf asd f','sdfsda fsad fas','2026-04-29 12:13:54','2026-05-04 10:06:25',1),(16,'national_park','Shey Phoksundo National Park','shey-phosundo-national-park','Dolpa','Karnali','Best Tour Package','nothing to say, just awesome',NULL,NULL,120.00,NULL,NULL,7,11,1,15,'moderate',NULL,NULL,'Kathmandu','Kathmandu','[\"fasdfasdf\"]','[\"dfsfasf sdf\"]','[\"dsfasd fasdfasd f\"]',NULL,NULL,0,1,3,'good','awesome','2026-05-04 10:06:07','2026-05-04 10:48:09',NULL),(17,'cultural_tour','Test — Golden Triangle Cultural Tour','test-golden-triangle-cultural-tour','Delhi, Agra, Jaipur','North India','A 7-day journey through India\'s most iconic cultural landmarks.','<p>Experience the Golden Triangle — Delhi, Agra, and Jaipur — on this immersive cultural tour packed with history, architecture, and authentic local cuisine.</p>',NULL,'[]',45000.00,NULL,NULL,7,6,2,12,'easy',216,'October to March','New Delhi Airport','Jaipur Airport','[\"Taj Mahal sunrise visit\",\"Amber Fort elephant ride\",\"Qutb Minar tour\",\"Street food walk in Old Delhi\"]','[\"Hotel accommodation (6 nights)\",\"All transfers by AC vehicle\",\"English-speaking guide\",\"Entry fees to monuments\"]','[\"International airfare\",\"Visa fees\",\"Personal expenses\",\"Tips\"]','[\"Valid passport\",\"Travel insurance recommended\"]','[{\"q\":\"Is this tour suitable for children?\",\"a\":\"Yes, the tour is family-friendly and suitable for all ages.\"}]',1,1,3,'Golden Triangle Cultural Tour — 7 Days','Explore Delhi, Agra, and Jaipur on a 7-day cultural tour from TrekBazar.','2026-05-04 10:47:02','2026-05-17 07:13:47',4);
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `post_types`
--

DROP TABLE IF EXISTS `post_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type_key` varchar(255) NOT NULL,
  `icon_emoji` varchar(255) NOT NULL DEFAULT '?',
  `color` varchar(255) NOT NULL DEFAULT 'blue',
  `hero_image_url` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT 99,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_types_slug_unique` (`slug`),
  UNIQUE KEY `post_types_type_key_unique` (`type_key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_types`
--

LOCK TABLES `post_types` WRITE;
/*!40000 ALTER TABLE `post_types` DISABLE KEYS */;
INSERT INTO `post_types` VALUES (1,'Blog','blog','blog','✍️','blue','https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=2000&auto=format&fit=crop',1,1,'2026-04-26 23:25:36','2026-04-27 00:09:23'),(2,'Food & Drink','food-drink','food','🍜','amber',NULL,2,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(3,'Culture','culture','culture','🎭','purple',NULL,3,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(4,'Festivals','festivals','festival','🎆','rose',NULL,4,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(5,'City Tour','city-tour','city_tour','🏙','teal',NULL,5,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(6,'Travel Guide','travel-guide','travel_guide','🗺','green',NULL,6,1,'2026-04-26 23:25:36','2026-04-26 23:25:36'),(7,'Pilgrimage Story','pilgrimage-story','pilgrimage_story','📝','slate',NULL,7,1,'2026-04-26 23:34:35','2026-04-26 23:57:09'),(8,'Adventure Story','adventure-story','adventure_story','🧗','orange',NULL,10,1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `post_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `post_type` varchar(255) NOT NULL DEFAULT 'blog',
  `category` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `read_time` smallint(5) unsigned NOT NULL DEFAULT 5,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `related_package_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_related_package_id_foreign` (`related_package_id`),
  KEY `posts_published_post_type_index` (`published`,`post_type`),
  KEY `posts_post_type_index` (`post_type`),
  CONSTRAINT `posts_related_package_id_foreign` FOREIGN KEY (`related_package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'Everest Base Camp vs Annapurna Base Camp: Which is right for you?','everest-base-camp-vs-annapurna-base-camp-which-is-right-for-you','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about Everest Base Camp vs Annapurna Base Camp: Which is right for you?. Full content coming soon.</p>',NULL,'blog','trekking','[\"nepal\",\"travel\",\"blog\"]',1,'2026-04-26 08:53:49',1475,6,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 11:20:04'),(2,1,'What to eat in Nepal: 15 dishes you must try','what-to-eat-in-nepal-15-dishes-you-must-try','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about What to eat in Nepal: 15 dishes you must try. Full content coming soon.</p>',NULL,'food','nepali-food','[\"nepal\",\"travel\",\"food\"]',1,'2026-04-26 08:53:49',416,13,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 09:53:38'),(3,1,'Dashain Festival 2025: Complete guide for visitors','dashain-festival-2025-complete-guide-for-visitors','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about Dashain Festival 2025: Complete guide for visitors. Full content coming soon.</p>',NULL,'festival','dashain','[\"nepal\",\"travel\",\"festival\"]',1,'2026-04-26 08:53:49',952,7,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(4,1,'Kathmandu city tour: 3 days itinerary for first-timers','kathmandu-city-tour-3-days-itinerary-for-first-timers','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about Kathmandu city tour: 3 days itinerary for first-timers. Full content coming soon.</p>',NULL,'city_tour','kathmandu','[\"nepal\",\"travel\",\"city_tour\"]',1,'2026-04-26 08:53:49',516,7,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-29 08:20:52'),(5,1,'Nepal travel guide: Visa, money, safety, transport','nepal-travel-guide-visa-money-safety-transport','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about Nepal travel guide: Visa, money, safety, transport. Full content coming soon.</p>',NULL,'travel_guide','general','[\"nepal\",\"travel\",\"travel_guide\"]',1,'2026-04-26 08:53:49',1415,12,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-27 09:02:45'),(6,1,'Newari culture: Understanding the people of Kathmandu Valley','newari-culture-understanding-the-people-of-kathmandu-valley','A comprehensive guide for travellers visiting Nepal.','<p>This is a detailed guide about Newari culture: Understanding the people of Kathmandu Valley. Full content coming soon.</p>',NULL,'culture','newari','[\"nepal\",\"travel\",\"culture\"]',0,NULL,812,9,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(7,1,'Test Blog','test-blog','sdfasdfasdfsadfasdfasdfasdfs','dsfasdfsadfasdfasdfasdfasdf',NULL,'culture','Limbu','[\"dsfsdfdfsd\"]',1,'2026-04-26 10:01:32',5,5,'sdfsadfsddfsa','dsfasdfasdfasdf',NULL,'2026-04-26 10:01:32','2026-04-26 23:45:06'),(8,1,'Test — Top 10 Tips for Visiting the Taj Mahal','test-top-10-tips-for-visiting-the-taj-mahal','Planning a visit to the Taj Mahal? These insider tips will make your experience unforgettable.','<h2>1. Go at Sunrise</h2><p>The Taj Mahal at sunrise is breathtaking. Crowds are thinner and the light is magical.</p><h2>2. Book Tickets Online</h2><p>Avoid long queues by booking your entry tickets online before visiting.</p>',NULL,'travel_guide','Tips & Advice','[\"taj mahal\",\"india\",\"travel tips\",\"agra\"]',1,'2026-05-04 10:47:02',0,5,'Top 10 Tips for Visiting the Taj Mahal','Insider tips to make the most of your Taj Mahal visit — sunrise, tickets, and more.',17,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL,
  `weight_grams` int(10) unsigned DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`)),
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Osprey Atmos AG 65L','osprey-atmos-ag-65l','Professional grade gear for Himalayan trekking.',289.99,340.00,NULL,14,NULL,NULL,'[\"trekking\",\"gear\",\"nepal\"]','{\"Volume\":\"65L\",\"Frame\":\"Anti-Gravity\"}',1,1,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 22:00:36'),(2,2,'Salomon X Ultra 4 Mid GTX','salomon-x-ultra-4-mid-gtx','Professional grade gear for Himalayan trekking.',179.99,210.00,NULL,22,NULL,NULL,'[\"trekking\",\"gear\",\"nepal\"]','{\"Waterproof\":\"Gore-Tex\",\"Ankle\":\"Mid-cut\"}',1,1,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(3,5,'Sea to Summit Spark SP3','sea-to-summit-spark-sp3','Professional grade gear for Himalayan trekking.',349.00,NULL,NULL,8,NULL,NULL,'[\"trekking\",\"gear\",\"nepal\"]','{\"Rating\":\"-9\\u00b0C\",\"Fill\":\"850+ Down\"}',1,1,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(4,1,'Deuter Speed Lite 20L','deuter-speed-lite-20l','Professional grade gear for Himalayan trekking.',89.99,110.00,NULL,30,NULL,NULL,'[\"trekking\",\"gear\",\"nepal\"]','{\"Volume\":\"20L\",\"Type\":\"Daypack\"}',0,1,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(5,1,'asdfasdfasd','asdfasdfasd','sdfasdfsadfasdf',100.00,NULL,'[\"\\/storage\\/products\\/CI89KQPuIvVDsbdtXAFwRyrd7dpiZpleYtujt54c.jpg\",\"\\/storage\\/products\\/z41hLRaSJGreBQF0ihE2BZzjFL4TWR2t7khbziLG.png\"]',2,NULL,55,NULL,'{\"s\":\"dfsdf\",\"d\":\"asdfas\",\"a\":\"sdfsadf\"}',1,1,NULL,NULL,'2026-04-26 11:10:35','2026-05-17 22:20:44'),(6,9,'Test — Carbon Fibre Trekking Poles','test-trekking-poles-carbon-fiber','<p>Lightweight, collapsible carbon fibre trekking poles with anti-shock system. Suitable for all terrains.</p>',2999.00,3999.00,'[]',50,'TRK-POLE-CF-001',480,'[\"trekking\",\"poles\",\"carbon fibre\",\"hiking\"]','[{\"label\":\"Material\",\"value\":\"Carbon Fibre\"},{\"label\":\"Weight\",\"value\":\"480g per pair\"},{\"label\":\"Length\",\"value\":\"65\\u2013135 cm adjustable\"}]',1,1,'Carbon Fibre Trekking Poles — Lightweight Hiking Poles','High-quality carbon fibre trekking poles for trekkers and hikers in Nepal.','2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `btn_text` varchar(255) DEFAULT NULL,
  `btn_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slides`
--

LOCK TABLES `slides` WRITE;
/*!40000 ALTER TABLE `slides` DISABLE KEYS */;
INSERT INTO `slides` VALUES (1,'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1600&q=80','🇳🇵 Nepal\'s #1 curated travel platform','Adventure, culture &\nHimalayan wilderness','Book treks, adventures, wildlife safaris, lake expeditions and cultural tours — all curated by a local Nepali with insider knowledge.','Browse all experiences','/packages',1,1,'2026-04-26 11:26:22','2026-04-26 11:26:22'),(2,'https://images.unsplash.com/photo-1542224566-6e85f2e6772f?w=1600&q=80','Heritage','Discover Ancient\nTemples & Culture','Wander through the historic durbar squares and mystical temples of the Kathmandu Valley.','Valley Visits','/packages/valley-visits',2,1,'2026-04-26 11:26:22','2026-04-26 11:26:22'),(3,'https://images.unsplash.com/photo-1588614959060-4d144f28b207?w=1600&q=80','Wildlife','Into the Wild:\nJungle Safaris','Encounter rhinos, tigers, and elephants in the dense sub-tropical jungles of Chitwan and Bardia.','Wildlife Packages','/packages/wildlife',3,1,'2026-04-26 11:26:22','2026-04-26 11:26:22'),(4,'https://images.unsplash.com/photo-1586861214151-5182103f6f34?w=1600&q=80','Adventure','Thrilling Heights &\nAdrenaline Rushes','Paragliding over Phewa Lake, bungee jumping, and white-water rafting for the ultimate thrill seekers.','Adventure Packages','/packages/adventure',4,1,'2026-04-26 11:26:22','2026-04-26 11:26:22'),(5,'https://images.unsplash.com/photo-1627896157734-4bcdd68b6a67?w=1600&q=80','Nature','Serene Lakes &\nHidden Paradises','Reflect by the pristine waters of Rara, Phoksundo, and Gokyo high in the Himalayas.','Lake Expeditions','/packages/lakes',5,1,'2026-04-26 11:26:22','2026-04-26 11:26:22'),(6,NULL,'New Destination','Test — Discover the Himalayas','Journey through the world\'s highest peaks with TrekBazar — your trusted Himalayan travel partner.','Explore Tours','/packages',99,1,'2026-05-04 10:47:02','2026-05-04 10:47:02');
/*!40000 ALTER TABLE `slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_pages`
--

DROP TABLE IF EXISTS `static_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `show_in_footer` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `static_pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_pages`
--

LOCK TABLES `static_pages` WRITE;
/*!40000 ALTER TABLE `static_pages` DISABLE KEYS */;
INSERT INTO `static_pages` VALUES (1,'about-us','About Us','<h2>Welcome to TrekBazar Nepal</h2>\n<p>TrekBazar is Nepal\'s premier online travel marketplace, connecting adventurers from around the world with authentic, locally-guided experiences in the heart of the Himalayas.</p>\n\n<h3>Our Story</h3>\n<p>Founded in Kathmandu, TrekBazar was born from a passion for responsible tourism and a deep love for Nepal\'s incredible landscapes, culture, and people. We believe that the best travel experiences come from genuine local knowledge and meaningful connections.</p>\n\n<h3>What We Offer</h3>\n<ul>\n  <li><strong>Trekking & Adventure</strong> — From Everest Base Camp to remote Himalayan trails</li>\n  <li><strong>Cultural Experiences</strong> — UNESCO World Heritage sites, festivals, and local homestays</li>\n  <li><strong>Wildlife & Nature</strong> — Chitwan, Bardia, and Nepal\'s stunning national parks</li>\n  <li><strong>Gear Shop</strong> — Authentic Nepali trekking gear and souvenirs</li>\n</ul>\n\n<h3>Our Mission</h3>\n<p>We are committed to sustainable, community-based tourism that benefits local guides, porters, and villages across Nepal. Every booking you make directly supports Nepali families and conservation efforts.</p>\n\n<h3>Get In Touch</h3>\n<p>Have questions? Our team of local experts is always happy to help you plan your perfect Nepal adventure.</p>\n<p>📧 <a href=\"mailto:hello@trekbazar.com\">hello@trekbazar.com</a><br>📞 +977-1-XXXXXXX<br>📍 Thamel, Kathmandu, Nepal</p>','Learn about TrekBazar Nepal — your trusted partner for authentic Himalayan travel experiences.',1,1,'2026-04-26 22:45:53','2026-04-26 22:45:53'),(2,'privacy-policy','Privacy Policy','<h2>Privacy Policy</h2>\n<p><em>Last updated: April 2026</em></p>\n\n<p>TrekBazar Nepal (\"we\", \"us\", \"our\") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>\n\n<h3>Information We Collect</h3>\n<ul>\n  <li><strong>Personal Information:</strong> Name, email address, phone number when you register or make a booking</li>\n  <li><strong>Payment Information:</strong> Processed securely by our payment partners; we do not store card details</li>\n  <li><strong>Usage Data:</strong> Pages visited, time spent, browser type, IP address for analytics</li>\n</ul>\n\n<h3>How We Use Your Information</h3>\n<ul>\n  <li>To process bookings and purchases</li>\n  <li>To send booking confirmations and travel updates</li>\n  <li>To improve our services and website experience</li>\n  <li>To send promotional emails (you may opt out at any time)</li>\n</ul>\n\n<h3>Data Security</h3>\n<p>We implement appropriate technical and organisational measures to protect your personal information against unauthorised access, alteration, or destruction.</p>\n\n<h3>Third-Party Services</h3>\n<p>We may use third-party services (Google Analytics, payment processors) that collect data under their own privacy policies.</p>\n\n<h3>Your Rights</h3>\n<p>You have the right to access, correct, or delete your personal data. Contact us at <a href=\"mailto:privacy@trekbazar.com\">privacy@trekbazar.com</a> to exercise these rights.</p>\n\n<h3>Contact</h3>\n<p>For privacy-related enquiries: <a href=\"mailto:privacy@trekbazar.com\">privacy@trekbazar.com</a></p>','Read TrekBazar\'s privacy policy — updated by AdminTestDataSeeder to verify admin page-edit functionality.',1,2,'2026-04-26 22:45:53','2026-05-04 10:47:02'),(3,'terms-of-service','Terms of Service','<h2>Terms of Service</h2>\n<p><em>Last updated: April 2026</em></p>\n\n<p>By accessing or using TrekBazar Nepal (\"the Service\"), you agree to be bound by these Terms of Service. Please read them carefully.</p>\n\n<h3>1. Acceptance of Terms</h3>\n<p>By using our website, you confirm that you are at least 18 years old and agree to these terms. If you do not agree, please do not use our services.</p>\n\n<h3>2. Bookings & Payments</h3>\n<ul>\n  <li>All bookings are subject to availability and confirmation</li>\n  <li>Prices are displayed in USD unless otherwise stated</li>\n  <li>Full payment is required to confirm a booking</li>\n  <li>TrekBazar acts as an agent between you and local service providers</li>\n</ul>\n\n<h3>3. Cancellation Policy</h3>\n<ul>\n  <li><strong>30+ days before departure:</strong> Full refund minus processing fees</li>\n  <li><strong>15–29 days:</strong> 50% refund</li>\n  <li><strong>0–14 days:</strong> No refund</li>\n</ul>\n\n<h3>4. Traveller Responsibilities</h3>\n<p>You are responsible for ensuring you have valid travel documents, travel insurance, and physical fitness appropriate for your chosen activity.</p>\n\n<h3>5. Limitation of Liability</h3>\n<p>TrekBazar shall not be liable for injury, loss, or damage arising from participation in activities. All adventure activities carry inherent risks.</p>\n\n<h3>6. Governing Law</h3>\n<p>These terms are governed by the laws of Nepal. Disputes shall be subject to the exclusive jurisdiction of courts in Kathmandu.</p>\n\n<h3>Contact</h3>\n<p>For legal enquiries: <a href=\"mailto:legal@trekbazar.com\">legal@trekbazar.com</a></p>','TrekBazar Nepal Terms of Service — the rules and guidelines for using our platform.',1,3,'2026-04-26 22:45:53','2026-04-26 22:45:53'),(4,'contact-us','Contact Us','<h2>Contact Us</h2>\n<p>We\'d love to hear from you! Whether you have a question about a package, need help with a booking, or just want to learn more about Nepal — our team is here to help.</p>\n\n<h3>📍 Our Office</h3>\n<p>TrekBazar Nepal<br>Thamel, Kathmandu 44600<br>Nepal</p>\n\n<h3>📞 Phone & WhatsApp</h3>\n<p>+977-1-XXXXXXX<br>WhatsApp: +977-98XXXXXXXX<br>Available: Sunday–Friday, 9am–6pm NPT</p>\n\n<h3>📧 Email</h3>\n<ul>\n  <li>General enquiries: <a href=\"mailto:hello@trekbazar.com\">hello@trekbazar.com</a></li>\n  <li>Bookings: <a href=\"mailto:bookings@trekbazar.com\">bookings@trekbazar.com</a></li>\n  <li>Support: <a href=\"mailto:support@trekbazar.com\">support@trekbazar.com</a></li>\n</ul>\n\n<h3>🕐 Response Time</h3>\n<p>We aim to respond to all enquiries within 24 hours during business days.</p>','Get in touch with TrekBazar Nepal — we\'re here to help plan your perfect Nepal adventure.',1,4,'2026-04-26 22:45:53','2026-04-26 22:45:53');
/*!40000 ALTER TABLE `static_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@himalayatrails.com',NULL,'$2y$12$QARcpS7YX6hRo7lgSFXoQe3fOZjE.SyrmBJLLvYIm3qazpkmB6hhK','admin',NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 09:54:39'),(2,'Ram Shrestha','customer@trekbazar.com',NULL,'$2y$12$2xwV7PwgxXz5bI53vUA24OEHhVQTnM9vfC/vYvQEVOFs4zVrbuQeq','customer',NULL,NULL,NULL,NULL,'2026-04-26 08:53:49','2026-04-26 08:53:49'),(6,'Kabindra Koirala','kabindrawordpress@gmail.com',NULL,'$2y$12$59uKgmy1KM6RHAaZwXpIp.NsdiwlZl9iOBlq8nWcln08i5ngV3xXC','customer',NULL,NULL,NULL,NULL,'2026-04-28 14:13:03','2026-04-28 14:13:03'),(7,'Test Traveller','testuser@pathtosnow.com',NULL,'$2y$12$y3C2Scb/Zfr20py7Eq38dukVTPunmmflZeDNuSPQQQQeks59tX6jm','customer',NULL,NULL,NULL,NULL,'2026-05-04 10:47:02','2026-05-04 10:47:02');
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

-- Dump completed on 2026-05-18 10:08:30
