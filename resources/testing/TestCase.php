<?php

namespace Tests;

use AttractCores\LaravelCoreTestBench\OauthInteracts;
use AttractCores\LaravelCoreTestBench\PHPUnitConsole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker, CreatesApplication, OauthInteracts, PHPUnitConsole;

    protected $seed = true;

    /**
     * Default api headers.
     *
     * @var array
     */
    protected $defaultApiHeaders = [
        'X-Requested-With' => 'XMLHttpRequest',
    ];

    /**
     * Resolve user class
     *
     * @return \App\Models\User
     */
    public function resolveUser() : Model
    {
        return new \App\Models\User();
    }

    /**
     * Resolve user class
     *
     * @return \Database\Factories\UserFactory
     */
    public function resolveUserFactory() : Factory
    {
        return $this->resolveUser()->factory();
    }

    /**
     * Set up whole tests.
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->runConsoleOutput();

        // Clear framework data.
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('event:clear');
        //\Artisan::call('cache:clear');

        // Create clients.
        Artisan::call('passport:client',
            [ '--client' => true, '--name' => 'Clients', '--redirect_uri' => config('app.url'), '--user_id' => NULL, '--provider' => 'users' ]);
        Artisan::call('passport:client',
            [ '--password' => true, '--name' => 'Password Clients', '--redirect_uri' => config('app.url'), '--user_id' => NULL, '--provider' => 'users' ]);

        // Set Password client config.
        $client = $this->getPasswordOauthClient();
        config([
            'kit-auth.password_grant.id' => $client->getKey(),
            'kit-auth.password_grant.secret' => $client->secret,
        ]);
    }

    /**
     * Return has failed indication as string.
     *
     * @return string
     */
    protected function hasFailedAsString()
    {
        return $this->hasFailed() ? 'true' : 'false';
    }

    /**
     * Return reverted has failed indication as string.
     *
     * @return string
     */
    protected function isSucceededAsString()
    {
        return ! $this->hasFailed() ? 'true' : 'false';
    }
}
