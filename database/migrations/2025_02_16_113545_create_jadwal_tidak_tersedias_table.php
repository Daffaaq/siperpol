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
        Schema::create('jadwal_tidak_tersedias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangs_id');
            $table->foreign('ruangs_id')->references('id')->on('ruangs')->onDelete('cascade');
            $table->date('tanggal_mulai'); // Tanggal mulai periode tidak tersedia
            $table->date('tanggal_selesai'); // Tanggal selesai periode tidak tersedia
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tidak_tersedias');
    }
};
