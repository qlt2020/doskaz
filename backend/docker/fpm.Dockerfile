FROM php:7.4.14-fpm AS base
RUN apt-get update \
    && apt-get install -y \
        libpq-dev \
        libzip-dev \
        unzip \
        librabbitmq-dev \
        libmagickwand-dev \
        jpegoptim \
        optipng \
        pngquant \
        gifsicle \
        webp \
        libgmp-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install amqp \
    && pecl install imagick \
    && echo extension=amqp.so > /usr/local/etc/php/conf.d/amqp.ini \
    && echo extension=imagick.so > /usr/local/etc/php/conf.d/imagick.ini

RUN docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        zip \
        opcache \
        gmp

FROM base as build
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-suggest && composer clear-cache
COPY . .
ARG app_env
RUN composer dump-autoload --optimize && php bin/console c:c --env=$app_env --no-debug


FROM base AS dev
ARG user_id=1000
RUN usermod -u $user_id www-data && groupmod -g $user_id www-data
RUN mkdir /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/storage
VOLUME /var/www/html/storage
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/override.ini $PHP_INI_DIR/conf.d/


FROM base as prod
ENV APP_ENV prod
COPY --from=build /var/www/html /var/www/html
#RUN mkdir /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/storage
VOLUME /var/www/html/storage
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/override.ini $PHP_INI_DIR/conf.d/
COPY ./docker/opcache.ini $PHP_INI_DIR/conf.d/