<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SYSTEM CONFIG
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Cấu hình hệ thống',
            'page_icon' => 'fas fa-tools',
            'page_url' => '',
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 2,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Cấu hình chung',
                'page_icon' => '',
                'page_url' => '/admin/system-config',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'page_parent_id' => $id_parent,
                'show_order' => 1,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Mẫu mail',
                'page_icon' => '',
                'page_url' => '/admin/system-config/mail-template',
                'is_duplicate' => 1,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Cấu hình khác',
                'page_icon' => '',
                'page_url' => '/admin/system-config/other',
                'is_duplicate' => 0,
                'show_order' => 3,
                'is_page_file' => 0,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
        // MANAGE ADMIN
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Tài khoản quản trị',
            'page_icon' => 'fas fa-lock',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 8,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Quản lý nhóm',
                'page_icon' => '',
                'page_url' => '/admin/manage-admin',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Danh sách tài khoản',
                'page_icon' => '',
                'page_url' => '/admin/manage-admin/accounts',
                'is_duplicate' => 0,
                'show_order' => 2,
                'is_page_file' => 0,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // MEMBER
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Thành viên',
            'page_icon' => 'fas fa-user-friends',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 9,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Tài khoản thường',
                'page_icon' => '',
                'page_url' => '/admin/tai-khoan-thuong',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Tài khoản doanh nghiệp',
                'page_icon' => '',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // PROJECT
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Quản lý dự án',
            'page_icon' => 'fas fa-tasks',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 10,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        // Lấy id dự án
        $id_parent_project = $id_parent;
        $children_page = [
            [
                'page_name' => 'Thêm dự án mới',
                'page_icon' => '',
                'page_url' => '/admin/project/add',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Danh sách dự án',
                'page_icon' => '',
                'page_url' => '/admin/project',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Danh sách yêu cầu',
                'page_icon' => '',
                'page_url' => '/admin/danh-sach-yeu-cau',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý cập nhật',
                'page_icon' => '',
                'page_url' => '/admin/quan-ly-cap-nhat',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 4,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý chuyên mục',
                'page_icon' => '',
                'page_url' => '/admin/quan-ly-chuyen-muc',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 5,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // EVENT
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Sự kiện',
            'page_icon' => 'fas fa-tasks',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 12,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Danh sách sự kiện',
                'page_icon' => '',
                'page_url' => '/admin/event',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Báo cáo',
                'page_icon' => '',
                'page_url' => '/admin/event/report',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '/admin/event/setting',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // ADD COIN
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Nạp Express Coin',
            'page_icon' => 'fab fa-bitcoin',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 13,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Danh sách nạp',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Hóa đơn',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // PACKAGE
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Quản lý gói tin',
            'page_icon' => 'fab fa-ups',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 14,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Danh sách mua gói tin',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }


        // Hỗ trợ kỹ thuật
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Hỗ trợ kỹ thuật',
            'page_icon' => 'fas fa-headset',
            'page_url' => '/admin/chat',
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 22,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        // $children_page = [
        //     [
        //         'page_name' => 'CSKH',
        //         'page_icon' => '',
        //         'page_url' => '',
        //         'is_duplicate' => 0,
        //         'is_page_file' => 0,
        //         'show_order' => 1,
        //         'page_parent_id' => $id_parent,
        //         'created_by' => null,
        //         'created_at' => strtotime('now'),
        //     ],[
        //         'page_name' => 'Quản lý nội dung',
        //         'page_icon' => '',
        //         'page_url' => '',
        //         'is_duplicate' => 0,
        //         'is_page_file' => 0,
        //         'show_order' => 2,
        //         'page_parent_id' => $id_parent,
        //         'created_by' => null,
        //         'created_at' => strtotime('now'),
        //     ]
        // ];

        // foreach ($children_page as $children){
        //     DB::table('page')->insert($children);
        // }

        // CLASSIFIED
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Tin rao',
            'page_icon' => 'fas fa-newspaper',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 11,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        // Lấy id tin rao
        $id_parent_classified = $id_parent;
        $children_page = [
            [
                'page_name' => 'Danh sách tin rao',
                'page_icon' => '',
                'page_url' => '/admin/classified',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý chuyên mục',
                'page_icon' => '',
                'page_url' => '/admin/classified-group',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // MAIL CAMPAIGN
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Chiến dịch email',
            'page_icon' => 'fas fa-ellipsis-h',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 23,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);

        $children_page = [
            [
                'page_name' => 'Tạo mẫu mail',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Tạo chiến dịch',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Cấu hình mail',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý chiến dịch',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 4,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
        // STATIC PAGE
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Trang tĩnh',
            'page_icon' => 'fas fa-book',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 6,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);

        $children_page = [
            [
                'page_name' => 'Quản lý nhóm trang',
                'page_icon' => '',
                'page_url' => '/admin/static/group',
                'is_duplicate' => 1,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý trang',
                'page_icon' => '',
                'page_url' => '/admin/static/page',
                'is_duplicate' => 1,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
        ];

        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // PROMOTION
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Mã khuyến mãi',
            'page_icon' => 'fas fa-tags',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' => 7,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Mã khuyến mãi',
                'page_icon' => '',
                'page_url' => '/admin/promotion',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Bài viết khuyến mãi',
                'page_icon' => '',
                'page_url' => '/admin/promotion-news',
                'is_duplicate' => 0,
                'show_order' => 2,
                'is_page_file' => 0,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
        // FOCUS NEWS
        $id_parent = DB::table('page')->insertGetId(
            [
                'page_name' => 'Tiêu điểm',
                'page_icon' => 'fas fa-flag',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 21,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        );
        $children_page = [
            [
                'page_name' => 'Danh sách tiêu điểm',
                'page_icon' => '',
                'page_url' => '/admin/focus-news/',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý danh mục',
                'page_icon' => '',
                'page_url' => '/admin/focus-news-category/',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
        // Bình thêm trang con của mục dự án
        $children_page = [
            [
                'page_name' => 'Quản lý báo cáo',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 6,
                'page_parent_id' => $id_parent_project,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '/admin/danh-sach-du-an',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 7,
                'page_parent_id' => $id_parent_project,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thuộc tính',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 8,
                'page_parent_id' => $id_parent_project,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
        // bình thêm danh mục con tin rao
        $children_page = [
            [
                'page_name' => 'Thuộc tính',
                'page_icon' => '',
                'page_url' => '/admin/classified-properties',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent_classified,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '/admin/classified-setup',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 4,
                'page_parent_id' => $id_parent_classified,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
            [
                'page_name' => 'Báo cáo',
                'page_icon' => '',
                'page_url' => '/admin/classified-report',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 5,
                'page_parent_id' => $id_parent_classified,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        //Bình thêm Quản lý bình luận
        $id_parent = DB::table('page')->insertGetId([
            'page_name' => 'Quản lý bình luận',
            'page_icon' => 'fas fa-comments',
            'page_url' => null,
            'is_duplicate' => 0,
            'is_page_file' => 0,
            'show_order' =>19,
            'page_parent_id' => null,
            'created_by' => null,
            'created_at' => strtotime('now'),
        ]);
        $children_page = [
            [
                'page_name' => 'Bình luận dự án',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Bình luận tin đăng',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Bình luận bài viết',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Báo cáo bình luận dự án',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 4,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Báo cáo bình luận tin đăng',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 5,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Báo cáo bình luận bài viết',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 6,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Thiết lập',
                'page_icon' => '',
                'page_url' => '',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 7,
                'page_parent_id' => $id_parent,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }

        // SINGLE PAGE
        $single_page = [
            [
                'page_name' => 'Thống kê dữ liệu',
                'page_icon' => 'fas fa-database',
                'page_url' => '/admin/thong-ke-du-lieu',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Trang chủ',
                'page_icon' => 'fa fa-globe',
                'page_url' => '/admin/home',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 3,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Trang chuyên mục',
                'page_icon' => 'fa fa-copy',
                'page_url' => '/admin/category-page-config',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 4,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý SEO',
                'page_icon' => 'fas fa-chart-pie',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 5,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[

                'page_name' => 'Quảng cáo express',
                'page_icon' => 'fas fa-search-dollar',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 15,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý báo cáo',
                'page_icon' => 'fas fa-sliders-h',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 16,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý banner',
                'page_icon' => 'fas fa-hand-sparkles',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 17,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý liên hệ',
                'page_icon' => 'fas fa-id-card',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 18,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Trang cá nhân',
                'page_icon' => 'fas fa-user',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 20,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Quản lý cấp bậc',
                'page_icon' => 'fas fa-columns',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 24,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Hòm thư',
                'page_icon' => 'fas fa-envelope',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 25,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Hướng dẫn',
                'page_icon' => 'fas fa-chalkboard-teacher',
                'page_url' => '/admin/guide/',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 26,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'File đã tải',
                'page_icon' => 'fas fa-download',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 1,
                'show_order' => 27,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],[
                'page_name' => 'Danh sách chặn/cấm',
                'page_icon' => 'fas fa-users-slash',
                'page_url' => null,
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 28,
                'page_parent_id' => null,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
        ];

        foreach ($single_page as $page){
            DB::table('page')->insert($page);
        }
        // Chinh: children page of Banner
        $id_parent_banner = 68;
        $children_page = [
            [
                'page_name' => 'Quản lý banner',
                'page_icon' => '',
                'page_url' => '/admin/banner',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 1,
                'page_parent_id' => $id_parent_banner,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ],
            [
                'page_name' => 'Quản lý nhóm banner',
                'page_icon' => '',
                'page_url' => '/admin/banner/locate',
                'is_duplicate' => 0,
                'is_page_file' => 0,
                'show_order' => 2,
                'page_parent_id' => $id_parent_banner,
                'created_by' => null,
                'created_at' => strtotime('now'),
            ]
        ];
        foreach ($children_page as $children){
            DB::table('page')->insert($children);
        }
    }
}
