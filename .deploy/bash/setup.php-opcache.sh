#!/bin/bash

set -xe;

# OPcache
docker-php-ext-install opcache;

# Remove the default opcache configuration by us configuration
rm "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini";
cp .deploy/php/docker-php-ext-opcache.ini "$PHP_INI_DIR/conf.d";
