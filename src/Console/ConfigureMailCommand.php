<?php

namespace Orchestra\Installation\Console;

use Illuminate\Console\Command;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Installation\MailConfigurationUpdater;

class ConfigureMailCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchestra:configure-email';

    /**
     * Handle the command.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @return int
     */
    public function handle(Foundation $foundation)
    {
        if (! $foundation->installed()) {
            $this->error('This command can only be executed when the application has been installed!');

            return 1;
        }

        $memory = $foundation->memory();

        $name = $this->ask('What is the application name?', $memory->get('email.from.name'));
        $email = $this->ask('What is the e-mail address?', $memory->get('email.from.address'));

        \with(new MailConfigurationUpdater($memory), static function ($updater) use ($name, $email) {
            $updater($name, $email);
        });

        return 0;
    }
}
