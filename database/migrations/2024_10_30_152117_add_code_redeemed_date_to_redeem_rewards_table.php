<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeRedeemedDateToRedeemRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redeem_rewards', function (Blueprint $table) {
            $table->dateTime('code_redeemed_date')->nullable()->after('code_expired_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redeem_rewards', function (Blueprint $table) {
            $table->dropColumn('code_redeemed_date');
        });
    }
}
