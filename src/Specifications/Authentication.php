<?php

namespace Orchestra\Installation\Specifications;

use Orchestra\Foundation\Auth\User;
use Orchestra\Support\Str;
use ReflectionException;

class Authentication extends Specification
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
    protected $description = 'Orchestra Platform strictly require Eloquent based authentication with our Role Based Access Control (RBAC) which utilize eloquent relationship. Please ensure that <code>{model}</code> is extending <code>Orchestra\Foundation\Auth\User</code>.';

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check(): bool
    {
        $auth = $this->parseAuthenticationConfiguration(\config('auth'));

        try {
            $this->description = Str::replace($this->description, ['model' => $auth['provider']['model']]);

            return $this->validateUserProvider($auth) && $this->validateUserInstance($auth);
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
    protected function parseAuthenticationConfiguration(array $auth): array
    {
        $driver = $auth['defaults']['guard'] ?? null;

        $guard = $auth['guards'][$driver] ?? [
            'driver' => 'session',
            'provider' => 'users',
        ];

        $provider = $auth['providers'][$guard['provider']] ?? [
            'driver' => 'eloquent',
            'model' => User::class,
        ];

        return \compact('guard', 'provider');
    }

    /**
     * Validate user instance.
     *
     * @param  array  $auth
     *
     * @return bool
     */
    protected function validateUserInstance(array $auth): bool
    {
        return \app($auth['provider']['model']) instanceof User;
    }

    /**
     * Validate user provider.
     *
     * @param  array  $auth
     *
     * @return bool
     */
    protected function validateUserProvider(array $auth): bool
    {
        return \in_array($auth['provider']['driver'], ['authen', 'eloquent']);
    }
}
