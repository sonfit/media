<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Color;
use App\Models\Configure;
use App\Models\RedirectDetail;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Image;
use Session;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class BasicController extends Controller
{
    use Upload;

    public function index()
    {
        $configure = Configure::firstOrNew();
        $timeZone = timezone_identifiers_list();
        $control = $configure;
        $control->time_zone_all = $timeZone;
        return view('admin.basic-controls', compact('control'));
    }

    public function updateConfigure(Request $request)
    {
        $configure = Configure::firstOrNew();
        $reqData = Purify::clean($request->except('_token', '_method'));
        $request->validate([
            'site_title' => 'required',
            'time_zone' => 'required',
            'paginate' => 'required|integer',
            'session_expire' => 'required|min:5|integer',
        ]);


        config(['basic.site_title' => $reqData['site_title']]);
        config(['basic.time_zone' => $reqData['time_zone']]);
        config(['basic.paginate' => (int) $reqData['paginate']]);
        config(['basic.registration' => (int) $reqData['registration']]);
        config(['basic.strong_password' => (int) $reqData['strong_password']]);
        config(['basic.maintenance' => (int) $reqData['maintenance']]);
        config(['basic.session_expire' => (int) $reqData['session_expire']]);
        config(['basic.error_log' => (int)$reqData['error_log']]);


        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);

        $configure->fill($reqData)->save();

        $envPath = base_path('.env');
        $env = file($envPath);
        $env = $this->set('APP_DEBUG', ($configure->error_log == 1) ?'true' : 'false', $env);
        $env = $this->set('DEBUGBAR_ENABLED', ($configure->error_log == 1) ?'true' : 'false', $env);
        $env = $this->set('APP_TIMEZONE', '"'.$reqData['time_zone'].'"', $env);

        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);

        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        session()->flash('success', ' Updated Successfully');
        return back();
    }

    private function set($key, $value, $env)
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }

    public function colorSettings()
    {
        $configure = Color::firstOrNew();
        $control = $configure;
        return view('admin.colors', compact('control'));
    }

    public function colorSettingsUpdate(Request $request)
    {
        $configure = Color::firstOrNew();
        $reqData = Purify::clean($request->except('_token', '_method'));
        $configure->fill($reqData)->save();

        Artisan::call('optimize:clear');

        session()->flash('success', ' Updated Successfully');
        return back();
    }

    public function logoSeo()
    {
        $seo = (object)config('seo');
        return view('admin.logo', compact('seo'));
    }

    public function logoUpdate(Request $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = 'logo.png';
                $this->uploadImage($request->image, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Logo could not be uploaded.');
            }
        }
        if ($request->hasFile('admin_image')) {
            try {
                $old = 'admin-logo.png';
                $this->uploadImage($request->admin_image, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Admin Logo could not be uploaded.');
            }
        }
        if ($request->hasFile('favicon')) {
            try {
                $old = 'favicon.png';
                $this->uploadImage($request->favicon, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'favicon could not be uploaded.');
            }
        }
        return back()->with('success', 'Logo has been updated.');
    }

    public function breadcrumb()
    {
        return view('admin.banner');
    }

    public function breadcrumbUpdate(Request $request)
    {
        if ($request->hasFile('banner')) {
            try {
                $old = 'banner.jpg';
                $this->uploadImage($request->banner, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Banner could not be uploaded.');
            }
        }

        if ($request->hasFile('background_image')) {
            try {
                $old = 'background_image.jpg';
                $this->uploadImage($request->background_image, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Background Image could not be uploaded.');
            }
        }

        return back()->with('success', 'Banner has been updated.');
    }

    public function seoUpdate(Request $request)
    {

        $reqData = Purify::clean($request->except('_token', '_method'));
        $request->validate([
            'meta_keywords' => 'required',
            'meta_description' => 'required',
            'social_title' => 'required',
            'social_description' => 'required'
        ]);


        config(['seo.meta_keywords' => $reqData['meta_keywords']]);
        config(['seo.meta_description' => $request['meta_description']]);
        config(['seo.social_title' => $reqData['social_title']]);
        config(['seo.social_description' => $reqData['social_description']]);


        if ($request->hasFile('meta_image')) {
            try {
                $old = config('seo.meta_image');
                $meta_image = $this->uploadImage($request->meta_image, config('location.logo.path'), null, $old, null, $old);
                config(['seo.meta_image' => $meta_image]);
            } catch (\Exception $exp) {
                return back()->with('error', 'favicon could not be uploaded.');
            }
        }

        $fp = fopen(base_path() . '/config/seo.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('seo'), true) . ';');
        fclose($fp);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return back()->with('success', 'Update Successfully.');

    }

    public function pluginConfig()
    {
        $control = Configure::firstOrNew();
        return view('admin.plugin_panel.pluginConfig', compact('control'));
    }

    public function tawkConfig(Request $request)
    {
        $basicControl = basicControl();
        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.tawkControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'tawk_id' => 'required|min:3',
                'tawk_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->tawk_id = $purifiedData->tawk_id;
            $basicControl->tawk_status = $purifiedData->tawk_status;
            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function fbMessengerConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.fbMessengerControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'fb_messenger_status' => 'nullable|integer|min:0|in:0,1',
                'fb_app_id' => 'required|min:3',
                'fb_page_id' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->fb_app_id = $purifiedData->fb_app_id;
            $basicControl->fb_page_id = $purifiedData->fb_page_id;
            $basicControl->fb_messenger_status = $purifiedData->fb_messenger_status;

            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function googleRecaptchaConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.googleReCaptchaControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'reCaptcha_status_login' => 'nullable|integer|min:0|in:0,1',
                'reCaptcha_status_registration' => 'nullable|integer|min:0|in:0,1',
                'NOCAPTCHA_SECRET' => 'required|min:3',
                'NOCAPTCHA_SITEKEY' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->reCaptcha_status_login = $purifiedData->reCaptcha_status_login;
            $basicControl->reCaptcha_status_registration = $purifiedData->reCaptcha_status_registration;
            $basicControl->save();


            $envPath = base_path('.env');
            $env = file($envPath);
            $env = $this->set('NOCAPTCHA_SECRET', $purifiedData->NOCAPTCHA_SECRET, $env);
            $env = $this->set('NOCAPTCHA_SITEKEY', $purifiedData->NOCAPTCHA_SITEKEY, $env);
            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function googleAnalyticsConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.analyticControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'MEASUREMENT_ID' => 'required|min:3',
                'analytic_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->MEASUREMENT_ID = $purifiedData->MEASUREMENT_ID;
            $basicControl->analytic_status = $purifiedData->analytic_status;
            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function clearLogs($model) {
        $cutoffDate = Carbon::now()->subMonths(2);
        $recordCount = $model::where('created_at', '<', $cutoffDate)->count();
        if ($recordCount > 0) {
            $model::where('created_at', '<', $cutoffDate)->delete();
            return response()->json(['success' => 'Xóa thành công.']);
        } else {
            return response()->json(['error' => 'Không có dữ liệu.']);
        }
    }

    public function clearUserLogs() {
        return $this->clearLogs(UserLogin::class);
    }

     public function clearRedirectLogs() {
         return $this->clearLogs(RedirectDetail::class);
     }

    public function help() {

        $user = Auth::guard('admin')->user();
        if($user){
            return view('admin.help');
        }else{
            $this->theme = template();
            $this->theme .='user.';
            return view($this->theme . 'help');
        }

    }


}
