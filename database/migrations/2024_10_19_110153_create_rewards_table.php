<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('reward_name'); // Reward name
            $table->text('reward_description'); // Reward description
            $table->dateTime('reward_duration_date'); // Reward description
            $table->string('reward_status');
            $table->integer('reward_quantity');
            $table->integer('reward_point_required');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
