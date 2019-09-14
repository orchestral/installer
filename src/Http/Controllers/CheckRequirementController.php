<?php

namespace Orchestra\Installation\Http\Controllers;

use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Installation\Processors\Installer;

class CheckRequirementController extends Controller
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate(): void
    {
        parent::onCreate();

        $this->middleware('orchestra.installed');
    }

    /**
     * Check installation requirement page.
     *
     * GET (:orchestra)/install
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function __invoke(Installer $processor)
    {
        \set_meta('description', 'Check Requirements');

        return $processor->checkRequirement($this);
    }

    /**
     * Response for installation welcome page.
     *
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirements
     *
     * @return mixed
     */
    public function showRequirementStatus(Requirement $requirements)
    {
        return \view('orchestra/installer::index', \compact('requirements'));
    }
}
