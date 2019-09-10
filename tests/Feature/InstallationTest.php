<?php

namespace Orchestra\Installation\Tests\Feature;

use Mockery as m;
use Illuminate\Support\Facades\Schema;
use Orchestra\Installation\Installation;

class InstallationTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');

        parent::tearDown();
    }

    /** @test */
    public function it_can_boot_installer_files()
    {
        $this->instance('files', $files = m::mock('\Illuminate\Filesystem\Filesystem'));

        $files->shouldReceive('exists')->once()->with($this->app->databasePath('orchestra/installer.php'))->andReturn(true)
            ->shouldReceive('getRequire')->once()->with($this->app->databasePath('orchestra/installer.php'))->andReturnNull()
            ->shouldReceive('exists')->once()->with($this->app->basePath('orchestra/installer.php'))->andReturn(true)
            ->shouldReceive('getRequire')->once()->with($this->app->basePath('orchestra/installer.php'))->andReturnNull();

        $stub = new Installation();
        $this->assertNull($stub->bootInstallerFiles());
    }

    /** @test */
    public function it_can_migrate_installer()
    {
        $this->assertNotInstalled();

        $stub = new Installation();
        $this->assertTrue($stub->migrate());

        $this->assertInstalled();
    }

    /** @test */
    public function it_can_run_installation()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email' => 'admin@orchestraplatform.com',
            'password' => 'secret',
            'fullname' => 'Administrator',
        ];

        $this->assertNotInstalled();

        $stub = new Installation();

        $this->assertTrue($stub->migrate());
        $this->assertTrue($stub->make($data, false));

        $this->assertInstalled();

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }

    /** @test */
    public function it_cant_run_installation_due_to_validation_fails()
    {
        $data = [
            'site_name' => 'Orchestra Platform',
            'email' => 'admin[at]orchestraplatform.com',
            'password' => 'secret',
            'fullname' => 'Administrator',
        ];

        $this->assertNotInstalled();

        $stub = new Installation();

        $this->assertTrue($stub->migrate());
        $this->assertFalse($stub->make($data, false));

        $this->assertInstalled();

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
            'fullname' => $data['fullname'],
        ]);
    }

    /** @test */
    public function it_cant_run_installation_due_existing_users()
    {
        $this->instance('orchestra.messages', $messages = m::mock('\Orchestra\Contracts\Messages\MessageBag'));

        $adminUser = $this->runInstallation();

        $data = [
            'site_name' => 'Orchestra Platform',
            'email' => 'admin@orchestraplatform.com',
            'password' => 'secret',
            'fullname' => 'Administrator',
        ];

        $messages->shouldReceive('add')->once()
            ->with('error', 'Unable to install when there already user registered.')->andReturnNull();

        $stub = new Installation();
        $this->assertFalse($stub->make($data, false));
    }

    /**
     * Assert not yet installed.
     *
     * @return void
     */
    protected function assertNotInstalled(): void
    {
        $this->assertFalse(Schema::hasTable('password_resets'));
        $this->assertFalse(Schema::hasTable('orchestra_options'));
        $this->assertFalse(Schema::hasTable('roles'));
        $this->assertFalse(Schema::hasTable('users'));
        $this->assertFalse(Schema::hasTable('user_meta'));
        $this->assertFalse(Schema::hasTable('user_role'));
    }

    /**
     * Assert has been installed.
     *
     * @return void
     */
    protected function assertInstalled(): void
    {
        $this->assertTrue(Schema::hasTable('password_resets'));
        $this->assertTrue(Schema::hasTable('orchestra_options'));
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('user_role'));
    }
}
