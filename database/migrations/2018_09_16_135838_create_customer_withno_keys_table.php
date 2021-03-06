<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateCustomerWithnoKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_withno_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->text('product_name')->nullable();
            $table->string('license_key', 191)->nullable()->default('');
            $table->text('customer_email')->nullable();
            $table->text('reason')->nullable();
            $table->nullableTimestamps();

            

            

        });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_withno_keys');
    }
}
