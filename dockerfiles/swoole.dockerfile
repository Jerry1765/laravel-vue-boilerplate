FROM php:8.4.10-cli-alpine

RUN apk update && \
    apk add --update linux-headers && \
    apk add --no-cache git libzip-dev nodejs npm zip unzip autoconf gcc g++ make openssh-client curl-dev libxml2-dev php-soap libpng jpeg-dev freetype-dev jpegoptim optipng pngquant gifsicle && \
    apk add supervisor

RUN docker-php-ext-install pdo pdo_mysql opcache pcntl exif zip soap bcmath curl

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN pecl install redis && \
    docker-php-ext-enable redis

RUN pecl install openswoole && \
    docker-php-ext-enable openswoole

ENV NODE_PATH "/home/www-data/.npm-global/lib/node_modules"

RUN mkdir -p "/home/www-data/.npm-global/" && \
    npm config set prefix "/home/www-data/.npm-global/" && \
    npm install -g chokidar

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir src db

COPY ../ ./src

WORKDIR /var/www/html/src

COPY .env.example .env

RUN chown -R www-data:www-data /var/www/html/src && \
    chmod -R 775 /var/www/html/src/storage /var/www/html/src/bootstrap/cache

RUN npm install

RUN mkdir -p /var/log/supervisor && \
    chmod 775 /var/log/supervisor

EXPOSE 7654

CMD ["/bin/sh", "-ec", "rm -f composer.lock && composer install && exec supervisord -c /etc/supervisor/conf.d/supervisord.conf"]
