# Project Docs

- [x] [Docker LOCAL deploy](docker/local/readme.md)
- [x] [Docker DEV/STAGE deploy](docker/dev/readme.md)
- [x] [Base information](#base-information)
- [x] [Push Actions](#push-typesclick_action)
- [x] [General Docs](#general-docs-list)
- [x] [Api Docs](#api-docs-list)
- [x] [Backend Api Docs](#backend-docs-list)

## Base information

> !!!NOTE!!! Project using [sextant filter for search operations](https://git.attractgroup.com/amondar/sextant/-/tree/1.2.10). Read his docs before start working. 

> Note that all urls in docs created as relative paths. You should attach 
> module relative path into correct base path, that described below.

> **WARNING!!!** Required headers for each request described below: \
> `X-Requested-With: XMLHttpRequest` - Tells server basic core functions that we work by default REST standards. \
> `Accept: application/json` - Tells server basic core functions that we need json response.

> Validation tips contains most required validation rules. Some rules are bolded, that rules could be: _type marker or requirements marker_. \
> **sometimes** rule in validation tips means, that field can be present or not in request body, but if field present, then it will be validated by type marker validator and others. \
> **nullable** rule in validation tips means, that field can accept `NULL` value or `empty string` value(all empty strings converted into `NULL` values automatically) even if value should be typed to **int** or **string** or etc. \
> **present** rule in validation tips means, that field must present in form body in each request, but can be empty.

> All described in profile or other CRUD's expands are available only, if other restrictions not listed in CRUD tips. 

- [x] HTTPS protocol.
- [x] Hosts. Placeholder is - `{base_host}` 
    - DEV: https://api.{PROJECT_NAME}-dev.php-cd.attractgroup.com
    - ~~STAGE: https://api.{PROJECT_NAME}-stage.php-cd.attractgroup.com~~
- [x] Base URL for Oauth2.0 - `{base_host}/api/oauth`. Placeholder - `{oauth_url}`.
- [x] Base URL for Authorization Microservice - `{base_host}/api/auth`. Placeholder - `{auth_url}`.
- [x] Base Url for API routes - `{base_host}/api/v1`. Placeholder - `{api_url}`.
- [x] Base Url for BACKEND API routes - `{base_host}/backend/v1`. Placeholder - `{backend_url}`.
- [x] Dev admin panel credentials: 
    - Login: `team2@attractgroup.com`
    - Password: `hfyh374Dgh37$5hdj`
- [x] Configured frontend urls: 
    - DEV: https://app.{PROJECT_NAME}-dev.php-cd.attractgroup.com
    - ~~STAGE: https://app.{PROJECT_NAME}-stage.php-cd.attractgroup.com~~
- [x] Configured admin frontend url: 
    - DEV: https://app.{PROJECT_NAME}-dev.php-cd.attractgroup.com/admin
    - ~~STAGE: https://app.{PROJECT_NAME}-stage.php-cd.attractgroup.com/admin~~
- [x] Possible cors urls: 
    - any urls in a pattern: http(s)://localhost(:)*
    - any sub domains on: *.attractgroup.com
    - also restricted cors paths: 
        - api/oauth/*
        - api/auth/*
        - api/v*
        - backend/v*
- [x] Device client credentials:
    - ID: **1**
    - Secret(DEV): **{CLIENT_SECRET_KEY}**
    - ~~Secret(STAGE): **{CLIENT_SECRET_KEY}**~~
- [x] Password client credentials(ONLY for Postman faster oAuth 2.0 interacting, DOESN't exists on prod env):
    - ID: **2**
    - Secret(DEV): **nUe9fkMNYe326gMXUUQtq6Pjyx6DXSuO5X9PLR2i**
    - ~~Secret(STAGE): **MckOMJJiciRON9wYmNF9kNO1l4eZgojMBtNNepE4**~~
    
## Push types(click_action)

>  `type` parameters list below. \
>  `click_action` equals to `FLUTTER_NOTIFICATION_CLICK`.

Types List:

- `{PUSH_TYPE}` - {DESCRIPTION}. 

## General Docs list

* [Oauth device authorization](docs/auth/oauth.md)
* [Sign In/Out flow docs](docs/auth/sign-in-out.md)
* [Registration flow docs](docs/auth/registration.md)
* [Verification flow docs](docs/auth/verification.md)
* [Password flow docs](docs/auth/pwd-reset.md)
* [Media Interactions](docs/media)

## Api Docs List

Start writing here.


## Backend Docs List

Start writing here