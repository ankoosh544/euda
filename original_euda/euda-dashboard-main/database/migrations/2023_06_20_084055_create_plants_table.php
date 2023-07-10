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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->required();
            $table->string('name')->required();
            $table->string('slug')->required();
            $table->string('topic')->required();
            $table->string('datastore')->required();
            $table->string('state')->required();
            $table->string('city')->required();
            $table->string('cap')->required();
            $table->string('address')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
