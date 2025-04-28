## Install & Run Docker

```bash
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret
```

### .env
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Run Migrations
```bash
docker-compose exec app php artisan migrate
```

### Create Test Data
```bash
docker-compose exec app php artisan db:seed
```

#### PHP Container
```bash
docker-compose exec app bash
```

#### Permissions
```bash
sudo chown -R $USER:$USER .
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
```

## Tests
```bash
docker-compose exec app bash -c "vendor/bin/phpunit"
```

## Pages
- Login http://localhost:8000/login
- Snippets http://localhost:8000/snippets