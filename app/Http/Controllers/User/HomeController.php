<?php

namespace App\Http\Controllers\User;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;

use Carbon\CarbonPeriod;
use App\Models\{Country,
    CountryService,
    Coupon,
    Fund,
    Gateway,
    IdentifyForm,
    KYC,
    Language,
    RedirectDetail,
    SendingPurpose,
    SendMoney,
    SourceFund,
    Template,
    Ticket,
    Transaction};
use DeviceDetector\Parser\Device\Console;
use Illuminate\Validation\Rules\Password;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Hash, Validator};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

use hisorange\BrowserDetect\Parser as Browser;

class HomeController extends Controller
{
    use Upload, Notify;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->theme = template();
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
                $this->theme .='user.';

            return $next($request);
        });
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = $this->user;
        $data['domain'] = $user->getDomains->count();
        $data['redirect'] = $user->getRedirects->count();
        $data['iplist'] = $user->getIpLists->count() + $user->getListType->count();

        return view($this->theme . 'dashboard',$data);

    }

    public function getPlatformData(Request $request): array
    {
        $start_date = Carbon::parse($request['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($request['end_date'])->format('Y-m-d');

        $query = RedirectDetail::whereBetween('created_at', [date($start_date), date($end_date)])
            ->selectRaw('platform_name, SUM(count) as count_platform')
            ->groupBy('platform_name')
            ->orderBy('platform_name', 'desc');

        $redirectid = $this->user->getRedirects->pluck('id')->toArray();
        $query->whereIn('redirect_id', $redirectid);


        $platformCounts = $query->get()->pluck('count_platform', 'platform_name')->toArray();
        $data = [
            'labels' => [
                'AndroidOS',
                'iOS',
                'Windows',
                'Other',
            ],
            'dataPoints' => [
                $platformCounts['AndroidOS'] ?? 0,
                $platformCounts['iOS'] ?? 0,
                $platformCounts['Windows'] ?? 0,
            ],
        ];

        $data['dataPoints'][] = array_sum($platformCounts) - array_sum($data['dataPoints']);

        return $data;
    }

    public function getCountryData(Request $request): array
    {
        $limit  = 4;
        $start_date = Carbon::parse($request['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($request['end_date'])->format('Y-m-d');

        $query = RedirectDetail::whereBetween('created_at', [date($start_date), date($end_date)])
            ->selectRaw('country,SUM(count) as count_country')
            ->groupBy('country')
            ->orderBy('count_country', 'desc');

        $redirectid = $this->user->getRedirects->pluck('id')->toArray();
        $query->whereIn('redirect_id', $redirectid);


        $result = $query->get()->pluck('count_country', 'country')->toArray();

        if (count($result) > $limit) {
            $slicedData = array_slice($result, 0, $limit, true);
            $otherCount = array_sum(array_slice($result, $limit, null, true));
            $result = $slicedData + ['other' => $otherCount];
        }
        $labels = array_keys($result);
        $counts = array_values($result);

        return [
            'labels' => $labels,
            'dataPoints' => $counts,
        ];
    }

    public function getYearlyData(Request $request)
    {

        $start_date = Carbon::parse($request['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($request['end_date'])->format('Y-m-d');

        $query = RedirectDetail::whereBetween('created_at', [date($start_date), date($end_date)]);

        $redirectid = $this->user->getRedirects->pluck('id')->toArray();
        $query->whereIn('redirect_id', $redirectid);

        $redirect = $query
            ->groupBy(DB::raw('DATE_FORMAT(updated_at, "%b %d")'))
            ->orderBy('updated_at')
            ->select(DB::raw('DATE_FORMAT(updated_at, "%b %d") as month'), DB::raw('SUM(count) as total_redirect'))
            ->get()
            ->keyBy('month');


        $period = CarbonPeriod::create($start_date, $end_date);
        $labelsData = array_map(function ($datePeriod) {
            return $datePeriod->format('M d');
        }, iterator_to_array($period));

        $incomeOverviewData = array_map(function ($datePeriod) use ($redirect) {
            $month = $datePeriod->format('M d');

            return $redirect->has($month) ? $redirect->get($month)->total_redirect : 0;
        }, iterator_to_array($period));

        $data['labels'] = $labelsData;
        $data['total_redirect'] = $incomeOverviewData;
        return $data;
    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), []);
        $data['user'] = $this->user;
        $data['languages'] = Language::all();

        return view($this->theme . 'profile.myprofile', $data);
    }

    public function updateProfile(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->image;
        $this->validate($request, [
            'image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->extension());
                    if (!in_array($ext, $allowedExtensions)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    } else {
                        if (($image->getSize() / 1000000) > 2) {
                            return $fail("Images MAX  2MB ALLOW!");
                        }
                    }

                }
            ]
        ]);
        $user = $this->user;
        if ($request->hasFile('image')) {
            $path = config('location.user.path');
            try {
                $user->image = $this->uploadImage($image, $path);
            } catch (\Exception $exp) {
                return back()->with('error', 'Could not upload your ' . $image)->withInput();
            }
        }
        $user->save();
        return redirect()->route('user.profile')->with('success', 'Updated Successfully.');
    }

    public function updateInformation(Request $request)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $req = Purify::clean($request->all());
        $user = $this->user;
        $rules = [
            'fullname' => 'required',
            'username' => "sometimes|required|alpha_dash|min:4|unique:users,username," . $user->id,
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'fullname.required' => 'Name field is required',
        ];




        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return redirect()->route('user.profile')->withErrors($validator)->withInput();
        }
        $user->language_id = $req['language_id'];
        $user->fullname = $req['fullname'];
        $user->username = $req['username'];
        $user->address = $req['address'];
        $user->save();
        return redirect()->route('user.profile')->with('success', 'Updated Successfully.');
    }

    public function updatePassword(Request $request)
    {

        $rules['current_password'] = ["required"];

        if (config('basic.strong_password') == 0) {
            $rules['password'] = ["required", "min:6", 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('password', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect()->route('user.profile')->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return redirect()->route('user.profile')->with('error', $e->getMessage());
        }
    }

    public function twoStepSecurity()
    {
        $basic = (object)config('basic');
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $secret);
        $previousCode = $this->user->two_fa_code;

        $previousQR = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $previousCode);
        return view($this->theme . 'twoFA.index', compact('secret', 'qrCodeUrl', 'previousCode', 'previousQR'));
    }

    public function twoStepEnable(Request $request)
    {
        $user = $this->user;
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        $userCode = $request->code;
        if ($oneCode == $userCode) {
            $user['two_fa'] = 1;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = $request->key;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_ENABLED', [
                'action' => 'Enabled',
                'code' => $user->two_fa_code,
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);
            return back()->with('success', 'Two Factor has been enabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }

    }

    public function twoStepDisable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = $this->user;
        $ga = new GoogleAuthenticator();

        $secret = $user->two_fa_code;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {
            $user['two_fa'] = 0;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = null;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_DISABLED', [
                'action' => 'Disabled',
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);
            return back()->with('success', 'Two Factor has been disabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }
    }

}
