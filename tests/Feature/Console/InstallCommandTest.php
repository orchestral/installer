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
            ->expectsOutput('This command can only be executed when the application is not installed!')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_can_make_installation_when_orchestra_is_installed()
    {
        $this->assertFalse($this->app->make('orchestra.app')->installed());

        $this->artisan('orchestra:install')
            ->expectsQuestion('Application name?', 'The Application')
            ->expectsQuestion('Administrator fullname?', 'App Administrator')
            ->expectsQuestion('Administrator e-mail address?', 'crynobone@gmail.com')
            ->expectsQuestion('Administrator password?', 'secret')
            ->expectsOutput('Installation completed')
            ->assertExitCode(0);

        $memory = $this->app->make('orchestra.memory');

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
}
