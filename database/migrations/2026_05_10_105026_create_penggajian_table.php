<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->string('nomor_rekening');
            $table->string('bank');
            $table->decimal('nominal_gaji', 12, 2);
            $table->enum('status_penggajian', ['Belum Diproses', 'Diproses', 'Sudah Dibayar'])->default('Belum Diproses');
            $table->string('periode');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};