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
COPY ./docker/${DOCKER_ENV_TYPE}/php /var/www/docker
WORKDIR /var/www/docker
RUN mv php.ini /usr/local/etc/php/conf.d/php.ini && \
    mv php-fpm.conf /usr/local/etc/ && \
    mv www.conf /usr/local/etc/php-fpm.d/www.conf && \
    chmod u+x start.sh

################# Create PROJECT environment
COPY ./project-files /var/www/project
WORKDIR /var/www/project
RUN chmod 777 -R ./bootstrap && \
    chmod 777 -R ./storage

EXPOSE 80
EXPOSE 9000

################# Server RUN commands. DO NOT CHANGE ORDER
CMD /var/www/docker/start.sh && \
    php-fpm -F -R

