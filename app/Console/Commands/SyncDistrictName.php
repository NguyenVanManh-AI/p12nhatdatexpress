<?php

namespace App\Console\Commands;

use App\Models\District;
use Hamcrest\Type\IsNumeric;
use Illuminate\Console\Command;

class SyncDistrictName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:district-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đổi tên của các quận có tên là số thành "Quận + số';

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
        $districts = District::where('district_type', 0)
            ->get();

        foreach($districts as $district) {
            if (is_numeric($district->getOriginal('district_name'))) {
                $district->update([
                    'district_name' => 'Quận ' . $district->getOriginal('district_name'),
                    'district_url' => 'quan-' . $district->getOriginal('district_name'),
                ]);
            }
        }

        return 0;
    }
}
