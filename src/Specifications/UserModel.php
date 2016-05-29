<?php

namespace Orchestra\Installation\Specifications;

use Orchestra\Model\User;
use Orchestra\Support\Str;

class UserModel extends Specification
{
    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid = 'model';

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title = 'Model is configured to authenticate Orchestra Platform';

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = 'Orchestra Platform strictly require Eloquent based authentication with our Role Based Access Control (RBAC) which utilize eloquent relationship. Please ensure that <code>{model}</code> is extending <code>Orchestra\Model\User</code>.';

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check()
    {
        $auth = $this->getAuthConfiguration($this->app->make('config')->get('auth'));

        try {
            $this->description = Str::replace($this->description, ['model' => $auth['provider']['model']]);

            return ($auth['provider']['driver'] === 'eloquent' && $this->app->make($auth['provider']['model']) instanceof User);
        } catch (ReflectionException $e) {
            // Catch ReflectionException and return false.
        } finally {
            // Catch any exception.
        }

        return false;
    }

    /**
     * Resolve auth configuration.
     *
     * @param  array  $auth
     *
     * @return array
     */
    protected function getAuthConfiguration(array $auth)
    {
        $driver = Arr::get($auth, 'defaults.guard');

        $guard = Arr::get($auth, "guards.{$driver}", [
            'driver'   => 'session',
            'provider' => 'users',
        ]);

        $provider = Arr::get($auth, "providers.{$guard['provider']}", [
            'driver' => 'eloquent',
            'model'  => User::class,
        ]);

        return compact('guard', 'provider');
    }
}
