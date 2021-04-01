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
        $this->setFortifyForWeb();
    }

    /**
     * Change fortify guard & middleware.
     *
     * @param   string  $guard
     *
     * @return  void
     */
    private function changeFortifyGuard(string $guard)
    {
        config(['fortify.guard' => $guard]);
        config(['fortify.middleware' => $guard]);
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
        }
    }

    /**
     * Change some fortify configs on web request.
     *
     * @return  void
     */
    private function setFortifyForWeb()
    {
        if (request()->path() === 'login' && !request()->wantsJson()) {
            $this->changeFortifyGuard('admin');
        }
    }
}
