<?php

namespace App\Console\Commands;

use App\Models\Classified\Classified;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClassifiedViewOfWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classifiedview:week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The 20 most viewed posts of the week';

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
        $classified = Classified::query()->orderBy('num_view_today','desc')->pluck('id')->take(20)->toArray();
        $array = serialize($classified);
        DB::table('classified_view_week')
            ->where('classified_view_week.id',1)
            ->update(['classified_id'=>$array]);
        DB::table('classified')->update(['num_view_today'=>0]);
        $this->info('classified view week success !');

    }
}
