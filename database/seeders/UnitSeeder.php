<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => "Centimeter", 'symbol' => "cm"],
            ['name' => "Meter", 'symbol' => "m"],
            ['name' => "Kilometer", 'symbol' => "km"],
            ['name' => "Millimeter", 'symbol' => "mm"],
            ['name' => "Foot", 'symbol' => "feet"],
            ['name' => "Inches", 'symbol' => "inch"],
            ['name' => "Mile", 'symbol' => "mile"],
            ['name' => "Milligram", 'symbol' => "mg"],
            ['name' => "Gram", 'symbol' => "g"],
            ['name' => "Kilogram", 'symbol' => "kg"],
            ['name' => "Ounce", 'symbol' => "oz"],
            ['name' => "Pound", 'symbol' => "lb"],
            ['name' => "Ton", 'symbol' => "ton"],
            ['name' => "Millilitre", 'symbol' => "ml"],
            ['name' => "Litre", 'symbol' => "l"],
            ['name' => "Kilolitre", 'symbol' => "kl"],
            ['name' => "Fluid ounce", 'symbol' => "fl. oz."],
            ['name' => "Gallon", 'symbol' => "gal"],
            ['name' => "Pint", 'symbol' => "pt"],
            ['name' => "Percentage", 'symbol' => "%"],
        ];

        $data = array_map(fn ($row) => array_merge($row, ['entry_by' => 1, 'created_at' => now(), 'updated_at' => now()]), $array);
        DB::table('units')->insert($data);

        \App\Models\PaymentType::insert([
            ['name' => 'Cash', 'entry_by' => 1, 'created_at' => now(),'updated_at' => now()],
            ['name' => 'Card', 'entry_by' => 1, 'created_at' => now(),'updated_at' => now()],
            ['name' => 'MFS', 'entry_by' => 1, 'created_at' => now(),'updated_at' => now()],
            ['name' => 'Others', 'entry_by' => 1, 'created_at' => now(),'updated_at' => now()],
        ]);
    }
}
