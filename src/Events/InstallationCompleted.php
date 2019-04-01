<?php

namespace Orchestra\Installation\Events;

class InstallationCompleted
{
    /**
     * Form input.
     *
     * @var array
     */
    public $input;

    /**
     * Create a new event instance.
     *
     * @param  array  $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }
}
