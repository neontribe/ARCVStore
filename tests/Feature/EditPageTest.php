<?php

namespace Tests;

use App\Carer;
use App\Centre;
use App\Child;
use App\User;
use App\Registration;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use URL;

class EditPageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Centre $centre
     * @var User $user
     * @var Registration $registration
     */
    private $centre;
    private $user;
    private $registration;

    public function setUp()
    {
        parent::setUp();

        $this->centre = factory(Centre::class)->create();

        // Create a User
        $this->user =  factory(User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $this->centre->id,
        ]);

        // make centre some registrations
        $this->registration = factory(Registration::class)->create([
            "centre_id" => $this->centre->id,
        ]);
    }

    /** @test */
    public function itShowsAPrimaryCarerInput()
    {
        $pri_carer = $this->registration->family->carers->first();
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
            ->seeElement('input[name="carer"][value="'. $pri_carer->name .'"]')
        ;
    }

    /** @test */
    public function itShowsASecondaryCarerInput()
    {
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
            ->seeElement('input[name="carer_adder_input"]')
            ->seeElement('button[id="add-dob"]')
        ;
    }

    /** @test */
    public function itShowsAListOfSecondaryCarers()
    {
        // Clear the carers
        $this->registration->family->carers()->delete();
        // Make 4 more
        $new_carers = factory(Carer::class, 4)->make();
        // Add to Family
        $this->registration->family->carers()->saveMany($new_carers);

        // Get the carers again
        $carers = $this->registration->family->carers;

        // Knock the first one off
        $carers->shift();

        // There should be 3...
        $this->assertTrue($carers->count() == 3);

        // Find the edit page
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
        ;
        // See the names in the page
        foreach ($carers as $sec_carer) {
             $this->see($sec_carer->name)
                 ->seeElement('input[type="hidden"][value="'. $sec_carer->name .'"]')
                 ;
        }
    }


    /** @test */
    public function itShowsAChildInputComplex()
    {
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
            ->seeElement('input[name="dob-month"]')
            ->seeElement('input[name="dob-year"]')
            ->seeElement('button[id="add-dob"]')
        ;
    }

    /** @test */
    public function itShowsAListOfChildren()
    {
        // Clear the children
        $this->registration->family->children()->delete();
        // Make 4 more
        $new_children = factory(Child::class, 4)->make();
        // Add to Family
        $this->registration->family->children()->saveMany($new_children);

        // Get the children again
        $children = $this->registration->family->children;

        // There should be 4...
        $this->assertTrue($children->count() == 4);

        // Find the edit page
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
        ;
        // See the names in the page
        foreach ($children as $child) {
            $this->see('<td>'. $child->getAgeString() .'</td>')
                ->see('<td>'. $child->getDobAsString() .'</td>')
                ->seeElement('input[type="hidden"][value="'. $child->dob->format('Y-m') .'"]')
            ;
        }
    }

    /** @test */
    public function itShowsAFormSaveButton()
    {
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]))
            ->seeInElement('button[type=submit]', 'Save Changes')
        ;
    }

    /** @test */
    public function itOnlyShowsAFoodMattersInputsToFoodMattersUsers()
    {
        // Create a FM User
        $users[] = factory(User::class)->create([
            "name"  => "test FM user",
            "email" => "testufmser@example.com",
            "password" => bcrypt('test_fm_user_pass'),
            "centre_id" => $this->centre->id,
            "role" => 'foodmatters_user',
        ]);

        $users[] = factory(User::class)->create([
            "name"  => "test cc user",
            "email" => "testccuser@example.com",
            "password" => bcrypt('test_cc_user_pass'),
            "centre_id" => $this->centre->id,
            "role" => 'centre_user',
        ]);

        foreach ($users as $user) {
            $this->actingAs($user)
                ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration ]));
            if ($user->can('updateDiary', Registration::class) ||
                $user->can('updateChart', Registration::class)
            ) {
                $this->see('Documents Received:');
                if ($user->can('updateChart')) {
                    $this->seeElement('input[type="hidden"][name="fm_chart"]');
                    $this->seeElement('input[type="checkbox"][name="fm_chart"]');
                }
                if ($user->can('updateDiary')) {
                    $this->seeElement('input[type="hidden"][name="fm_diary"]');
                    $this->seeElement('input[type="checkbox"][name="fm_diary"]');
                }
            } else {
                $this->dontSee('Documents Received:');
                $this->dontSeeElement('input[type="hidden"][name="fm_chart"]');
                $this->dontSeeElement('input[type="checkbox"][name="fm_chart"]');
                $this->dontSeeElement('input[type="hidden"][name="fm_diary"]');
                $this->dontSeeElement('input[type="checkbox"][name="fm_diary"]');
            }
        }
    }

    /** @test */
    public function itShowsDiaryAndChartCheckedCorrectlyAsStoredInDatabase()
    {
        // Create a User
        $fmuser = factory(User::class)->create([
            'name' => 'test fm user',
            'email' => 'testfmuser@example.com',
            'password' => bcrypt('test_fmuser_pass'),
            'centre_id' => $this->centre->id,
            'role' => 'foodmatters_user',
        ]);

        $now = Carbon::now();

        // make no docs registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => null,
            'fm_chart_on' => null,
        ]);

        // make chart only registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => $now,
            'fm_chart_on' => null,
        ]);

        // make diary only registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => null,
            'fm_chart_on' => $now,
        ]);

        // make both docs registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => $now,
            'fm_chart_on' => $now,
        ]);

        foreach ($regs as $reg) {
            $route = URL::route('service.registration.edit', ['id' => $reg->id]);
            Auth::logout();
            $this->actingAs($fmuser)
                ->visit($route)
            ;

            // Test Chart
            if ($reg->fm_chart_on !== null) {
                $this->seeInDatabase(
                    'registrations',
                    ['id' => $reg->id, 'fm_chart_on' => $now]
                );
                $this->seeElement('input[name="fm_chart"][checked]');
            } else {
                $this->seeInDatabase(
                    'registrations',
                    [ 'id' => $reg->id, 'fm_chart_on' => null ]
                );
                $this->seeElement('input[name="fm_chart"]:not(:checked)');
                $this->dontSeeElement('input[name="fm_chart"][checked]');
            }

            // Test Diary;
            if ($reg->fm_diary_on !== null) {
                $this->seeInDatabase(
                    'registrations',
                    ['id' => $reg->id, 'fm_diary_on' => $now]
                );
                $this->seeElement('input[name="fm_diary"][checked]');
            } else {
                $this->seeInDatabase(
                    'registrations',
                    [ 'id' =>  $reg->id, 'fm_diary_on' => null ]
                );
                $this->seeElement('input[name="fm_diary"]:not(:checked)');
                $this->dontSeeElement('input[name="fm_diary"][checked]');
            }
        }

    }

    /** @test */
    public function itLetsAnAuthedUserUpdateDiaryOrChartState()
    {
        // Create a User
        $fmuser = factory(User::class)->create([
            'name' => 'test fm user',
            'email' => 'testfmuser@example.com',
            'password' => bcrypt('test_fmuser_pass'),
            'centre_id' => $this->centre->id,
            'role' => 'foodmatters_user',
        ]);

        $now = Carbon::now();

        // make no docs registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => null,
            'fm_chart_on' => null,
        ]);

        // make chart only registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => $now,
            'fm_chart_on' => null,
        ]);

        // make diary only registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => null,
            'fm_chart_on' => $now,
        ]);

        // make both docs registration
        $regs[] = factory(Registration::class)->create([
            'centre_id' => $this->centre->id,
            'fm_diary_on' => $now,
            'fm_chart_on' => $now,
        ]);

        foreach ($regs as $reg) {
            $route = URL::route('service.registration.edit', ['id' => $reg->id]);
            $this->actingAs($fmuser)
                ->visit($route);

            // Test Chart
            if ($reg->fm_chart_on !== null) {
                $this->check('fm_chart');
            } else {
                $this->uncheck('fm_chart');
            }

            // Test Diary;
            if ($reg->fm_diary_on !== null) {
                $this->check('fm_diary');
            } else {
                $this->uncheck('fm_diary');
            }

            // We get back to edit page
            $this->press('Save Changes')
                ->seeStatusCode(200)
                ->seePageIs($route)
            ;

            // Check the data has changed
            if ($reg->fm_chart_on !== null) {
                // Just chec it is no longer null. Using Carbon time caused frequent intermittant failure.
                $this->dontSeeInDatabase(
                    'registrations',
                    [ 'id' => $reg->id, 'fm_chart_on' => null ]
                );
            } else {
                $this->seeInDatabase(
                    'registrations',
                    [ 'id' => $reg->id, 'fm_chart_on' => null ]
                );
            }

            if ($reg->fm_diary_on !== null) {
                $this->dontSeeInDatabase(
                    'registrations',
                    [ 'id' => $reg->id, 'fm_diary_on' => null ]
                );
            } else {
                $this->seeInDatabase(
                    'registrations',
                    [ 'id' => $reg->id, 'fm_diary_on' => null ]
                );
            }
        }
    }

    /** @test */
    public function itShowsTheLoggedInUserDetails()
    {
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration->id ]))
            ->see($this->user->name)
            ->see($this->user->centre->name)
        ;
    }

    /** @test */
    public function itShowsTheLeavingFormIfFamilyIsOnScheme()
    {
    }

    /** @test */
    public function itDoeNotShowTheLeavingFormIfFamilyIsOnScheme()
    {
    }

    /** @test */
    public function itWillRejectLeavingWithoutAReason()
    {
    }

    /** @test */
    public function itWillAcceptLeavingWithAReason()
    {
    }

    /** @test */
    public function itWillRejectUpdatesIfFamilyHasLeft()
    {
    }
}
