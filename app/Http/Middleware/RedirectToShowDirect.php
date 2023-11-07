<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RedirectToShowDirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Lấy URL hiện tại
        $currentUrl = $request->url();

//        // Kiểm tra xem URL có tồn tại trong danh sách route hay không
//        if (!Route::has($request->path())) {
//            return redirect()->route('show_direct');
//        }
        return redirect()->route('show_direct');

//        return $next($request);
    }
}
