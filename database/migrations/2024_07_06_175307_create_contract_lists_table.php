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
        Schema::create('contract_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('SKU');
            $table->bigInteger('price');
            $table->timestamps();
            $table->primary(['user_id', 'SKU']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('SKU')->references('SKU')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_lists');
    }
};
