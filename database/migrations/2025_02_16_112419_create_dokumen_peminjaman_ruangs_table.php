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
        Schema::create('dokumen_peminjaman_ruangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipe_dokumen_peminjaman_id');
            $table->foreign('tipe_dokumen_peminjaman_id')->references('id')->on('tipe_dokumen_peminjaman')->onDelete('cascade');
            $table->unsignedBigInteger('peminjaman_ruangs_id');
            $table->foreign('peminjaman_ruangs_id')->references('id')->on('peminjaman_ruangs')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->string('dokumen');
            $table->enum('status1', ['Pending', 'Approved', 'Rejected'])->default('Pending')->nullable(); // Kepala Jurusan
            $table->enum('status2', ['Pending', 'Approved', 'Rejected'])->default('Pending')->nullable(); // admin Jurusan
            $table->enum('status3', ['Pending', 'Approved', 'Rejected'])->default('Pending')->nullable(); // Badan Eksekutif Mahasiswa
            $table->enum('status4', ['Pending', 'Approved', 'Rejected'])->default('Pending')->nullable(); // Himpunan Mahasiswa Jurusan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_peminjaman_ruangs');
    }
};
