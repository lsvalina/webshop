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
        Schema::create('order_pricing_modifier', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('pricing_modifier_id');
            $table->primary(['order_id', 'pricing_modifier_id']);
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('no action');
            $table->foreign('pricing_modifier_id')
                ->references('id')
                ->on('pricing_modifiers')
                ->onDelete('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pricing_modifier');
    }
};
