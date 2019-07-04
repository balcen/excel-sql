<?php

use Illuminate\Database\Seeder;
use App\Client;
use App\Product;
use App\Order;
use App\Invoice;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::truncate();
        Product::truncate();
        Order::truncate();
        Invoice::truncate();

        // Client::unguard;
        // Product::unguard;
        // Order::unguard;
        // Invoice::unguard;

        factory (Client::class, 20)->create();
        factory (Product::class, 20)->create();
        factory (Order::class, 20)->create();
        factory (Invoice::class, 20)->create();

        // Client::reguard();
        // Product::reguard();
        // Order::reguard();
        // Invoice::reguard();
    }
}
