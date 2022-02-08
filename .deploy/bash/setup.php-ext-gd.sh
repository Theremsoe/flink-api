#!/bin/bash

set -xe;

# PHP GD extension
apt-get install --no-install-recommends -y libpng-dev libjpeg62-turbo-dev libfreetype6-dev;
docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/;
docker-php-ext-install gd;
