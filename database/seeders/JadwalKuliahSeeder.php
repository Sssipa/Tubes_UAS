<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\TahunAkademik;

class JadwalKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $mataKuliahIds = MataKuliah::pluck('id_mata_kuliah')->toArray();
        $dosenIds = Dosen::pluck('id_dosen')->toArray();
        $ruanganIds = Ruangan::pluck('id_ruangan')->toArray();
        $tahunAkademikIds = TahunAkademik::pluck('id_tahun_akademik')->toArray();

        if (empty($mataKuliahIds) || empty($dosenIds) || empty($ruanganIds) || empty($tahunAkademikIds)) {
            $this->command->warn('Tidak dapat membuat Jadwal Kuliah karena data Mata Kuliah/Dosen/Ruangan/Tahun Akademik kosong.');
            return;
        }

        $hariKuliah = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamMulaiPagi = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00'];
        $jamMulaiSiang = ['13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00'];

        for ($i = 1; $i <= 50; $i++) { // AWAL BLOK FOR - TAMBAHKAN {
            $mataKuliahId = $faker->randomElement($mataKuliahIds);
            $mataKuliah = MataKuliah::find($mataKuliahId);

            if (!$mataKuliah) { 
                continue;
            }
            $sks = $mataKuliah->sks;
            $durasiMenit = $sks * 50;

            $jamMulaiArray = $faker->boolean(70) ? $jamMulaiPagi : $jamMulaiSiang;
            $jamMulai = $faker->randomElement($jamMulaiArray);

            try {
                $jamSelesai = (new \DateTime($jamMulai))->add(new \DateInterval('PT' . $durasiMenit . 'M'))->format('H:i');
            } catch (\Exception $e) {
                $jamSelesai = (new \DateTime($jamMulai))->add(new \DateInterval('PT100M'))->format('H:i'); // default 100 menit
            }


            JadwalKuliah::create([
                'mata_kuliah_id'    => $mataKuliahId,
                'ruangan_id'        => $faker->randomElement($ruanganIds),
                'dosen_id'          => $faker->randomElement($dosenIds),
                'hari'              => $faker->randomElement($hariKuliah),
                'jam_mulai'         => $jamMulai,
                'jam_selesai'       => $jamSelesai,
                'tahun_akademik_id' => $faker->randomElement($tahunAkademikIds),
            ]);
        }
    }
}