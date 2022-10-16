FROM attractgrouphub/alpine-nginx-npm-yarn:2.0.1

ARG ENVIRONMENT
ARG DOCKER_ENV_TYPE

MAINTAINER Amondar-SO

################# Deploy config
COPY ./docker/${DOCKER_ENV_TYPE}/nginx /var/www/docker

################# Create SERVER environment
WORKDIR /var/www/docker
RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.default && \
    mkdir /etc/nginx/sites-enabled/ && \
    mv nginx-app.conf /etc/nginx/sites-enabled/nginx-app.conf && \
    mv nginx.conf /etc/nginx/nginx.conf && \
    mv params /etc/nginx/params && \
    mv nginx-image-app.conf /etc/nginx/sites-enabled/nginx-image-app.conf && \
    chmod u+x start.sh


################# Create PROJECT environment
COPY ./project-files /var/www/project
WORKDIR /var/www/project
RUN touch ./storage/logs/nginx-error.log && \
    echo "" > ./storage/logs/nginx-error.log && \
    chmod 775 -R ./bootstrap && \
    chmod 775 -R ./storage

EXPOSE 443
EXPOSE 80


################# Server RUN commands. DO NOT CHANGE ORDER
CMD cp -rf .env.${ENVIRONMENT} .env && \
    ln -sf /var/www/docker/nginx-app.conf /etc/nginx/sites-enabled/nginx-app.conf && \
    ln -sf /var/www/docker/nginx-image-app.conf /etc/nginx/sites-enabled/nginx-image-app.conf && \
    /var/www/docker/start.sh



