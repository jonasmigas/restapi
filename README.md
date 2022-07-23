# Test for Applicants

> ### ⚠️ Read it first!
> I know... you probably love to code (we also love it!). But please, read this file until the end and make sure you understood the requirements before you start coding! If you have any question, just let us know!

---
## Backstory

We want to build a *next-gen* company review website in order to help people around the world finding their **Best Place to Work**.

## Requirements

The goal is to create a REST-API with the following endpoints:

1. Submit a review for a company
    * A review cannot be created without: title, user and ratings (culture, management, work_live_balance, career_development).
    * A review title must have between 10 and 200 characters.
    * A rating must be a number between 0 and 5.
2. List of top 10 recommended companies
    * Company rating is calculated based on the AVG of all dimensions.

### Enhancement

Choose **at least one** of the following and add it to the API implementation:

1. Build a functionality that rewards users with badges: Users that review at least three companies, users that write a review with at least 400 characters, etc. Try to come up with some ideas on your own and make the gamification system efficient and extensible (go crazy!).
2. Build a simple [sentiment analysis](https://en.wikipedia.org/wiki/Sentiment_analysis): Given a review as input, try to guess whether it is positive, negative or even abusive.
3. Build a "users who reviewed this company also reviewed" functionality: Given a particular company list other companies that other users also reviewed.
4. Retrieve the highest and lowest rating review for a particular company. Try to make it as efficient as possibility.
5. Create a tag cloud with the most popular terms for a given company. Try to make it as meaningful as possible, e.g. by filtering out stop words like "the", "and", etc.

----
## Technology

This is your chance to show off, use PHP to build a clearly-written application. Data is provided via a json array in [data.json](./app/data.json) file.

Also provided is a basic [Symfony](https://symfony.com/) application skeleton with a single dummy "Hello!" controller.

### Must haves

1. Use PHP >= 7.2
2. Add tests to your code!
3. Add a postman collection to document your endpoints
4. Implement it in a way that you can change the storage layer without much effort.
5. Follow a loose coupling approach between components
3. Use git as a version control system. Make sure that you have clear commit messages and understandable steps in your git-history ([How to Write a Git Commit Message](https://chris.beams.io/posts/git-commit/))

### Nice to haves

1. Add tests to your postman collection
2. Implement a solution using the CQRS pattern.
3. Implement a solution following a DDD (Domain-drive Design) approach. If you need a domain expert, you can always get in touch with us.
4. Make use of a relational-database (e.g. MySQL or PostgreSQL), using a script to migrate all the data from [data.json](./app/data.json) to the database.

### Basic Tooling

To get you started in the right direction, we give you a couple of basic tooling and folder structure. The application is running with [docker](https://docs.docker.com/) with two main containers using a PHP container and a NGINX container to run the server. The application can be run using the following command (within the "docker" folder):

```sh
docker-compose up
```

Additionally, the unit test can run with the following command (after the application is already running):

```sh
docker exec docker_php_1 composer test
```

**Note:** Feel free to improve it, extend it as you go through the assignment or completely discard it if you feel that will not help you achieve your goal. If you want to use other solution (e.g. built in PHP Webserver or custom docker setup) then provide the instructions for how we can run your solution on the README.md file and note that **it should be runnable with one command**.

----
> ### ⚠️ Tips
> * We appreciate the fact that you think outside the box and add additional technical approaches that will improve your application scalability and code or add any additional cool features to the application, **but first...** Make sure you completed all the [Must haves](#must-haves) and ideally the [Nice to haves](#nice-to-haves) first!
> * In general, we'd rather see something simple you understand than something fancy you can't explain.
> * Using other libraries or frameworks from the PHP ecosystem is completely fine, as long as you can explain what they do.
> * Before you contact us saying that you finished the technical challenge, take 5 minutes to clean-up your code and repository;
