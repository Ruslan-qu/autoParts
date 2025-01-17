FROM php:8.3-fpm-alpine3.20

# /Install packages
RUN apk --no-cache update
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash linux-headers

# /Install extensions
RUN apk add --no-cache icu-dev && docker-php-ext-configure intl && docker-php-ext-install intl

# /Install zip
RUN apk add --no-cache libzip-dev zip && docker-php-ext-install zip

# /Install pgsql
RUN apk add --no-cache libpq-dev && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql
 
# /Install imap
RUN set -xe && \
 apk add --update --no-cache \
 imap-dev \
 openssl-dev \
 krb5-dev && \
 (docker-php-ext-configure imap --with-kerberos --with-imap-ssl) && \
 (docker-php-ext-install imap > /dev/null) && \
 php -m | grep -F 'imap'

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

COPY ./ /var/www
WORKDIR /var/www

# /Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD php-fpm

EXPOSE 9000