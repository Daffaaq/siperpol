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
        Schema::create('organisasi_intras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_organisasi_intra');
            $table->string('kode_organisasi_intra');
            $table->string('nama_ketua_umum')->nullable();
            $table->string('email_ketua_umum')->nullable();
            $table->string('password_ketua_umum')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('tipe_organisasi_intra', ['jurusan', 'non-jurusan', 'lembaga-tinggi']);
            $table->unsignedBigInteger('jurusans_id')->nullable();
            $table->foreign('jurusans_id')->references('id')->on('jurusans')->onDelete('cascade');
            $table->unsignedBigInteger('users_id')->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasi_intras');
    }
};
