FROM php:8.0-cli

WORKDIR /var/www/html

# Copy project
COPY --chown=www-data:www-data . /var/www/html

# Configure php (with extensions)
RUN bash .deploy/bash/setup.php.sh
RUN bash .deploy/bash/setup.php-gd.sh

USER www-data

# Install project with developent packages and testing environment
RUN make install mode=testing

# Run Tests
CMD make test
