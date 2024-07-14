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
        $start = $request->get("start");
        $length = $request->input('length');
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
            ->skip($start)
            ->take($length)
            ->get();
//            ->paginate($rowperpage, ['*'], 'page', $page);

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
                "music_expire" => $record->music_expire < time(),
                "music_thumb" => '<a target="_blank" href="https://www.youtube.com/watch?v='.$record->music_id_ytb.'"><img src="' . $record->music_thumb . '" height="55"></a>',
                "music_url" => '<audio class="playback" src='.$record->music_url.'  controls="controls" preload="none"></audio>',
                "music_tags" => $tags,
                "music_title" => '<p class="text-dark mb-0 font-16 font-medium">Length: '.gmdate("H:i:s", $record->music_lengthSeconds).'</p><span class="text-muted font-14">'.$record->music_title.'</span>',
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
        $music = Musics::where('music_id_ytb',$music_id_ytb)->firstorFail();
        if($music->music_expire < time()){
            try {
                $youtube = new YouTubeDownloader();
                $downloadOptions = $youtube->getDownloadLinks($music_id_ytb);
                $info = $downloadOptions->getInfo();
                $music->update([
                    'music_url' => $downloadOptions->getFirstCombinedFormat()->url,
                    'music_thumb' =>   $youtube->getThumbnails($music_id_ytb)['default'],
                    'music_title' => $info->title,
                    'music_expire' =>$this->getValueFromUrl($downloadOptions->getFirstCombinedFormat()->url,'expire'),
                    'music_lengthSeconds' =>$info->durationSeconds,
                    'music_status' => 1,
                ]);

            }catch (\Exception $exception) {
                $music->update([
                    'music_status'=> 0,
                    'music_expire' => time()+21600,
                    ]);
            }
        }
        return response()->json([
            'success'=> 'Update Successfully.',
            'music'=> $music
        ]);
    }

    public function redirectLinkYTB(Request $request){
        $response = $this->getInfo($request);
        $musicInfo = $response->getData();
        return redirect($musicInfo->music->music_url);
    }

    public function updateMusics(Request $request, YouTubeDownloader $youtube){
        $order = $request->input('order', 'id');
        $limit = $request->input('limit', 5);
        $time = $request->input('time', 2);
        $status = $request->input('status', 1);

        try {
            $musics = Musics::latest()
                ->where('music_status','<>',$status)
                ->where(function ($query) {
                    $query->where('music_url',null)
                        ->orWhere('music_expire', '<', time());
                })
                ->limit($limit)
                ->get();

            if ($musics->isEmpty()) {
                echo "No music found. Stopping execution.";
                return;
            }

            $updateMusic = [];
            $result = '';
            foreach ($musics as $item){
                $music_id_ytb = $item->music_id_ytb;
                try {
                    $downloadOptions = $youtube->getDownloadLinks($music_id_ytb);
                    $info = $downloadOptions->getInfo();
                    $format = $downloadOptions->getFirstCombinedFormat();
                    $music_status = 1;
                    $updateMusic[] = [
                        'id' => $item->id,
                        'music_id_ytb' => $music_id_ytb,
                        'music_title' => $info->title,
                        'music_url' =>  $format->url,
                        'music_thumb' =>   $youtube->getThumbnails($music_id_ytb)['default'],
                        'music_expire' =>   $this->getValueFromUrl($format->url,'expire'),
                        'music_lengthSeconds' =>$info->durationSeconds,
                        'music_status' => $music_status,
                    ];
                }catch (\Exception $exception) {
                    $music_status = $item->music_status + 2;
                    if ($item->music_status > 6) {
                        $item->delete();
                    } else {
                        $updateMusic[] = [
                            'id' => $item->id,
                            'music_status' => $music_status,
                            'music_expire' => time()+21600,
                        ];
                    }
                }
                $result .= 'Processing: ' . $item->music_id_ytb .' - Status: '.$item->music_status.' -> '.$music_status. '<br>';
            }
            $batch = batch()->update(new Musics(), $updateMusic, 'id');
            echo "<pre>";
            print_r ($result);
            print_r ($batch);
            echo "</pre>";
            if($request->input('action') == 'auto'){
                echo '<META http-equiv="refresh" content="'.$time.';URL='.route('admin.musics.updateMusics').'?order='.$order.'&action=auto&time='.$time.'&limit='.$limit.'&status="'.$status.'>';
            }
        } catch (\Exception $exception) {
            echo "An error occurred: " . $exception->getMessage().'- Line: '.$exception->getLine() . "<br>";
            echo "Refreshing in 5 seconds...";
            echo '<META http-equiv="refresh" content="5;URL=' . route('admin.musics.updateMusics') . '?order='.$order.'&action=auto&time='.$time.'&limit='.$limit.'&status="'.$status.'">';
        }
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
