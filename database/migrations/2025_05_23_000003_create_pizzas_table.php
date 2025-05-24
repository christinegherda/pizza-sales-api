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
        Schema::create('pizzas', function (Blueprint $table) {
            $table->string('pizza_id')->primary(); // primary key (string)
            $table->string('pizza_type_id');
            $table->string('size');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('pizza_type_id')->references('pizza_type_id')->on('pizza_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pizzas');
    }
};
