<?php

namespace Orchestra\Installation\TestCase\Feature;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Installation\Requirement;

class RequirementTest extends TestCase
{
    /**
     * Test construct Orchestra\Foundation\Installation\Requirement.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $stub = new Requirement($this->app);

        $spec = m::mock('\Orchestra\Contracts\Installation\Specification');
        $spec->shouldReceive('uid')->andReturn('mock')
            ->shouldReceive('check')->andReturn(true);

        $stub->add($spec);

        $this->assertInstanceOf('\Illuminate\Support\Collection', $stub->items());
        $this->assertEquals(['mock' => $spec], $stub->items()->all());
        $this->assertTrue($stub->isInstallable());
    }

    /**
     * Test Orchestra\Foundation\Installation\Requirement::check() method.
     *
     * @test
     */
    public function testCheckMethod()
    {
        $stub = new Requirement($this->app);

        $spec = m::mock('\Orchestra\Contracts\Installation\Specification');
        $spec->shouldReceive('uid')->andReturn('mock')
            ->shouldReceive('check')->andReturn(false)
            ->shouldReceive('optional')->andReturn(false);

        $stub->add($spec);

        $this->assertFalse($stub->check());
        $this->assertFalse($stub->isInstallable());
    }
}
