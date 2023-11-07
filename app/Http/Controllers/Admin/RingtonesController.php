<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ringtones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RingtonesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->except('compareRingtones');
    }
    public function index()
    {
        if(!Auth::user()->can('admin.ringtones')){
            abort(403);
        }
        $data['title'] = 'Ringtones';
        return view('admin.ringtones.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.ringtones')){
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

        $ringtonesQuery = Ringtones::query();
        $ringtonesQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('ringtone_name', 'like', $searchTerm)
                    ->orWhereHas('tags', function ($query) use ($searchTerm) {
                        return $query->where('tag_name', 'like', $searchTerm);
                    });
            });

        $totalRecordswithFilter = $ringtonesQuery->count();

        $records = $ringtonesQuery
            ->orderBy($columnName, $columnSortOrder)
            ->paginate($rowperpage, ['*'], 'page', $page);

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = Ringtones::select('count(*) as allcount')->count();
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

    public function store(Request $request){
        if(!Auth::user()->can('admin.ringtones.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $request->validate([
            'ringtones_upload' => 'required|file|mimes:mp3|max:2048',
            'ringtones_tags' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('ringtones_upload');
            $originalFileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $monthYear = now()->format('FY');
            $path_ringtone = storage_path('app/public/ringtones/' . $monthYear . '/');
            createDirectory($path_ringtone);

            $filename = uniqid('ringtone_') . '.' . $extension;
            $path = $file->storeAs($monthYear, $filename, 'ringtones_file');

            $ringtoneData = [
                'ringtone_name' => pathinfo($originalFileName, PATHINFO_FILENAME),
                'ringtone_file' => $path, // Lưu đường dẫn tới tệp ringtone
                'ringtone_extension' => $extension,
                'ringtone_type' => $file->getMimeType(),
                'ringtone_hash' => md5_file(storage_path('app/public/ringtones/').$path),
            ];

            $ringtone = Ringtones::create($ringtoneData);
            $ringtone->tags()->attach($request->input('ringtones_tags'));
            DB::commit();

            return response()->json([
                'success' => 'Saved Successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'An error occurred while saving the ringtone: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.ringtones.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $ringtone = Ringtones::find($id);
        $ringtone->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    public function deleteTag(Request $request)
    {
        if(!Auth::user()->can('admin.ringtones.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $ringtone_id = $request->ringtone_id;
        $tag_id = $request->tag_id;
        $ringtone = Ringtones::find($ringtone_id);
        $ringtone->tags()->detach([$tag_id]);
        return response()->json(['success' => 'Delete Successfully.']);
    }

    public function compareRingtones(){
        $duplicateHashes = Ringtones::select('ringtone_hash')
            ->groupBy('ringtone_hash')
            ->havingRaw('COUNT(ringtone_hash) > 1')
            ->get();

        foreach ($duplicateHashes as $duplicateHash) {
            // Lấy tất cả các bản sao với cùng ringtone_hash
            $duplicates = Ringtones::where('ringtone_hash', $duplicateHash->ringtone_hash)
                ->orderBy('id', 'desc')
                ->skip(1)
                ->take(10)
                ->get();

            // Xóa các bản sao
            foreach ($duplicates as $duplicateRingtone) {
                $duplicateRingtone->delete();
            }
        }
    }

    public function md5Hash(){
        Ringtones::where('ringtone_hash',null )->chunk(200, function ($ringtones){
            $updateRingtone = [];
            foreach ($ringtones as $item){
                $pathRingtone      =   storage_path('app/public/ringtones/').$item->ringtone_file;
                if(file_exists($pathRingtone)){
                    $updateRingtone[] = [
                        'id' => $item->id,
                        'ringtone_file' => $item->ringtone_file,
                        'ringtone_hash' => md5_file($pathRingtone),
                    ];
                }
            }
            $ringtoneInstance = new Ringtones;
            $index = 'id';
            return batch()->update($ringtoneInstance, $updateRingtone, $index);
        });


    }
}
