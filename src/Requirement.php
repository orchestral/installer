<?php

namespace Orchestra\Installation;

use Illuminate\Support\Collection;
use IteratorAggregate;
use Orchestra\Contracts\Installation\Requirement as RequirementContract;
use Orchestra\Contracts\Installation\Specification as SpecificationContract;

class Requirement implements RequirementContract, IteratorAggregate
{
    /**
     * Installation checklist for Orchestra Platform.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items = [];

    /**
     * Installable status.
     *
     * @var bool
     */
    protected $installable;

    /**
     * Construct a new instance.
     */
    public function __construct()
    {
        $this->items = Collection::make($this->items);
    }

    /**
     * Add requirement specification.
     *
     * @param  \Orchestra\Contracts\Installation\Specification  $specification
     *
     * @return $this
     */
    public function add(SpecificationContract $specification)
    {
        $this->items->put($specification->uid(), $specification);

        return $this;
    }

    /**
     * Check all requirement.
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->installable = $this->items->filter(static function ($specification) {
            return $specification->check() === false && $specification->optional() === false;
        })->isEmpty();
    }

    /**
     * Get rules.
     *
     * @return iterable
     */
    public function items(): iterable
    {
        return $this->items;
    }

    /**
     * Get an iterator for the items.
     *
     * @return iterable
     */
    public function getIterator()
    {
        return $this->items;
    }

    /**
     * Get installable status.
     *
     * @return bool
     */
    public function isInstallable(): bool
    {
        return \is_null($this->installable) ? $this->check() : $this->installable;
    }
}
