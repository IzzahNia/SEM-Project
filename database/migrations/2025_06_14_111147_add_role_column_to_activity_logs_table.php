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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add the 'role' column.
            // Using string for text roles (e.g., 'admin', 'user').
            // ->nullable() means it's optional and can be empty.
            // ->after('causer_type') is a common placement, but optional.
            // If you want a default value for existing records, use ->default('default_role_name')
            $table->string('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop the 'role' column if this migration is rolled back
            $table->dropColumn('role');
        });
    }
};
