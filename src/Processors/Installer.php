<?php

namespace Orchestra\Installation\Processors;

use Exception;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;
use Orchestra\Contracts\Installation\Installation;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Installation\Events\InstallationCompleted;
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
    protected $requirements;

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
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirements
     * @param  \Orchestra\Installation\Http\Presenters\Setup  $presenter
     */
    public function __construct(Installation $installer, Requirement $requirements, Presenter $presenter)
    {
        $this->installer = $installer;
        $this->requirements = $requirements;
        $this->presenter = $presenter;
    }

    /**
     * Start an installation and check for requirement.
     *
     * @param  object  $listener
     *
     * @return mixed
     */
    public function checkRequirement($listener)
    {
        $this->requirements->check();

        return $listener->showRequirementStatus($this->requirements);
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
        if (! $this->requirements->check()) {
            return $listener->preparationNotCompleted();
        }

        $this->installer->migrate();

        return $listener->preparationCompleted();
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
        $model = new Fluent([
            'site' => ['name' => \config('app.name', 'Orchestra Platform')],
        ]);

        $form = $this->presenter->form($model);

        return $listener->createSucceed(\compact('form', 'model'));
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
        try {
            $this->installer->make($input);
        } catch (ValidationException $e) {
            return $listener->storeFailedValidation($e->validator->messages());
        } catch (Exception $e) {
            return $listener->storeHasFailed($e);
        }

        \event(new InstallationCompleted($input));

        return $listener->storeSucceed();
    }
}
