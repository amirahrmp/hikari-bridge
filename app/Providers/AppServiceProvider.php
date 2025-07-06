<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Carbon\Carbon;

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
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $unreadNotifications = Auth::user()->unreadNotifications; // atau sesuai relasi Anda
            $view->with('unreadNotifications', $unreadNotifications);
        } else {
            $view->with('unreadNotifications', collect()); // kosongkan jika tidak login
        }
    });
}
}
