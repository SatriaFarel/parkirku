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
        Schema::create('parkir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petugas')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_member')->nullable()->constrained('members')->cascadeOnDelete();
            $table->string('kode_tiket')->unique()->nullable();
            $table->string('plat_nomor')->nullable();
            $table->dateTime('waktu_masuk')->useCurrent();
            $table->dateTime('waktu_keluar')->nullable();
            $table->integer('tarif')->nullable();
            $table->integer('bayar')->nullable();
            $table->integer('kembalian')->nullable();
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkir');
    }
};
