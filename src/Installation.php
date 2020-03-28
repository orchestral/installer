<?php

namespace Orchestra\Installation;

use Carbon\Carbon;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Orchestra\Contracts\Installation\Installation as InstallationContract;
use Orchestra\Contracts\Memory\Provider;
use Orchestra\Foundation\Auth\User;
use Orchestra\Foundation\Jobs\UpdateMailConfiguration;
use Orchestra\Model\Role;

class Installation implements InstallationContract
{
    use Concerns\FileLoader;

    /**
     * Path for after installation completed redirect.
     *
     * @var string
     */
    public static $redirectAfterInstalled = 'orchestra::login';

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
     * @return void
     */
    public function make(array $input, bool $multiple = true): void
    {
        $this->validate($input);

        ! $multiple && $this->noExistingUser();

        $this->create(
            $this->createUser($input), $input
        );

        // Installation is successful, we should be able to generate
        // success message to notify the user. Installer route will be
        // disabled after this point.
    }

    /**
     * Run application setup.
     *
     * @param  \Orchestra\Foundation\Auth\User  $user
     * @param  array  $input
     *
     * @return void
     */
    public function create(User $user, array $input): void
    {
        $memory = $this->memoryProvider();

        // Bootstrap auth services, so we can use orchestra/auth package
        // configuration.
        $actions = ['Manage Orchestra', 'Manage Users'];
        ['admin' => $admin, 'member' => $member] = \config(
            'orchestra/foundation::roles', ['admin' => 1, 'member' => 2]
        );

        $roles = Role::pluck('name', 'id')->all();

        // Attach Administrator role to the newly created administrator.
        DB::table('user_role')->where('user_id', '=', $user->id)
            ->where('role_id', '=', $member)
            ->update([
                'role_id' => $admin,
                'updated_at' => Carbon::now(),
            ]);

        // Add some basic configuration for Orchestra Platform, including
        // email configuration.
        $memory->put('site.name', $input['site_name']);
        $memory->put('site.theme', [
            'frontend' => 'default',
            'backend' => 'default',
        ]);

        \dispatch_now(new UpdateMailConfiguration($input['site_name'], $input['email']));

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
        \app('validator')->make($input, [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'fullname' => ['required'],
            'site_name' => ['required'],
        ])->validate();

        return true;
    }

    /**
     * Create user account.
     *
     * @param  array  $input
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    public function createUser(array $input): User
    {
        return User::unguarded(static function () use ($input) {
            $user = User::hs([
                'email' => $input['email'],
                'password' => $input['password'],
                'fullname' => $input['fullname'],
                'status' => User::VERIFIED,
            ]);

            \event('orchestra.install: user', [$user, $input]);

            $user->save();

            return $user;
        });
    }

    /**
     * Check for existing User.
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function noExistingUser(): bool
    {
        // Before we create administrator, we should ensure that users table
        // is empty to avoid any possible hijack or invalid request.
        if (User::count() < 1) {
            return true;
        }

        throw new Exception(\trans('orchestra/foundation::install.user.duplicate'));
    }

    /**
     * Get memory provider.
     *
     * @return \Orchestra\Contracts\Memory\Provider
     */
    protected function memoryProvider(): Provider
    {
        $app = Container::getInstance();

        if (! $app->bound('orchestra.installed') || $app['orchestra.installed'] === false) {
            $app->instance('orchestra.platform.memory', $app->make('orchestra.memory')->make());
        }

        return $app->make('orchestra.platform.memory');
    }
}
