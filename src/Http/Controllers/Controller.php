<?php

namespace Orchestra\Installation\Http\Controllers;

use Orchestra\Foundation\Http\Controllers\BaseController;

abstract class Controller extends BaseController
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate(): void
    {
        \set_meta('navigation::usernav', false);
        \set_meta('title', 'Installation');
    }
}
