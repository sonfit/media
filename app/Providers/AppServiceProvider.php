<?php

namespace App\Providers;



use App\Models\Language;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');

        $data['basic'] = (object) config('basic');
        $data['theme'] = template();
        $data['themeTrue'] = template(true);
        View::share($data);

        try {

            view()->composer([
                $data['theme'] . 'layouts.app',
                $data['theme'] . 'layouts.user'
            ], function ($view) {
                $view->with('languages', Language::toBase()->orderBy('name')->where('is_active', 1)->get());
            });


        } catch (\Exception $exception){

        }

    }
}
