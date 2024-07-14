<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Domain;
use App\Models\DomainLoginLogs;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class DomainController extends Controller
{
    public function index()
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $data['title'] = 'Domain';
        return view('admin.domain.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $start = $request->get("start");
        $length = $request->input('length');
        $page = $request->input('page');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $domainsQuery = Domain::withCount('categories');
        $domainsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('domain_web', 'like', $searchTerm)
                    ->orWhere('domain_name', 'like', $searchTerm)
                    ->orWhere('domain_project', 'like', $searchTerm);
            });

        $totalRecordswithFilter = $domainsQuery->count();

        $records = $domainsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

         if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Domain::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn editDomain"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteDomain"><i class="fa fa-trash text-danger"></i></a>';
            $btn .= ' <a href="'.route('admin.domain.categories',['id'=>$record->id]).'" class="btn"><i class="fa fa-cog text-secondary"></i></a>';

            $status = $record->is_publish == 1 ? "success" :  "danger" ;
            $domain_project = '<span class="badge badge-pill badge-primary m-1 font-16">'.$record->domain_project.'</span><a href="javascript:void(0)" data-id="'.$record->id.'" class="btn getInfoAIO"><i class="fa fa-download text-success"></i></a>';

            $domainImage = $record->domain_logo;
            $imagePath = $domainImage
                ? asset('storage/logos/'.$record->id.'/'. $domainImage)
                : asset('storage/default.png');

            $image = '<a class="image-popup-no-margins" href="'.$imagePath.'"><img src="' . $imagePath . '" height="55"></a>';


            $data_arr[] = array(
                "id" => $record->id,
                "domain_logo" => $image,
                "domain_web" => '<h5 class="text-dark mb-0 font-16 font-medium">'.$record->domain_name.'</h5><span class="text-muted font-14"><i class="fa fa-globe mr-2"></i>'.$record->domain_web.'</span>',
                "domain_project" => $domain_project,
                "categories_count" => $record->categories_count,
                "is_publish" => '<span class="badge  badge-pill  badge-'.$status.'">'.$status.'</span>',
                "action"=> $btn,
            );
        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('admin.domain.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $domainData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'domain_web' => 'required|unique:domains,domain_web',
            'domain_name' => 'required',
            'domain_project' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $result = new Domain();
        $result->domain_web = trim($domainData['domain_web']);
        $result->domain_name = $domainData['domain_name'];
        $result->domain_project = $domainData['domain_project'];
        $result->is_publish = $domainData['domain_status'] == 1 ? 1 : 0 ;
        $result->is_ads = $domainData['isAds_status'] == 1 ? 1 : 0 ;
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);

    }

    public function edit($id)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $domain = Domain::find($id);
        return response()->json($domain);
    }

    public function update(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $domainData = Purify::clean($request->except('_token', '_method'));
        $id = $request->domain_id;
        $rules = [
            'domain_web' => 'required|unique:domains,domain_web,'.$id,
            'domain_name' => 'required',
            'domain_project' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = Domain::findOrFail($id);
        $result->domain_web = trim($domainData['domain_web']);
        $result->domain_name = $domainData['domain_name'];
        $result->domain_project = $domainData['domain_project'];
        $result->is_publish = $domainData['domain_status'] == 1 ? 1 : 0 ;
        $result->is_ads = $domainData['isAds_status'] == 1 ? 1 : 0 ;
        $result->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.domain.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $domain = Domain::find($id);
        $domain->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    //============================= ADS =============================

    public function manageAds($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Manage ADS';
        $domain = Domain::find($domain_id);
        return view('admin.domain.manageAds', compact('domain','domain_id','title'));
    }

    public function updateAds(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $adsData = Purify::clean($request->except('_token', '_method'));
        $domain_id = $request->domain_id;
        $domain = Domain::find($domain_id);
        $domain->manage_ads = json_encode($adsData['manage_ads']);
        $domain->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    //============================= HOME =============================

    public function manageHome($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Manage Home';
        $domain = Domain::find($domain_id);
        return view('admin.domain.manageHome', compact('domain','domain_id','title'));
    }

    public function updateHome(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $homeData = Purify::clean($request->except('_token', '_method'));
        $domain_id = $request->domain_id;
        $domain = Domain::find($domain_id);
        $domain->manage_home = json_encode($homeData['manage_home']);
        $domain->direct_link = $homeData['direct_link'];
        $domain->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    //============================= CONFIG =============================

    public function config($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Config';
        $domain = Domain::find($domain_id);
        return view('admin.domain.config', compact('domain','domain_id','title'));
    }

    public function updateConfig(Request $request)
    {
        dd(1);
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $homeData = Purify::clean($request->except('_token', '_method'));
        $domain_id = $request->domain_id;
        $domain = Domain::find($domain_id);
        $domain->manage_home = json_encode($homeData['manage_home']);
        $domain->direct_link = $homeData['direct_link'];
        $domain->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    //============================= CATEGORY =============================

    public function categories($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Categories';
        $domain = Domain::find($domain_id);
        return view('admin.domain.categories', compact('domain','domain_id','title'));
    }

    public function getDomainCategories(Request $request)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $start = $request->get("start");
        $length = $request->input('length');
        $page = $request->input('page');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $categoriesQuery = Categories::withCount('wallpapers','ringtones','musics');
        $categoriesQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('category_name', 'like', $searchTerm);
            })->where('domain_id', $request->domain_id);

        $totalRecordswithFilter = $categoriesQuery->count();

        $records = $categoriesQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Categories::select('count(*) as allcount')->where('domain_id', $request->domain_id)->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn editCategory"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteCategory"><i class="fa fa-trash text-danger"></i></a>';

            $status = $record->category_checked_ip == 1 ? "success" :  "danger" ;

            $tags = '';
            foreach ($record->tags as $tag){
                $tags .= '<span class="badge badge-pill badge-secondary m-1 font-16">'.$tag->tag_name.'</span>';
            }

            $categoryImage = $record->category_image;
            $imagePath = $categoryImage
                ? asset('storage/categories/'. $categoryImage)
                : asset('storage/defaultCate.png');

            $image = '<a class="image-popup-no-margins" href="'.$imagePath.'"><img src="' . $imagePath . '" height="55"></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "category_image" => $image,
                "category_name" => $record->category_name,
                "category_tags" => $tags,
                "wallpapers_count" => $record->wallpapers_count,
                "ringtones_count" => $record->ringtones_count,
                "musics_count" => $record->musics_count,
                "category_checked_ip" => '<span class="badge  badge-pill  badge-'.$status.'">'.$status.'</span>',
                "action"=> $btn,
            );
        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    public function storeCategory(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $categoryData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'category_name' => 'required',
            'category_tags' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = new Categories();
        $result->domain_id = $categoryData['domain_id'];
        $result->category_name = trim($categoryData['category_name']);
        $result->category_view_count = 1000 ;
        $result->category_checked_ip = $categoryData['category_status'] == 1 ? 1 : 0 ;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $categoryData['domain_id'] . '/'.Str::uuid(). '.' . $file->getClientOriginalExtension(); // . $categoryData['domain_id'] . '/'
            $path = storage_path('app/public/categories/');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $image = Image::make($file)->encode($file->getClientOriginalExtension(), 80);
            $image->save($path . '/' . $fileName);
            $result->category_image = $fileName;
        }
        $result->save();
        $result->tags()->attach($categoryData['category_tags']);
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function editCategory(Request $request)
    {

        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $id = $request->id;
        $category = Categories::find($id);
        return response()->json($category->load('tags'));
    }

    public function updateCategory(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $categoryData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'category_name' => 'required',
            'category_tags' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = Categories::findOrFail($request->category_id);
        $result->category_name = trim($categoryData['category_name']);
        $result->category_checked_ip = $categoryData['category_status'] == 1 ? 1 : 0 ;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName =  $categoryData['domain_id'] . '/'.Str::uuid(). '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/public/categories/');
            $category_image_old = $result->category_image;
            if ($category_image_old != "") {
                unlink($path.'/'.$category_image_old);
            }
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $image = Image::make($file)->encode($file->getClientOriginalExtension(), 80);
            $image->save($path . '/' . $fileName);

            $result->category_image = $fileName;
        }
        $result->save();
        $result->tags()->sync($categoryData['category_tags']);
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    public function deleteCategory(Request $request)
    {
        if(!Auth::user()->can('admin.domain.edit')){
            return response()->json([
                'errors'=> ['Tài khoản không có quyền.'],
            ]);
        }
        $id = $request->id;
        $category = Categories::find($id);
        $category->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    //============================= WALLPAPERS =============================

    public function wallpapers ($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Wallpapers ';
        $domain = Domain::find($domain_id);
        return view('admin.domain.wallpapers', compact('domain','domain_id','title'));
    }

    public function getDomainWallpapers(Request $request,$id)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $page = $request->input('page');
        $length = $request->input('length');
        $search = $request->input('search');
        $domain = Domain::find($id);
        $wallpapersQuery = $domain->wallpapers()
            ->when(isset($search) && $search != 'null', function ($query) use ($search) {
                $query->where('wallpaper_name','like','%' . $search . '%');
            })
            ->paginate($length, ['*'], 'page', $page);
        return json_encode($wallpapersQuery);
    }

    //============================= RINGTONES =============================

    public function ringtones($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Ringtones';
        $domain = Domain::find($domain_id);
        return view('admin.domain.ringtones', compact('domain','domain_id','title'));
    }

    public function getDomainRingtones(Request $request,$id)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $start = $request->get("start");
        $length = $request->input('length');
        $page = $request->input('page');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $domain = Domain::find($id);

        $ringtonesQuery = $domain->ringtones()->with('tags');
        $ringtonesQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('ringtone_name', 'like', $searchTerm);
            });

        $totalRecordswithFilter = $ringtonesQuery->count();

        $records = $ringtonesQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = $ringtonesQuery->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteRingtone"><i class="fa fa-trash text-danger"></i></a>';

            $tags = '';
            foreach ($record->tags as $tag){
                $tags .= '<span class="badge badge-pill badge-secondary m-1 font-16 text-truncate ">'.$tag->tag_name.'
                            <i class="fa fa-times close-icon deleteRingtoneTag" data-ringtone="'.$record->id.'" data-tag="'.$tag->id.'"></i>
                        </span>';
            }
            $data_arr[] = array(
                "id" => $record->id,
                "ringtone_file" => '<audio class="playback" src='.url('/storage/ringtones').'/'.$record->ringtone_file.' controls="controls" preload="none"></audio>',
                "ringtone_name" => $record->ringtone_name,
                "ringtone_tags" => $tags,
                "action"=> $btn,
            );
        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    //============================= MUSICS =============================

    public function musics($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Musics';
        $domain = Domain::find($domain_id);
        return view('admin.domain.musics', compact('domain','domain_id','title'));
    }

    public function getDomainMusics(Request $request,$id)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $start = $request->get("start");
        $length = $request->input('length');
        $page = $request->input('page');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $domain = Domain::find($id);

        $musicsQuery = $domain->musics()->with('tags');
        $musicsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('music_id_ytb', 'like', $searchTerm);
            });

        $totalRecordswithFilter = $musicsQuery->count();

        $records = $musicsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = $musicsQuery->count();
        }


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteMusic"><i class="fa fa-trash text-danger"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-music_id_ytb="'.$record->music_id_ytb.'" class="btn updateMusic"><i class="fa fa-retweet text-dark"></i></a>';
            $tags = '';
            foreach ($record->tags as $tag){
                $tags .= '<span class="badge badge-pill badge-secondary m-1 font-16 text-truncate ">'.$tag->tag_name.'
                            <i class="fa fa-times close-icon deleteMusicTag" data-music="'.$record->id.'" data-tag="'.$tag->id.'"></i>
                        </span>';
            }

            $data_arr[] = array(
                "id" => $record->id,
                "music_thumb" => '<img src="' . $record->music_thumb . '" height="55">',
                "music_url" => '<audio class="playback" src='.$record->music_url.'  controls="controls" preload="none"></audio>',
                "music_tags" => $tags,
                "action"=> $btn,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    //============================= LOGS =============================

    public function logs($id)
    {
        if(!Auth::user()->can('admin.domain')){
            abort(403);
        }
        $domain_id = $id;
        $title = 'Logs';
        $domain = Domain::find($domain_id);
        return view('admin.domain.logs', compact('domain','domain_id','title'));
    }

    public function getDomainLogs(Request $request,$id)
    {
        if(!Auth::user()->can('admin.domain')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $start = $request->get("start");
        $length = $request->input('length');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $domainLogsQuery = DomainLoginLogs::query();
        $domainLogsQuery->where('domain_id',$id)
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('ip_address', 'like', $searchTerm)
                    ->orwhere('device_name', 'like', $searchTerm)
                    ->orwhere('platform_name', 'like', $searchTerm)
                    ->orwhere('country', 'like', $searchTerm)
                    ->orwhere('browser', 'like', $searchTerm);
            });

        $totalRecordswithFilter = $domainLogsQuery->count();

        $records = $domainLogsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = $domainLogsQuery->count();
        }
        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "ip_address" => $record->ip_address,
                "device_name" => $record->device_name,
                "platform_name" => $record->platform_name,
                "country" => $record->country,
                "count" => $record->count,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    //============================= LOGS =============================


    public function getInfoAIO($id){
        try {
            $domain = Domain::findorFail($id);
            $url = "https://aio.vietmmo.net/api/project-aio/".$domain->domain_project;
            $dataGet = $this->CURL($url);
            if($dataGet['msg'] == 'success'){
                $data = $dataGet['data'];
                $key = array_search('CHPLAY', array_column($data['markets'], 'market_name'));

                if($key || $key == 0){
                    $market_get = $data['markets'][$key];
                    $ads_get = json_decode($market_get['pivot']['ads'],true);
                    $package = $market_get['pivot']['package'];
                    $link = $market_get['pivot']['app_link'];
                    $site_app_name = $market_get['pivot']['app_name_x'];
                    $ads = [
                        "app_id" => $ads_get['ads_id'],
                        "banner_ads_id" => $ads_get['ads_banner'],
                        "interstitial_ads_id" => $ads_get['ads_inter'],
                        "rewarded_ads_id" => $ads_get['ads_reward'],
                        "native_ads_id" => $ads_get['ads_native'],
                        "open_ads_id" => $ads_get['ads_open'],
                    ];

                    $this->downloadImage('https://aio.vietmmo.net/storage/projects/' . $data['da']['ma_da'] . '/' . $data['projectname'] . '/' . $data['logo'], $domain);

                    $update = [
                        'domain_name' => $data['title_app'],
                        'domain_app_name' => $site_app_name,
                        'direct_link' => $link,
                        'domain_logo' => $data['logo'],
                        'domain_package' => $package,
                        'manage_ads' => json_encode($ads),
                    ];
                    $domain->update($update);
                    return response()->json([
                        'success'=>'Get thành công',
                        'domain'=> $domain]);
                }else{
                    return response()->json(['error'=>'Kiểm tra Market trên AIO']);
                }
            }else{
                return response()->json(['error'=>'Lỗi, kiểm tra tên project']);
            }

        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '---getAIO--' . $exception->getLine());
        }

    }

    public function CURL($url){
        $dataArr = [
            'Content-Type'=>'application/json',
        ];
        $response = Http::withHeaders($dataArr)->get($url);
        if ($response->successful()) {
            $data = $response->json();
        }
        return $data;
    }

    public function downloadImage($imageUrl,$domain)
    {
        $client = new Client();
        $filename = basename($imageUrl);
        $storagePath = storage_path('app/public/logos/'.$domain->id);
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $response = $client->get($imageUrl);
        $imagePath = $storagePath . '/' . $filename;
        file_put_contents($imagePath, $response->getBody()->getContents());
        return response()->json(['message' => 'Hình ảnh đã được tải xuống thành công']);
    }

    public function multipleUpdate(Request $request){
        ini_set('max_execution_time', 1200);
        try {
            $data = array_map('trim', explode("\r\n", $request->projectAIO));

            $domainsInstance = new Domain();
            $index = 'id';

            Domain::whereIn('domain_project', $data)->chunk(10, function ($domains) use ($request, $domainsInstance, $index) {
                $valueInsert = [];

                foreach ($domains as $domain) {
                    $arr['id'] = $domain->id;
                    $arr['is_publish'] = $request->isPublish_statusMultiple == 1 ? 1 : 0;
                    $arr['is_ads'] = $request->isAds_statusMultiple == 1 ? 1 : 0;

                    if ($request->getInfoAIOMultiple == 1) {
                        $this->getInfoAIO($domain->id);
                    }

                    $valueInsert[] = $arr;
                }

                batch()->update($domainsInstance, $valueInsert, $index);
            });

            return response()->json(['success' => 'Thành công']);
        } catch (\Exception $exception) {
            Log::error('Message:updateMultiple---' . $exception->getMessage() . '--' . $exception->getLine());
            return response()->json(['error' => 'Lỗi ']);
        }
    }


}
