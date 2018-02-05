<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    $roles = ['centre_user', 'foodmatters_user'];

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role' => $roles[mt_rand(0, count($roles) - 1)],
    ];
});

// A model with a random Centre
// Should be Registration or User
$factory->state(App\User::class, 'withRandomCentre', function () {

    $centres  = App\Centre::get();

    if ($centres->count() > 0) {
        // Pick a random Centre
        $centre = $centres[random_int(0, $centres->count()-1)];
    } else {
        // There should be at least one Centre
        $centre = factory(App\Centre::class)->create();
    }

    return [
        'centre_id' => $centre->id,
    ];
});

// Centre, with random sponsor
$factory->define(App\Centre::class, function (Faker\Generator $faker) {

    $sponsors = App\Sponsor::get();

    if ($sponsors->count() > 0) {
        // Pick a random Sponsor
        $sponsor = $sponsors[random_int(0, $sponsors->count()-1)];
    } else {
        // There must be at least one Sponsor
        $sponsor = factory(App\Sponsor::class)->create();
    }

    $name = $faker->streetName;

    return [
        'name' => $name,
        // *Probably* not going to generate a duplicate...
        // But metaphone will occassionally return 6 chars if endish char is an X -> KS
        // https://bugs.php.net/bug.php?id=60123
        // Also might return 4 chars - but that's ok for seeds? Or do we pad?
        'prefix' => substr(metaphone($name, 5), 0, 5),
        'sponsor_id' => $sponsor->id,
        // print_pref will be 'collection' by default.
        // To ensure we always have one 'individual', adding to seeder as well.
        'print_pref' => $faker->randomElement(['individual', 'collection']),
    ];
});

// Sponsor, or Local Authority.
$factory->define(App\Sponsor::class, function (Faker\Generator $faker) {

    $counties = [
        "Barnfordshire",
        "Barsetshire",
        "Borsetshire",
        "Burtondon",
        "Diddlesex",
        "Downshire",
        "Ffhagdiwedd",
        "Gaultshire",
        "Glebeshire",
        "Glenshire",
        "West PassingBury",
        "Loamshire",
        "Mangelwurzelshire",
        "Markshire",
        "Mallardshire",
        "Melfordshire",
        "Mertonshire",
        "Mortshire",
        "Midsomer",
        "Mummerset",
        "Naptonshire",
        "Oatshire",
        "Placefordshire",
        "Quantumshire",
        "Radfordshire",
        "Redshire",
        "Russetshire",
        "Rutshire",
        "Shiring",
        "Shroudshire",
        "Slopshire",
        "Southmoltonshire",
        "South Riding",
        "Stonyshire",
        "Trumptonshire",
        "Wessex",
        "Westershire",
        "Waringham",
        "Westshire",
        "Winshire",
        "Wordenshire",
        "Worfordshire",
        "South Worfordshire",
        "Wyverndon",
    ];

    $index = $faker->unique()->numberBetween(0, 43);

    return [
        'name' => $counties[$index],
        'shortcode' => "RV" . str_pad($index, 3, "0", STR_PAD_LEFT),
    ];
});

// Registration
$factory->define(App\Registration::class, function () {

    $eligibilities = ['healthy-start', 'other'];

    $centre = App\Centre::inRandomOrder()->first();
    if (is_null($centre)) {
        $centre = factory(App\Centre::class)->create();
    }
    $family = factory(App\Family::class)->make();
    $family->generateRVID($centre);
    $family->save();
    $family->carers()->saveMany(factory(App\Carer::class, random_int(1, 3))->make());
    $family->children()->saveMany(factory(\App\Child::class, random_int(0, 4))->make());


    return [
        'centre_id' => $centre->id,
        'family_id' => $family->id,
        'eligibility' => $eligibilities[mt_rand(0, count($eligibilities) - 1)],
        'consented_on' => Carbon\Carbon::now(),
    ];
});

// Family
$factory->define(App\Family::class, function () {
    // One day there will be useful things here.
    return [];
});

// Carer
$factory->define(App\Carer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName ." ". $faker->lastName,
    ];
});

// Random Age Child
$factory->define(App\Child::class, function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-6 years', '+9 months')->getTimestamp());
    $dob = $dob->startOfMonth();
    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - unborn
$factory->defineAs(App\Child::class, 'unbornChild', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('+2 month', '+8 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - under 1
$factory->defineAs(App\Child::class, 'underOne', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-10 months', '-2 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - under School Age
$factory->defineAs(App\Child::class, 'underSchoolAge', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-32 months', '-14 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - over SchoolAge
$factory->defineAs(App\Child::class, 'overSchoolAge', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-10 years', '-6 years')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});
