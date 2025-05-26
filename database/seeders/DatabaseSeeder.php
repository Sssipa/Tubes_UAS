<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);

        $this->call([
        TahunAkademikSeeder::class,
        MahasiswaSeeder::class,
        DosenSeeder::class,
        MataKuliahSeeder::class,
        RuanganSeeder::class,
        JadwalKuliahSeeder::class
        ]);
    }
}
