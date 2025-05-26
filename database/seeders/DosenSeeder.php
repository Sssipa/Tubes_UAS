<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            Dosen::create([
                'nidn' => '1001' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'telepon' => $faker->phoneNumber,
            ]);
        }
    }
}
