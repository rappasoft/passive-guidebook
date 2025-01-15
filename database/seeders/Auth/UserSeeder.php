<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'display_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'free' => true,
        ]);

        $admin->assignRole(Role::whereName('Super Admin')->firstOrFail());

        $admin->trial_ends_at = null;
        $admin->save();
    }
}
