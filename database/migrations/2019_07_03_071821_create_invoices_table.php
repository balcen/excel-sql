<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('i_no', 30);
            $table->date('i_date');
            $table->date('i_mature');
            $table->string('i_order_no', 30);
            $table->string('i_seller_name', 50);
            $table->string('i_buyer_name', 50);
            $table->string('i_product_name', 50);
            $table->string('i_product_part_no', 30);
            $table->string('i_product_spec', 100);
            $table->float('i_product_price', 12, 4);
            $table->string('i_currency', 10)->nullable()->default('USD');
            $table->integer('i_quantity');
            $table->float('i_amount', 15, 4);
            $table->text('i_note')->nullable();
            $table->string('author', 50);
            $table->string('file_name', 50);
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
        Schema::dropIfExists('invoices');
    }
}
