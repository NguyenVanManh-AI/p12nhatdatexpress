<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10000; $i++)
        {
            DB::transaction(function () use ($i){
                $user_id = DB::table('user')->insertGetId([
                    'user_code' => Str::limit(time() - rand(1 , 5000000), 6, '') . Str::random(4),
                    'username' => 'user_'.time(). "_$i",
                    'user_level_id' => 1,
                    'user_type_id' => rand(1,3),
                    'created_at' => time() - rand(0, (84000) * rand(1,10))
                ]);

                DB::table('user_detail')->insert([
                    'user_id' => $user_id,
                    'fullname' => "Nguyễn Văn " . Str::random(5),
                    'intro' => 'Intro of user ' . $i,

                ]);

                DB::table('user_location')->insert([
                    'user_id' => $user_id,
                    'address' => "test",
                    'province_id' => Province::orderBy(DB::raw('RAND()'))->first('id')->id,
                    'district_id' => District::orderBy(DB::raw('RAND()'))->first('id')->id,
                    'ward_id' => Ward::orderBy(DB::raw('RAND()'))->first('id')->id,
                ]);

            }, 5);
        }
    }
}
