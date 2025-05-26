<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Pastikan sudah ada data tahun akademik!
        $tahunAkademikIds = \App\Models\TahunAkademik::pluck('id_tahun_akademik')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            Mahasiswa::create([
                'nim' => '22' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'email' => $faker->unique()->safeEmail,
                'telepon' => $faker->phoneNumber,
                'tahun_akademik_id' => $faker->randomElement($tahunAkademikIds),
            ]);
        }
    }
}