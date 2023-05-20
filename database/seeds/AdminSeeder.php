<?php
  
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
   
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(User::where('email', 'admin@example.com')->first())) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' =>  bcrypt('password'),
                'remember_token' => Str::random(10)
            ]);
        }
    }
}