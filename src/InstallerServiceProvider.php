<?php namespace Orchestra\Installation;

use Orchestra\Support\Providers\ServiceProvider;
use Orchestra\Contracts\Installation\Requirement as RequirementContract;
use Orchestra\Contracts\Installation\Installation as InstallationContract;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InstallationContract::class, function ($app) {
            return new Installation($app);
        });

        $this->app->bind(RequirementContract::class, function ($app) {
            return new Requirement($app);
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../');

        $this->addViewComponent('orchestra/installer', 'orchestra/installer', "{$path}/resources/views");

        if (! $this->app->routesAreCached()) {
            require "{$path}/src/routes.php";
        }
    }
}
