<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('redeem_rewards', function (Blueprint $table) {
            $table->string('redeem_code_status'); // Add the new column
        });
    }

    public function down()
    {
        Schema::table('redeem_rewards', function (Blueprint $table) {
            $table->dropColumn('redeem_code_status'); // Drop the column if rolling back
        });
    }
};
