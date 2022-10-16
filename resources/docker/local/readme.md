Starting project for local development
===============
Read the explanation carefully. You can change any files for your project if you know what are you doing.

## docker.sh
Docker local set up contains two files. You should choose which one you will use.
- `docker.sh` - simple docker set up WITHOUT proxy. API access will be through links, like: `https://localhost:7777/...`
- `dockerProxy.sh` - simple docker set up WITH proxy. API access will be through links, like: `https://my-project.local/...`

> NOTE: if you use `dockerProxy.sh` you should set up docker proxy by your self.

### File permissions

First of all we need give this sh script execute permissions:

```bash
chmod +x dockerProxy.sh
#or
chmod +x docker.sh
``` 

### Own environment 

> For example, we will use environment name: `my_env`. \
> NOTE: Environment name should be individually names for each developer of the project, who used docker env.
> DO NOT PUSH YOUR ENV files into **git**. Each local env contains security keys, which can leak to internet.

Create your own environment:
- choose one of environment type - proxy or without proxy.
- [OR] copy paste `docker-compose.proxy.local.yml` -> `docker-compose.proxy.my_env.yml`
- [OR] copy paste `docker-compose.without-proxy.local.yml` -> `docker-compose.without-proxy.my_env.yml`
- copy paste `./project-files/.env.example` -> `./project-files/.env.my_env`. This file should be added into git.
- copy paste `./docker/local/envs/.env.example` -> `./docker/local/envs/.env.my_env`. this file should be IGNORED by git. 

### Start own environment

> For example, we will use environment name: `my_env`. \
> NOTE: Environment name should be individually names for each developer of the project, who used docker env.

To start the project we need to run *docker.sh*:

```bash
dockerProxy.sh <ENVIRONMENT> <COMPOSE_PROJECT_NAME>  <IS_OSX?> <ACTION?> 
dockerProxy.sh my_env attract-starter-kit 0

#or

docker.sh <ENVIRONMENT> <COMPOSE_PROJECT_NAME>  <IS_OSX?> <ACTION?> 
docker.sh my_env attract-starter-kit 0
```

Parameters explanation: 
- *ENVIRONMENT* - Possible environment of the project. Used for env file detection(`.env.my_env`).
- *COMPOSE_PROJECT_NAME* - Containers prefix and docker compose project name.
- *IS_OSX* - This flag say to script that it must use osx filesystem improvements, like _:cached_ option. OPTIONAL, if blank = false.
- *ACTION* - Script action.  Example below:
    ```bash
    dockerProxy.sh my_env attract-starter-kit 0 down
    #or
    docker.sh my_env attract-starter-kit 0 down   
    ```
    For now _ACTION_ can be:
    - **down** - down the project at hte end of the work day, for example.
    - **build** - build a new copy of the project images.
    - **reload** - reload project without re-build. Can be useful for env changes.
     
    If empty, _ACTION_ parameter will run: **down**, **build** and **reload** actions in sequence. 