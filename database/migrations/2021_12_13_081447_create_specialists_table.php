<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('bath_id')->nullable();
            $table->integer('bath_serices_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('media')->nullable();
            $table->string('courses')->nullable();
            $table->string('achievements')->nullable();
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
        Schema::dropIfExists('specialists');
    }
}
