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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dosen');
            $table->string('nidn_dosen')->unique()->nullable();
            $table->string('nip_dosen')->unique()->nullable();
            $table->string('email_dosen')->unique();
            $table->string('password_dosen');
            $table->text('alamat_dosen');
            $table->string('no_telepon_dosen')->nullable();
            $table->enum('jenis_kelamin_dosen', ['L', 'P']);
            $table->date('tanggal_lahir_dosen');
            $table->string('pendidikan_terakhir_dosen')->nullable();
            $table->enum('status_kepegawaian_dosen', ['PNS', 'Honorer', 'Lainnya'])->nullable();
            $table->text('status_kepegawaian_lainnya')->nullable();
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
        Schema::dropIfExists('dosens');
    }
};
