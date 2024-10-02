<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Schema\Builder;

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

        Builder::defaultStringLength(255);

        if(file_exists(storage_path('installed'))){
            config(['websockets.dashboard.port' => setting('liveChatPort')]);
            config(['broadcasting.connections.pusher.options.port' => setting('liveChatPort')]);
            config(['broadcasting.connections.pusher.options.host' => parse_url(url('/'))["host"]]);
        }

        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {

            if(setting('RECAPTCH_TYPE')!='GOOGLE')
                return true;

            $client = new Client();

            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                ['form_params'=>
                    [
                        'secret'=> setting('GOOGLE_RECAPTCHA_SECRET'),
                        'response'=> $value
                     ]
                ]
            );

            $body = json_decode((string)$response->getBody());

            return $body->success;
        });
        Validator::extend('no_script_tags', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/<\s*script(?:\s+[\w\-\d]+(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^>\s]+))?)*\s*>(.*?)<\s*\/script\s*>/is';
            return !preg_match($pattern, $value);
        });
    }
}
