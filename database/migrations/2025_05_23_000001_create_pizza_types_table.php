<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {  
        Schema::create('pizza_types', function (Blueprint $table) {
            $table->string('pizza_type_id')->primary();
            $table->string('name');
            $table->string('category');
            $table->text('ingredients');
            $table->timestamps();
        });

        // Add this to set proper charset on the table
        DB::statement('ALTER TABLE pizza_types CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pizza_types');
    }


    
};
