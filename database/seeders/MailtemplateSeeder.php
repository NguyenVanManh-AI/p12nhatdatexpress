<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailtemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_mail_template')->insert([
//           ['id'=>1,'template_title'=>'Gửi cho người đăng tin','template_content'=>'Nội dung mail','template_action'=>'user-contact'],
           ['id'=>2,'template_title'=>'Gửi cho người đăng tin','template_content'=>'Nội dung mail','template_action'=>'user-contact'],
           ['id'=>3,'template_title'=>'Gửi cho người đăng tin','template_content'=>'Nội dung mail','template_action'=>'user-contact'],
           ['id'=>4,'template_title'=>'Gửi cho người đăng tin','template_content'=>'Nội dung mail','template_action'=>'user-contact'],
        ]);
    }
}
