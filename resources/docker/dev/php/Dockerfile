FROM attractgrouphub/alpine-php-composer:3.0.9

MAINTAINER Amondar-SO

ARG ENVIRONMENT
ARG PACKAGIST_TOKEN
ARG DOCKER_ENV_TYPE
ARG PHP_DATE_TIMEZONE
ARG PHP_MEMORY_LIMIT
ARG PHP_POST_MAX_SIZE
ARG PHP_UPLOAD_MAX_SIZE
ARG PHP_SMTP
ARG PHP_SMTP_PORT

################# Deploy Config
COPY ./docker/${DOCKER_ENV_TYPE}/php /var/www/docker
WORKDIR /var/www/docker
RUN mv php-fpm.conf /usr/local/etc/ && \
    mv www.conf /usr/local/etc/php-fpm.d/www.conf && \
    mv php.ini /usr/local/etc/php/conf.d/php.ini

################# Pre install packages
RUN mkdir -p /var/www/project/vendor && \
    chmod 775 -R ./
COPY ./project-files/composer.json /var/www/project
WORKDIR /var/www/project
RUN composer config --global --auth http-basic.repo.packagist.com token ${PACKAGIST_TOKEN}
RUN composer install --no-scripts --no-autoloader

################# Create PROJECT environment
COPY ./project-files /var/www/project
RUN cp -rf .env.${ENVIRONMENT} .env && \
    chmod 777 -R ./bootstrap && \
    chmod 777 -R ./storage && \
    ln -s /var/www/project/storage/app/public /var/www/project/public/storage

EXPOSE 80
EXPOSE 9000

CMD composer dump-autoload --optimize && \
    #php artisan passport:keys --force && \
    php artisan route:cache && \
    php artisan config:cache && \
    php artisan event:cache && \
    php-fpm -F -R

