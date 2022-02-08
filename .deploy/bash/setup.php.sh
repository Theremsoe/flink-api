#!/bin/bash

set -xe;

# Use the default production configuration
mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini";

# Common installation
apt-get update && apt-get install --no-install-recommends -y libpq-dev libzip-dev zip;

docker-php-ext-install pdo pdo_pgsql pgsql zip pcntl;
