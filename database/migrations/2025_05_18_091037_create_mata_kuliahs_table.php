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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->bigIncrements('id_mata_kuliah');
            $table->string('kode');
            $table->string('nama');
            $table->integer('sks');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->timestamps();

            $table->foreign('dosen_id')
                ->references('id_dosen')
                ->on('dosens')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
