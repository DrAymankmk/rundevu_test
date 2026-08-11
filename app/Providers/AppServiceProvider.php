<?php

namespace App\Providers;

use App\Models\ContactUs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            $rootUrl = request()->getSchemeAndHttpHost();
            if ($rootUrl !== '') {
                URL::forceRootUrl($rootUrl);
            }
        }

        if (! function_exists('proc_open')) {
            config([
                'media-library.image_optimizers' => [],
                'media-library.queue_conversions_by_default' => false,
            ]);
        }

        Paginator::useBootstrap();

        Blade::if('clinicModule', function (string $module) {
            return clinic_has_module($module);
        });

        View::composer('layout_new.partials.sidebar', function ($view) {
            $unreadContactUsCount = 0;

            if (Schema::hasTable('contact_us')) {
                $unreadContactUsCount = ContactUs::query()->unread()->count();
            }

            $view->with('unreadContactUsCount', $unreadContactUsCount);
        });
    }
}
