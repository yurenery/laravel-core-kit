# Core Kit

> To start project you should have locally installed: `php@^7.4|^8.0`, `composer`. \ 
> You can use google search with requests: `linux php local install`, `brew install php`. \
> After first setup project can work via Valet or docker containers.

Main Core Code for starter project modules and extensions

- [x] [What is CoreKit micro service](#what-is-corekit-micro-service)
- [x] [How to install from private repository](#how-to-install-from-private-repository)
- [x] [Installation Guide](#installation-guide)
- [x] [Extension possibilities](#extension-possibilities)

## What is CoreKit micro service

After installing CoreKit microservice you will receive next services out of the box:
- Authorization microservice. Sign-in, Sign-out, verification and password reset processes.
Also, any other verification actions can be developed through Core Verification Broker.
- User registration.
- Profile get and update.
- Backend management system for users.
- Permissions and roles management system.
- SPA application fake routing. CoreKit give you powerful feature to make fake routes for SPA endpoints. 

## How to install from private repository

> !!!!Never commit the auth.json file to your repository. 
> For docker containerization use Vault or other secure variables to store access token for a user.

1. Run 
    ```
    composer config --global --auth http-basic.repo.packagist.com AttraactGroupUser <request token from maintainer>
    ```
2. Add this into your composer.json:
    ```json
    {
        "repositories": [
                {"type": "composer", "url": "https://repo.packagist.com/attractgroup/"}
            ]
    }
    ```

## Installation Guide

Step by step instructions, that will help you start a new project to build something amazing.

- [x] [Directories and project environment](#directories-and-project-environment)
- [x] [Setup project files](#setup-project-files)

### Directories and project environment

List of root directories for future project:
- `project-files` - contain all code files. 
- `docker` - contains all scripts for docker containerization process.
- `docs` - contain all **readme.md** doc files.
- `terraform` - contain all **terraform templates** for **AWS** resources setup.

You can find full description about each directory setup below.

> Replace directories and project names to the real project name.

### Setup project files

Create root directory of the project. For example, `attract-starter-kit`:

```bash
mkdir -m 777 attract-starter-kit &&
cd attract-starter-kit
```

After that you are ready for `project-files` install:

```bash
laravel new project-files &&
cd project-files
```

#### Set up attract starter kit

> TIMELESS - operations that will be changed after several steps.

Follow this TODO list to set up starter project:

- [x] Change default php requirements in composer.json to `^7.4|^8.0`
- [x] [Authorize your composer into private packagist](#how-to-install-from-private-repository)
- [x] [TIMELESS] Configure `DB_CONNECTION` to use sqlite driver into your `.env`:
   ```dotenv
   DB_CONNECTION=sqlite
   DB_DATABASE=project_files # remove this row
   ```
   ```bash
   cd ./database && touch database.sqlite && cd ..
   ```
- [x] Disable mysql configuration strict mode to prevent errors on cross relations ordering in _sextant_ operations:
    ```php
    [
      'mysql' => [
          //...,
          'strict' => false,
          //...,
      ]
    ];
    ```
- [x] Run:
    ```bash
    composer require attract-cores/laravel-core-kit attract-cores/laravel-core-test-bench
    ```
- [x] Remove all default laravel migrations
- [x] Run `vendor:publish` operations to create configuration environment for new project:
     ```bash
      php artisan vendor:publish --tag=laravel-mail && 
      php artisan vendor:publish --tag=attract-core-kit-core-modules --force && 
      php artisan vendor:publish --tag=attract-core-kit-auth-migrations --force &&
      php artisan vendor:publish --tag=attract-core-verification-broker-migrations --force &&
      php artisan vendor:publish --tag=attract-core-kit-auth-seeders --force &&
      php artisan vendor:publish --tag=attract-core-kit-auth-tests &&
      php artisan vendor:publish --tag=attract-core-kit-tests &&  
      composer dump-autoload
     ```
- [x] Go into terraform directory and run:
    ```bash
    cd ../terraform/tf-s3 && ln -s ../vars/variables.tf variables.tf && cd ../../project-files &&
    cd ../terraform/s3 && ln -s ../vars/variables.tf variables.tf && cd ../../project-files
    ```
- [x] Update `./config/auth.php` api driver  from token to passport:
    ```php
    [
        'api' => [
                    //'driver' => 'token', you can remove this row.
                    'driver' => 'passport',
                    'provider' => 'users',
                    'hash' => false,
                ],
    ];
    ```
- [x] Add `'read_write_timeout' => 0` to your `./config/database.php` redis section:
    ```php
    [
        'default' => [
                    'url'                => env('REDIS_URL'),
                    'host'               => env('REDIS_HOST', '127.0.0.1'),
                    'password'           => env('REDIS_PASSWORD', NULL),
                    'port'               => env('REDIS_PORT', '6379'),
                    'database'           => env('REDIS_DB', '0'),
                    'read_write_timeout' => 0, // Add this into each settings block to prevent errors - "error while reading line from the server."
                ],
    ];
    ```
- [x] Remove `api.php` and `web.php` route files.
- [x] Change verified middleware into Core version `AttractCores\LaravelCoreKit\Http\Middleware\EnsureEmailIsVerified` in `Kernel.php`:
    ```php
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
      //...,
      'verified' => \AttractCores\LaravelCoreKit\Http\Middleware\EnsureEmailIsVerified::class,
      //...,
    ];
    ```
- [x] Copy new app key(`APP_KEY`) from `.env` into `.env.example` and `.env.dev`
- [x] Copy `.env.example` -> `.env`. If you will use docker skip this row. 
If you are using Laravel Valet, set up variables for your local environment. 
- [x] Update DatabaseSeeder via:
    ```php
    $this->call([
      	    DefaultAdminSeeder::class,
      	    DefaultRolesAndPermissionsSeeder::class
      	]);
    ```
- [x] If you will add new permissions/roles then you should extend 
`AttractCores\LaravelCoreAuth\Database\Seeders\DefaultRolesAndPermissionsSeeder` class and add new models like parent class do.
For example:
    ```php
        namespace Database\Seeders;
        
        use App\Models\Permission;
        use App\Models\Role;
        use AttractCores\LaravelCoreAuth\Database\Seeders\DefaultRolesAndPermissionsSeeder as CoreDefaultRolesAndPermissionsSeeder;
        use AttractCores\LaravelCoreAuth\Resolvers\CorePermission;
        use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
        
        /**
         * Class DefaultRolesAndPermissionsSeeder
         *
         * @package AttractCores\LaravelCoreAuth\Database\Seeders\Publishes
         * Date: 16.12.2020
         * Version: 1.0
         * Author: Yure Nery <yurenery@gmail.com>
         */
        class DefaultRolesAndPermissionsSeeder extends CoreDefaultRolesAndPermissionsSeeder
        {
            /**
             * Seed the application's database.
             *
             * @return void
             */
            public function run()
            {
                parent::run();
        
                $permissions = CorePermission::all();
                $permissionSlugFieldName = CorePermission::getSlugField();
        
                if ( ! $permissions->contains($permissionSlugFieldName, Permission::CAN_ORGANIZATION_ACCESS) ) {
        
                    CorePermission::factory()
                                  ->createOne([ 'name_en' => 'Can have Organisation access', 'slug' => Permission::CAN_ORGANIZATION_ACCESS ]);
        
                    CoreRole::factory()
                            ->createOne([ 'name_en' => 'Organisation Access', 'slug' => Role::CAN_ORGANIZATION ])
                            ->permissions()
                            ->sync([ 6 ]);
                }
            }
        }
    ```
- [x] You require to follow [docker environment docs](resources/docker/local/readme.md) or use [Laravel Valet](https://laravel.com/docs/8.x/valet) for local serving.
- [x] If you are using Laravel Valet, then follow below steps:
    - Update migrations if needed and run below command if you are using Laravel Valet:
        ```bash
        php artisan migrate --seed
        ```
    - After migrations processed we should create passport keys and clients:
        ```bash
        php artisan passport:keys --force &&
        php artisan passport:client --client --no-interaction &&
        php artisan passport:client --password --no-interaction
        ```
- [x] Serve app via docker or Laravel Valet and move farther.
- [x] Update `APP_KIT_AUTH_PASSWORD_GRANT_CLIENT_ID` and `APP_KIT_AUTH_PASSWORD_GRANT_CLIENT_SECRET` variables values in `.env`. 
Update them in `./docker/local/envs/{YOUR_ENV}` if you are using docker.
- [x] In case of changes inside exception handler and validation messages structure we need add some updates into tests, to prevent failing:
    - Update `OauthTest.php` class by adding this function:
    ```php
    /**
     * Return status for handler catchers.
     *
     * @return int
     */
    protected function getCantLoginStatus()
    {
        return 401;
    }
    ```
    - Update `RegisterTest.php` adn add replace of `testApiRegistrationValidation` function:
    ```php
    /**
     * Test api registration validation.
     *
     * @return void
     * @throws \Throwable
     */
    public function testApiRegistrationValidation()
    {
        $notUnique = $this->getTestRegisterData(false, 5);
        $response = $this->withHeaders([ 'Authorization' => $this->getBearerClientToken() ])
                         ->json('POST', $this->getRegisterRoute(), $notUnique);

        $response->assertStatus(422);
        $errors = collect($response->decodeResponseJson()->json('errors'));
        $this->assertEquals(2, count($errors));
        $this->assertTrue($errors->contains('field', 'password'));
        $this->assertTrue($errors->contains('field', 'email'));
    }    
    ```
- [x] Remove `./tests/Feature/ExampleTest.php` file.
- [x] For DEV/STAGE environment setup use [this docs](resources/docker/dev/readme.md).
- [x] After all actions, just run below command inside docker container or in `project-files` root(if using Valet):
    ```bash
    ./vendor/bin/phpunit --stop-on-failur
    ```
- [x] If green status obtained, starter kit ready to extend.

  
  
## Extension possibilities

Any core request, controller, resource or library can be easily extended via Laravel service providers 
bind features.

> After `vendor:publish` process your `AppServiceProvider` already include all necessary functions for extension.

### User resource extension

After CoreKit installation project will grow, 
so we will need to extend default user resource with new relation expands. Let's do this.

> For example: we need to separate fist_name and last_name fields. Don't forget to update the migration.

```php
namespace App\Http\Resources;

use AttractCores\LaravelCoreKit\Http\Resources\UserResource as CoreUserResource;
use Illuminate\Support\Arr;

/**
 * Class UserResource
 *
 * @property \App\Models\User $resource
 *
 * @package App\Http\Resources\PublicResources
 * Date: 17.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class UserResource extends CoreUserResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $parentData = Arr::except(parent::toArray($request), [ 'name' ]);

        return array_merge(
            $parentData, [
            'first_name'                  => $this->resource->first_name,
            'last_name'                   => $this->resource->last_name,
            'relations'                   => array_merge($parentData[ 'relations' ], [
                'avatar'             => $this->whenLoaded('avatar', function () {
                    return new MediaResource($this->resource->avatar);
                }),  
            ]),
        ]);
    }
}
```

Also, we should update `App\Models\User` class for our case:

```php
/**
* The attributes that are mass assignable.
*
* @var array
*/
protected $fillable = [
    'email',
    'first_name',
    'last_name',
    'firebase_token',
];
```

After we create a new resource we should tell laravel to bind to our resource class rather, then to kit's one.
Add given code row into `extendsCoreKitBinds` function in `AppServiceProvider`:

```php
// Replace Core User Resource
$this->app->bind(\AttractCores\LaravelCoreKit\Http\Resources\UserResource::class, \App\Http\Resources\UserResource::class);
```

After that manipulations, any `UserResource` response in CoreKit actions will use your class rather, then core one.


## Translation text
- Your email address is not verified.
- You should specify at least one role.
- This role does not exist, or you are trying to use deprecated role for your access.
- Slug field is required.
- Slug should be unique.
- Role name is required.
- You can't create a role without permissions.
- Given permission is not exists in our db.
- Name field is required.
- Name length should be less than 255 chars.
- Name should be unique.
- Is active flag is required.
- Flag value should be boolean.