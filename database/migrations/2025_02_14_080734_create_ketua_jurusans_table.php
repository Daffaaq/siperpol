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
        Schema::create('ketua_jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ketua_jurusan');
            $table->string('nama_pendek_ketua_jurusan');
            $table->string('nip_ketua_jurusan')->unique();
            $table->string('email_ketua_jurusan')->unique();
            $table->string('password_ketua_jurusan');
            $table->unsignedBigInteger('jurusans_id');
            $table->foreign('jurusans_id')->references('id')->on('jurusans')->onDelete('cascade');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_jurusans');
    }
};
