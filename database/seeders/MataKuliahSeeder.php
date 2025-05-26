<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MataKuliah;
use App\Models\Dosen;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $dosenIds = Dosen::pluck('id_dosen')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            MataKuliah::create([
                'kode' => 'MK' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => 'Mata Kuliah ' . $i,
                'sks' => $faker->numberBetween(2, 4),
                'dosen_id' => $faker->randomElement($dosenIds),
            ]);
        }
    }
}