<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('p_type', 30);
            $table->string('p_image', 30)->nullable();
            $table->string('p_name', 50);
            $table->string('p_part_no', 30);
            $table->string('p_spec', 100);
            $table->float('p_price', 12, 4)->nullable();
            $table->string('p_currency', 10)->nullable()->default('USD');
            $table->string('p_size', 50)->nullable();
            $table->string('p_weight', 15)->nullable();
            $table->text('p_note')->nullable();
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
        Schema::dropIfExists('products');
    }
}
