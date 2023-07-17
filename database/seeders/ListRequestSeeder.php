<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_request')->insert([
          [
            'project_name'=>'Shantira Beach resort & spa',
            'user_id'=>'2',      
            'address'=>'92 Trần Nguyên Hãn',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'0',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],[
            'project_name'=>' Beach resort & spa',
            'user_id'=>'1',      
            'address'=>'92 Trần Nguyên ',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],[
            'project_name'=>'Shantira resort & spa',
            'user_id'=>'2',      
            'address'=>'9 Trần Nguyên Hãn',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],
          [
            'project_name'=>'Shantira Đà Nẵng resort & spa',
            'user_id'=>'1',      
            'address'=>'2 Trần Nguyên Hãn',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'1'
          

          
          ],
          [
            'project_name'=>'Shantira Bình Định resort & spa',
            'user_id'=>'1',      
            'address'=>'92 Trần  Hãn',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'1'
          

          
          ],
          [
            'project_name'=>'Shantira ĐN Beach resort & spa',
            'user_id'=>'1',      
            'address'=>'Trần Nguyên Hãn',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],
          [
            'project_name'=>'BĐ Beach resort & spa',
            'user_id'=>'1',      
            'address'=>'Tố Hũu',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],
          [
            'project_name'=>'Sơn Trà resort & spa',
            'user_id'=>'1',      
            'address'=>'33 Xô Viết Nghệ Tĩnh',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],
          [
            'project_name'=>'Hải Châu Beach resort & spa',
            'user_id'=>'1',      
            'address'=>'34 Xô Viết Nghệ Tĩnh',
            'district_id'=>'1',
            'province_id'=>'2',
            'confirmed_status'=>'1',
            'confirmed_by'=>'29',
            'updated_at'=>null,
            'created_at'=>time(),
            'is_deleted'=>'0'
          

          
          ],


         ]);
    }
}
