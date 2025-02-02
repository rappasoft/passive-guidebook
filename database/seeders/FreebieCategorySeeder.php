<?php

namespace Database\Seeders;

use App\Models\FreebieCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreebieCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FreebieCategory::create(['name' => 'Beauty']);
        FreebieCategory::create(['name' => 'Entertainment']);
        FreebieCategory::create(['name' => 'Food']);
        FreebieCategory::create(['name' => 'Photography']);
    }
}
