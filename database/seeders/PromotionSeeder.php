<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promotion')->insert([
            [   
                'id'=>1,
                'promotion_type'=>0 ,
                'promotion_code'=>"" ,
                'value'=>0,
                'promotion_unit' => 0,
                'num_use'=>0,
                'used'=> 0,
                'user_get'=>null,
                'is_all'=>0,
                'is_private'=>0,
                'user_id_use' => null,
                'date_from'=>0,
                'date_to'=> 0,
                'is_show'=>1,
                'is_deleted'=>0,
                'created_at'=> time(),
                'created_by'=>2,
                'updated_at'=>null,
                'updated_by'=>null,
            ],                      
        ]);
    }
}
