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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mahasiswa');
            $table->string('email_mahasiswa');
            $table->string('password_mahasiswa');
            $table->text('alamat_mahasiswa');
            $table->string('nim_mahasiswa');
            $table->string('no_telepon_mahasiswa')->nullable();
            $table->enum('jenis_kelamin_mahasiswa', ['L', 'P']);
            $table->date('tanggal_lahir_mahasiswa');
            $table->unsignedBigInteger('prodis_id');
            $table->foreign('prodis_id')->references('id')->on('prodis')->onDelete('cascade');
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
        Schema::dropIfExists('mahasiswas');
    }
};
