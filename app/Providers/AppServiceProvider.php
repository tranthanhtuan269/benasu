<?php

namespace App\Providers;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use \Illuminate\Pagination\Paginator;


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
        Password::defaults(function () {
            $rule = Password::min( 8 );
            return $this->app->isProduction() ? $rule->mixedCase()->uncompromised() : $rule;
        });


        // for multi-locale setups
        \Illuminate\Auth\Notifications\VerifyEmail::$createUrlCallback = function($notifiable) {
            $time = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60));
            $params = [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ];

            if( env( 'SHOP_MULTILOCALE' ) ) {
                $params['locale'] = Request::route( 'locale', Request::input( 'locale', app()->getLocale() ) );
            }

            return URL::temporarySignedRoute('verification.verify', $time, $params);
        };


        View::composer('*', function ( $view ) {
            $view->with( 'aimeossite', app( 'aimeos.context' )->get()->locale()->getSiteItem() );
        });


        \Cache::forget('SiteConfigs');
        $siteConfigs = \Cache::rememberForever('SiteConfigs', function () {
            return \DB::table('siteconfigs')->pluck('value', 'key');
        });

        View::share('siteConfigs', $siteConfigs);

        Paginator::useBootstrap();
    }
}
