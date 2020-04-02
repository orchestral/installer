<?php

namespace Orchestra\Installation\Listeners;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Installation\Events\Event;

class MigrateDatabaseSchema
{
    /**
     * Handle the event.
     *
     * @param  \Orchestra\Installation\Events\Event  $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        Artisan::call('migrate', ['--force' => true]);
    }
}
