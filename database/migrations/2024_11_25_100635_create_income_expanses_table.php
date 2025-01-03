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
        Schema::create('income_expanses', function (Blueprint $table) {
            $table->id();
            $table->decimal('value');
            $table->enum('currency',['UZS','USD']);
            $table->foreignId('type_id')->constrained('types');
            $table->string('comment');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_expanses');
    }
};
