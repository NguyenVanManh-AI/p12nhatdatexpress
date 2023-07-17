<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(MailtemplateSeeder::class);
//        $this->call(AdminSeeder::class);
// 		  $this->call(PageSeeder::class);
//        $this->call(Page_Permission::class);
//        $this->call(CategoryConfigSeeder::class);
// 		  $this->call(PromotionSeeder::class);
    	  $this->call(ListRequestSeeder::class);
    }
}
