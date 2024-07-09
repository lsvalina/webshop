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
        Schema::create('price_list_product', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id');
            $table->string('SKU');
            $table->bigInteger('price');
            $table->primary(['price_list_id', 'SKU']);
            $table->foreign('price_list_id')
                ->references('id')
                ->on('price_lists')
                ->onDelete('cascade');
            $table->foreign('SKU')
                ->references('SKU')
                ->on('products')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_product');
    }
};
