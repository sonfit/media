<?php

namespace App\Http\Controllers\User;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{

    use Notify;

    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });

        $this->theme = template();
    }

    public function check()
    {
        $user = $this->user;
        if (!$user->status) {
            Auth::logout();
        }

        elseif (!$user->two_fa_verify) {
            $page_title = '2FA Code';
            return view(template().'auth.verification.2stepSecurity', compact('user', 'page_title'));

        }
        return redirect()->route('user.dashboard_user.home');
    }

    public function twoFAverify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ], [
            'code.required' => 'Email verification code is required',
        ]);
        $ga = new GoogleAuthenticator();
        $user = Auth::user();
        $getCode = $ga->getCode($user->two_fa_code);
        if ($getCode == trim($request->code)) {
            $user->two_fa_verify = 1;
            $user->save();
            return redirect()->intended(route('user.dashboard_user.home'));
        }
        throw ValidationException::withMessages(['error' => 'Wrong Verification Code']);

    }



}
