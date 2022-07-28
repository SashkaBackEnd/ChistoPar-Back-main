<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailColumnsTobath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('avatar')->nullable();
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
            $table->dropColumn('email');
            $table->dropColumn('manager_name');
            $table->dropColumn('avatar');
        });
    }
}
