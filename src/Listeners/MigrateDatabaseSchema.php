<?php

namespace Orchestra\Installation\Listeners;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Installation\Events\InstallationCompleted;

class MigrateDatabaseSchema
{
    /**
     * Handle the event.
     *
     * @param  \Orchestra\Installation\Events\InstallationCompleted  $event
     *
     * @return void
     */
    public function handle(InstallationCompleted $event)
    {
        Artisan::call('migrate', ['--force' => true]);
    }
}
