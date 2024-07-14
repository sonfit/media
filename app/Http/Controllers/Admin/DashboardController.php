<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Domain;

use App\Models\DomainLoginLogs;
use App\Models\IPLIST;


use App\Models\Musics;
use App\Models\Ringtones;
use App\Models\Tags;
use App\Models\User;
use App\Models\Wallpapers;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use Upload;

    public function forbidden()
    {
        return view('admin.errors.403');
    }

    public function dashboard()
    {
        $wallpaperCount = Wallpapers::count();
        $ringtoneCount = Ringtones::count();
        $musicCount = Musics::count();

        $data['wallpapers_active'] = Cache::get('wallpapers_active', function () use ($wallpaperCount) {
            return Wallpapers::where('wallpaper_status', 1)->count();
        });

        $data['wallpapers_inactive'] = $wallpaperCount - $data['wallpapers_active'];

        $data['ringtones_active'] = Cache::get('ringtones_active', function () use ($ringtoneCount) {
            return Ringtones::where('ringtone_status', 1)->count();
        });

        $data['ringtones_inactive'] = $ringtoneCount - $data['ringtones_active'];

        $data['musics_active'] = Cache::get('musics_active', function () use ($musicCount) {
            return Musics::where('music_status', 1)->count();
        });

        $data['musics_inactive'] = $musicCount - $data['musics_active'];


        $data = [
            'domain' => number_format(Domain::count()),
            'tags' => number_format(Tags::count()),
            'iplist' => number_format(IPLIST::count()),

            'wallpapers' => number_format($wallpaperCount),
            'wallpapers_inactive' => number_format($data['wallpapers_inactive']),

            'ringtones' => number_format($ringtoneCount),
            'ringtones_inactive' => number_format($data['ringtones_inactive']),

            'musics' => number_format($musicCount),
            'musics_inactive' => number_format($data['musics_inactive']),
        ];
        return view('admin.dashboard', $data);
    }

    public function getPlatformData(Request $request): array
    {
        $start_date = Carbon::parse($request['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($request['end_date'])->format('Y-m-d');

        $query = DomainLoginLogs::whereBetween('created_at', [date($start_date), date($end_date)])
            ->selectRaw('platform_name, SUM(count) as count_platform')
            ->groupBy('platform_name')
            ->orderBy('platform_name', 'desc');

        $platformCounts = $query->get()->pluck('count_platform', 'platform_name')->toArray();
        $data = [
            'labels' => [
                'AndroidOS',
                'iOS',
                'Windows',
                'Unknown',
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

        $query = DomainLoginLogs::whereBetween('created_at', [date($start_date), date($end_date)])
            ->selectRaw('country,SUM(count) as count_country')
            ->groupBy('country')
            ->orderBy('count_country', 'desc');

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

        $query = DomainLoginLogs::whereBetween('created_at', [date($start_date), date($end_date)]);


        $redirect = $query
            ->groupBy(DB::raw('DATE_FORMAT(updated_at, "%b %d")'))
            ->orderBy('updated_at')
            ->select(DB::raw('DATE_FORMAT(updated_at, "%b %d") as month'), DB::raw('SUM(count) as total_login'))
            ->get()
            ->keyBy('month');


        $period = CarbonPeriod::create($start_date, $end_date);
        $labelsData = array_map(function ($datePeriod) {
            return $datePeriod->format('M d');
        }, iterator_to_array($period));

        $incomeOverviewData = array_map(function ($datePeriod) use ($redirect) {
            $month = $datePeriod->format('M d');

            return $redirect->has($month) ? $redirect->get($month)->total_login : 0;
        }, iterator_to_array($period));

        $data['labels'] = $labelsData;
        $data['total_login'] = $incomeOverviewData;
        return $data;
    }

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {

        $req = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'sometimes|required',
            'username' => 'sometimes|required|unique:admins,username,' .  Auth::user()->id,
            'email' => 'sometimes|required|email|unique:admins,email,' .  Auth::user()->id,
            'phone' => 'sometimes|required',
            'address' => 'sometimes|required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user =  Auth::user();
        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = $this->uploadImage($request->image, config('location.admin.path'), config('location.admin.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }
        $user->name = $req['name'];
        $user->username = $req['username'];
        $user->email = $req['email'];
        $user->phone = $req['phone'];
        $user->address = $req['address'];
        $user->save();

        return back()->with('success', 'Updated Successfully.');
    }

    public function password()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $req = Purify::clean($request->all());

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $request = (object)$req;
        $user = $this->user;
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', "Password didn't match");
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return back()->with('success', 'Password has been Changed');
    }
}
