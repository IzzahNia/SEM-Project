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
        Schema::create('recycle_activities', function (Blueprint $table) {
            $table->id();
            $table->string('recycle_category');
            $table->datetime('recycle_datetime');
            $table->decimal('recycle_weight');
            $table->string('recycle_status');
            $table->string('recycle_comment');
            $table->decimal('recycle_rate');
            $table->integer('reward_point_earned');
            $table->decimal('recycle_price');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::dropIfExists('recycle_activities');
    }
};
