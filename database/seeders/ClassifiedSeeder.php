<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassifiedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group_list = [3,4,5,6,7,8,9,11,12,13,14,15,16,17];
        for ($i = 1; $i <= 20000; $i++) {
           DB::transaction(function () use ($i, $group_list){

               $classified_id = DB::table('classified')->insertGetId([
                   'group_id' => $group_list[rand(0, 12)],
                    'user_id' => rand(0,10),
                    'classified_name' => "$i BÁN LÔ ĐẤT HẺM XE HƠI ĐƯỜNG 49, HIỆP BÌNH CHÁNH, THÀNH PHỐ THỦ ĐỨC",
                    'classified_description' => "$i ĐẤT HIỆP BÌNH CHÁNH - Chủ Gửi",
                    'classified_url' => Str::uuid(),
               ]);

               DB::table('classified_location')->insert([
                   'classified_id' => $classified_id,
                   'address' => "test",
                   'province_id' => Province::orderBy(DB::raw('RAND()'))->first('id')->id,
                   'district_id' => District::orderBy(DB::raw('RAND()'))->first('id')->id,
                   'ward_id' => Ward::orderBy(DB::raw('RAND()'))->first('id')->id,
               ]);

           }, 5);
        }
    }
}
