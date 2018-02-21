<?php

namespace Orchestra\Installation\TestCase\Feature;

use Orchestra\Testing\TestCase as Testing;

abstract class TestCase extends Testing
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make('Orchestra\Foundation\Bootstrap\LoadExpresso')->bootstrap($app);
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
            'Orchestra\Installation\InstallerServiceProvider',
        ];
    }
}
