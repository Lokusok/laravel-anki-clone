## Backend API для Anki-клона

Приложение для эффективного запоминания.

- Laravel
- Sanctum
- Docker
- Тесты
- MySQL
- Scribe
- PHPStan

---

## Запуск проекта

1. `docker compose up --build`
2. `docker compose exec php-fpm composer install`
3. `docker compose exec php-fpm chmod -R 777 ./`
4. `docker compose exec php-fpm php artisan key:generate`
5. `docker compose exec php-fpm php artisan migrate`

#### Для тестов:

1. `docker compose exec php-fpm php artisan migrate --seed --env=testing`
2. `docker compose exec php-fpm php artisan test`

---

## Технологии, использованные при разработке

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)![Redis](https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white)![Swagger](https://img.shields.io/badge/-Swagger-%23Clojure?style=for-the-badge&logo=swagger&logoColor=white)![GitHub Actions](https://img.shields.io/badge/github%20actions-%232671E5.svg?style=for-the-badge&logo=githubactions&logoColor=white)

---

Ссылка на [Frontend-часть](https://github.com/Lokusok/frontend-anki-clone)
