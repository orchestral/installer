<?php

namespace Orchestra\Installation\Events;

use Orchestra\Contracts\Authorization\Authorization;

class AuthorizationCreated extends Event
{
    /**
     * Create a new event instance.
     *
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     */
    public function __construct(Authorization $acl)
    {
        $this->acl = $acl;
    }
}
