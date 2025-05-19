<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// In the migration file
public function up()
{
    Schema::table('order_items', function (Blueprint $table) {
        // Only add the foreign key if it doesn't exist
        if (!Schema::hasColumn('order_items', 'order_id')) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
        }
    });
}


public function down()
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropForeign(['order_id']);
    });
}

};
