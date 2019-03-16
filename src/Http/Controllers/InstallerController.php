<?php

namespace Orchestra\Installation\Http\Controllers;

use Illuminate\Http\Request;
use Orchestra\Installation\Processors\Installer;

class InstallerController extends Controller
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
     * Show create adminstrator page.
     *
     * GET (:orchestra)/install/create
     *
     * @param  \Orchestra\Installation\Processor\Installer  $processor
     *
     * @return mixed
     */
    public function create(Installer $processor)
    {
        \set_meta('description', 'Setup Orchestra Platform');

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
    public function store(Request $request, Installer $processor)
    {
        return $processor->store($this, $request->all());
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
        return \view('orchestra/installer::create', $data);
    }

    /**
     * Response when store installation config is failed.
     *
     * @return mixed
     */
    public function storeHasFailed()
    {
        return \redirect(\handles('orchestra::install/create'));
    }

    /**
     * Response when store installation config is succeed.
     *
     * @return mixed
     */
    public function storeSucceed()
    {
        return \redirect(\handles('orchestra::install/done'));
    }
}
