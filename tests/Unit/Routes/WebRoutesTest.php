<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WebRoutesTest extends TestCase
{
    use DatabaseMigrations;

    private $user = null;
    private $centre = null;

    public function setUp()
    {
        parent::setUp();

        $this->centre = factory(App\Centre::class)->create();

        // Create a User
        $this->user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $this->centre->id,
        ]);
    }

    /**
     * Verify login direct.
     *
     * @return void
     * @test
     */
    public function testLoginGuestRoute()
    {
        Auth::logout();
        $this->get(URL::route('service.login'))
            ->seePageIs(URL::route('service.login'))
            ->assertResponseStatus(200)
        ;
    }

    /**
     * Verify forgot password direct
     *
     * @return void
     * @test
     */
    public function testForgotPasswordGuestRoute()
    {
        Auth::logout();
        $this->get(URL::route('password.request'))
            ->seePageIs(URL::route('password.request'))
            ->assertResponseStatus(200)
        ;
    }

    /** @test */
    public function testDashboardRouteGate()
    {
        $route = URL::route('service.dashboard');

        Auth::logout();
        // You cannot get there logged out.
        $this->visit($route)
            ->seePageIs(URL::route('service.login'))
            ->assertResponseStatus(200)
        ;
        // You can get there logged in.
        $this->actingAs($this->user)
            ->visit($route)
            ->seePageIs($route)
            ->assertResponseStatus(200)
        ;
    }

    /** @test */
    public function testSearchRouteGate()
    {

        $route = URL::route('service.registration.index');

        Auth::logout();
        // You cannot get there logged out.
        $this->visit($route)
            ->seePageIs(URL::route('service.login'))
            ->assertResponseStatus(200)
        ;
        // You can get there logged in.
        $this->actingAs($this->user)
            ->visit($route)
            ->seePageIs($route)
            ->assertResponseStatus(200)
        ;
    }

    /** @test */
    public function testViewRouteGate()
    {

        // Create a random registration with our centre.
        $registration = factory(App\Registration::class)->create([
            "centre_id" => $this->centre->id,
        ]);

        $route = URL::route('service.registration.edit', [ 'registration' => $registration->id ]);

        Auth::logout();
        // You cannot get there logged out.
        $this->visit($route)
            ->seePageIs(URL::route('service.login'))
            ->assertResponseStatus(200)
        ;
        // You can get there logged in.
        $this->actingAs($this->user)
            ->visit($route)
            ->seePageIs($route)
            ->assertResponseStatus(200)
        ;
    }

    /** @test */
    public function testUpdateRouteGate()
    {
        // Create a random registration with our centre.
        $registration = factory(App\Registration::class)->create([
            "centre_id" => $this->centre->id,
        ]);

        $edit_route = URL::route('service.registration.edit', [ 'registration' => $registration->id ]);
        $login_route = URL::route('service.login');

        // You can get there logged in.
        $this->actingAs($this->user)
            ->visit($edit_route)
        ;

        $this->type("changedByTest", "carer")
            ->press("Save Changes")
            ->seePageIs($edit_route)
            ->assertResponseStatus(200)
            ->seeElement("input[value=changedByTest]")
        ;

        Auth::logout();

        $this->type("**blanked**", "carer")
            ->press("Save Changes")
            ->seePageIs($login_route)
            ->assertResponseStatus(200)
        ;
    }
}
