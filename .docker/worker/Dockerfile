FROM php:8.1.1-cli-alpine

# Add Repositories
RUN rm -f /etc/apk/repositories &&\
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/community" >> /etc/apk/repositories

# Add Build Dependencies
RUN apk update && apk add --no-cache  \
    libzip-dev \
    libmcrypt-dev \
    libjpeg-turbo-dev \
    libjpeg-turbo \
    jpeg-dev \
    libpng-dev \
    libxml2-dev \
    bzip2-dev \
    libwebp-dev \
    zip \
    jpegoptim \
    pngquant \
    optipng \
    icu-dev \
    freetype-dev \
    libpq \
    zlib-dev \
    imap-dev \
    git \
    vim \
    openssl-dev \
    pcre-dev  \
    pcre2-dev \
    supervisor

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd
RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

# Configure & Install Extension
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j "$(nproc)" \
    opcache \
    pdo \
    pdo_pgsql \
    pgsql


RUN apk add --update --no-cache autoconf g++ make \
&& pecl install redis \
&&  docker-php-ext-enable redis

#RUN pecl install openswoole-4.7.2 && docker-php-ext-enable openswoole

## Crontab
RUN apk update
RUN touch /var/log/cron.log
COPY .docker/worker/cron /etc/cron.d/cron
RUN chmod -R 755 /etc/cron.d/cron

RUN /usr/bin/crontab /etc/cron.d/cron
#CMD cron -f

## Supervisor
#COPY supervisord.conf /etc/supervisord.conf
#COPY .docker/worker/worker.conf /etc/supervisor/conf.d/worker.conf
COPY .docker/worker/supervisord.conf /etc/supervisord.conf
#RUN chmod -R 644 /etc/supervisor/conf.d/worker.conf
RUN chmod -R 644 /etc/supervisord.conf
RUN touch /tmp/supervisor.sock
RUN chmod 777 /tmp/supervisor.sock
# Remove Build Dependencies
#RUN apk del -f .build-deps

COPY .docker/worker/php.ini $PHP_INI_DIR/conf.d/

# Setup Working Dir
WORKDIR /var/www/app

RUN curl -sS https://getcomposer.org/installer | \
php -- --install-dir=/usr/bin/ --filename=composer
#
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
#ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]