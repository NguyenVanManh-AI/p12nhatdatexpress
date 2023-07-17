<?php

namespace Database\Seeders;

use App\Models\Admin\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class MorePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('page_super_admin');

        $pages = [
            [
                'id' => 104,
                'page_url' => '/admin/keyword-use',
                'page_name' => 'Từ khóa nổi bật',
                'show_order' => 6,
                'page_parent_id' => 31 // tin rao
            ],
            [
                'id' => 105, // id for check permission should check by key
                'page_url' => '/admin/seo/provinces',
                'page_name' => 'SEO vị trí',
                'show_order' => 5,
                'page_parent_id' => 65 // Quản lý SEO
            ]
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate([
                'id' => $page['id'],
            ], [
                'page_name' => data_get($page, 'page_name'),
                'page_url' => data_get($page, 'page_url'),
                'show_order' => data_get($page, 'show_order'),
                'page_parent_id' => data_get($page, 'page_parent_id'),
            ]);
        }
    }
}
