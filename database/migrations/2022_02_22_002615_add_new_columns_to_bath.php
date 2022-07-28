<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToBath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->integer('badge')->default(0);
            $table->string('site')->nullable();
            $table->string('vk')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fullname')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('manager_email')->nullable();
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
            $table->dropColumn('badge');
            $table->dropColumn('site');
            $table->dropColumn('vk');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
            $table->dropColumn('twitter');
            $table->dropColumn('fio');
            $table->dropColumn('fullname');
            $table->dropColumn('owner_email');
            $table->dropColumn('owner_phone');
            $table->dropColumn('manager_phone');
            $table->dropColumn('manager_email');
        });
    }
}
