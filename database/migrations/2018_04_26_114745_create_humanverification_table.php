<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHumanverificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('humanverification', function (Blueprint $table) {
            $table->string('uid')->unique();
            $table->string('id');
            $table->integer('unusedResultPages');
            $table->boolean('whitelist');
            $table->integer('whitelistCounter');
            $table->boolean('locked');
            $table->string('lockedKey');
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
        Schema::drop('humanverification');
    }
}
