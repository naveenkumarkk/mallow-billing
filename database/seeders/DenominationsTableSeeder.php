<?php

namespace Database\Seeders;

use App\Models\Denomination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DenominationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Denomination::create([
            'name' => '500',
            'value' => 500,
        ]);

        Denomination::create([
            'name' => '200',
            'value' => 200,
        ]);
    }
}
