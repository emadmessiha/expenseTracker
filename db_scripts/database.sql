-- MySQL
--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(256) NOT NULL,
  `default_tag_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'none',1);
INSERT INTO `stores` VALUES (2,'N/A',3);
INSERT INTO `stores` VALUES (3,'other',2);
INSERT INTO `stores` VALUES (4,'MID EAST',15);
INSERT INTO `stores` VALUES (5,'168 Sushi',17);
INSERT INTO `stores` VALUES (6,'BIG RIG',17);
INSERT INTO `stores` VALUES (7,'ARZ LEBANESE',17);
INSERT INTO `stores` VALUES (8,'Thai Express',17);
INSERT INTO `stores` VALUES (9,'A&W',17);
INSERT INTO `stores` VALUES (10,'Malones Lakeside',17);
INSERT INTO `stores` VALUES (11,'RED PEPPER THAI',17);
INSERT INTO `stores` VALUES (12,'TIKI MING',17);
INSERT INTO `stores` VALUES (13,'Sports Experts',19);
INSERT INTO `stores` VALUES (14,'ALDO',19);
INSERT INTO `stores` VALUES (15,'TOYS R US',19);
INSERT INTO `stores` VALUES (16,'Second Cup',17);
INSERT INTO `stores` VALUES (17,'JOE FRESH',19);
INSERT INTO `stores` VALUES (18,'SEARS',19);
INSERT INTO `stores` VALUES (19,'PIONEER',20);
INSERT INTO `stores` VALUES (20,'Giant Tiger',19);
INSERT INTO `stores` VALUES (21,'VINCI PARK',5);
INSERT INTO `stores` VALUES (22,'SHELL',20);
INSERT INTO `stores` VALUES (23,'Scotiabank',4);
INSERT INTO `stores` VALUES (24,'UNIFUND',6);
INSERT INTO `stores` VALUES (25,'ON THE RUN',17);
INSERT INTO `stores` VALUES (26,'BANK OF MONTREAL',21);
INSERT INTO `stores` VALUES (27,'DIXON MNGT',11);
INSERT INTO `stores` VALUES (28,'HYDRO OTTAWA',9);
INSERT INTO `stores` VALUES (29,'ENBRIDGE',8);
INSERT INTO `stores` VALUES (30,'YM-YWCA',7);
INSERT INTO `stores` VALUES (31,'Dollar Tree',11);
INSERT INTO `stores` VALUES (32,'Staples',11);
INSERT INTO `stores` VALUES (33,'XS CARGO',11);
INSERT INTO `stores` VALUES (34,'Shoppers',10);
INSERT INTO `stores` VALUES (35,'Subway',17);
INSERT INTO `stores` VALUES (36,'Dollarama',11);
INSERT INTO `stores` VALUES (37,'Old Navy',19);
INSERT INTO `stores` VALUES (38,'Harveys',17);
INSERT INTO `stores` VALUES (39,'Rogers Wireless',16);
INSERT INTO `stores` VALUES (40,'Sofa World',23);
INSERT INTO `stores` VALUES (41,'Value Village',23);
INSERT INTO `stores` VALUES (42,'Walmart',15);
INSERT INTO `stores` VALUES (43,'Costco',15);
INSERT INTO `stores` VALUES (44,'BestBuy',13);
INSERT INTO `stores` VALUES (45,'Food Basics',15);
INSERT INTO `stores` VALUES (46,'OC Transpo',14);
INSERT INTO `stores` VALUES (47,'Ikea',23);
INSERT INTO `stores` VALUES (48,'Canadian Tire',11);
INSERT INTO `stores` VALUES (49,'Winners',19);
INSERT INTO `stores` VALUES (50,'Home Hardware',22);
INSERT INTO `stores` VALUES (51,'Home Depot',22);
INSERT INTO `stores` VALUES (52,'SportChek',19);
INSERT INTO `stores` VALUES (53,'ESSO',20);
INSERT INTO `stores` VALUES (54,'Gas Station',20);
INSERT INTO `stores` VALUES (55,'Metro',15);
INSERT INTO `stores` VALUES (56,'Independant',15);
INSERT INTO `stores` VALUES (57,'HomeSense',23);
INSERT INTO `stores` VALUES (58,'Loblaw',15);
INSERT INTO `stores` VALUES (59,'Tim Hortons',17);
INSERT INTO `stores` VALUES (60,'Starbucks',17);
INSERT INTO `stores` VALUES (61,'Damas',15);
INSERT INTO `stores` VALUES (62,'The Farm Team',17);
INSERT INTO `stores` VALUES (63,'Blue Line',14);
INSERT INTO `stores` VALUES (64,'BED BATH & BEYOND',23);
INSERT INTO `stores` VALUES (65,'MAGICJACK',2);
INSERT INTO `stores` VALUES (66,'Montanas',17);
INSERT INTO `stores` VALUES (67,'Hyatt',18);
INSERT INTO `stores` VALUES (68,'Hilton',18);
INSERT INTO `stores` VALUES (69,'Taxi',14);
INSERT INTO `stores` VALUES (70,'US Airways',18);
INSERT INTO `stores` VALUES (71,'EINSTEINS BROS BAGEL',17);
INSERT INTO `stores` VALUES (72,'JERSEY MIKES SUBS',17);
INSERT INTO `stores` VALUES (73,'JOCKS & JILLS',17);
INSERT INTO `stores` VALUES (74,'NANAS SOUL FOOD',17);
INSERT INTO `stores` VALUES (75,'ROAM Mobility',16);
INSERT INTO `stores` VALUES (76,'Jysk',23);
INSERT INTO `stores` VALUES (77,'TARGET',19);
INSERT INTO `stores` VALUES (78,'HAKIM OPTICAL',24);
INSERT INTO `stores` VALUES (79,'Amazon',19);
INSERT INTO `stores` VALUES (80,'Paypal',2);
INSERT INTO `stores` VALUES (81,'BEAVERTAILS',17);
INSERT INTO `stores` VALUES (82,'MAGENTA STUDIO',7);
INSERT INTO `stores` VALUES (83,'EB GAMES',25);
INSERT INTO `stores` VALUES (84,'SALEM STORE',7);
INSERT INTO `stores` VALUES (85,'DUFRESNE',23);
INSERT INTO `stores` VALUES (86,'GRAND PIZZERIA',17);
INSERT INTO `stores` VALUES (87,'Presto',14);
INSERT INTO `stores` VALUES (88,'Talay Thai',17);
INSERT INTO `stores` VALUES (89,'Expedia',18);
INSERT INTO `stores` VALUES (90,'McDonalds',17);
INSERT INTO `stores` VALUES (91,'JAMES STREET PUB',17);
INSERT INTO `stores` VALUES (92,'Rockwell s Restaurant',17);
INSERT INTO `stores` VALUES (93,'CORA Breakfast',17);
INSERT INTO `stores` VALUES (94,'Petro-Canada',20);
INSERT INTO `stores` VALUES (95,'ServiceOntario',7);
INSERT INTO `stores` VALUES (96,'Google',19);
INSERT INTO `stores` VALUES (97,'Bulk Barn',15);
INSERT INTO `stores` VALUES (98,'MICHAEL HILL',19);
INSERT INTO `stores` VALUES (99,'THE WORKS',17);
INSERT INTO `stores` VALUES (100,'Leon s',23);
INSERT INTO `stores` VALUES (101,'TOOTSIES Shoe Market',19);
INSERT INTO `stores` VALUES (102,'GoDaddy',2);
INSERT INTO `stores` VALUES (103,'Uber',14);
INSERT INTO `stores` VALUES (104,'The Ottawa Hospital',24);
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'none');
INSERT INTO `tags` VALUES (2,'other');
INSERT INTO `tags` VALUES (3,'N/A');
INSERT INTO `tags` VALUES (4,'Banking');
INSERT INTO `tags` VALUES (5,'Parking');
INSERT INTO `tags` VALUES (6,'Insurance');
INSERT INTO `tags` VALUES (7,'Personal');
INSERT INTO `tags` VALUES (8,'Heat');
INSERT INTO `tags` VALUES (9,'Electricity and Water');
INSERT INTO `tags` VALUES (10,'Pharmacy');
INSERT INTO `tags` VALUES (11,'Home');
INSERT INTO `tags` VALUES (12,'Mortgage');
INSERT INTO `tags` VALUES (13,'Electronics');
INSERT INTO `tags` VALUES (14,'Transportation');
INSERT INTO `tags` VALUES (15,'Food');
INSERT INTO `tags` VALUES (16,'Phone and Internet');
INSERT INTO `tags` VALUES (17,'Eat out');
INSERT INTO `tags` VALUES (18,'Travel');
INSERT INTO `tags` VALUES (19,'Shopping');
INSERT INTO `tags` VALUES (20,'Gas');
INSERT INTO `tags` VALUES (21,'Car');
INSERT INTO `tags` VALUES (22,'House maintenance');
INSERT INTO `tags` VALUES (23,'Furniture');
INSERT INTO `tags` VALUES (24,'Medical');
INSERT INTO `tags` VALUES (25,'Entertainment');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tdate` date NOT NULL,
  `in_amount` decimal(10,0) NOT NULL DEFAULT '0',
  `out_amount` decimal(10,0) NOT NULL DEFAULT '0',
  `category` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  `ttype` enum('visa','checking') NOT NULL,
  `tmonth` int(11) NOT NULL,
  `tyear` int(11) NOT NULL,
  `tday` int(11) NOT NULL,
  `istransfer` tinyint(1) NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL DEFAULT '1',
  `tag_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=789 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

