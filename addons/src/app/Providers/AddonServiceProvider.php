<?php

namespace Uhelp\Addons\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Sentry\Sentrykey\App\Http\Controllers\SentryController;

class AddonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {

        Route::group(['middleware' => 'web','namespace' => 'Uhelp\Addons\App\Http\Controllers'], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'addons');

        
    }

  
}
