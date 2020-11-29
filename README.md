# Bus Service Project Assignment

> ### This is the back-end side of the Bus Service test given to me.

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation#installation)

Clone the repository

    git clone git@github.com:jpaylaga/bus-service.git

Switch to the repo folder

    cd bus-service

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate
    
Setup database connection in .env by supplying the following

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Run the database migrations

    php artisan migrate
    
Install laravel passport

    php artisan passport:install
    
Add a passport client with grant type `client_credentials` and save its details

    php artisan passport:client --client

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:jpaylaga/bus-service.git
    cd bus-service
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan passport:install
    php artisan passport:client --client
    php artisan serve

## Database seeding

**Populate the database with seed data with relationships which includes buses, bus schedules, bus stops, and bus stop distances. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Open the SampleDataSeeder and set the property values as per your requirement

    database/seeders/SampleDataSeeder.php

Run the database seeder and you're done

    php artisan db:seed --class=SampleDataSeeder

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## Dependencies

- [laravel/passport](https://github.com/laravel/passport) - For authenticating with oauth
- [alexpechkarev/google-maps](https://github.com/alexpechkarev/google-maps) - For searching nearby bus stops and eta calculations
- [salmanzafar/laravel-repository-pattern](https://github.com/salmanzafar949/Laravel-Repository-Pattern) - For repository pattern generation
