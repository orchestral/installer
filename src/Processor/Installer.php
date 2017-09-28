<?php

namespace Orchestra\Installation\Processor;

use Orchestra\Model\User;
use Illuminate\Support\Fluent;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Contracts\Installation\Installation;
use Orchestra\Installation\Http\Presenters\Setup as Presenter;

class Installer
{
    /**
     * Installer instance.
     *
     * @var \Orchestra\Contracts\Installation\Installation
     */
    protected $installer;

    /**
     * Requirement instance.
     *
     * @var \Orchestra\Contracts\Installation\Requirement
     */
    protected $requirement;

    /**
     * Presenter instance.
     *
     * @var \Orchestra\Installation\Http\Presenters\Setup
     */
    protected $presenter;

    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Contracts\Installation\Installation  $installer
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirement
     * @param  \Orchestra\Installation\Http\Presenters\Setup  $presenter
     */
    public function __construct(Installation $installer, Requirement $requirement, Presenter $presenter)
    {
        $this->installer = $installer;
        $this->requirement = $requirement;
        $this->presenter = $presenter;

        $this->installer->bootInstallerFiles();
    }

    /**
     * Start an installation and check for requirement.
     *
     * @param  object  $listener
     *
     * @return mixed
     */
    public function index($listener)
    {
        $requirements = $this->requirement;

        $requirements->check();

        return $listener->indexSucceed(compact('requirements'));
    }

    /**
     * Run migration and prepare the database.
     *
     * @param  object  $listener
     *
     * @return mixed
     */
    public function prepare($listener)
    {
        if (! $this->requirement->check()) {
            return $listener->prepareUnreachable();
        }

        $this->installer->migrate();

        return $listener->prepareSucceed();
    }

    /**
     * Display initial user and site configuration page.
     *
     * @param  object  $listener
     *
     * @return mixed
     */
    public function create($listener)
    {
        $model = new Fluent(['site' => ['name' => config('app.name', 'Orchestra Platform')]]);

        $form = $this->presenter->form($model);

        return $listener->createSucceed(compact('form', 'model'));
    }

    /**
     * Store/save administator information and site configuration.
     *
     * @param  object  $listener
     * @param  array   $input
     *
     * @return mixed
     */
    public function store($listener, array $input)
    {
        if (! $this->installer->make($input)) {
            return $listener->storeFailed();
        }

        return $listener->storeSucceed();
    }

    /**
     * Complete the installation.
     *
     * @param  object  $listener
     *
     * @return mixed
     */
    public function done($listener)
    {
        return $listener->doneSucceed();
    }
}
