<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInBath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->dropColumn('price_phone');
            $table->dropColumn('price_online');
            $table->dropColumn('price_time');
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
            //
        });
    }
}
