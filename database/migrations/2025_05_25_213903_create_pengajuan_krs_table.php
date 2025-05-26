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
        Schema::create('pengajuan_krs', function (Blueprint $table) {
            $table->id(); // Primary Key, auto-increment
            
            // Foreign key ke tabel mahasiswas
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswas')->onDelete('cascade');

            // Foreign key ke tabel tahun_akademiks
            $table->unsignedBigInteger('tahun_akademik_id');
            $table->foreign('tahun_akademik_id')->references('id_tahun_akademik')->on('tahun_akademiks')->onDelete('cascade');

            $table->integer('total_sks_diambil')->default(0); // Total SKS yang diajukan pada KRS ini
            
            // Status pengajuan KRS
            $table->enum('status_pengajuan', [
                'diajukan_mahasiswa',    // Mahasiswa telah mengajukan
                'disetujui_akademik',    // Disetujui oleh admin akademik
                'ditolak_akademik'       // Ditolak oleh admin akademik
            ])->default('diajukan_mahasiswa');

            $table->text('catatan_akademik')->nullable(); // Catatan dari admin akademik (misal alasan penolakan)
            
            $table->timestamp('tanggal_pengajuan_mahasiswa')->nullable(); // Kapan mahasiswa mengajukan/update terakhir
            $table->timestamp('tanggal_keputusan_akademik')->nullable(); // Kapan admin mengambil keputusan

            // Foreign key ke tabel users (untuk mencatat admin yang memproses)
            $table->unsignedBigInteger('admin_id_keputusan')->nullable();
            $table->foreign('admin_id_keputusan')->references('id_user')->on('users')->onDelete('set null');
            
            $table->timestamps(); // Kolom created_at dan updated_at

            // Unique constraint untuk memastikan mahasiswa hanya bisa mengajukan satu KRS per tahun akademik
            $table->unique(['mahasiswa_id', 'tahun_akademik_id'], 'mahasiswa_tahun_akademik_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_krs');
    }
};
