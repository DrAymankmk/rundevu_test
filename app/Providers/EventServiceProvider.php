<?php

namespace App\Providers;

use App\Events\SystemNotifications\ClinicRegistered;
use App\Events\SystemNotifications\DemoRequested;
use App\Listeners\SendSystemNotification;
use App\Models\CmsItem;
use App\Models\CmsPage;
use App\Models\CmsSection;
use App\Models\Package;
use App\Models\Specialty;
use App\Observers\FrontendCacheObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClinicRegistered::class => [
            SendSystemNotification::class,
        ],
        DemoRequested::class => [
            SendSystemNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $observer = FrontendCacheObserver::class;

        Specialty::observe($observer);
        Package::observe($observer);
        CmsPage::observe($observer);
        CmsSection::observe($observer);
        CmsItem::observe($observer);
    }
}
