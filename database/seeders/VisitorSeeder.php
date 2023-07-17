<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10000; $i++)
        {
            DB::table('access_statistic')->insert([
                'ip' => "127.0.0." . rand(0, 255),
                'user-agent' => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0",
                'access_at' => time() - rand(0, (84000) * rand(1,10))
            ]);
        }
    }
}
