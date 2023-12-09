<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Alice',
                'email' => 'alice@mail.com',
                'password' => '123456'
            ],
            [
                "name"=> "Bob",
                'email' => 'bob@mail.com',
                'password' => '123456'
            ],
        ];
        foreach($users as $user){ 
            \App\Models\User::factory()->create([
                'email' => $user['email'],
                'password' => $user['password'],
            ]);
        }
      
    }
}
