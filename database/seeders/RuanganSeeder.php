<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            Ruangan::create([
                'kode' => 'R' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => 'Ruangan ' . $i,
                'kapasitas' => $faker->numberBetween(20, 60),
            ]);
        }
    }
}