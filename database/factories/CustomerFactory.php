<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;
use App\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {

    $faker->addProvider(new Person($faker));
    
    return [
        "type_id" => 1,
        "name" => $faker->name,
        "cpf" => $faker->cpf,
        "state_id" => 1,
        "city_id" => 1,
        "district" => $faker->streetName,
        "complement" => $faker->streetAddress
    ];
    
});
