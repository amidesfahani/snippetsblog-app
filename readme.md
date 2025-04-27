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
```

### Run Migrations
```bash
docker-compose exec app php artisan migrate
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