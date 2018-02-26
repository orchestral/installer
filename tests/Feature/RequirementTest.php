<?php

namespace Orchestra\Installation\Tests\Feature;

use Mockery as m;
use Orchestra\Installation\Requirement;

class RequirementTest extends TestCase
{
    /** @test */
    public function it_declares_proper_signature()
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

    /** @test */
    public function it_can_check_requirements()
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
