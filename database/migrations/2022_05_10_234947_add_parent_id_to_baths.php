<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToBaths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baths', function (Blueprint $table) {
            $table->string('link')->nullable();
            $table->foreignId('parent_id')->nullable()->references('id')->on('baths')->onDelete('cascade');
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
            $table->dropColumn('link');
            $table->dropConstrainedForeignId('parent_id');
        });
    }
}
