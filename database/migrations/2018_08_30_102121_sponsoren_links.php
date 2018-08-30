<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SponsorenLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsorenlinks', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('linktext');
            $table->string('link');
            $table->string('langcode');
            $table->timestamp('updated_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sponsorenlinks');

    }
}
