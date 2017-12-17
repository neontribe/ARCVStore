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
$factory->state(\Illuminate\Database\Eloquent\Model::class, 'withRandomCentre', function () {

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
/** @var \Illuminate\Database\Eloquent\Factory $factory */
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

// registration
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Registration::class, function (Faker\Generator $faker) {

    return [
        'cc_reference' => '',
        'eligability' =>
    ];
});

// Family
/** @var \Illuminate\Database\Eloquent\Factory $factory */#
$factory->define(App\Family::class, function (Faker\Generator $faker) {
    return [
        'rvid' => \App\Family::generateRVID(),
    ];
});

// Carer
$factory->define(App\Family::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->firstName ." ". $faker->lastName,
    ];
});
