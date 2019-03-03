<?php

namespace Orchestra\Installation;

use Exception;
use Orchestra\Model\Role;
use Orchestra\Foundation\Auth\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Orchestra\Support\Facades\Messages;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\ValidationException;
use Orchestra\Contracts\Installation\Installation as InstallationContract;

class Installation implements InstallationContract
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Is installation under test (PHPUnit etc).
     *
     * @var bool
     */
    protected $isTestingEnvironment = false;

    /**
     * Construct a new instance.
     *
     * @param  bool  $isTestingEnvironment
     */
    public function __construct(bool $isTestingEnvironment = false)
    {
        $this->isTestingEnvironment = $isTestingEnvironment;
    }

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

        foreach ($paths as $path) {
            $file = \rtrim($path, '/').'/installer.php';

            if (File::exists($file)) {
                File::{$method}($file);
            }
        }
    }

    /**
     * Migrate Orchestra Platform schema.
     *
     * @return bool
     */
    public function migrate(): bool
    {
        \app('orchestra.publisher.migrate')->foundation();
        \event('orchestra.install.schema');

        return true;
    }

    /**
     * Create adminstrator account.
     *
     * @param  array  $input
     * @param  bool   $multiple
     *
     * @return bool
     */
    public function make(array $input, bool $multiple = true): bool
    {
        try {
            $this->validate($input);
        } catch (ValidationException $e) {
            Session::flash('errors', $e->validator->messages());

            return false;
        }

        try {
            ! $multiple && $this->hasNoExistingUser();

            $this->create(
                $this->createUser($input), $input
            );

            // Installation is successful, we should be able to generate
            // success message to notify the user. Installer route will be
            // disabled after this point.
            Messages::add('success', \trans('orchestra/foundation::install.user.created'));

            return true;
        } catch (Exception $e) {
            Messages::add('error', $e->getMessage());
        }

        return false;
    }

    /**
     * Run application setup.
     *
     * @param  \Orchestra\Model\User  $user
     * @param  array  $input
     *
     * @return void
     */
    public function create(User $user, array $input): void
    {
        $memory = \app('orchestra.memory')->make();

        // Bootstrap auth services, so we can use orchestra/auth package
        // configuration.
        $actions = ['Manage Orchestra', 'Manage Users'];
        $admin = \config('orchestra/foundation::roles.admin', 1);
        $roles = Role::pluck('name', 'id')->all();
        $theme = [
            'frontend' => 'default',
            'backend' => 'default',
        ];

        // Attach Administrator role to the newly created administrator.
        $user->roles()->sync([$admin]);

        // Add some basic configuration for Orchestra Platform, including
        // email configuration.
        $memory->put('site.name', $input['site_name']);
        $memory->put('site.theme', $theme);
        $memory->put('email', \config('mail'));
        $memory->put('email.from', [
            'name' => $input['site_name'],
            'address' => $input['email'],
        ]);

        // We should also create a basic ACL for Orchestra Platform, since
        // the basic roles is create using Fluent Query Builder we need
        // to manually insert the roles.
        $acl = \app('orchestra.platform.acl');

        $acl->attach($memory);
        $acl->actions()->attach($actions);
        $acl->roles()->attach(\array_values($roles));
        $acl->allow($roles[$admin], $actions);

        \event('orchestra.install: acl', [$acl]);
    }

    /**
     * Validate request.
     *
     * @param  array  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return bool
     */
    public function validate(array $input): bool
    {
        // Grab input fields and define the rules for user validations.
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'fullname' => ['required'],
            'site_name' => ['required'],
        ];

        $validation = \app('validator')->make($input, $rules);

        // Validate user registration, we should stop this process if
        // the user not properly formatted.
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return true;
    }

    /**
     * Create user account.
     *
     * @param  array  $input
     *
     * @return \Orchestra\Model\User
     */
    public function createUser(array $input): User
    {
        User::unguard();

        $user = new User([
            'email' => $input['email'],
            'password' => $input['password'],
            'fullname' => $input['fullname'],
            'status' => User::VERIFIED,
        ]);

        \event('orchestra.install: user', [$user, $input]);

        $user->save();

        return $user;
    }

    /**
     * Check for existing User.
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function hasNoExistingUser(): bool
    {
        // Before we create administrator, we should ensure that users table
        // is empty to avoid any possible hijack or invalid request.
        if (User::count() < 1) {
            return true;
        }

        throw new Exception(
            \trans('orchestra/foundation::install.user.duplicate')
        );
    }
}
