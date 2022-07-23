-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.10-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `avg_rating` float DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `user` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `culture` int(11) NOT NULL,
  `management` int(11) NOT NULL,
  `work_live_balance` int(11) NOT NULL,
  `career_development` int(11) NOT NULL,
  `pro` varchar(500) DEFAULT NULL,
  `contra` varchar(500) DEFAULT NULL,
  `suggestions` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `review_FK` (`user`),
  KEY `review_FK_1` (`company`),
  CONSTRAINT `review_FK` FOREIGN KEY (`user`) REFERENCES `user` (`user_id`),
  CONSTRAINT `review_FK_1` FOREIGN KEY (`company`) REFERENCES `company` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



CREATE TABLE jsontest (
 id int(11) NOT NULL AUTO_INCREMENT,
 feature json,
 name varchar(200) DEFAULT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

LOAD DATA INFILE '/mydata/data.json'
INTO TABLE jsontest 
fields terminated by '\0' escaped by ''
(feature);


INSERT INTO `user` (`email`)
SELECT distinct reviews.*
FROM jsontest, 
JSON_TABLE(feature, '$.companies[*]' COLUMNS (
                NESTED PATH '$.reviews[*]' COLUMNS (email VARCHAR(100) PATH "$.user")
               )) reviews;


INSERT INTO company (name, city, country, industry)
SELECT companies.*
FROM jsontest, 
JSON_TABLE(feature, '$.companies[*]' COLUMNS (
                name VARCHAR(200) PATH "$.name",
                city VARCHAR(100) PATH "$.city",
                country VARCHAR(100) PATH "$.country",
                industry VARCHAR(200) PATH "$.industry"
               )) companies;

INSERT INTO review (title, user, company, culture, management, work_live_balance, career_development, 
pro, contra, suggestions)
SELECT reviews.title, user.user_id as user, company.company_id as company, reviews.culture, 
reviews.management, reviews.work_live_balance, reviews.career_development, reviews.pro, reviews.contra, 
reviews.suggestions
FROM jsontest, 
JSON_TABLE(feature, '$.companies[*]' COLUMNS (
                  company VARCHAR(200) PATH "$.name",
                  NESTED PATH '$.reviews[*]' COLUMNS (
                  user VARCHAR(100) PATH "$.user",
                  title VARCHAR(200) PATH "$.title",
                  pro VARCHAR(500) PATH "$.pro",
                  contra VARCHAR(500) PATH "$.contra",
                  suggestions VARCHAR(500) PATH "$.suggestions",
                  NESTED PATH '$.rating' COLUMNS (
                  culture int PATH "$.culture",
                  management int PATH "$.management",
                  work_live_balance int PATH "$.work_live_balance",
                  career_development int PATH "$.career_development"
                  )))) reviews
LEFT JOIN user
ON reviews.user = user.email
LEFT JOIN company
ON reviews.company = company.name;

UPDATE company
LEFT JOIN (
  SELECT company, ROUND(AVG(culture + management + work_live_balance + 
career_development)/4, 2) AS avg
FROM review GROUP BY company
) AS average ON average.company = company_id
SET company.avg_rating = average.avg;
--
--
-- Dumping routines for database 'test'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

