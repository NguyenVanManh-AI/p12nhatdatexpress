<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\HomeConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // Share data menu
        $categories_home = Group::select('id', 'group_url','group_name')
            ->whereNull('parent_id')
            ->with(['children' => function ($group) {
                $group->with('children');
            }])
            ->get();

        View::share('categories_home', $categories_home);
        // View::share('categories_home', Group::select('id', 'group_url','group_name')->whereNull('parent_id')->with('children.children:id,group_url,group_name')->get());

        View::share('google_api_key', getGoogleApiKey());

        // share data
        $system_config = Cache::rememberForever('system_config', function() {
            return DB::table('system_config')->first();
        });
        View::share('system_config', $system_config);

        $homeSEOConfig = Cache::rememberForever('home_seo_config', function() {
            return HomeConfig::select('meta_title', 'meta_key', 'meta_desc', 'desktop_header_image')->first();
        });
        View::share('home_seo_config', $homeSEOConfig);
    }
}
