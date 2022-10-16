#!/usr/bin/env bash

export DOCKER_ENV_TYPE=local
export ENVIRONMENT=$1
export COMPOSE_PROJECT_NAME=$2
export IS_OSX=$3||0
export ACTION=$4||0

if [ "$IS_OSX" == "1" ]
then
    export CACHED=:cached
    echo "Mac OS X development started."
else
    export CACHED=
fi

echo "Will be used docker-compose file - ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml"
echo "Will be used env_file - .env.$ENVIRONMENT"

if [ "$ACTION" == "down" ]
then
    docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml down
    echo "Docker compose exited successfully."
    exit 0;
fi

if [ "$ACTION" == "build" ]
then
    docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml build  --force-rm --no-cache
    echo "Docker compose built successfully."
    exit 0;
fi

if [ "$ACTION" == "reload" ]
then
    docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml down
    docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml up -d
    echo "Docker compose reloaded successfully."
    exit 0;
fi

docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml down &&
docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml build  --force-rm --no-cache &&
docker-compose -f ./compose-files/docker-compose.without-proxy.$ENVIRONMENT.yml up -d
