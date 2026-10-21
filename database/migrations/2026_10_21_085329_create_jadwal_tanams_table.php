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
        Schema::create('jadwal_tanams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->string('lokasi_lahan');
            $table->date('tanggal_tanam');
            $table->string('status')->default('direncanakan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tanams');
    }
};
