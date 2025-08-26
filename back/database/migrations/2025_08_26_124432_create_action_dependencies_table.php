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
        Schema::create('action_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained('actions')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('depends_on_action_id');
            $table->foreign('depends_on_action_id')->references('id')->on('actions')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_dependencies');
    }
};
