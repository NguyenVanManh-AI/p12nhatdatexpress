<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert(
            [
              ['admin_username'=>'superadmin','admin_fullname'=>'Quản trị cao cấp','password'=>Hash::make('superadmin', ),'admin_email'=>'supperadmin@gmail.com','admin_type'=>1, 'rol_id'=>null],
              ['admin_username'=>'admin','admin_fullname'=>'fullquyen','password'=>Hash::make('password'),'admin_email'=>'admin@gmail.com','admin_type'=>2, 'rol_id'=>18]
            ]
        );
    }
}
