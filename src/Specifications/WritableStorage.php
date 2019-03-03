<?php

namespace Orchestra\Installation\Specifications;

use Orchestra\Support\Str;

class WritableStorage extends Specification
{
    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid = 'writable.storage';

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title = 'Able to write to storage folder';

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = 'Orchestra Platform would need to be able to write to <code>{path}</code> path to properly log errors for your application.';

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check(): bool
    {
        $path = \app()->storagePath();

        $this->description = Str::replace($this->description, \compact('path'));

        return $this->checkPathIsWritable($path);
    }
}
