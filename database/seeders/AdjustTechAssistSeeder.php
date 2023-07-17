<?php

namespace Database\Seeders;

use App\Models\Admin\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class AdjustTechAssistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('page_super_admin');

        // điều chỉnh menu hỗ trợ kĩ thuật thành 1
        $techAssist = Page::firstWhere('page_name', 'Hỗ trợ kỹ thuật');

        if ($techAssist) {
            // sửa url của hỗ trợ kĩ thuật
            $techAssist->update([
                'page_url' => '/admin/chat'
            ]);

            // xóa quản lý nội dung và chăm sóc khách hàng
            $techAssist->children()
                ->whereIn('page_name', ['Quản lý nội dung', 'CSKH'])
                ->forceDelete();
        }
    }
}
