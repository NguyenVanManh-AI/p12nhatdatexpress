<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Page_Permission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
           [
               "permission_type" => 0,
               "permission_name" => "Thêm",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Cập nhật",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Nhân bản",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Danh sách",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Thùng rác",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Khôi phục",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Xóa",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ],[
               "permission_type" => 1,
               "permission_name" => "Quản lý thùng rác",
               "is_file" => 0,
               "created_at" => strtotime('now'),
           ]
        ];
        $permission_file = [
            [
                "permission_type" => 0,
                "permission_name" => "Danh sách",
                "is_file" => 1,
                "created_at" => strtotime('now'),
            ],[
                "permission_type" => 0,
                "permission_name" => "Quản lý thùng rác",
                "is_file" => 1,
                "created_at" => strtotime('now'),
            ],[
                "permission_type" => 0,
                "permission_name" => "Xóa",
                "is_file" => 1,
                "created_at" => strtotime('now'),
            ],
        ];

        foreach ($permission as $p)
            DB::table('page_permission')->insert($p);

        foreach ($permission_file as $pf)
            DB::table('page_permission')->insert($pf);
    }
}
