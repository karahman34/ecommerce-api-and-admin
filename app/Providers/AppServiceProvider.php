<?php

namespace App\Providers;

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
        $this->disableFortifyViewsOnApiRequest();
    }

    private function disableFortifyViewsOnApiRequest()
    {
        if (request()->segment(1) === 'api') {
            config(['fortify.views' => false]);
        }
    }
}
