FROM php:8.0-cli as php-cli-stage

WORKDIR /var/www/html

# Copy project
COPY --chown=www-data:www-data . /var/www/html

## Add docker compose wait tool
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.6.0/wait /tmp/wait
RUN chown www-data:www-data /tmp/wait
RUN chmod 550 /tmp/wait

# Install all extensions, packages and configure project
RUN bash .deploy/bash/setup.php.sh
RUN bash .deploy/bash/setup.php-xdebug.sh
USER www-data
RUN make install mode=production

#######################################
# PHP API Server
#######################################
FROM php-cli-stage as php-api
CMD /tmp/wait && make db-fresh && make serve
