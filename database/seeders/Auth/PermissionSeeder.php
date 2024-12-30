<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Manage General Settings']);

        // Passive
        Permission::create(['name' => 'View Social Casinos']);
        Permission::create(['name' => 'Create/Edit Social Casinos']);
        Permission::create(['name' => 'Delete/Restore Social Casinos']);

        // Blog
        Permission::create(['name' => 'Manage Posts']);
        Permission::create(['name' => 'Manage Pages']);
        Permission::create(['name' => 'Manage FAQs']);
        Permission::create(['name' => 'Manage Libraries']);
        Permission::create(['name' => 'Manage Tags']);
        Permission::create(['name' => 'Manage Navigations']);
    }
}
