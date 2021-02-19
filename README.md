# Symfony-control 

## Requirements :
Prerequisites for the proper functioning of the application
 - [PHP 7.2](https://lmgtfy.com/?q=How%20to%20get%20php%207.2&iie=1)
 - [Composer](https://getcomposer.org/)
 - [Symfony 5.2](https://symfony.com/)

## Installation

### Get the project :

- git clone :  https://github.com/AhmedBouk/Symfony-control.git

### Install bundles with composer :

- composer install

### Create an .env.local, add your database URL                           

- DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

### Create your MySQL database with the following commands:
- php bin/console make:migration


- php bin/console doctrine:migrations:migrate

## Contributors :

 - [Ahmed Bouknana](https://github.com/AhmedBouk)