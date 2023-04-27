# REST API

### PHP (PHP-FPM)

### Database (MariaDB)

### Webserver (Nginx)

```
cd docker

docker compose up -d

docker compose run php-fpm composer install

docker compose exec php-fpm php bin/console doctrine:migrations:migrate

docker compose exec php-fpm php bin/console lexik:jwt:generate-keypair
```
```
{
"username":"user",
"password":"pwd",
"firstName":"firstName",
"lastName":"lastName",
"phone":"+7654321",
"email":"user@g.g"
}
```

![Screenshot from 2023-04-28 00-47-47.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-47-47.png)
![Screenshot from 2023-04-28 00-48-09.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-48-09.png)
![Screenshot from 2023-04-28 00-48-13.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-48-13.png)
![Screenshot from 2023-04-28 00-48-20.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-48-20.png)
![Screenshot from 2023-04-28 00-49-02.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-49-02.png)
![Screenshot from 2023-04-28 00-58-26.png](..%2F..%2FPictures%2FScreenshot%20from%202023-04-28%2000-58-26.png)
