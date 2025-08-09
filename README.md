# Laravel 12 Swoole - Vue3 template

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Overview

Laravel 12 as API backend using PHP 8.4.10 with a Swoole server, with role-based access & auth + a Vue3, Tailwind, DaisyUI frontend. 
Complete with a docker-compose.yml containing MariaDB & Redis & Nginx to deploy on a server.

### Optimize images

```php
ImageOptimizer::optimize(storage_path('app/public/' . $pathToImage));
```

### Development Setup

1. Clone the repository
2. Edit `.env.example` and `docker-compose.yml` to match your environment
3. Run `docker compose up -d --build`
4. Generate application key: `php artisan key:generate`
5. Run storage link: `php artisan storage:link`
6. Run migrations: `php artisan migrate`
7. Run seeders: `php artisan db:seed`
8. Start development server: `npm run dev`
9. Access at `127.0.0.1:7654`
