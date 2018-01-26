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

    /** test */
    public function testCentreRegistrationsSummaryGate() {
        // Create an FM User
        $fmuser =  factory(App\User::class)->create([
            "name"  => "FM test user",
            "email" => "testfmuser@example.com",
            "password" => bcrypt('test_fmuser_pass'),
            "centre_id" => $this->centre->id,
            "role" => "foodmatters_user",
        ]);

        // Create a CC user
        $ccuser =  factory(App\User::class)->create([
            "name"  => "CC test user",
            "email" => "testccuser@example.com",
            "password" => bcrypt('test_ccuser_pass'),
            "centre_id" => $this->centre->id,
            "role" => "centre_user",
        ]);

        // Make some registrations
        factory(App\Registration::class, 5)->create([
            "centre_id" => $this->centre->id,
        ]);

        $route = URL::route('service.centres.registrations.summary');

        Auth::logout();

        // Bounce unauth'd to login
        $this->visit($route)
            ->seePageIs(URL::route('service.login'))
            ->assertResponseStatus(200)
        ;

        // Throw a 403 for auth'd but forbidden
        $this->actingAs($ccuser)
            // need to get, because visit() bombs out with exceptions before you can check them.
            ->get($route)
            ->assertResponseStatus(403)
        ;
        Auth::logout();

        // See page do interesting things
        $this->actingAs($fmuser)
            ->visit($route)
            ->assertResponseOK()
        ;
    }
}
