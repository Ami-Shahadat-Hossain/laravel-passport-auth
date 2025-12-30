<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Create admin user
            $admin = User::create([
                'name'              => 'Admin User',
                'email'             => 'admin@passport.test',
                'password'          => Hash::make('password123'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('📧 Email: admin@passport.test');
            $this->command->info('🔑 Password: password123');

            // Create regular users
            for ($i = 1; $i <= 5; $i++) {
                User::create([
                    'name'              => 'User ' . $i,
                    'email'             => 'user' . $i . '@passport.test',
                    'password'          => Hash::make('password123'),
                    'role'              => 'user',
                    'email_verified_at' => now(),
                ]);
            }

            $this->command->info('✅ 5 regular users created successfully!');

        } catch (\Exception $e) {
            $this->command->error('❌ Error creating users: ' . $e->getMessage());
        }
    }
}
