<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IPLIST;
use App\Models\ListType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class IpblockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    //==================Admin===================
    public function index()
    {
        if(!Auth::user()->can('admin.ipblock')){
            abort(403);
        }
        $data['title'] = 'IP List';
        $data['list_type'] = ListType::all();
        return view('admin.ipblocklist.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.ipblock')){
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

        $ipListQuery = IPLIST::with( 'list_type');

        if (!empty($searchValue)) {
            $searchTerm = '%' . $searchValue . '%';
            $ipListQuery->where('ip_address', 'like', $searchTerm);
        }

        $totalRecordsFilter = $ipListQuery->count();

        $records = $ipListQuery
            ->orderBy($columnName, $columnSortOrder)
            ->paginate($rowperpage, ['*'], 'page', $page);

        $totalRecords = IPLIST::count();

        $data_arr = [];

        foreach ($records as $record) {
            $ip_address = $record->ip_address;
            $btn = ' <a href="javascript:void(0)" data-id="' . $record->id . '" class="btn editIplistBlock"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="' . $record->id . '" class="btn deleteIplistBlock"><i class="fa fa-trash text-danger"></i></a>';

            $data_arr[] = [
                "id" => $record->id,
                "ip_address" => $ip_address,
                "list_type_id" => $record->list_type->name ?? null,
                "action" => $btn,
            ];
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsFilter,
            "aaData" => $data_arr,
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('admin.ipblock.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $iplistData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'ip_address' => 'required|unique:i_p_l_i_s_t_s,ip_address',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }

        $result = new Iplist();
        $result->ip_address = $iplistData['ip_address'];
        $result->list_type_id = $iplistData['list_type_id'];
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);

    }

    public function edit($id)
    {
        if(!Auth::user()->can('admin.ipblock.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $ipList = IPLIST::find($id);
        return response()->json($ipList);
    }

    public function update(Request $request)
    {
        if(!Auth::user()->can('admin.ipblock.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $iplistData = Purify::clean($request->except('_token', '_method'));

        $id = $request->IplistBlock_id;
        $rules = [
            'ip_address' => 'required|unique:i_p_l_i_s_t_s,ip_address,'.$id,
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = Iplist::findOrFail($id);
        $result->ip_address = $iplistData['ip_address'];
        $result->list_type_id = $iplistData['list_type_id'];
        $result->save();
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function bulk_store(Request $request)
    {
        if(!Auth::user()->can('admin.ipblock.create')){
            abort(403);
        }
        $list_type_id = $request->list_type_id_bulk;

        $items_file = $items_input = [];
        if ($request->hasFile('file_ip_address')) {
            $uploadedFile = fopen($request->file('file_ip_address'), 'r');
            while (($line = fgets($uploadedFile)) !== false) {
                $items_file[] = trim($line);
            }
            fclose($uploadedFile);
        }
        if($request->bulk_ip_address){
            $ipAddress_bulk = $request->bulk_ip_address;
            $items_input = preg_split("/[,|\r\n]+/", $ipAddress_bulk);
        }

        $items_array = array_merge($items_file, $items_input);

        foreach ($items_array as $ipAddress) {
            $parts = explode('.', $ipAddress);
            $ipAddressPrefix = implode('.', array_slice($parts, 0, 3));

            IPLIST::updateOrInsert(
                ['ip_address' => trim($ipAddressPrefix)],
                [
                    'list_type_id' => (int)$list_type_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }

        return response()->json([
            'success'=>  'Thêm mới thành công.',
        ]);
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.ipblock.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $ipList = IPLIST::find($id);
        if($ipList->user->count() > 0){
            return response()->json(['error'=>'Delete error.']);
        }else{
            $ipList->delete();
            return response()->json(['success'=>'Delete Successfully.']);
        }
    }
}
