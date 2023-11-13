<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    //==================Admin===================
    public function index()
    {
        if(!Auth::user()->can('admin.tags')){
            abort(403);
        }
        $data['title'] = 'Manage Tags';
        return view('admin.tags.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.tags')){
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

        $tagsQuery = Tags::withCount('wallpapers','ringtones','musics');
        $tagsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('tag_name', 'like', $searchTerm);
            });

        $totalRecordsFilter = $tagsQuery->count();

        $records = $tagsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->paginate($rowperpage, ['*'], 'page', $page);

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordsFilter;
        } else {
            $totalRecords = Tags::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn editTag"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn deleteTag"><i class="fa fa-trash text-danger"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "tag_name" => $record->tag_name,
                "wallpapers_count" => $record->wallpapers_count,
                "ringtones_count" => $record->ringtones_count,
                "musics_count" => $record->musics_count,
                "action"=> $btn,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('admin.tags.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $tagData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'tag_name' => 'required|unique:tags,tag_name',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = new Tags();
        $result->tag_name = trim($tagData['tag_name']);
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);

    }

    public function edit(Request $request)
    {
        if(!Auth::user()->can('admin.tags.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $tag = Tags::find($id);
        return response()->json($tag);
    }

    public function update(Request $request)
    {
        if(!Auth::user()->can('admin.tags.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $iplistData = Purify::clean($request->except('_token', '_method'));

        $id = $request->tag_id;
        $rules = [
            'tag_name' => 'required|unique:tags,tag_name,'.$id,
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = Tags::findOrFail($id);
        $result->tag_name = trim($iplistData['tag_name']);
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.tags.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $tag = Tags::find($id);
        if($tag->categories->count() > 0){
            return response()->json(['error'=>'Delete error.']);
        }else{
            $tag->delete();
            return response()->json(['success'=>'Delete Successfully.']);
        }
    }
}
