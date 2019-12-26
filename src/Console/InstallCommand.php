<?php

namespace Orchestra\Installation\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Installation\Processors\Installer;

class InstallCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'orchestra:install
        {--email= : Administrator e-mail address.}
        {--password= : Administrator password.}';

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
        $bar = $this->output->createProgressBar(4);

        if ($foundation->installed()) {
            $this->error('This command can only be executed when the application is not installed!');

            return 1;
        }

        $bar->advance();

        if (! $installer->checkRequirement($this)) {
            return 1;
        }

        $bar->advance();

        if (! $installer->prepare($this)) {
            return 1;
        }

        $bar->advance();

        $ask = ! $this->option('no-interaction');

        $input['site_name'] = $ask ? $this->ask('Application name?', \config('app.name')) : \config('app.name');
        $input['fullname'] = $ask ? $this->ask('Administrator fullname?', 'Administrator') : 'Administrator';
        $input['email'] = $this->option('email') ?? $this->ask('Administrator e-mail address?');

        if (! \is_null($this->option('password'))) {
            $input['password'] = $this->option('password');
        } else {
            $input['password'] = $ask ? $this->secret('Administrator password?') : 'secret';
        }

        if (! $installer->store($this, $input)) {
            return 1;
        }

        $bar->advance();

        $bar->finish();

        $this->output->newLine();

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
     * Response when store installation failed validation.
     *
     * @param  \Illuminate\Support\MessageBag  $errors
     *
     * @return bool
     */
    public function storeFailedValidation(MessageBag $errors): bool
    {
        throw new ValidationException::withMessages($errors->all());

        return false;
    }

    /**
     * Response when store installation config is failed.
     *
     * @param  \Exception  $exception
     * @return bool
     */
    public function storeHasFailed(Exception $exception): bool
    {
        $this->warn('Unable to complete installation: '.$exception->getMessage());

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
