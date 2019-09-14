<?php

namespace Orchestra\Installation\Specifications;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
    public function uid(): string
    {
        return $this->uid;
    }

    /**
     * Get sanitized specification id.
     *
     * @return string
     */
    public function sanitizedUid(): string
    {
        return Str::slug($this->uid);
    }

    /**
     * Get specification title.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get specification description.
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Is specification optional.
     *
     * @return bool
     */
    public function optional(): bool
    {
        return $this->optional;
    }

    /**
     * Specification has error.
     *
     * @return bool
     */
    public function hasError(): bool
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
    protected function checkPathIsWritable(string $path): bool
    {
        return File::isWritable($path);
    }
}
