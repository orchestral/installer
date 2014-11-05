<?php namespace Orchestra\Installation;

use Orchestra\Support\Providers\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Orchestra\Contracts\Installation\Installation', function ($app) {
            return new Installation($app);
        });

        $this->app->bind('Orchestra\Contracts\Installation\Requirement', function ($app) {
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

        $this->addViewComponent('orchestra/installer', 'orchestra/installer', $path.'/view');

        require "{$path}/routes.php";
    }
}
