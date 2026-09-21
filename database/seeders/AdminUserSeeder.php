<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the clinic staff account used to sign in to the admin panel.
     */
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.name'),
                'password' => Hash::make($password),
            ],
        );

        $this->command?->info("Admin login: {$user->email} / {$password}");
    }
}
