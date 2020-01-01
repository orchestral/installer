<?php

namespace Orchestra\Installation\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Mockery as m;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Contracts\Memory\Provider as MemoryProvider;
use Orchestra\Foundation\Auth\User;

class InstallCommandTest extends TestCase
{
    /** @test */
    public function it_cant_make_installation_when_orchestra_is_not_installed()
    {
        $this->app->instance('orchestra.app', $foundation = m::mock(Foundation::class));

        $foundation->shouldReceive('installed')->andReturn(true)
            ->shouldReceive('memory')->andReturn(m::mock(MemoryProvider::class));

        $this->artisan('orchestra:install')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_can_make_installation_when_orchestra_is_not_installed()
    {
        $this->assertFalse($this->app->make('orchestra.app')->installed());

        $this->artisan('orchestra:install')
            ->expectsQuestion('Application name?', 'The Application')
            ->expectsQuestion('Administrator fullname?', 'App Administrator')
            ->expectsQuestion('Administrator e-mail address?', 'crynobone@gmail.com')
            ->expectsQuestion('Administrator password?', 'secret')
            ->assertExitCode(0);

        $memory = $this->app->make('orchestra.platform.memory');

        $this->assertEquals('The Application', $memory->get('site.name'));
        $this->assertEquals('The Application', $memory->get('email.from.name'));
        $this->assertEquals('crynobone@gmail.com', $memory->get('email.from.address'));

        $this->assertDatabaseHas('users', [
            'fullname' => 'App Administrator',
            'email' => 'crynobone@gmail.com',
        ]);

        $user = User::find(1);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($user->hasRoles('Administrator'));
    }

    /** @test */
    public function it_can_make_installation_using_options_when_orchestra_is_not_installed()
    {
        $this->assertFalse($this->app->make('orchestra.app')->installed());

        $this->artisan('orchestra:install', ['--email' => 'crynobone@gmail.com'])
            ->expectsQuestion('Application name?', 'The Application')
            ->expectsQuestion('Administrator fullname?', 'App Administrator')
            ->expectsQuestion('Administrator password?', 'secret')
            ->assertExitCode(0);

        $memory = $this->app->make('orchestra.platform.memory');

        $this->assertEquals('The Application', $memory->get('site.name'));
        $this->assertEquals('The Application', $memory->get('email.from.name'));
        $this->assertEquals('crynobone@gmail.com', $memory->get('email.from.address'));

        $this->assertDatabaseHas('users', [
            'fullname' => 'App Administrator',
            'email' => 'crynobone@gmail.com',
        ]);

        $user = User::find(1);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($user->hasRoles('Administrator'));
    }

    /** @test */
    public function it_can_make_installation_using_options_with_password_when_orchestra_is_not_installed()
    {
        $this->assertFalse($this->app->make('orchestra.app')->installed());

        $this->artisan('orchestra:install', ['--email' => 'crynobone@gmail.com', '--password' => 'password'])
            ->expectsQuestion('Application name?', 'The Application')
            ->expectsQuestion('Administrator fullname?', 'App Administrator')
            ->assertExitCode(0);

        $memory = $this->app->make('orchestra.platform.memory');

        $this->assertEquals('The Application', $memory->get('site.name'));
        $this->assertEquals('The Application', $memory->get('email.from.name'));
        $this->assertEquals('crynobone@gmail.com', $memory->get('email.from.address'));

        $this->assertDatabaseHas('users', [
            'fullname' => 'App Administrator',
            'email' => 'crynobone@gmail.com',
        ]);

        $user = User::find(1);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertTrue($user->hasRoles('Administrator'));
    }

    /** @test */
    public function it_can_make_installation_using_options_without_interaction_when_orchestra_is_not_installed()
    {
        $this->assertFalse($this->app->make('orchestra.app')->installed());

        $this->artisan('orchestra:install', ['--email' => 'crynobone@gmail.com', '--no-interaction' => true])
            ->assertExitCode(0);

        $memory = $this->app->make('orchestra.platform.memory');

        $this->assertEquals('My Application', $memory->get('site.name'));
        $this->assertEquals('My Application', $memory->get('email.from.name'));
        $this->assertEquals('crynobone@gmail.com', $memory->get('email.from.address'));

        $this->assertDatabaseHas('users', [
            'fullname' => 'Administrator',
            'email' => 'crynobone@gmail.com',
        ]);

        $user = User::find(1);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($user->hasRoles('Administrator'));
    }
}
