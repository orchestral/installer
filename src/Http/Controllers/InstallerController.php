<?php

namespace Orchestra\Installation\Http\Controllers;

use Illuminate\Http\Request;
use Orchestra\Foundation\Http\Controllers\BaseController;
use Orchestra\Installation\Processor\Installer as InstallerProcessor;

class InstallerController extends BaseController
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        set_meta('navigation::usernav', false);
        set_meta('title', 'Installation');

        $this->middleware('orchestra.installed', [
            'only' => ['index', 'create', 'store'],
        ]);
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
    public function index(InstallerProcessor $processor)
    {
        set_meta('description', 'Check Requirements');

        return $processor->index($this);
    }

    /**
     * Migrate database schema for Orchestra Platform.
     *
     * GET (:orchestra)/install/prepare
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function prepare(InstallerProcessor $processor)
    {
        set_meta('description', 'Run Migrations');

        return $processor->prepare($this);
    }

    /**
     * Show create adminstrator page.
     *
     * GET (:orchestra)/install/create
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function create(InstallerProcessor $processor)
    {
        set_meta('description', 'Setup Orchestra Platform');

        return $processor->create($this);
    }

    /**
     * Create an adminstrator.
     *
     * POST (:orchestra)/install/create
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function store(Request $request, InstallerProcessor $processor)
    {
        return $processor->store($this, $request->all());
    }

    /**
     * End of installation.
     *
     * GET (:orchestra)/install/done
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function done(InstallerProcessor $processor)
    {
        return $processor->done($this);
    }

    /**
     * Response for installation welcome page.
     *
     * @param  array   $data
     *
     * @return mixed
     */
    public function indexSucceed(array $data)
    {
        return view('orchestra/installer::index', $data);
    }

    /**
     * Response when installation can't be prepared.
     *
     * @return mixed
     */
    public function prepareUnreachable()
    {
        return $this->redirect(handles('orchestra::install/index'));
    }

    /**
     * Response when installation is prepared.
     *
     * @return mixed
     */
    public function prepareSucceed()
    {
        return $this->redirect(handles('orchestra::install/create'));
    }

    /**
     * Response view to input user information for installation.
     *
     * @param  array   $data
     *
     * @return mixed
     */
    public function createSucceed(array $data)
    {
        return view('orchestra/installer::create', $data);
    }

    /**
     * Response when store installation config is failed.
     *
     * @return mixed
     */
    public function storeFailed()
    {
        return $this->redirect(handles('orchestra::install/create'));
    }

    /**
     * Response when store installation config is succeed.
     *
     * @return mixed
     */
    public function storeSucceed()
    {
        return $this->redirect(handles('orchestra::install/done'));
    }

    /**
     * Response when installation is done.
     *
     * @return mixed
     */
    public function doneSucceed()
    {
        return view('orchestra/installer::done');
    }
}
