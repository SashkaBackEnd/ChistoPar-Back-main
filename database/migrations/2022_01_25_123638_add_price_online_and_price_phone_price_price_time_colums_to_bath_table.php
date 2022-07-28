<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceOnlineAndPricePhonePricePriceTimeColumsToBathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->integer('price_phone')->default(0);
            $table->integer('price_time')->default(0);
            $table->integer('price_online')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->dropColumn('price_phone');
            $table->dropColumn('price_time');
            $table->dropColumn('price_online');
        });
    }
}
