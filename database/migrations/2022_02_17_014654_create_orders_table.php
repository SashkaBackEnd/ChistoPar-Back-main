<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('bath_id');
            $table->integer('user_id');
            $table->text('bath_formats_ids');
            $table->text('comment')->nullable();
            $table->float('duration');
            $table->string('date_from');
            $table->string('date_to');
            $table->integer('status')->default(0);
            $table->integer('price');
            $table->integer('pay_type');
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
