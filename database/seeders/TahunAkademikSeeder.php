<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAkademik;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tahun' => '2023/2024',
                'semester' => 'Ganjil',
                'status_aktif' => true,
            ],
            [
                'tahun' => '2023/2024',
                'semester' => 'Genap',
                'status_aktif' => false,
            ],
            [
                'tahun' => '2024/2025',
                'semester' => 'Ganjil',
                'status_aktif' => false,
            ],
            [
                'tahun' => '2024/2025',
                'semester' => 'Genap',
                'status_aktif' => false,
            ],
        ];

        foreach ($data as $item) {
            TahunAkademik::create($item);
        }
    }
}