Starting project for dev development
==============
There is full, ready to start example for dev environment. Read it carefully and change variables for your project.

> All jobs in jenkins environment will contain same `GENERAL`, `DOCKER SETTINGS` and `STACK WORKAROUND` blocks.\
> Each job except `directories setup` should be a parametrized job.

Create `Choice Parameter` for build with given settings:
- name=BUILD_CACHE_ENABLED
- variants:
    ```text
    &#9166;(empty line)
    --force-rm --no-cache
    ```
- description=""

After all jobs configured and run you should exec into php container thorugh portainer engine and run:
```bash
php artisan possport:keys --force &&
php artisan possport:client --client --no-interaction &&
php artisan possport:client --password --no-interaction
``` 

Copy received keys and save them into docs. There is only one chance to see them in decoded state.

## GENERAL block for configuration
```bash
################################ GENERAL ################################

## .env.${ENVIRONMENT} file
export ENVIRONMENT="dev"

## Project name for all needed affixes/suffixes/preffixes
export PROJECT_NAME="attract-starter-kit"

## Choose this folder name with confs
export DOCKER_ENV_TYPE="dev"
cd ./docker/${DOCKER_ENV_TYPE}/

## Change this name to real docker proxy container name
export DOCKER_PROXY_CONTAINER_NAME="ag-docker-proxy"

## Use this variable to change compose project name. Example - ag-starter-kit-release
export DOCKER_ENV_TYPE_ALIAS="stage"  
```

## DOCKER SETTINGS block for configuration
```bash
################################ DOCKER SETTINGS #########################

export PROJECT_NAME_PREFIX="ag-${PROJECT_NAME}-${DOCKER_ENV_TYPE_ALIAS}"
export DOCKER_HOST="tcp://148.251.99.196:2376"
export DOCKER_TLS_VERIFY=1
export DOCKER_CERT_PATH='/certs/php-dev'
export HUB_REPOSITORY_DOMAIN='docker.attractgroup.com/'
export COMPOSE_PROJECT_NAME="${PROJECT_NAME_PREFIX}-{db|api|redis|urility}"
export COMPOSE_HTTP_TIMEOUT=300
export NETWORK_NAME="${PROJECT_NAME_PREFIX}-network"
# Correct free subnetmask. Use this formulae to calculate it: For example, project id in CRM is 827
# If id contain 3 digits, the we should prepend it via 0, like 0827.
# Ip address will be: [10].[27 + 41].[0 + 8].0, so ip should be: 10.68.8.0/24.
export NETWORK_SUBNET="[IP HERE]/24"
# Any unique label, for example api, utility, database or etc.
export SWARM_NODE_LABEL="[SWARM_NODE_LABEL]"
export REPLICAS_COUNT=2
export MAX_REPLICAS_PER_NODE=2
```

## Vault workaround

```bash
################################# Vault ######################################

export VAULT_ADDR='https://secrets.attractgroup.com/'
export VAULT_PATH='dev/projects/drrp/main'

chmod u+x ./vault.sh
source ./vault.sh $VAULT_ADDR $VAULT_TOKEN $VAULT_PATH

export VAULT_PATH='dev/projects/drrp/db'
source ./vault.sh $VAULT_ADDR $VAULT_TOKEN $VAULT_PATH 0

export VAULT_PATH='dev/projects/drrp/redis'
source ./vault.sh $VAULT_ADDR $VAULT_TOKEN $VAULT_PATH 0

export VAULT_PATH='dev/projects/drrp/rrp'
source ./vault.sh $VAULT_ADDR $VAULT_TOKEN $VAULT_PATH 0
```

## Build workaround

```bash
################################ BUILD STAGE  ############################

chmod u+x ./build.sh
export BUILD_FILE="./compose-files/docker-compose.api.build.yml"
./build.sh  $DOCKER_HUB_PWD $BUILD_FILE $BUILD_CACHE_ENABLED
```

## STACK WORKAROUND block for configuration

```bash
################################ STACK WORKAROUND ########################

chmod u+x ./stack_workaround.sh

## Remove stack
## Uncomment if you need to remove stack
## NOTE: Job will fails if stack does not exists.
#./stack_workaround.sh rm

## Remove Network
## Uncomment if you need to remove project network.
## NOTE: Job will fails if stack connected to this network is up. 
## Uncomment above command to remove swarm stack or  stop it manually.
#./stack_workaround.sh rm_network

chmod u+x ./network.sh
export TRUSTED_PROXY_IP=$(./network.sh $NETWORK_NAME $DOCKER_PROXY_CONTAINER_NAME $NETWORK_SUBNET)

################################## DEPLOY #################################

chmod u+x ./deploy.sh
###################
# Use stack deploy file, other rows should be removed.
## Use this for DB stack
export COMPOSE_FILE_DEPLOY="./compose-files/docker-compose.db.swarm.yml"
###################
## Use this for REDIS stack
export COMPOSE_FILE_DEPLOY="./compose-files/docker-compose.redis.swarm.yml"
###################
## Use this for API stack
export COMPOSE_FILE_DEPLOY="./compose-files/docker-compose.api.swarm.yml"
###################
export COMPOSE_FILE_DEPLOY="./compose-files/docker-compose.utility.swarm.yml"
###################


## Deploy project.
## NOTE: Job will fails if you have in docker-compose.swarm.yml file any volumes that don't physically exists on server.
## NOTE: Swarm doesn't create volumes automatically.
./deploy.sh $DOCKER_HUB_PWD $COMPOSE_FILE_DEPLOY $BUILD_FILE
```

## Directories Jenkins shell environment

```bash
###################################### GENERAL ##########################################

#...

#################################### DOCKER SETTINGS ####################################

#...

################################ STACK WORKAROUND #######################################

cd ./docker/${DOCKER_ENV_TYPE}/

export COMPOSE_FILE="./compose-files/docker-compose.directory-setup.yml"

docker-compose up -d
```

## Database Jenkins shell environment

```bash
###################################### GENERAL ##########################################

#...

#################################### DOCKER SETTINGS ####################################

#...
export SWARM_NODE_LABEL=database

################################ PROJECT ENVIRONMENT ####################################

export MYSQL_DATABASE="attract-starter-api"
export MYSQL_USER="some-user"
export MYSQL_PASSWORD="some-user-pwd"
export MYSQL_ROOT_PASSWORD="some-root-user-pwd"

export PHP_DB_GUI_HOST="pma.attract-starter-dev.php-cd.attractgroup.com"

#################################### VAULT ##############################################

#...

################################ STACK WORKAROUND #######################################

#...

sleep 30

export DB_CONTAINER_ID=$(docker ps | grep $COMPOSE_PROJECT_NAME"_db" | awk '{print $1}')
docker exec $DB_CONTAINER_ID /bin/bash -c "/usr/bin/mysql -u $MYSQL_USER --password='$MYSQL_PASSWORD' $MYSQL_DATABASE"

```

## Redis Jenkins shell environment

```bash
###################################### GENERAL ##########################################

#...

#################################### DOCKER SETTINGS ####################################

#...
export SWARM_NODE_LABEL=redis

################################ PROJECT ENVIRONMENT ####################################

export REDIS_PASSWORD="some-strong-password"
export MEMORY_LIMIT="500M"
export MEMORY_RESERVED="200M"

#################################### VAULT ##############################################

#...

################################ STACK WORKAROUND #######################################

#...


```

## API/Utility Jenkins shell environment
```bash
###################################### GENERAL ##########################################

#...

#################################### DOCKER SETTINGS ####################################

#...
export SWARM_NODE_LABEL=api/utility

################################ PROJECT ENVIRONMENT ####################################

export APP_DEBUG="true"
export APP_NAME="VizableU"
export CACHE_DRIVER="redis"
export QUEUE_DRIVER="redis"
export SESSION_DRIVER="redis"
export SESSION_LIFE_TIME=120

# Filesystem
export NGINX_IMAGE_INTERACTS_S3_PROXY_PASS="FULL BUCKET HOST WITH REGION"
# Change to s3 after AWS configured.
export FILESYSTEM_DRIVER="public"
## AWS Filesystem Environment
export AWS_DEFAULT_REGION="AWS REGION"
export AWS_BUCKET="AWS BUCKET"
export AWS_ACCESS_KEY_ID="AWS KEY ID"
export AWS_SECRET_ACCESS_KEY="AWS SECRET KEY"


# Start User
export START_USER_EMAIL="backend@attractgroup.com"
export START_USER_PASSWORD='hfyh374Dgh37$5hdj'


# Start Password Client
export KIT_AUTH_PASSWORD_GRANT_CLIENT_ID="2"
export KIT_AUTH_PASSWORD_GRANT_CLIENT_SECRET="PWD SECRET KEY, received after first creation. NOTE: all keys encoded into db"

# Oauth
export OAUTH_ACCESS_TOKEN_EXPIRED_MINUTES=1440
export OAUTH_REFRESH_TOKEN_EXPIRED_MINUTES=7200

# DB
export MYSQL_HOST="${PROJECT_NAME_PREFIX}-db.db"
export MYSQL_DATABASE="attract-starter-api"
export MYSQL_USER="some-user"
export MYSQL_PASSWORD="some-user-pwd"

# REDIS
export REDIS_HOST="${PROJECT_NAME_PREFIX}-redis.redis"
export REDIS_PORT=6379
export REDIS_PASSWORD="some-strong-password"


# SMTP
export SMTP_EMAIL_FROM="EMAIL HERE"
export SMTP_USER_NAME="LOGIN CREDENTIAL HERE"
export SMTP_USER_PASSWORD="PWD CREDENTIAL HERE"

# HOSTS
export VIRTUAL_PORT=80
export FPM_HOST="${COMPOSE_PROJECT_NAME}.php:9000"
## Change this for real
export APP_HOST="api.attract-starter-dev.php-cd.attractgroup.com"
export APP_URL="https://${APP_HOST}"
export SPA_BACKEND_URL="https://app.attract-starter-dev.php-cd.attractgroup.com"
export SPA_FRONTEND_URL=$SPA_BACKEND_URL

# PHP limitations
export PHP_MEMORY_LIMIT="512M"
export PHP_POST_MAX_SIZE="20M"
export PHP_UPLOAD_MAX_SIZE="40M"

# Services
export PACKAGIST_TOKEN="PACKAGIST TOKEN RECEIVED FROM TEAM LEAD"
export FCM_SERVER_KEY="FCM APP SERVER KEY"

#################################### VAULT ##############################################

#...

#################################### BUILD STAGE ########################################

#...

################################ STACK WORKAROUND #######################################

#...
```

## Echo Server Jenkins shell environment

```bash
################################ GENERAL ################################

#...

################################ DOCKER SETTINGS #########################

#...
export SWARM_NODE_LABEL="echoserver"

################################ PROJECT ENVIRONMENT ######################

export LARAVEL_ECHO_SERVER_AUTH_HOST="https://example.php-cd.attractgroup.com"
export LARAVEL_ECHO_SERVER_DEBUG="true"
export LARAVEL_ECHO_SERVER_DB="redis"

# REDIS
export REDIS_HOST="${PROJECT_NAME_PREFIX}-redis.redis"
export REDIS_PORT_NUM=6379
export REDIS_PASSWORD="some-strong-password"
export REDIS_PREFIX="php_starter_kit_app_database_"
export REDIS_DB=0

# URLs
export VIRTUAL_HOST="echo.attract-starter-dev.php-cd.attractgroup.com"
export VIRTUAL_PORT=7070

################################ STACK WORKAROUND ########################

#...
```