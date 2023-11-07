<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Domain;
use App\Models\Redirect;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Stevebauman\Purify\Facades\Purify;
use Torann\GeoIP\Facades\GeoIP;

class RedirectController extends Controller
{

    public function __construct()
    {
        $this->theme = template();
//        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->theme .='user.';
            return $next($request);
        })->except(['show_direct','redirect301']);
    }

    //==================Admin===================


    public function index()
    {
        $data['title'] = 'Redirect';
        return view('admin.redirects.index', $data);
    }

    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $search_arr = $request->get('search');


        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $searchValue = $search_arr['value']; // Search value


        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc



        $recordsQuery = Redirect::with(['getDomains', 'getUser']);

        $recordsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('redirect_url', 'like', $searchTerm)
                    ->orwhere('redirect_name', 'like', $searchTerm);
            })->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereIN('user_id', array_map('intval',$request->user_id));
            })->when(isset($request->domain_id), function ($query) use ($request) {
                $query->whereHas('getDomains', function ($query) use ($request) {
                    return $query->whereIn('id',  $request->domain_id );
                });
            });

        $totalRecordswithFilter = $recordsQuery->count();

        $records = $recordsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Redirect::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="'.route('admin.redirect.edit',['id'=> $record->id]).'" class="btn"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteRedirect"><i class="fa fa-trash text-danger"></i></a>';
            $btn .= ' <a href="'.route('admin.redirect.show',['id'=> $record->id]).'" class="btn"><i class="fa fa-eye text-primary"></i></a>';

            $domains = '';
            foreach ($record->getDomains as $domain){
                $domains .= '<span class="badge badge-pill badge-secondary m-1 font-16">'.$domain->domain_web.'</span><i class="fa fa-copy copyButtonName" data-full_url="'.$domain->domain_web.'/'.$record->redirect_url.'"></i>';
            }
            $is_webview = '';
            if($record->is_webview == 1){
                $is_webview = ' <span class="badge  badge-pill  badge-primary">Webview</span>';
            }

            $is_ads = '';
            if($record->is_ads == 1){
                $is_ads = ' <span class="badge  badge-pill  badge-warning">Ads</span>';
            }

            $data_arr[] = array(
                "id" => $record->id,
                "redirect_name" => '<h5 class="text-dark mb-0 font-16">'.$record->redirect_name.$is_webview.$is_ads.'</h5><span class="text-muted font-14">'.$record->redirect_url.'</span>',
                "user_id" => $record->getUser->fullname ? '<a href="'.route('admin.user.edit',['id'=>$record->getUser->id]).'" target="_blank"><h5 class="text-dark mb-0 font-16 font-medium">'.$record->getUser->fullname.'</h5><span class="text-muted font-14"><i class="fa fa-user mr-2"></i>'.$record->getUser->username.'</span></a>' : null,
                "domain_id" => $domains,
                "is_devices" => $record->is_devices == 1 ? '<span class="badge  badge-pill  badge-success">Devices</span>' : '<span class="badge  badge-pill  badge-danger">Country</span>',
                "exp_date_at" => $record->exp_date_at,
                "action"=> $btn,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function create()
    {
        $data['title'] = 'Add Redirect';
        $data['countries'] = Country::all();
        return view('admin.redirects.add-redirect',$data);
    }

    public function store(Request $request){
        $redirectData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'redirect_url' => 'required|unique:redirects,redirect_url',
            'devices_value.*' => 'nullable|url',
            'country_value.url.*' => 'nullable|url',
            'redirect_url_block' => 'nullable|url',
        ];

        $message = [
            'devices_value.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'country_value.url.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'redirect_url_block.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
        ];

        $attributes = [
            'devices_value.*' => 'Url thiết bị',
            'country_value.*' => 'Url quốc gia',
            'redirect_url_block' => 'Url Block',
        ];

        $validator = Validator::make($request->all(),$rules,$message,$attributes);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $country_value = transformArray($request->country_value,'iso_code','url');

        $result = new Redirect();
        $result->user_id = (int)$redirectData['user_id'];
        $result->redirect_name = $redirectData['redirect_name'];
        $result->redirect_url = $redirectData['redirect_url'];
        $result->redirect_url_block = $redirectData['redirect_url_block'];
        $result->domain_id = array_map('intval',$redirectData['domain_id']);
        $result->redirect_html = $request->redirect_html;
        $result->is_devices = $request['isDevices_status'] ;
        $result->country_value = json_encode($country_value) ;
        $result->devices_value = json_encode($request->devices_value);
        $result->exp_date_at = $request->exp_date_at;
        $result->is_webview = $request['isWebview_status'] ;
        $result->is_ads = $request['isAds_status'];
        $result->manage_ads = json_encode($request->manage_ads);
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);


    }

    public function edit($id)
    {
        if(!adminAccessRoute(config('role.redirect_management.access.edit'))){
            abort(403);
        }
        $data['title'] = 'Edit Redirect';
        $data['countries'] = Country::all();
        $data['redirect'] = Redirect::find($id)->load('getUser');
        return view('admin.redirects.edit-redirect',$data);
    }

    public function show($id)
    {
        $data['title'] = 'Show Redirect';
        $data['redirect'] = Redirect::find($id)->load('getUser');
        return view('admin.redirects.show-redirect',$data);
    }

    public function update(Request $request)
    {
        if(!adminAccessRoute(config('role.redirect_management.access.edit'))){
            abort(403);
        }
        $redirectData = Purify::clean($request->except('_token', '_method'));
        $id = $request->redirect_id;
        $rules = [
            'redirect_url' => 'required|unique:redirects,redirect_url,'.$id,
            'devices_value.*' => 'nullable|url',
            'country_value.url.*' => 'nullable|url',
            'redirect_url_block' => 'nullable|url',
        ];

        $attributes = [
            'devices_value.*' => 'Url thiết bị',
            'country_value.*' => 'Url quốc gia',
            'redirect_url_block' => 'Url Block',
        ];

        $message = [
            'devices_value.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'country_value.url.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'redirect_url_block.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
        ];

        $validator = Validator::make($request->all(),$rules,$message,$attributes);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $country_value = transformArray($request->country_value,'iso_code','url');

        $result = Redirect::findOrFail($id);
        $result->user_id = (int)$redirectData['user_id'];
        $result->redirect_name = $redirectData['redirect_name'];
        $result->redirect_url = $redirectData['redirect_url'];
        $result->redirect_url_block = $redirectData['redirect_url_block'];
        $result->domain_id = array_map('intval',$redirectData['domain_id']);
        $result->redirect_html = $request->redirect_html;
        $result->is_devices = $redirectData['isDevices_status'] ;
        $result->country_value = json_encode($country_value) ;
        $result->devices_value = json_encode($request->devices_value);
        $result->exp_date_at = $request->exp_date_at;
        $result->is_webview = $request['isWebview_status'];
        $result->is_ads = $request['isAds_status'];
        $result->manage_ads = json_encode($request->manage_ads);
        $result->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if(!adminAccessRoute(config('role.redirect_management.access.delete'))){
            return response()->json(['errors'=>'Delete Successfully.'],403);
        }
        $id = $request->id;
        $redirect = Redirect::find($id);
        $redirect->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    //==================User===================

    public function indexUser()
    {
        $data['title'] = 'Redirect';
        $data['user'] = $this->user;
        return view($this->theme.'.redirect.index', $data);
    }

    public function getIndexUser(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        $redirectQuery = Redirect::with(['getDomains']);

        $redirectQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('redirect_name', 'like', $searchTerm)
                    ->orwhere('redirect_url', 'like', $searchTerm);
            })
            ->where('user_id', $this->user->id);

        $totalRecordswithFilter = $redirectQuery->count();

        $records = $redirectQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Redirect::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="'.route('user.redirect_user.edit',['id'=> $record->id]).'" class="btn"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="'.route('user.redirect_user.show',['id'=> $record->id]).'" class="btn"><i class="fa fa-eye text-primary"></i></a>';

            $domains = '';
            foreach ($record->getDomains as $domain){
                $domains .= '<span class="badge badge-pill badge-secondary m-1 font-16">'.$domain->domain_web.'</span><i class="fa fa-copy copyButtonName" data-full_url="'.$domain->domain_web.'/'.$record->redirect_url.'"></i>';
            }
            $is_webview = '';
            if($record->is_webview == 1){
                $is_webview = ' <span class="badge  badge-pill  badge-primary">Webview</span>';
            }
            $is_ads = '';
            if($record->is_ads == 1){
                $is_ads = ' <span class="badge  badge-pill  badge-warning">Ads</span>';
            }
            $data_arr[] = array(
                "id" => $record->id,
                "redirect_name" => '<h5 class="text-dark mb-0 font-16">'.$record->redirect_name.$is_webview.$is_ads.'</h5><span class="text-muted font-14">'.$record->redirect_url.'</span>',
                "user_id" => $record->getUser->fullname ?? null,
                "domain_id" => $domains,
                "is_devices" => $record->is_devices == 1 ? '<span class="badge  badge-pill  badge-success">Devices</span>' : '<span class="badge  badge-pill  badge-danger">Country</span>',
                "exp_date_at" => $record->exp_date_at,
                "action"=> $btn,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function createUser()
    {
        $data['title'] = 'Add Redirect';
        $data['countries'] = Country::all();
        $data['user'] = $this->user;
        return view($this->theme.'.redirect.add-redirect',$data);
    }

    public function storeUser(Request $request){
        if($this->user->limit_redirect <= $this->user->getRedirects->count()){
            return response()->json([
                'errors'=>['Liên hệ Admin để thêm mới Domain'],
            ]);
        }
        $redirectData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'redirect_url' => 'required|unique:redirects,redirect_url',
            'devices_value.*' => 'nullable|url',
            'country_value.url.*' => 'nullable|url',
            'redirect_url_block' => 'nullable|url',
        ];

        $message = [
            'devices_value.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'country_value.url.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'redirect_url_block.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
        ];

        $attributes = [
            'devices_value.*' => 'Url thiết bị',
            'country_value.*' => 'Url quốc gia',
            'redirect_url_block' => 'Url Block',
        ];

        $validator = Validator::make($request->all(),$rules,$message,$attributes);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $country_value = transformArray($request->country_value,'iso_code','url');

        $result = new Redirect();
        $result->user_id = (int)$this->user->id;
        $result->redirect_name = $redirectData['redirect_name'];
        $result->redirect_url = $redirectData['redirect_url'];
        $result->redirect_url_block = $redirectData['redirect_url_block'];
        $result->domain_id = array_map('intval',$redirectData['domain_id']);
        $result->redirect_html = $request->redirect_html;
        $result->is_devices = $request['isDevices_status'] ;
        $result->country_value = json_encode($country_value) ;
        $result->devices_value = json_encode($request->devices_value);
        $result->exp_date_at = $request->exp_date_at;
        $result->is_webview = $request['isWebview_status'];
        $result->is_ads = $request['isAds_status'];
        $result->manage_ads = json_encode($request->manage_ads);
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function editUser($id)
    {
        $data['title'] = 'Edit Redirect';
        $data['users'] = User::where('status',1)->get();
        $data['countries'] = Country::all();
        $data['domains'] = Domain::where('is_publish',1)->get();
        $redirect = Redirect::find($id);
        if($redirect->user_id != $this->user->id){
            abort(403);
        }
        $data['redirect'] = $redirect->load('getUser');
        return view($this->theme.'.redirect.edit-redirect',$data);
    }

    public function showUser($id)
    {
        $data['title'] = 'Show Redirect';
        $redirect = Redirect::find($id);
        if($redirect->user_id != $this->user->id){
            abort(403);
        }
        $data['redirect'] = $redirect->load('getUser');
        return view($this->theme.'.redirect.show-redirect',$data);
    }

    public function updateUser(Request $request)
    {

        $redirectData = Purify::clean($request->except('_token', '_method'));
        $id = $request->redirect_id;
        $rules = [
            'redirect_url' => 'required|unique:redirects,redirect_url,'.$id,
            'devices_value.*' => 'nullable|url',
            'country_value.url.*' => 'nullable|url',
            'redirect_url_block' => 'nullable|url',
        ];

        $attributes = [
            'devices_value.*' => 'Url thiết bị',
            'country_value.*' => 'Url quốc gia',
            'redirect_url_block' => 'Url Block',
        ];

        $message = [
            'devices_value.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'country_value.url.*.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
            'redirect_url_block.url' => 'Giá trị :attribute không phải là một URL hợp lệ.',
        ];

        $validator = Validator::make($request->all(),$rules,$message,$attributes);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $country_value = transformArray($request->country_value,'iso_code','url');

        $result = Redirect::findOrFail($id);
        $result->user_id = (int)$this->user->id;
        $result->redirect_name = $redirectData['redirect_name'];
        $result->redirect_url = $redirectData['redirect_url'];
        $result->redirect_url_block = $redirectData['redirect_url_block'];
        $result->domain_id = array_map('intval',$redirectData['domain_id']);
        $result->redirect_html = $request->redirect_html;
        $result->is_devices = $redirectData['isDevices_status'] ;
        $result->country_value = json_encode($country_value) ;
        $result->devices_value = json_encode($request->devices_value);
        $result->exp_date_at = $request->exp_date_at;
        $result->is_webview = $request['isWebview_status'];
        $result->is_ads = $request['isAds_status'];
        $result->manage_ads = json_encode($request->manage_ads);
        $result->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    //==========================================

    public function show_direct(Request $request,GeoIP $geoIP)
    {
        $ip_address = getIp();
        $ip_prefix = getIpPrefix($ip_address);
        $value = $request->path();

        $redirect_link = $this->getRedirectLink($value);

        $location = $geoIP::getLocation($ip_address);

        $domain = array_map('strtolower', $redirect_link->getDomains->pluck('domain_web', 'id')->toArray());
        $domain_check = strtolower($request->server('SERVER_NAME'));

        $key = array_search($domain_check, $domain);

        if(isset($_GET['webview'])){
            return $this->handleWebView($redirect_link, $domain_check);
        }

        if (!$key) {
            abort(404);
        }

        // Check if IP is blocked
        $blockIpClient = checkBlockIpClient($redirect_link->getUser, $ip_prefix);

        $redirect_detail = $this->getRedirectDetail($redirect_link, $location, $ip_address, $ip_prefix, $key, $blockIpClient['block']);

        if ($blockIpClient['block']) {
            $redirect_url = $redirect_link->redirect_url_block ?: false;
        } else {
            $redirect_url = $this->getRedirectUrl($redirect_link, $redirect_detail, $location);
        }
        // Update redirect details
        $this->updateRedirectDetails($redirect_link, $redirect_detail, $redirect_url);

        // Redirect or show HTML
        return $this->performRedirectOrShowHtml($redirect_link, $redirect_url);
    }
    protected function getRedirectLink($value)
    {
        return Redirect::where('redirect_url', $value)
            ->where('exp_date_at','>',date('Y-m-d',time()))
            ->firstorFail();
    }

    private function handleWebView($redirect_link, $domain_check)
    {
        $ads_ids = [
            'app_id',
            'open_ads_id',
            'banner_ads_id',
            'interstitial_ads_id',
            'native_ads_id',
            'rewarded_ads_id'
        ];

        $manage_ads = json_decode($redirect_link->manage_ads, true) ?? [];

        $result = array_merge([
            "ads_status"=> $redirect_link->is_ads == 1,
            "fw_status"=> $redirect_link->is_webview == 1,
            "fw_url"=> encryptAES($domain_check.'/'.$redirect_link->redirect_url)
        ], array_fill_keys($ads_ids, null), array_filter($manage_ads));

        return response()->json($result);
    }

    protected function getRedirectDetail($redirect_link, $location,string $ip_address, string $ip_prefix, int $key, $is_block)
    {
        $agent = new Agent();
        return [
            'redirect_id' => $redirect_link->id,
            'domain_id' => $key,
            'url_full' => strtolower(\request()->server('SERVER_NAME')).'/'.$redirect_link->redirect_url,
            'device_name_full' => $agent->getUserAgent(),
            'device_name' => $agent->device(),
            'browser' => $agent->browser(),
            'platform_name' => $agent->platform() !=0 ? $agent->platform() : 'Other' ,
            'ip_address' => $ip_address,
            'ip_prefix' => $ip_prefix,
            'is_block' => $is_block ? 1 : 0,
            'is_robot' => (int) ($agent->isRobot()),
            'robot' => (string) ($agent->robot()),
            'country' => (string) ($location['country']),
            'created_at' => Carbon::now()->startOfDay()
        ];
    }

    protected function getRedirectUrl($redirect_link, array &$redirect_detail, &$location)
    {
        if ($isDevices = (int) ($redirect_link->is_devices)) {
            // Redirect based on device
            return json_decode($redirect_link->devices_value, true)[$redirect_detail['platform_name']] ?? false;
        } else {
            // Redirect based on country
            return json_decode($redirect_link->country_value, true)[$location['iso_code']] ?? json_decode($redirect_link->country_value, true)['other'] ?? false;
        }
    }

    protected function updateRedirectDetails($redirect_link, array &$redirect_detail,$redirect_url)
    {
        $redirect_detail['redirect_url'] = $redirect_url;
        return $redirect_link->redirect_details()->updateOrCreate($redirect_detail)->increment('count', 1);
    }

    protected function performRedirectOrShowHtml($redirect_link, string &$redirect_url)
    {
        if ($redirect_url) {
            return $this->redirect301($redirect_url);
        } else {
            return response($redirect_link->redirect_html);
        }
    }

    public function redirect301($redirect_url)
    {
        return redirect($redirect_url, 301)->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

}
