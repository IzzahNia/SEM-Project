<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecycleImageToRecycleActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recycle_activities', function (Blueprint $table) {
            $table->string('recycle_image')->nullable()->after('recycle_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recycle_activities', function (Blueprint $table) {
            $table->dropColumn('recycle_image');
        });
    }
}
