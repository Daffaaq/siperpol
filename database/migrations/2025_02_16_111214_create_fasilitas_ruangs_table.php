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
        Schema::create('fasilitas_ruangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangs_id');
            $table->unsignedBigInteger('fasilitas_id');
            $table->foreign('ruangs_id')->references('id')->on('ruangs')->onDelete('cascade');
            $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_ruangs');
    }
};
