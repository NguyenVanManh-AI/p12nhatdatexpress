<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class ListAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([

        
        [ 
        'admin_username'=>'user1' ,
        'admin_fullname'=>'Nguyễn Thị An1 ' ,
        'admin_password'=>Hash::make('nguyenthian') ,
        'admin_email' => 'thian@gmail.com',
        'admin_birthday'=>strtotime('07/02/2002'),
        'admin_type'=>'2',
        'is_deleted'=> ' 0'
        ],
        [
        'admin_username'=>'user2' ,
        'admin_fullname'=>'Nguyễn Thị An2 ' ,
        'admin_password'=>Hash::make('vananh72') ,
        'admin_email' => 'vananh.it72@gmail.com',
        'admin_birthday'=>strtotime('07/02/2003'),
        'admin_type'=>'3',
        'is_deleted' => '1'
        ],
        [
            'admin_username'=>'user3' ,
            'admin_fullname'=>'Nguyễn Thị An3 ' ,
            'admin_password'=>Hash::make('vananh720') ,
            'admin_email' => 'vana.it72@gmail.com',
            'admin_birthday'=>strtotime('07/02/2003'),
            'admin_type'=>'3',
            'is_deleted' => '1'
            ],
            [
                'admin_username'=>'user04' ,
                'admin_fullname'=>'Nguyễn Thị An4 ' ,
                'admin_password'=>Hash::make('user04') ,
                'admin_email' => 'user04.it72@gmail.com',
                'admin_birthday'=>strtotime('07/02/2007'),
                'admin_type'=>'2',
                'is_deleted' => '1'
                ],
                [
                    'admin_username'=>'user05' ,
                    'admin_fullname'=>'Nguyễn Thị An5 ' ,
                    'admin_password'=>Hash::make('vananh72') ,
                    'admin_email' => 'user05.it72@gmail.com',
                    'admin_birthday'=>strtotime('07/02/2006'),
                    'admin_type'=>'3',
                    'is_deleted' => '0'
                    ],
                    [
                        'admin_username'=>'user06' ,
                        'admin_fullname'=>'Nguyễn Thị An6 ' ,
                        'admin_password'=>Hash::make('vananh72') ,
                        'admin_email' => 'user06.it72@gmail.com',
                        'admin_birthday'=>strtotime('07/03/2006'),
                        'admin_type'=>'1',
                        'is_deleted' => '0'
                        ],
                        

        ]);
    }
}
