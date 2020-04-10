<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAuthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function(Blueprint $table) {
            $table->renameColumn('author', 'user_id');
        });
        Schema::table('products', function(Blueprint $table) {
            $table->renameColumn('author', 'user_id');
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->renameColumn('author', 'user_id');
        });
        Schema::table('invoices', function(Blueprint $table) {
            $table->renameColumn('author', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
