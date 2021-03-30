# TodoList

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/040e0002d74e47f89e4c161aa3ccd867)](https://app.codacy.com/gh/Jpetitgas/TodoList?utm_source=github.com&utm_medium=referral&utm_content=Jpetitgas/TodoList&utm_campaign=Badge_Grade_Settings)

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