<?php

namespace App\Console\Commands;

use App\Helpers\SystemConfig;
use App\Models\ClassifiedPackage;
use App\Models\UserBalance;
use Illuminate\Console\Command;

class ResetDefaultUserBalancePostAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-default-package-amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all default package amount of normal|vip|highlight of user balance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $basePackage = ClassifiedPackage::find(1);
        if (!$basePackage) return 0;

        $basePackage->userBalances()
            ->where('status', 1)
            ->limit(1)
            ->where(function ($query) use ($basePackage) {
                return $query->where('vip_amount', '!=', $basePackage->vip_amount)
                    ->orWhere('highlight_amount', '!=', $basePackage->highlight_amount)
                    ->orWhere('classified_normal_amount', '!=', $basePackage->classified_nomal_amount);
            })
            ->each(function ($userBalance) use ($basePackage) {
                $userBalance->update([
                    'vip_amount' => data_get($basePackage, 'vip_amount', 20),
                    'highlight_amount' => data_get($basePackage, 'highlight_amount', 0),
                    'classified_normal_amount' => data_get($basePackage, 'classified_nomal_amount', SystemConfig::CLASSIFIED_PER_MONTH),
                ]);
            });

        return 0;
    }
}
