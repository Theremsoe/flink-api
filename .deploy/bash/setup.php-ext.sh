#!/bin/bash

set -xe;

apt-get update && apt-get install --no-install-recommends -y libpq-dev libzip-dev zip;

docker-php-ext-install pdo pdo_pgsql pgsql zip pcntl;
