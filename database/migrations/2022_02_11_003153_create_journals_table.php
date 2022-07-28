<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id');
            $table->integer('journal_category_id');
            $table->integer('type');
            $table->integer('bath_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('place')->nullable();
            $table->string('date')->nullable();
            $table->string('format')->nullable();
            $table->string('contacts')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('media');
            $table->integer('views');
            $table->text('hashtags');
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
        Schema::dropIfExists('journals');
    }
}
