<?php

namespace Orchestra\Installation\Specifications;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Contracts\Installation\Specification as SpecificationContract;

abstract class Specification implements SpecificationContract
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid;

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title;

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Specification optional.
     *
     * @var bool
     */
    protected $optional = false;

    /**
     * Specification error.
     *
     * @var mixed|null
     */
    protected $error;

    /**
     * Construct the specification.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get specification id.
     *
     * @return string
     */
    public function uid()
    {
        return $this->uid;
    }

    /**
     * Get specification title.
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Get specification description.
     *
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Is specification optional.
     *
     * @return bool
     */
    public function optional()
    {
        return $this->optional;
    }

    /**
     * Specification has error,
     *
     * @return bool
     */
    public function hasError()
    {
        return ! empty($this->error);
    }

    /**
     * Get specification error.
     *
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Check if path is writable.
     *
     * @param  string   $path
     *
     * @return bool
     */
    protected function checkPathIsWritable($path)
    {
        return $this->app->make('files')->isWritable($path);
    }
}
