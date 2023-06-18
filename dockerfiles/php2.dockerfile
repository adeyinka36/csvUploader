# Start from PHP 8 alpine
FROM php:8-fpm-alpine

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add necessary packages for npm & node
RUN apk add --update npm

# Install node
RUN apk add --update nodejs

# Check versions
RUN php -v; composer -V; node -v; npm -v

