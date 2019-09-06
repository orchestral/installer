<?php

namespace Orchestra\Installation\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

trait FileLoader
{
    /**
     * Is installation under test (PHPUnit etc).
     *
     * @var bool
     */
    protected $isTestingEnvironment = false;

    /**
     * Boot installer files.
     *
     * @return void
     */
    public function bootInstallerFiles(): void
    {
        $this->requireInstallerFiles(! $this->isTestingEnvironment);
    }

    /**
     * Boot installer files for testing.
     *
     * @return void
     */
    public function bootInstallerFilesForTesting(): void
    {
        $this->requireInstallerFiles(false);
    }

    /**
     * Requires the installer files.
     *
     * @param  bool  $once
     *
     * @return void
     */
    protected function requireInstallerFiles(bool $once = true): void
    {
        $paths = \config('orchestra/installer::installers.paths', []);

        $method = ($once === true ? 'requireOnce' : 'getRequire');

        Collection::make($paths)->transform(static function ($path) {
            return \rtrim($path, '/').'/installer.php';
        })->filter(static function ($file) {
            return File::exists($file);
        })->each(static function ($file) use ($method) {
            File::{$method}($file);
        });
    }
}
