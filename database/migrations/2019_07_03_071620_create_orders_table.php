<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('o_no', 30);
            $table->date('o_date');
            $table->string('o_seller_name', 50);
            $table->string('o_buyer_name', 50);
            $table->string('o_product_name', 50);
            $table->string('o_product_part_no', 30);
            $table->string('o_product_spec', 100);
            $table->float('o_product_price', 12, 4);
            $table->string('o_currency', 10)->nullable()->default('USD');
            $table->integer('o_quantity');
            $table->float('o_amount', 15, 4);
            $table->text('o_note')->nullable();
            $table->string('author', 50)->nullable();
            $table->string('file_name', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
