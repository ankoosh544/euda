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
        Schema::create('door_faults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->required();
            $table->string('floor')->required();
            $table->integer('doorfault_value')->required();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('door_faults');
    }
};
