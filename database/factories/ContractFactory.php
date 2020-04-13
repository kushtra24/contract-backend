<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(App\Contract::class, function (Faker $faker) {

    $year = rand(2019, 2020);
    $month = rand(1, 12);
    $day = rand(1, 28);

    $date = Carbon::create($year,$month ,$day , 0, 0, 0);

    return [
        'type_id' => $faker->numberBetween(100,999),
        'title' => $faker->streetName,
        'temporary' => $faker->boolean,
        'end_date' => $date->format('Y-m-d H:i:s'),
        'original_at_team_assistant' => $faker->boolean,
        'segment_id' => $faker->numberBetween(1000, 9999),
        'rating' => $faker->numberBetween(1,5),
        'rating_bg' => $faker->numberBetween(1,5),
        'submitting_person_id' => $faker->numberBetween(1000, 9999),
        'customer_number' => $faker->numberBetween(100,999),
        'signed_date' => $date->format('Y-m-d H:i:s'),
        'customer_id' => $faker->numberBetween(1,99),
        'created_at' => $faker->date(),
    ];
});
