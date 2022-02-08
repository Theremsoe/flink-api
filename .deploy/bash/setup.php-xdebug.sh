#!/bin/bash

set -xe

# Xdebug
pecl install xdebug;
docker-php-ext-enable xdebug;

# Remove the default xdebug configuration by us configuration
rm "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini";
cp .deploy/php/docker-php-ext-xdebug.ini "$PHP_INI_DIR/conf.d";
