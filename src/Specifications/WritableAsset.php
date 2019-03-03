<?php

namespace Orchestra\Installation\Specifications;

use Orchestra\Support\Str;

class WritableAsset extends Specification
{
    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid = 'writable.asset';

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title = 'Able to write to public folder';

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = 'Orchestra Platform would need to be able to write to <code>{path}</code> path to affectively publish extension assets via the webserver.';

    /**
     * Specification optional.
     *
     * @var bool
     */
    protected $optional = true;

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check(): bool
    {
        $path = \app()->publicPath('packages');

        $this->description = Str::replace($this->description, \compact('path'));

        return $this->checkPathIsWritable($path);
    }
}
