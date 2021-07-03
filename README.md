## Installation in local

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.8/installation#installation)


Unzip the File or clone from remote repository

Switch to the repo folder

```
    cd weather-forecast-app
```

Install all the dependencies using composer

```
    composer install
```

```
    npm install && npm run dev
```

Copy the example env file and make the required configuration changes in the .env file

```
    cp .env.example .env
```

Set the database connection(main database and log) in .env
```
    DB_CONNECTION=
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
```

Run config:cache
```
    php artisan config:cache
```

Run the database migrations and seeders, make sure database is created
```
    php artisan migrate --seed
```

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command
```
    php artisan migrate:refresh --seed
```

```
    php artisan generate:test-user {number of users} {--mixed}
```
