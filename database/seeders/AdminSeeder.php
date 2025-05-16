<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obj=new Admin();
        $obj->name = 'Admin';
        $obj->email = 'nasimadmin@gmail.com';
        $obj->password = Hash::make('12345678');
        $obj->save();
    }
}
