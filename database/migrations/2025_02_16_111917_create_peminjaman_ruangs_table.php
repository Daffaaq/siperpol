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
        Schema::create('peminjaman_ruangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangs_id');
            $table->unsignedBigInteger('mahasiswas_id');
            $table->foreign('ruangs_id')->references('id')->on('ruangs')->onDelete('cascade');
            $table->foreign('mahasiswas_id')->references('id')->on('mahasiswas')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->time('jam_pinjam');
            $table->time('jam_selesai');
            $table->string('keterangan');
            $table->unsignedBigInteger('organisasi_intras_id')->nullable();
            $table->foreign('organisasi_intras_id')->references('id')->on('organisasi_intras')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_ruangs');
    }
};
