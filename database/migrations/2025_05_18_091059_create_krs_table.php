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
        Schema::create('krs', function (Blueprint $table) {
            $table->bigIncrements('id_krs');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->unsignedBigInteger('tahun_akademik_id');
            $table->integer('semester_mahasiswa');
            $table->timestamps();

            $table->foreign('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');

            $table->foreign('mata_kuliah_id')
                ->references('id_mata_kuliah')
                ->on('mata_kuliahs')
                ->onDelete('cascade');

            $table->foreign('tahun_akademik_id')
                ->references('id_tahun_akademik')
                ->on('tahun_akademiks')
                ->onDelete('cascade');

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik_id'], 'krs_unique_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
