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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('nama_staff');
            $table->string('nama_panggilan_staff')->nullable();
            $table->string('email_staff')->unique();
            $table->string('password_staff');
            $table->text('alamat_staff');
            $table->string('no_telepon_staff')->nullable();
            $table->enum('jenis_kelamin_staff', ['L', 'P']);
            $table->date('tanggal_lahir_staff');
            $table->string('pendidikan_terakhir_staff')->nullable();
            $table->enum('status_kepegawaian_staff', ['PNS', 'Honorer', 'Lainnya'])->nullable();
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
        Schema::dropIfExists('staff');
    }
};
