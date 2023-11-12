<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Musics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use YouTube\YouTubeDownloader;

class MusicsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth')->except('getInfo', 'getLinkYTB');

//        $this->middleware('auth')->except('getLinkYTB');
    }

    public function index()
    {
        if(!Auth::user()->can('admin.musics')){
            abort(403);
        }
        $data['title'] = 'Musics';
        return view('admin.musics.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.musics')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $draw = $request->input('draw');
        $rowperpage = $request->input('length');
        $page = $request->input('page');

        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $searchValue = $request->input('search')['value'];

        $musicsQuery = Musics::query();
        $musicsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('music_id_ytb', 'like', $searchTerm)
                    ->orWhere('music_title', 'like', $searchTerm)
                    ->orWhereHas('tags', function ($query) use ($searchTerm) {
                        return $query->where('tag_name', 'like', $searchTerm);
                    });
            });

        $totalRecordswithFilter = $musicsQuery->count();

        $records = $musicsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->paginate($rowperpage, ['*'], 'page', $page);

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Musics::select('count(*) as allcount')->count();
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

    public function store(Request $request)
    {
        if(!Auth::user()->can('admin.musics.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $musicData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'music_ids_ytb' => 'required',
            'music_tags' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $music_ids   = preg_split("/[|,]+/",$musicData['music_ids_ytb']);
        foreach ($music_ids as $music_id){
            $result = Musics::updateOrCreate([
                'music_id_ytb' => trim($music_id)
            ]);
            $result->tags()->attach($musicData['music_tags']);
        }
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.musics.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $music = Musics::find($id);
        $music->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    public function deleteTag(Request $request)
    {
        if(!Auth::user()->can('admin.musics.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $music_id = $request->music_id;
        $tag_id = $request->tag_id;
        $music = Musics::find($music_id);
        $music->tags()->detach([$tag_id]);
        return response()->json(['success' => 'Delete Successfully.']);
    }

    public function getInfo(Request $request){
        $music_id_ytb = trim($request->music_id_ytb);
        $youtube = new YouTubeDownloader();
        $downloadOptions = $youtube->getDownloadLinks($music_id_ytb);
        $info = $downloadOptions->getInfo();

        $music = Musics::where('music_id_ytb',$music_id_ytb)->firstorFail();
        if($music->music_expire < time()){
            try {
                $music->update([
                    'music_url' => $downloadOptions->getFirstCombinedFormat()->url,
                    'music_thumb' =>  $info->getThumbnail()[0]['url'],
                    'music_title' => $info->getTitle(),
                    'music_expire' =>$this->getValueFromUrl($downloadOptions->getFirstCombinedFormat()->url,'expire'),
                    'music_lengthSeconds' => $info->getLengthSeconds(),
                    'music_status' => 1,
                ]);

            }catch (\Exception $exception) {
                $music->update(['music_status'=> 0]);
            }
        }
        return response()->json([
            'success'=> 'Update Successfully.',
            'music'=> $music
        ]);
    }

    public function getLinkYTB(Request $request){
        dd(12);
        $response = $this->getInfo($request);
        $musicInfo = $response->getData();
        return $musicInfo->music->music_url;
    }

    public function updateMusics(YouTubeDownloader $youtube){
        Musics::latest()
            ->where('music_url',null )
            ->orWhere('music_expire','<', time())
            ->chunk(200, function ($musics) use ($youtube) {
            $updateMusic = [];

            foreach ($musics as $item){
                $music_id_ytb = $item->music_id_ytb;
                $downloadOptions = $youtube->getDownloadLinks($music_id_ytb);
                $info = $downloadOptions->getInfo();
                $updateMusic[] = [
                        'id' => $item->id,
                        'music_id_ytb' => $item->music_id_ytb,
                        'music_title' => $info->getTitle(),
                        'music_url' =>  $downloadOptions->getFirstCombinedFormat()->url,
                        'music_thumb' =>   $info->getThumbnail()[0]['url'],
                        'music_expire' =>   $this->getValueFromUrl($downloadOptions->getFirstCombinedFormat()->url,'expire'),
                        'music_lengthSeconds' => $info->getLengthSeconds(),
                        'music_status' => 1,
                    ];
            }
            $musicInstance = new Musics();
            $index = 'id';
            batch()->update($musicInstance, $updateMusic, $index);
            return response()->json([
                'success'=> 'Update Successfully.',
            ]);
        });
    }
    public function getValueFromUrl($url,$param){
        $pos = strpos($url, $param.'=');
        if ($pos !== false) {
            $expireValue = substr($url, $pos + 7);
            $ampPos = strpos($expireValue, "&");
            if ($ampPos !== false) {
                $expireValue = substr($expireValue, 0, $ampPos);
            }

            return $expireValue;
        } else {
            return null;
        }
    }
}
