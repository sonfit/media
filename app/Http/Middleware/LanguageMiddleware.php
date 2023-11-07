<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        session()->put('trans', $this->getCode());
        session()->put('rtl', $this->getDirection());

        app()->setLocale(session('trans', $this->getCode()));
        return $next($request);
    }

    public function getCode()
    {
        if (session()->has('trans')) {
            return session('trans');
        }

        try {
            $language = Language::where('is_active', 1)->where('default_status', 1)->first();
            if (!$language) {
                $language = Language::where('is_active', 1)->first();
            }
            return $language ? $language->short_name : 'en';
        } catch (\Exception $exception) {

        }
    }

    public function getDirection()
    {
        if (session()->has('rtl')) {
            return session('rtl');
        }

        try {
            $language = Language::where('is_active', 1)->where('default_status', 1)->first();
            if (!$language) {
                $language = Language::where('is_active', 1)->first();
            }
            return $language ? $language->rtl : 0;
        } catch (\Exception $exception) {

        }
    }

}
