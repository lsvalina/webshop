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
        Schema::create('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->string('SKU');
            $table->decimal('vat_percentage');
            $table->integer('price');
            $table->integer('quantity');
            $table->primary(['order_id', 'SKU']);
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('no action');
            $table->foreign('SKU')
                ->references('SKU')
                ->on('products')
                ->onDelete('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
