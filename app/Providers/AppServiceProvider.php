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
        $this->setFortifyForApi();
    }

    /**
     * Change some fortify configs on api request.
     *
     * @return  void
     */
    private function setFortifyForApi()
    {
        $fortifyPath = [
            'login',
            'logout',
            'register',
            'email/verification-notification',
            'forgot-password',
            'reset-password',
            'user/password',
            'user/confirm-password',
            'user/profile-information',
        ];

        if (request()->wantsJson() && in_array(request()->path(), $fortifyPath)) {
            config(['fortify.views' => false]);
            config(['fortify.guard' => 'api']);
            config(['fortify.middleware' => ['api']]);
        }
    }
}
