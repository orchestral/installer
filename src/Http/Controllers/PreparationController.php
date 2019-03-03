<?php

namespace Orchestra\Installation\Http\Controllers;

use Orchestra\Installation\Processors\Installer as InstallerProcessor;

class PreparationController extends Controller
{
    /**
     * Migrate database schema for Orchestra Platform.
     *
     * GET (:orchestra)/install/prepare
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function __invoke(InstallerProcessor $processor)
    {
        \set_meta('description', 'Run Migrations');

        return $processor->prepare($this);
    }

    /**
     * Response when installation can't be prepared.
     *
     * @return mixed
     */
    public function preparationNotCompleted()
    {
        return \redirect(\handles('orchestra::install/index'));
    }

    /**
     * Response when installation is prepared.
     *
     * @return mixed
     */
    public function preparationCompleted()
    {
        return \redirect(\handles('orchestra::install/create'));
    }
}
