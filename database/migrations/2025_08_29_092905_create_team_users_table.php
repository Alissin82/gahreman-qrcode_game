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
        Schema::create('team_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('name');
            $table->string('family');
            $table->string('national_code')->default('')->nullable();
            $table->boolean('gender')->default(true)->nullable();
            $table->integer('glevel')->default(0)->nullable();
            $table->string('school')->default('')->nullable();
            $table->string('reagon')->default('')->nullable();
            $table->string('city')->default('')->nullable();
            $table->string('province')->default('')->nullable();
            $table->string('student_code')->default('')->nullable();
            $table->string('basij_code')->default('')->nullable();
            $table->integer('average')->default(20)->nullable();
            $table->string('phone')->default('')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_users');
    }
};
