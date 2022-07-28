<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RatingColumsToSpecialistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->float('rating')->default(0);
            $table->integer('price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('price');
        });
    }
}
