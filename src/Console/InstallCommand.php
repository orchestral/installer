<?php

namespace Orchestra\Installation\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\MessageBag;
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
        $this->output->section('1. Verify Installation');

        if ($foundation->installed()) {
            $this->output->error('This command can only be executed when the application is not installed!');

            return 1;
        }

        $this->output->success('Successful');

        $this->output->section('2. Check Requirement');


        if (! $installer->checkRequirement($this)) {
            return 1;
        }

        $this->output->section('3. Preparing Installation');

        if (! $installer->prepare($this)) {
            return 1;
        }

        $this->output->section('4. Application Configuration');

        $ask = ! $this->option('no-interaction');
        $password = $this->option('password');

        if (! $ask && ! \is_null($password)) {
            $this->output->comment('Skipped via no interaction mode!');
        }

        $input['site_name'] = $ask ? $this->ask('Application name?', \config('app.name')) : \config('app.name');
        $input['fullname'] = $ask ? $this->ask('Administrator fullname?', 'Administrator') : 'Administrator';
        $input['email'] = $this->option('email') ?? $this->ask('Administrator e-mail address?');

        if (! \is_null($password)) {
            $input['password'] = $password;
        } else {
            $input['password'] = $ask ? $this->secret('Administrator password?') : 'secret';
        }

        $this->output->section('5. Installing');

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
            $this->output->success('Comply with requirements');

            return true;
        }


        $this->output->error('Does not comply with requirements');

        $failures = $requirements->items()->filter(static function ($specification) {
            return $specification->check() === false && $specification->optional() === false;
        });

        $this->output->table(
            ['Category', 'Status'],
            $failures->map(static function ($spec) {
                return [$spec->title(), '<fg=red>  ✗</>'];
            })->all()
        );

        return false;
    }

    /**
     * Response when installation can't be prepared.
     *
     * @return bool
     */
    public function preparationNotCompleted(): bool
    {
        $this->output->error('Unable to migrate database');

        return false;
    }

    /**
     * Response when installation is prepared.
     *
     * @return bool
     */
    public function preparationCompleted(): bool
    {
        $this->output->success('Database migrated');

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
        $this->output->error('Failed validation');

        $lists = [];
        $errors->setFormat(':message');

        foreach ($errors->keys() as $key) {
            foreach ($errors->get($key) as $index => $message) {
                if ($index === 0) {
                    $lists[] = [$key, $message];
                } else {
                    $lists[] = ['', $message];
                }
            }
        }

        $this->output->table(['Field', 'Errors'], $lists);

        return false;
    }

    /**
     * Response when store installation config is failed.
     *
     * @param  \Exception  $exception
     *
     * @return bool
     */
    public function storeHasFailed(Exception $exception): bool
    {
        $this->output->error('Unable to complete installation: '.$exception->getMessage());

        return false;
    }

    /**
     * Response when store installation config is succeed.
     *
     * @return bool
     */
    public function storeSucceed(): bool
    {
        $this->output->success('Installation completed');

        return true;
    }
}
