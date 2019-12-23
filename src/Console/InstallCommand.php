<?php

namespace Orchestra\Installation\Console;

use Illuminate\Console\Command;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Installation\Processors\Installer;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchestra:install';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Install Orchestra Platform';

    /**
     * Handle the command.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Contracts\Installation\Installation  $installer
     *
     * @return int
     */
    public function handle(Foundation $foundation, Installer $installer)
    {
        if ($foundation->installed()) {
            $this->error('This command can only be executed when the application is not installed!');

            return 1;
        }

        if (! $installer->checkRequirement($this)) {
            return 1;
        }

        if (! $installer->prepare($this)) {
            return 1;
        }

        $input['site_name'] = $this->ask('Application name?', \config('app.name'));
        $input['fullname'] = $this->ask('Administrator fullname?', 'Administrator');
        $input['email'] = $this->ask('Administrator e-mail address?');
        $input['password'] = $this->secret('Administrator password?');

        if (! $installer->store($this, $input)) {
            return 1;
        }

        return 0;
    }

    /**
     * Response for installation welcome page.
     *
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirements
     *
     * @return bool
     */
    public function showRequirementStatus(Requirement $requirements): bool
    {
        if ($requirements->isInstallable()) {
            return true;
        }

        $failures = $requirements->items()->filter(static function ($specification) {
            return $specification->check() === false && $specification->optional() === false;
        });

        // @TODO list failed requirements.

        return false;
    }

    /**
     * Response when installation can't be prepared.
     *
     * @return bool
     */
    public function preparationNotCompleted(): bool
    {
        return false;
    }

    /**
     * Response when installation is prepared.
     *
     * @return bool
     */
    public function preparationCompleted(): bool
    {
        return true;
    }

    /**
     * Response when store installation config is failed.
     *
     * @return bool
     */
    public function storeHasFailed(): bool
    {
        $this->warn('Unable to complete installation');

        return false;
    }

    /**
     * Response when store installation config is succeed.
     *
     * @return bool
     */
    public function storeSucceed(): bool
    {
        $this->info('Installation completed');

        return true;
    }
}
