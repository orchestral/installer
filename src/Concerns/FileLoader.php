<?php

namespace Orchestra\Installation\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

trait FileLoader
{
    /**
     * Is installation file has been booted.
     *
     * @var bool
     */
    protected $installerFileHasBeenBooted = false;

    /**
     * Boot installer files.
     *
     * @return void
     */
    public function bootInstallerFiles(): void
    {
        $this->requireInstallerFiles();
    }

    /**
     * Boot installer files for testing.
     *
     * @return void
     *
     * @deprecated v3.8.x
     */
    public function bootInstallerFilesForTesting(): void
    {
        $this->requireInstallerFiles();
    }

    /**
     * Requires the installer files.
     *
     * @param  bool  $once
     *
     * @return void
     */
    protected function requireInstallerFiles(): void
    {
        if ($this->installerFileHasBeenBooted === true) {
            return;
        }

        $paths = \config('orchestra/installer::installers.paths', []);

        Collection::make($paths)->transform(static function ($path) {
            return \rtrim($path, '/').'/installer.php';
        })->filter(static function ($file) {
            return File::exists($file);
        })->each(static function ($file) {
            File::getRequire($file);
        });

        $this->installerFileHasBeenBooted = true;
    }
}
