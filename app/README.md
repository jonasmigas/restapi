### Test for Applicants

This solution was built: 

- with docker (restapi\docker\docker-compose.yml) -> just run docker compose up inside docker folder
- with symfony 6.0.10
- relational database (MySql)
- PHP 8.0 

### Database confing
- database script (mysql) -> script_data_migration.sql (with data) (inside app folder)

3 tables:
	- company (company_id, name, city, country, industry, avg_rating) (created the avg_rating field in order to get the average rating of a company -> it's done during the iserts of data.json file and whenever a review is submited, the average rating of that company is updated as well. it's easier to get the top 10 list of recommended companies)
	- user (user_id, email)
	- review (review_id, title, user (foreign key of user_id), company (foreign key of company_id), culture, management, work_live_balance, career_development, pro, contra, suggestions)
  
 (assuming that the user submitting the review and the company being reviewed, are already in the db (company and user) at the moment that the data is being sent, hence using the user_id and company_id)   

.env file:
DATABASE_URL="mysql://root:root@symfony_mysql:3306/test"

restapi\docker\init\mysql.env:
MYSQL_ROOT_PASSWORD=root
MYSQL_USER=admin
MYSQL_PASSWORD=admin
MYSQL_DATABASE=test

to access database: http://localhost:8080/

in my case the database was named "test"


### Endpoints
CompanyController => actions to:
 - List of top 10 recommended companies (getTopcompanyAction) -> http://localhost/api/topcompanies
 
ReviewController => actions to:
 - Submit review (postReviewAction) -> http://localhost/api/submitreview
 - Highest and lowest rating review from a company (postHighestLowestRatingAction) -> http://localhost/api/ratinghighlow //could have been done with get tbh
 - Users who reviewed this company also reviewed (postUserswhoReviewedAction) -> http://localhost/api/userswhoreviewed //could have been done with get tbh

### Postman to run the endpoints
with collection file "Test API.postman_collection" 

### Requirements
requirements 1 and 2 done
enhancement 3 and 4 