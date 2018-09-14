<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Usedurls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usedurls', function (Blueprint $table) {
            $table->increments('number')->unique();
            $table->string('uid');
            $table->string('id');
            $table->text('eingabe');
            $table->timestamp('created_at');
            $table->foreign('uid')->references('uid')->on('humanverification')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('usedurls');
    }
}
