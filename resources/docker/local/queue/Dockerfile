FROM attractgrouphub/alpine-php-composer:3.0.9

MAINTAINER Amondar-SO

ARG ENVIRONMENT
ARG DOCKER_ENV_TYPE
ARG PHP_DATE_TIMEZONE
ARG PHP_MEMORY_LIMIT
ARG PHP_POST_MAX_SIZE
ARG PHP_UPLOAD_MAX_SIZE
ARG PHP_SMTP
ARG PHP_SMTP_PORT

################# Deploy Config
COPY ./docker/${DOCKER_ENV_TYPE}/queue /var/www/docker
WORKDIR /var/www/docker
RUN mv php.ini /usr/local/etc/php/conf.d/php.ini

################# Create PROJECT environment
COPY ./project-files /var/www/project
WORKDIR /var/www/project
RUN touch ./storage/logs/listener.log && \
    echo "" > ./storage/logs/listener.log && \
    chmod 777 -R ./bootstrap && \
    chmod 777 -R ./storage

CMD cp -rf .env.${ENVIRONMENT} .env && \
    ln -sf /var/www/docker/php.ini /usr/local/etc/php/conf.d/php.ini && \
    supervisord -n --configuration /var/www/docker/supervisor.conf
