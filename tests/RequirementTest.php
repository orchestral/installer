<?php namespace Orchestra\Installation\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Installation\Requirement;

class RequirementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    private $app;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $this->app = new Container();
        $this->app['db'] = m::mock('\Illuminate\Database\DatabaseManager');
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->app);

        m::close();
    }

    /**
     * Test construct Orchestra\Foundation\Installation\Requirement.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $app = $this->app;
        $stub = new Requirement($app);
        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');
        $installable = $refl->getProperty('installable');

        $items->setAccessible(true);
        $installable->setAccessible(true);

        $items->setValue($stub, ['foo', 'bar']);
        $installable->setValue($stub, true);

        $this->assertEquals(['foo', 'bar'], $stub->items());
        $this->assertTrue($stub->isInstallable());
    }

    /**
     * Test Orchestra\Foundation\Installation\Requirement::check() method.
     *
     * @test
     */
    public function testCheckMethod()
    {
        $app = m::mock('\Illuminate\Contracts\Foundation\Application');
        $stub = new Requirement($app);

        $db = m::mock('\Orchestra\Installation\Specifications\DatabaseConnection[check]', [$app]);

        $db->shouldReceive('check')->once()->andReturn(false);

        $stub->add($db);

        $this->assertFalse($stub->check());
        $this->assertFalse($stub->isInstallable());
    }
}
