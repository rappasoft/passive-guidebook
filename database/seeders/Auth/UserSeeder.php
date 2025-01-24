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

        if (! app()->isProduction()) {
            $currentTrial = User::create([
                'name' => 'Active Trial',
                'display_name' => 'Trial',
                'email' => 'trial@trial.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'free' => false,
            ]);

            $currentTrial->trial_ends_at = now()->addWeeks(2);
            $currentTrial->save();

            $expiredTrial = User::create([
                'name' => 'Expired Trial',
                'display_name' => 'Expired',
                'email' => 'expired@expired.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'free' => true, // Create all the accounts and then switch to false lol
            ]);

            $expiredTrial->trial_ends_at = now()->subYear();
            $expiredTrial->save();
        }
    }
}
