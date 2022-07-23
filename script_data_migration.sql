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