<?php
namespace Database\Seeders;

use Laravel\Passport\Client;
use Illuminate\Database\Seeder;
use Laravel\Passport\PersonalAccessClient;
use Illuminate\Support\Str;


class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Create password grant client
            $passwordClient = Client::create([
                'user_id'                => null,
                'name'                   => 'Password Grant Client',
                'secret'                 => env('PASSWORD_CLIENT_SECRET', Str::random(40)),
                'provider'               => 'users',
                'redirect'               => 'http://localhost',
                'personal_access_client' => false,
                'password_client'        => true,
                'revoked'                => false,
            ]);

            // Create personal access client
            $personalClient = Client::create([
                'user_id'                => null,
                'name'                   => 'Personal Access Client',
                'secret'                 => env('PERSONAL_ACCESS_CLIENT_SECRET', Str::random(40)),
                'provider'               => 'users',
                'redirect'               => 'http://localhost',
                'personal_access_client' => true,
                'password_client'        => false,
                'revoked'                => false,
            ]);

            // Create personal access client record
            PersonalAccessClient::create([
                'client_id' => $personalClient->id,
            ]);

            // Create authorization code grant client (for PKCE)
            $authCodeClient = Client::create([
                'user_id'                => null,
                'name'                   => 'Auth Code PKCE Client',
                'secret'                 => null, // PKCE doesn't use client secret
                'provider'               => 'users',
                'redirect'               => env('APP_URL') . '/oauth/callback',
                'personal_access_client' => false,
                'password_client'        => false,
                'revoked'                => false,
            ]);

            $this->command->info('✅ OAuth clients created successfully!');
            $this->command->table(
                ['ID', 'Name', 'Type', 'Client ID', 'Redirect URI'],
                [
                    [$passwordClient->id, 'Password Grant Client', 'Password', $passwordClient->id, $passwordClient->redirect],
                    [$personalClient->id, 'Personal Access Client', 'Personal Access', $personalClient->id, $personalClient->redirect],
                    [$authCodeClient->id, 'Auth Code PKCE Client', 'Auth Code (PKCE)', $authCodeClient->id, $authCodeClient->redirect],
                ]
            );

        } catch (\Exception $e) {
            $this->command->error('❌ Error creating OAuth clients: ' . $e->getMessage());
        }
    }
}
