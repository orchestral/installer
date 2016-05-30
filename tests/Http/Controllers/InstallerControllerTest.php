<?php namespace Orchestra\Installation\TestCase\Http\Controllers;

use Mockery as m;
use Illuminate\Support\Facades\Config;
use Orchestra\Installation\TestCase\TestCase;

class InstallerControllerTest extends TestCase
{

    /**
     * Test GET /admin/install.
     *
     * @test
     */
    public function testGetIndexAction()
    {
        $this->call('GET', 'admin/install');

        $this->assertResponseOk();

        $this->assertViewHas('requirements');
    }

    /**
     * Test GET /admin/install/prepare.
     *
     * @test
     */
    public function testGetPrepareAction()
    {
        $installer = m::mock('\Orchestra\Contracts\Installation\Installation');
        $requirement = m::mock('\Orchestra\Contracts\Installation\Requirement');

        $installer->shouldReceive('bootInstallerFiles')->once()->andReturnNull()
            ->shouldReceive('migrate')->once()->andReturnNull();

        $requirement->shouldReceive('check')->once()->andReturn(true);

        $this->app->bind('Orchestra\Contracts\Installation\Installation', function () use ($installer) {
            return $installer;
        });

        $this->app->bind('Orchestra\Contracts\Installation\Requirement', function () use ($requirement) {
            return $requirement;
        });

        $this->call('GET', 'admin/install/prepare');
        $this->assertRedirectedTo(handles('orchestra::install/create'));
    }

    /**
     * Test GET /admin/install/create.
     *
     * @test
     */
    public function testGetCreateAction()
    {
        $this->call('GET', 'admin/install/create');
        $this->assertResponseOk();
        $this->assertViewHas('siteName', 'Orchestra Platform');
    }

    /**
     * Test GET /admin/install/create.
     *
     * @test
     */
    public function testPostCreateAction()
    {
        $input = [];
        $installer = m::mock('\Orchestra\Contracts\Installation\Installation');
        $installer->shouldReceive('bootInstallerFiles')->once()->andReturnNull()
            ->shouldReceive('make')->once()->with($input)->andReturn(true);

        $this->app->bind('Orchestra\Contracts\Installation\Installation', function () use ($installer) {
            return $installer;
        });

        $this->call('POST', 'admin/install/create', $input);
        $this->assertRedirectedTo(handles('orchestra::install/done'));
    }

    /**
     * Test GET /admin/install/create when create admin failed.
     *
     * @test
     */
    public function testPostCreateActionWhenCreateAdminFailed()
    {
        $input = [];
        $installer = m::mock('\Orchestra\Contracts\Installation\Installation');
        $installer->shouldReceive('bootInstallerFiles')->once()->andReturnNull()
            ->shouldReceive('make')->once()->with($input)->andReturn(false);

        $this->app->bind('Orchestra\Contracts\Installation\Installation', function () use ($installer) {
            return $installer;
        });

        $this->call('POST', 'admin/install/create', $input);
        $this->assertRedirectedTo(handles('orchestra::install/create'));
    }

    /**
     * Test GET /admin/install/done.
     *
     * @test
     */
    public function testGetDoneAction()
    {
        $this->call('GET', 'admin/install/done');
        $this->assertResponseOk();
    }
}
