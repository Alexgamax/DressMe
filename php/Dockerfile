FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libssl-dev pkg-config git unzip && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    rm -rf /var/lib/apt/lists/*
COPY php.ini /usr/local/etc/php/
WORKDIR /var/www/html
COPY /src /var/www/html/
RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]