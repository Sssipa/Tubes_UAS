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
        Schema::create('jadwal_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->unsignedBigInteger('ruangan_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('tahun_akademik_id');
            $table->timestamps();

            $table->foreign('mata_kuliah_id')
                  ->references('id')
                  ->on('mata_kuliahs')
                  ->onDelete('cascade');

            $table->foreign('ruangan_id')
                  ->references('id')
                  ->on('ruangans')
                  ->onDelete('cascade');

            $table->foreign('dosen_id')
                  ->references('id')
                  ->on('dosens')
                  ->onDelete('cascade');

            $table->foreign('tahun_akademik_id')
                  ->references('id')
                  ->on('tahun_akademiks')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliahs');
    }
};
