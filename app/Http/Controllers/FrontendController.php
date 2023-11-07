<?php

namespace App\Http\Controllers;

use App\Http\Traits\Notify;
use App\Mail\SendMail;
use App\Models\Configure;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Country;
use App\Models\CountryService;
use App\Models\Language;
use App\Models\Subscriber;
use App\Models\Template;
use Facades\App\Services\Flutterwave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicCurl;

class FrontendController extends Controller
{
    use Notify;

    public function __construct()
    {
        $this->theme = template();
    }

    public function index()
    {
        $data['title'] = 'Home';
        return view($this->theme . 'home', $data);
    }
    public function language($code)
    {
        $language = Language::where('short_name', $code)->first();
        if (!$language) $code = 'US';
        session()->put('trans', $code);
        session()->put('rtl', $language ? $language->rtl : 0);
        return redirect()->back();
    }


    public function loadMore(Request $request)
    {
        $countries = Country::where('status',1)->where('send_to',1)->orderBy('name')->paginate(6);
        $data = '';
        if ($request->ajax()) {
            foreach ($countries as $country) {
                $data.=' <li><a href="'.route('toCountry', [@$country]).'">'.trans('Send money to') . ' '.$country->name.'</a></li>';
            }
            return $data;
        }
    }
}
