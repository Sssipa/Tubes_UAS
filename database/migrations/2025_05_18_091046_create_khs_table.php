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
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->string('nilai'); // Contoh: A, B, C, dst.
            $table->string('semester');
            $table->timestamps();

            $table->foreign('mahasiswa_id')
                  ->references('id')
                  ->on('mahasiswas')
                  ->onDelete('cascade');

            $table->foreign('mata_kuliah_id')
                  ->references('id')
                  ->on('mata_kuliahs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs');
    }
};
