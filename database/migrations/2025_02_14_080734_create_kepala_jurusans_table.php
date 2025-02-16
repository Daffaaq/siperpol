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
        Schema::create('kepala_jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kepala_jurusan');
            $table->string('email_kepala_jurusan')->unique();
            $table->string('password_kepala_jurusan');
            $table->unsignedBigInteger('jurusans_id');
            $table->foreign('jurusans_id')->references('id')->on('jurusans')->onDelete('cascade');
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
