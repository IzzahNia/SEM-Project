<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecycleRateToRecycleActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('recycle_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('recycle_activities', 'recycle_price')) {
                $table->decimal('recycle_price', 8, 2)->after('recycle_rate')->notNull();
            }
        });
    }

    public function down()
    {
        Schema::table('recycle_activities', function (Blueprint $table) {
            if (Schema::hasColumn('recycle_activities', 'recycle_price')) {
                $table->dropColumn('recycle_price');
            }
        });
    }
}
