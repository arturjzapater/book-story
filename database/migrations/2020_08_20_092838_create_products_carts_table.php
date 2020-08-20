<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_carts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('cart_id')
                ->constrained('carts')
                ->onDelete('cascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_carts');
    }
}
