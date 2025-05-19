<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToRewardsTable extends Migration
{
    public function up()
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->string('reward_image')->nullable(); // Add the image column
        });
    }

    public function down()
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn('reward_image'); // Remove the image column if rolling back
        });
    }
}