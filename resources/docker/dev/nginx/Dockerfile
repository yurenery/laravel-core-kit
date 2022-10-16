FROM attractgrouphub/alpine-nginx-npm-yarn:2.0.1

ARG ENVIRONMENT
ARG DOCKER_ENV_TYPE

MAINTAINER Amondar-SO

################# Pre install NPM packets with cache. UNCOMMENT if needed
#RUN mkdir -p /var/www/project/node-modules && chmod 775 -R /var/www/project
#COPY ./project-files/package.json /var/www/project
#RUN cd /var/www/project && yarn

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
    chmod u+x ./replica-checker.sh


################# Create PROJECT environment
COPY ./project-files /var/www/project
WORKDIR /var/www/project
RUN cp -rf .env.${ENVIRONMENT} .env && \
    chmod 775 -R ./bootstrap && \
    chmod 775 -R ./storage && \
    ln -s /var/www/project/storage/app/public /var/www/project/public/storage

EXPOSE 443
EXPOSE 80

################# NPM packages build if needed. UNCOMMENT
#npm ...

################# Server RUN commands
CMD supervisord -n --configuration /var/www/docker/supervisor.conf



