<?php

namespace Orchestra\Installation;

use PDOException;
use Illuminate\Support\Collection;
use Orchestra\Contracts\Installation\Requirement as RequirementContract;
use Orchestra\Contracts\Installation\Specification as SpecificationContract;

class Requirement implements RequirementContract
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Installation checklist for Orchestra Platform.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * Installable status.
     *
     * @var bool
     */
    protected $installable;

    /**
     * Construct a new instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->items = new Collection();
    }

    /**
     * Add requirement specification.
     *
     * @param  \Orchestra\Contracts\Installation\Specification  $specification
     * @return $this
     */
    public function add(SpecificationContract $specification)
    {
        $this->items[$specification->uid()] = $specification;

        return $this;
    }

    /**
     * Check all requirement.
     *
     * @return bool
     */
    public function check()
    {
        $this->installable = true;

        foreach ($this->items as $specification) {
            if ($specification->check() === false && $specification->optional() === false) {
                $this->installable = false;
            }
        }

        return $this->installable;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function getCheckList()
    {
        return $this->items;
    }

    /**
     * Get installable status.
     *
     * @return bool
     */
    public function isInstallable()
    {
        if (is_null($this->installable)) {
            return $this->check();
        }

        return $this->installable;
    }
}
