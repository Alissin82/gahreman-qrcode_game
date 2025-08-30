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
        Schema::create('team_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('name');
            $table->string('family');
            $table->boolean('gender')->default(true)->nullable();
            $table->timestamp('start')->default(now())->nullable();
            $table->string('national_code')->default('')->nullable();
            $table->string('phone')->default('')->nullable();
            $table->string('history')->default('')->nullable();
            $table->string('description')->default('')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_admins');
    }
};
