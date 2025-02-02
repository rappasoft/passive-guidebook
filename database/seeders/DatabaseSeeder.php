<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Database\Seeders\Auth\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear public storage
        $directory = storage_path('app/public');

        if (File::exists($directory)) {
            $files = File::files($directory);
            $directories = File::directories($directory);

            foreach ($files as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file->getRealPath());
                }
            }

            foreach ($directories as $dir) {
                File::deleteDirectory($dir);
            }
        }

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(PassiveSourceSeeder::class);
        $this->call(SocialCasinoSeeder::class);
        $this->call(FreebieCategorySeeder::class);
    }
}
