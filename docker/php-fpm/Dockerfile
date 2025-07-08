FROM public.ecr.aws/docker/library/php:8.4-fpm-bookworm AS base

SHELL ["/bin/bash", "-euxo", "pipefail", "-c"]

ARG TIMEZONE=UTC
ARG XDEBUG_CLIENT_HOST=host.docker.internal
ARG XDEBUG_START_WITH_REQUEST=trigger
ARG XDEBUG_CLIENT_PORT=9003
ARG WWWUSER

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y --no-install-recommends \
        openssl \
        libicu-dev \
        git \
        unzip \
        sqlite3 \
        # For ext-zip
        zlib1g-dev \
        libzip-dev \
        mariadb-client \
        wget \
        # For fixing internal docker host for linux
        net-tools \
        # For configuring locales
        locales \
        # For mysqldump
        mariadb-client \
        # For pgsql \
        libpq-dev \
        # For gd \
        libfreetype6-dev \
        # Clean up apt
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/* \
        # Install Composer
        && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
        && mkdir /run/php \
        # Set timezone
        && ln -snf "/usr/share/zoneinfo/${TIMEZONE}" /etc/localtime && echo "${TIMEZONE}" > /etc/timezone \
        && "date" \
        && chmod +x /usr/local/bin/install-php-extensions \
        # Type docker-php-ext-install to see available extensions
        && docker-php-ext-install pdo pdo_mysql \
        && docker-php-ext-install pdo_pgsql \
        # Enable APCu
        && pecl install apcu \
        && docker-php-ext-enable apcu \
        # Install OPCache
        && docker-php-ext-install opcache \
        # Install mbstring
        && install-php-extensions mbstring \
        # Install zip
        && docker-php-ext-install zip \
        && docker-php-ext-install bcmath \
        && docker-php-ext-install sockets \
        # PCNTL for Laravel horizon
        && docker-php-ext-install pcntl \
        && docker-php-ext-configure pcntl --enable-pcntl \
        # Install INTL \
        && docker-php-ext-install intl \
        && docker-php-ext-configure intl \
        # Install Redis
        && pecl install redis \
        && docker-php-ext-enable redis \
        # Create user
        && ORIG_UID=$(id -u www-data) && usermod -u "${WWWUSER:-$ORIG_UID}" www-data \
        # Configure locales
        && sed -i 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen \
        && locale-gen en_US.UTF-8 \
        && chown www-data:www-data /var/www
# Copy PHP configuration
COPY ./docker/php-fpm/conf.d/ "$PHP_INI_DIR/conf.d/"


# Copy PHP-FPM configuration
COPY ./docker/php-fpm/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -p /var/run/php \
    && chown -R www-data:www-data /var/run/php

COPY ./docker/php-fpm/run.sh /run.sh
RUN chmod a+x /run.sh

WORKDIR /var/www

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

CMD ["/run.sh"]

FROM base AS dev

# Install Xdebug
RUN touch /usr/local/etc/php/xdebug.log \
    && chmod a+rw /usr/local/etc/php/xdebug.log \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

USER www-data

FROM base AS prod

COPY --chown=www-data:www-data . /var/www
ENV APP_ENV=production
USER www-data

RUN composer install --no-interaction --no-dev --optimize-autoloader --no-progress --no-suggest --no-scripts --prefer-dist \
    && composer clear-cache \
    && rm -rf /var/www/.composer/cache
