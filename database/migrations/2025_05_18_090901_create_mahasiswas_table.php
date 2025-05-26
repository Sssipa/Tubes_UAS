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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->bigIncrements('id_mahasiswa');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('email')->unique();
            $table->string('telepon')->nullable();
            $table->unsignedBigInteger('tahun_akademik_id');
            $table->timestamps();

            $table->foreign('tahun_akademik_id')
                ->references('id_tahun_akademik')
                ->on('tahun_akademiks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
