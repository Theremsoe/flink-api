#!/bin/bash

set -xe

pecl install swoole;
docker-php-ext-enable swoole;
