<?php

namespace Orchestra\Installation;

use Illuminate\Database\Events\MigrationsStarted;
use Orchestra\Contracts\Installation\Installation as InstallationContract;
use Orchestra\Contracts\Installation\Requirement as RequirementContract;
use Orchestra\Foundation\Support\Providers\ModuleServiceProvider;

class InstallerServiceProvider extends ModuleServiceProvider
{
    /**
     * The application or extension namespace.
     *
     * @var string|null
     */
    protected $namespace = 'Orchestra\Installation\Http\Controllers';

    /**
     * Redirect path after installation completed.
     *
     * @var string
     */
    protected $redirectAfterInstalled;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(InstallationContract::class, static function () {
            return new Installation();
        });

        $this->app->singleton(RequirementContract::class, function () {
            $requirement = new Requirement();

            $this->addDefaultSpecifications($requirement);

            return $requirement;
        });

        $this->registerRedirection();

        if ($this->app->runningInConsole() === true) {
            $this->commands([
                Console\ConfigureMailCommand::class,
                Console\InstallCommand::class,
            ]);
        }
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens(): array
    {
        return [
            MigrationsStarted::class => [function () {
                $this->app->make(InstallationContract::class)->bootInstallerFiles();
            }],
        ];
    }

    /**
     * Register redirection services.
     *
     * @return void
     */
    protected function registerRedirection(): void
    {
        if (! empty($this->redirectAfterInstalled) && \is_string($this->redirectAfterInstalled)) {
            Installation::$redirectAfterInstalled = $this->redirectAfterInstalled;
        }
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
                ->add(new Specifications\DatabaseConnection($this->app))
                ->add(new Specifications\Authentication($this->app));
    }

    /**
     * Boot extension components.
     *
     * @return void
     */
    public function bootExtensionComponents()
    {
        $path = \realpath(__DIR__.'/../');

        $this->addConfigComponent('orchestra/installer', 'orchestra/installer', "{$path}/config");
        $this->addLanguageComponent('orchestra/installer', 'orchestra/installer', "{$path}/resources/lang");
        $this->addViewComponent('orchestra/installer', 'orchestra/installer', "{$path}/resources/views");
    }

    /**
     * Load extension routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $path = \realpath(__DIR__.'/../');

        $this->loadBackendRoutesFrom("{$path}/routes/backend.php");
    }
}
