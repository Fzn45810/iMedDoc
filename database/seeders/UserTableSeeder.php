<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();
        DB::table('role_user');

        $doctor_role = Role::where('name', 'doctor')->first();

        $doctor =  User::create([
            'fname' => 'Test Doctor',
            'email' => 'doctor@gmail.com',
            'password' => Hash::make('doctor'),
        ]);

        $doctor->roles()->attach($doctor_role);
    }
}
