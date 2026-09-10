<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        $admin->syncRoles(['admin']);

        // Author user
        $author = User::updateOrCreate(
            ['email' => 'author@example.com'],
            [
                'name' => 'Author User',
                'password' => bcrypt('password'),
            ]
        );

        $author->syncRoles(['author']);
    }
}
