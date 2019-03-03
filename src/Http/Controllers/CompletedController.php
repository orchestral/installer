<?php

namespace Orchestra\Installation\Http\Controllers;

class CompletedController extends Controller
{
    /**
     * End of installation.
     *
     * GET (:orchestra)/install/done
     *
     * @return mixed
     */
    public function __invoke()
    {
        \set_meta('description', 'Completed');

        return \view('orchestra/installer::done');
    }
}
