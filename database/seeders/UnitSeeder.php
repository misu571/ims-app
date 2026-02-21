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
        \App\Models\Category::insert([
            ['name' => "Electronics", 'category_id' => null, 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => "Laptop", 'category_id' => 1, 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => "Mobile", 'category_id' => 1, 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => "TV", 'category_id' => 1, 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        \App\Models\Brand::insert([
            ['name' => 'Dell', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HP', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vivo', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samsung', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $units = [
            ['name' => "Piece", 'symbol' => null],
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

        $unitUata = array_map(fn ($row) => array_merge($row, ['entry_by' => 1, 'created_at' => now(), 'updated_at' => now()]), $units);
        DB::table('units')->insert($unitUata);

        \App\Models\PaymentType::insert([
            ['name' => 'Cash', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Card', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MFS', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Others', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        \App\Models\Supplier::insert([
            ['name' => 'Hasan', 'phone' => '01445522100', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Labib', 'phone' => '01445522188', 'entry_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        \App\Models\Product::insert([
            [
                'code' => mt_rand(123456, 987456),
                'name' => 'Laptop',
                'cost' => '50000',
                'reorder' => 5,
                'brand_id' => 2,
                'category_id' => 2,
                'unit_id' => 1,
                'supplier_id' => 1,
                'entry_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => mt_rand(123456, 987456),
                'name' => 'Mobile',
                'cost' => '15000',
                'reorder' => 10,
                'brand_id' => 3,
                'category_id' => 3,
                'unit_id' => 1,
                'supplier_id' => 2,
                'entry_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        \App\Models\Inventory::insert([
            ['product_id' => 1, 'stock' => 0, 'stock_value' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'stock' => 0, 'stock_value' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
