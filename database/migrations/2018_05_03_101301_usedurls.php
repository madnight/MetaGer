<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->unique();
            $table->string('user_id');
            $table->string('url');
            $table->timestamp('created_at');
            $table->foreign('user_id')->references('id')->on('humanverification')->onDelete('cascade');
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
