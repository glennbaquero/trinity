<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

use Auth;
use Carbon\Carbon;

use App\Helpers\EnvHelpers;
use App\Helpers\GlobalChecker;

use App\Models\Users\Doctor;
use App\Observers\DoctorObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);

        if (config('web.force_https')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            if(Auth::guard('admin')->check()) {
                View::share('self', Auth::guard('admin')->user());
            }
            
            View::share('now', Carbon::now());
            View::share('isDev', EnvHelpers::isDev());
            View::share('checker', new GlobalChecker);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Doctor::observe(DoctorObserver::class);
    }
}
