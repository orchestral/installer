<?php

namespace Orchestra\Installation;

use Orchestra\Foundation\Support\Providers\ModuleServiceProvider;
use Orchestra\Contracts\Installation\Requirement as RequirementContract;
use Orchestra\Contracts\Installation\Installation as InstallationContract;

class InstallerServiceProvider extends ModuleServiceProvider
{
    /**
     * The application or extension namespace.
     *
     * @var string|null
     */
    protected $namespace = 'Orchestra\Installation\Http\Controllers';

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
            $requirement = new Requirement($app);

            $this->addDefaultSpecifications($requirement);

            return $requirement;
        });
    }

    /**
     * Add default specifications.
     *
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirement
     *
     * @return \Orchestra\Contracts\Installation\Requirement
     */
    protected function addDefaultSpecifications(RequirementContract $requirement)
    {
        return $requirement->add(new Specifications\WritableStorage($this->app))
                ->add(new Specifications\WritableBootstrapCache($this->app))
                ->add(new Specifications\WritableAsset($this->app))
                ->add(new Specifications\DatabaseConnection($this->app));
    }

    /**
     * Boot extension components.
     *
     * @return void
     */
    public function bootExtensionComponents()
    {
        $path = realpath(__DIR__.'/../resources');

        $this->addViewComponent('orchestra/installer', 'orchestra/installer', "{$path}/views");
    }

    /**
     * Load extension routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $path = realpath(__DIR__);

        $this->loadBackendRoutesFrom("{$path}/Http/backend.php");
    }
}
