<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        $this->saveStorageAsPublicAccess();

    }

    private function saveStorageAsPublicAccess()
    {
//        $storage = Setting::where('name','storage')->first();
//        $filesystem_driver = 'oci';
//        if($storage){
//            $filesystem_driver = $storage->data_json['driver'];
//            $temporary_url_expire_time = Carbon::now()->addMinutes($storage->data_json['expire_time']);
//        }
        $temporary_url_expire_time = Carbon::now()->addMinutes(60);

        config(['driver' => 'local', 'expire_time' => $temporary_url_expire_time]);
    }
}
