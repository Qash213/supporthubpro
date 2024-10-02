<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\SendNewUserNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\Envato\EnvatoExtendSocialite::class.'@handle',
            // for zoho ZohoExtendSocialite
            // \SocialiteProviders\Zoho\ZohoExtendSocialite::class.'@handle',
            \SocialiteProviders\Microsoft\MicrosoftExtendSocialite::class.'@handle',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendNewUserNotification::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
