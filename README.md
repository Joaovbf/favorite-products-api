## Getting started

This project uses laradock to set a bunch of useful docker containers in a fast way. 
So to set up these containers you should clone the repo via git and run the following commands, inside the project directory:

1. `git submodule init`
2. `git submodule update`
3. `cd laradock`
4. `cp .env.example .env`

At this point you need to checkup if the php version is `PHP_VERSION=8.3`, so after this run:

1. `docker compose up -d nginx redis postgres`

This command should take a while, when it's done we need to set up the laravel environment, following these commands:

1. `docker compose exec -u laradock workspace composer install`
2. Check if Laravel .env is created, if it's not run `cd ../ && cp .env .env.example`
3. Set up database connection data
```dotenv
DB_CONNECTION=pgsql
DB_HOST=laradock-postgres-1
DB_PORT=5432
DB_DATABASE=favorite-product-api-db
DB_USERNAME=default
DB_PASSWORD=secret
```

4. Run Migrations `docker compose exec -u laradock workspace php artisan migrate`
5. Set up Redis connection data
```dotenv
CACHE_STORE=redis
REDIS_CLIENT=phpredis
REDIS_HOST=laradock-redis-1
REDIS_PASSWORD=secret_redis
REDIS_PORT=6379
```

Ok, now the app is available at http://localhost
