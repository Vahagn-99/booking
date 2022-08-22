<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Models\Customizeinfo;
use App\Models\User;
use App\Models\RoomType;

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
        Paginator::useBootstrap();

        view()->composer('*', function($view) {
            $view->with('mainUrl', 'https://' . env('APP_MAINURL','bookingfwi.com'));
            $uid = 1; $agencydomain = null;
            if (count(explode('://',url()->current())) > 1) {
                if (strpos(explode('://',url()->current())[1], '.'.env('APP_MAINURL','bookingfwi.com')) !== false) {
                    $url = explode('://',url()->current())[1];
                    $agencydomain = explode('.'.env('APP_MAINURL','bookingfwi.com'), $url)[0];
                    if ($subUser = User::where([['subdomain',$agencydomain],['subdomain_status','active']])->first()) {
                        $uid = $subUser->id;
                        $view->with('mainUrl', 'https://' . $agencydomain . '.' . env('APP_MAINURL','bookingfwi.com'));
                    }
                }
            }
            $view->with('uid', $uid);
            $view->with('agencydomain', $agencydomain);
            if (Customizeinfo::where([['name','website-header-favicony'],['user',$uid]])->first()) {
                $view->with('favicon', Customizeinfo::where([['name','website-header-favicony'],['user',$uid]])->first()->photo);
            } else {
                $view->with('favicon', 'favicon.ico');
            }
            if (Customizeinfo::where([['name','website-header-logo'],['user',$uid]])->first()) {
                $view->with('logo', Customizeinfo::where([['name','website-header-logo'],['user',$uid]])->first()->photo);
            } else {
                $view->with('logo', 'img/logo.png');
            }
            if (Customizeinfo::where([['name','website-header-menu'],['user',$uid]])->first()) {
                $view->with('menu', Customizeinfo::where([['name','website-header-menu'],['user',$uid]])->get());
            }
            if (Customizeinfo::where([['header_back_type','video'],['user',$uid]])->first()) {
                $view->with('video', Customizeinfo::where([['header_back_type','video'],['user',$uid]])->first()->header_back_video_link);
            }
            if (Customizeinfo::where([['header_back_type','photo'],['user',$uid]])->first()) {
                $view->with('photo', Customizeinfo::where([['header_back_type','photo'],['user',$uid]])->first()->photo);
            }
            if (Customizeinfo::where([['name','website-header-background'],['user',$uid]])->first()) {
                $view->with('headerBack', Customizeinfo::where([['name','website-header-background'],['user',$uid]])->first());
            }
            if (Customizeinfo::where([['name','website-seo'],['user',$uid]])->first()) {
                $view->with('sitemeta', Customizeinfo::where([['name','website-seo'],['user',$uid]])->first());
            } else {
                $view->with('sitemeta', Customizeinfo::where([['name','website-seo'],['user',1]])->first());
            }
            if (Customizeinfo::where([['name','website-footer'],['user',$uid]])->first()) {
                $view->with('sitefooter', Customizeinfo::where([['name','website-footer'],['user',$uid]])->first());
            }
            if (Customizeinfo::where([['name','website-footer-more-info'],['user',$uid]])->get()) {
                $view->with('footerMoreInfo', Customizeinfo::where([['name','website-footer-more-info'],['user',$uid]])->get());
            }
            $view->with('currenciesSign', [
                null => "&#8364;",
                "USD" => "$",
                "EUR" => "&#8364;",
                "RUB" => "&#8381;",
                "CAD" => "C$",
                "BRL" => "R$",
                "GBP" => "&#163;"
            ]);
        });
    }
}
