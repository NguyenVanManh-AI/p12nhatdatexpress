<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Province;
use Illuminate\Console\Command;

class SyncProvinceUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:province-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync url for all provinces';

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
        Province::each(function ($province) {
            $provinceUrl = Helper::convertKebabCase($province->province_name);
            $province->update([
                'province_url' => $provinceUrl,
                'image_url' => '/frontend/provinces/' . $provinceUrl . '.png',
            ]);
        });

        return 0;
    }
}
