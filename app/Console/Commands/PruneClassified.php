<?php

namespace App\Console\Commands;

use App\Models\AdminConfig;
use App\Models\Classified\Classified;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PruneClassified extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classified:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean unapproved classifieds';

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
        $cleanDayConfig = AdminConfig::firstWhere('config_code', 'D001');
        $cleanDays = (int) data_get($cleanDayConfig, 'config_value', 14);

        // get unapproved or expired classifieds
        $classifieds = Classified::where(function ($query) use ($cleanDays) {
                return $query->where('expired_date', '<', now()->addDays($cleanDays * -1)->timestamp)
                    ->orWhere(function ($query) use ($cleanDays) {
                        return $query->whereIn('confirmed_status', [2, 3]) // khong duyet hoac chua tu cam
                                ->where(function ($query) use ($cleanDays) {
                                    return $query->where('unapproved_at', '<', now()->addDays($cleanDays * -1))
                                        ->orWhereNull('unapproved_at');
                                });
                    });
            })
            ->where('is_deleted', 0)
            ->get();

        foreach ($classifieds as $classified) {
            // clean image
            $images = $classified->image_perspective ? json_decode($classified->image_perspective) : [];

            foreach ($images as $image) {
                if ($image && File::exists(public_path($image))) {
                    File::delete(public_path($image));
                }
            }

            // clean thumbnail
            $thumbnail = $classified->image_thumbnail;
            if ($thumbnail) {
                if (File::exists(public_path($thumbnail))) {
                    File::delete(public_path($thumbnail));
                }
            }

            // deleted classified
            $classified->update([
                'is_deleted' => true,
            ]);
        }

        return 0;
    }
}
