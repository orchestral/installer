<?php

namespace Orchestra\Installation\Tests\Controller;

use Mockery as m;
use Illuminate\Support\Fluent;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Contracts\Installation\Installation;

class InstallerControllerTest extends TestCase
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
    public function it_can_check_requirements()
    {
        $this->visit('admin/install')
            ->assertViewHas('requirements');
    }

    /** @test */
    public function it_can_prepare_installation()
    {
        $this->visit('admin/install')
            ->seeLink('Next', 'admin/install/prepare')
            ->click('Next')
            ->seePageIs('admin/install/create');
    }

    /** @test */
    public function it_can_create_user()
    {
        $this->visit('admin/install')
            ->seeLink('Next', 'admin/install/prepare')
            ->click('Next')
            ->seePageIs('admin/install/create')
            ->type('Orchestra Platform', 'site_name')
            ->type('crynobone@gmail.com', 'email')
            ->type('secret', 'password')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->press('Submit')
            ->seePageIs('admin/install/done');

        $this->seeInDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);
    }

    /** @test */
    public function it_cant_create_user_due_to_validation_fails()
    {
        $this->visit('admin/install')
            ->seeLink('Next', 'admin/install/prepare')
            ->click('Next')
            ->seePageIs('admin/install/create')
            ->type('Orchestra Platform', 'site_name')
            ->type('crynobone[at]gmail.com', 'email')
            ->type('secret', 'password')
            ->type('Mior Muhammad Zaki', 'fullname')
            ->press('Submit')
            ->seePageIs('admin/install/create')
            ->seeText('The email must be a valid email address.');

        $this->dontSeeInDatabase('users', [
            'email' => 'crynobone@gmail.com',
            'fullname' => 'Mior Muhammad Zaki',
        ]);
    }

    /** @test */
    public function it_can_show_installation_is_done()
    {
        $this->runInstallation();

        $this->visit('admin/install/done')
            ->seeText('Thank you for choosing Orchestra Platform')
            ->seeLink('Login', 'admin/login');
    }
}
