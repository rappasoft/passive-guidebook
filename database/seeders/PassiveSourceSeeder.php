<?php

namespace Database\Seeders;

use App\Models\PassiveSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PassiveSourceSeeder extends Seeder
{

    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PassiveSource::create([
            'name' => 'Social Casinos',
            'slug' => 'social-casinos',
            'passive_percentage' => 75,
        ]);
    }
}
