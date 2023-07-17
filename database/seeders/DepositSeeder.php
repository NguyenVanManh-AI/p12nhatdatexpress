<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enum_type = ['C', 'I', 'B'];
        for ($i = 1; $i <= 10000; $i++)
        {
            DB::transaction(function () use ($i, $enum_type){
                $user_id = rand(1,10);
                $type = $enum_type[rand(0,2)];

                $transaction_id = DB::table('user_transaction')->insertGetId([
                    'user_id' => $user_id,
                    'transaction_type' => $type,
                ]);

                DB::table('user_deposit')->insert([
                    'user_id' => $user_id,
                    'deposit_code' => Str::limit(Str::uuid(), 4, '') . Str::random(6),
                    'is_confirm' => 1,
                    'deposit_status' => 1,
                    'confirm_time' => rand(1556693104, 1652942731),
                    'user_transaction_id' => $transaction_id,
                    'deposit_type' => $type
                ]);
            }, 5);
        }
    }
}
