<?php

namespace Database\Seeders;

use App\Models\Classified\Classified;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 1000; $i++)
        {
            DB::transaction(function () use ($i){
                $id = DB::table('customer')->insertGetId([
                    'user_id' => rand(1, 10),
                    'fullname' => 'Nguyễn văn '. $i . ' XXX',
                    'created_at' => rand(1556693104, 1652942731),
                    'created_date' => rand(1556693104, 1652942731),
                    'phone_number' => rand(1234567890, 1234567899),
                    'email' => Str::random(6) . "@gmail.com",
                    'job' => rand(8,19),
                    'cus_source' => rand(1,7),
                    'classified_id' => Classified::inRandomOrder()->first()->id,
                ]);

                $province_id = Province::inRandomOrder()->first()->id;
                $district_id = District::where('province_id', $province_id)->get()->random()->id;
                $ward_id = Ward::where('district_id', $district_id)->get()->random()->id;

                DB::table('customer_location')->insert([
                    'cus_id' => $id,
                    'address' => 'Địa chỉ '. $i,
                    'province_id' => $province_id,
                    'district_id' => $district_id,
                    'ward_id' => $ward_id,
                ]);
            }, 5);
        }
    }
}
