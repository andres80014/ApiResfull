<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.

|
*/
use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use App\Seller;
use App\Buyer;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'remember_token' => Str::random(10),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_NO_VERIFICADO,User::USUARIO_VERIFICADO]),
        'verification_token'=>  $verificado == User::USUARIO_VERIFICADO ? null : User::generarTerificationToken(),  
        'admin'=> $faker->randomElement([User::USUARIO_ADMINISTRADOR,User::USUARIO_REGULAR]),
    ];
});


$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});



$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' =>$faker->numberBetween(1,10),
        'status' => $faker->randomElement([Product::PRODUCTO_DISPONIBLE,Product::PRODUCTO_DISPONIBLE]),
        'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        'seller_id' => User::select('id')->inRandomOrder()->first()
    ];
});



$factory->define(App\Transaction::class, function (Faker $faker) {
    $vendedores = Seller::has('products')->inRandomOrder()->first();
    $comprador  = Buyer::where('id','!=',$vendedores->id)->inRandomOrder()->first();

    return [
        'quantity'   => $faker->numberBetween(1,5),
        'buyer_id'   => $comprador->id,
        'product_id' => $vendedores->id,
    ];
});

