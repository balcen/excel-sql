<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Client;
use App\Product;
use App\Order;
use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'c_tax_id' => $faker->randomNumber(),
        'c_name' => $faker->firstName(),
        'c_type' => $faker->word(),
        'c_contact' => $faker->firstName(),
        'c_phone' => $faker->tollFreePhoneNumber(),
        'c_mail' => $faker->email()
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'p_type' => $faker->word(),
        'p_name' => $faker->word(),
        'p_part_no' => $faker->randomNumber(),
        'p_spec' => $faker->sentence(),
        'p_price' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 2),
        'p_size' => $faker->randomDigitNotNull(),
        'p_weight' => $faker->randomFloat($nvMaxDecimals = 4)
    ];
});

$factory->define(Order::class, function (Faker $faker) {
    return [
        'o_no' => $faker->randomNumber($strict = true),
        'o_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'o_seller_name' => $faker->firstName(),
        'o_buyer_name' => $faker->firstName(),
        'o_product_name' => $faker->word(),
        'o_product_part_no' => $faker->randomNumber(),
        'o_product_spec' => $faker->sentence(),
        'o_product_price' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 2),
        'o_quantity' => $faker->randomDigitNotNull(),
        'o_amount' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 2)
    ];
});

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'i_no' => $faker->randomNumber($strict = true),
        'i_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'i_mature' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'i_order_no' => $faker->randomNumber($strict = true),
        'i_seller_name' => $faker->firstName(),
        'i_buyer_name' => $faker->firstName(),
        'i_product_name' => $faker->word(),
        'i_product_part_no' => $faker->randomNumber(),
        'i_product_spec' => $faker->sentence(),
        'i_product_price' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 2),
        'i_quantity' => $faker->randomDigitNotNull(),
        'i_amount' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 2),
        'author' => $faker->firstName(),
        'file_name' => $faker->word()
    ];
});
