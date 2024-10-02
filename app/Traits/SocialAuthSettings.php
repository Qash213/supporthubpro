<?php

/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;


use App\Models\SocialAuthSetting;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Config;

trait SocialAuthSettings
{

    public function setSocailAuthConfigs()
    {
        $settings = SocialAuthSetting::first();

        Config::set('services.google.client_id', ($settings->google_client_id)? $settings->google_client_id : env('GOOGLE_CLIENT_ID'));
        Config::set('services.google.client_secret', ($settings->google_secret_id)? $settings->google_secret_id : env('GOOGLE_CLIENT_SECRET'));
        Config::set('services.google.redirect', route('social.login-callback', 'google'));

        Config::set('services.envato.client_id', ($settings->envato_client_id)? $settings->envato_client_id : env('ENVATO_CLIENT_ID'));
        Config::set('services.envato.client_secret', ($settings->envato_secret_id)? $settings->envato_secret_id : env('ENVATO_CLIENT_SECRET'));
        Config::set('services.envato.redirect', route('social.login-callback', 'envato'));

        Config::set('services.microsoft.client_id', ($settings->microsoft_app_id)? $settings->microsoft_app_id : env('MICROSOFT_APP_ID'));
        Config::set('services.microsoft.client_secret', ($settings->microsoft_secret_id)? $settings->microsoft_secret_id : env('MICROSOFT_APP_SECRET'));
        Config::set('services.microsoft.redirect', route('social.login-callback', 'microsoft'));

    }
}
