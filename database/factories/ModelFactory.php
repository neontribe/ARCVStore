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

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
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

    return [
        'name' => $faker->streetName,
        'sponsor_id' => $sponsor->id,
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

    $family=factory(App\Family::class)->create();
    $family->carers()->saveMany(factory(App\Carer::class, random_int(1, 3))->make());
    $family->children()->saveMany(factory(\App\Child::class, random_int(0, 4))->make());

    $centre = Auth::user()->centre;

    return [
        'centre_id' => $centre->id,
        'family_id' => $family->id,
        'cc_reference' => '',
        'eligibility' => $eligibilities[mt_rand(0, count($eligibilities) - 1)],
        'consented_on' => Carbon\Carbon::now(),
    ];
});

// with RandomCCReference
$factory->state(App\Registration::class, 'withCCReference', function (Faker\Generator $faker) {

    $centre = Auth::user()->centre;
    $shortcode = $centre->sponsor->shortcode;

    return [
        'cc_reference' => $shortcode . "-" . $faker->unique()->randomNumber(6),
    ];
});

// Family
$factory->define(App\Family::class, function () {
    return [
        'rvid' => \App\Family::generateRVID(),
    ];
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
$factory->state(App\Child::class, 'withUnbornChild', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('1 month', '+9 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - under 1
$factory->state(App\Child::class, 'withChildUnderOne', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-11 months', '-1 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - under School Age
$factory->state(App\Child::class, 'withChildUnderSchoolAge', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-35 months', '-13 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});

// Child - over SchoolAge
$factory->state(App\Child::class, 'withChildOverSchoolAge', function (Faker\Generator $faker) {

    $dob = Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-6 years', '-37 months')->getTimestamp());
    $dob = $dob->startOfMonth();

    return [
        'born' => $dob->isPast(),
        'dob' => $dob->toDateTimeString(),
    ];
});
