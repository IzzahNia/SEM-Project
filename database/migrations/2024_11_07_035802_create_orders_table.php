<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            // $table->unsignedBigInteger('product_id'); // Foreign key for products
            // $table->integer('order_quantity'); // Quantity of the product ordered
            $table->decimal('order_total_price', 10, 2); // Total price for the order
            $table->string('order_status'); // Order status (e.g., pending, completed)
            $table->datetime('order_datetime'); // Order datetime
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
