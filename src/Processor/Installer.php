<?php

namespace Orchestra\Installation\Processor;

use ReflectionException;
use Orchestra\Model\User;
use Illuminate\Support\Arr;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Contracts\Installation\Installation;

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
     * Create a new processor instance.
     *
     * @param  \Orchestra\Contracts\Installation\Installation  $installer
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirement
     */
    public function __construct(Installation $installer, Requirement $requirement)
    {
        $this->installer   = $installer;
        $this->requirement = $requirement;

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
        return $listener->createSucceed([
            'siteName' => 'Orchestra Platform',
        ]);
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
