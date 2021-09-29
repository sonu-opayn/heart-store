<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\HeartStore\FileSystem\FileManager;
use App\HeartStore\FileSystem\FileManagerS3;
use App\HeartStore\FileSystem\FileManagerDisk;

class HeartStoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FileManager::class, function ($app) {

            $config = config('filesystems.default');

            if($config == 's3') {
                return new FileManagerS3();
            }

            return new FileManagerDisk();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
