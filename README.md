> [!NOTE]
> Take-home test from 2020, kept as submitted. A retrospective was added in October 2026: see [Looking back](#looking-back-added-october-2026).

# Bus Service Project Assignment

> ### This is the back-end side of the Bus Service test given to me.

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation#installation)

Please also note that you need to install redis on your server. [Redis Quickstart](https://redis.io/topics/quickstart)

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

You would also need to change the cache driver to use redis

```
CACHE_DRIVER=redis
```

If you have additional configuration for connecting to redis, please refer to the following file

    config/database.php
    
Add google maps api key on .env file

```
GOOGLE_MAPS_SERVICE_KEY=<google maps api key here>
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

---

## Looking back (added October 2026)

*Everything above this line is the original README. This section was added later and the code was not changed.*

### What I would improve

- **Auth:** a browser cannot keep an OAuth client secret, so I would make these read-only endpoints public (validated and rate limited) or keep the secret behind a small server-side proxy.
- **Next arrivals:** compute departures as full datetimes in the stop's timezone, so the search crosses midnight and the whole week, and return an `arrives_at` timestamp instead of minutes.
- **Bus stop data:** import stops once, keyed by Google `place_id` with numeric coordinates and a spatial index, and answer "near me" from the database by distance instead of calling Google inside a GET.
- **HTTP layer:** Form Requests for `lat`, `long` and `radius`, route model binding so an unknown stop is a 404, and API Resources with one response shape.
- **Structure:** repositories only for queries, Google and ETA logic in small injectable classes, and per-stop cache invalidation instead of `cache:clear`.
- **Tests:** fake Google and the cache, add feature tests for both endpoints, and pin midnight and week-wrap cases with `Carbon::setTestNow()`.
- **Setup:** Docker Compose for MySQL and Redis, a complete `.env.example`, a seeder that needs no Google key, CI with Larastan and Pint, and current Laravel and PHP.

### Corrections to the original README

- The API is served at `/bus-stops`, not under `/api` (the prefix was removed late in development).
