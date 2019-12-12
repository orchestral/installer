<?php

namespace Orchestra\Installation;

use Orchestra\Contracts\Memory\Provider;

class MailConfigurationUpdater
{
    /**
     * The memory implementation.
     *
     * @var \Orchestra\Contracts\Memory\Provider
     */
    protected $memory;

    /**
     * Construct a new mail configuration updater.
     *
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     */
    public function __construct(Provider $memory)
    {
        $this->memory = $memory;
    }

    /**
     * Update mail configuration.
     *
     * @param  string  $siteName
     * @param  string  $email
     *
     * @return void
     */
    public function __invoke(string $siteName, string $email): void
    {
        $this->memory->put('email', \config('mail'));
        $this->memory->put('email.from', [
            'name' => $siteName,
            'address' => $email,
        ]);
    }
}
