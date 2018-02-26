<?php

namespace Orchestra\Installation\Tests\Controller;

use Orchestra\Foundation\Auth\User;
use Orchestra\Testing\BrowserKit\TestCase as Testing;

abstract class TestCase extends Testing
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->make('config')->set(['auth.providers.users.model' => User::class]);

        $this->loadFactoriesUsing($app, __DIR__.'/../factories');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Installation\InstallerServiceProvider::class,
        ];
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return tap(parent::resolveApplication(), function ($app) {
            $app->useVendorPath(__DIR__.'/../../vendor');
        });
    }
}
