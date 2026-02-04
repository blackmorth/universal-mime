FROM php:8.2-cli

# Install system deps required by PHP extensions & tooling
RUN apt-get update && apt-get install -y \
    git curl zip unzip gosu \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# User switching via gosu
# Create dev user (UID/GID will be overriden dynamically using gosu)
RUN useradd -ms /bin/bash dev

# Install optional PHP extensions
RUN docker-php-ext-install pcntl

COPY ./docker/php.ini-dev /usr/local/etc/php/php.ini

WORKDIR /app
