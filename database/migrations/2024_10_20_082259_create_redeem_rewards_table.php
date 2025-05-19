<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('redeem_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('redeem_code');
            $table->dateTime('code_expired_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('reward_id')->constrained()->onDelete('cascade'); // Foreign key to rewards table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('redeem_rewards');
    }
}
