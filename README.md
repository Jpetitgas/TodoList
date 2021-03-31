# TodoList

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b5a42a9b67444876a17443b2d621d7ab)](https://www.codacy.com/gh/Jpetitgas/TodoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jpetitgas/TodoList&amp;utm_campaign=Badge_Grade)

How to install the project with your own local environment (like Wampserver for to have database)

## Configuration

* Symfony 4.4
* PHP 7.4.9
* MySQL 5.7.31

## Follow each following steps

First clone this repository from your terminal in your preferred project directory: git clone
> You need to edit the ".env" file:
> Setup Doctrine for DB connection (DATABASE_URL)

From your terminal, go to the project directory and tape those command line :

* composer install
* php bin/console doctrine:database:create
* php bin/console make:migration
* php bin/console doctrine:migrations:migrate
* php bin/console doctrine:fixtures:load
* symfony server:start -d

iden : admin

password: admin
