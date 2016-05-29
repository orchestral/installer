<?php

namespace Orchestra\Installation\Specifications;

class WritableBootstrapCache extends Specification
{
    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid = 'writable.bootstrap.cache';

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title = 'Able to write to bootstrap/cache folder';

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = 'Orchestra Platform would need to be able to write to <code>{path}</code> path to properly generate services manifest files and routes/config caches for your application.';

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check()
    {
        $path = rtrim($this->app['path.base'], '/').'/bootstrap/cache/';

        $this->description = Str::replace($this->description, compact('path'));

        return $this->checkPathIsWritable($path);
    }
}
