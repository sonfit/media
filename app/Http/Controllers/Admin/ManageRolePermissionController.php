<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Stevebauman\Purify\Facades\Purify;

class ManageRolePermissionController extends Controller
{
    protected $admin;

    public function index()
    {
        if(!Auth::user()->can('admin.staff')){
            abort(403);
        }
        $data['title'] = 'Manage Admin & Permission';
        return view('admin.staff.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.staff')){
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

        $adminQuery = Admin::query();

        if (!empty($searchValue)) {
            $searchTerm = '%' . $searchValue . '%';
            $adminQuery->where('name', 'like', $searchTerm)
                ->orWhere('username', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm);
        }

        $totalRecordsFilter = $adminQuery->count();

        $records = $adminQuery
            ->orderBy($columnName, $columnSortOrder)
            ->paginate($rowperpage, ['*'], 'page', $page);

        $totalRecords = Admin::count();

        $data_arr = [];

        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" data-id="' . $record->id . '" class="btn editStaff"><i class="fa fa-edit text-warning"></i></a>';

            $status = $record->status == 1 ? "success" :  "danger" ;

            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "username" => $record->username,
                "email" => $record->email,
                "phone" => $record->phone,
                "status" =>  '<span class="badge  badge-pill  badge-'.$status.'">'.$status.'</span>',
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
        if(!Auth::user()->can('admin.staff.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $staffData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'required|max:191',
            'username' => 'required|alpha_dash|unique:admins,username',
            'email' => 'required|email|max:191|unique:admins,email',
            'password' => 'required|min:6',
            'status' => 'required'
        ];
        $Validator = Validator::make($staffData, $rules);
        if ($Validator->fails()) {
            session()->flash('error',$Validator->errors()->first());
            return back()->withErrors($Validator)->withInput();
        }

//        $permissions = explode(',',join(',',$request->access));
//        foreach ($permissions as $permissionName) {
//            Permission::updateorCreate(['name' => $permissionName]);
//        }

        $item = new Admin();
        $item->name = $request->name;
        $item->username = $request->username;
        $item->email = $request->email;
        $item->phone = time();
        if(isset($request->password)){
            $item->password = Hash::make($request->password);
        }
        $item->status = $request->status;
        $item->save();
        $permissions = (isset($request->access)) ? explode(',',join(',',$request->access)) : [];
        $item->givePermissionTo($permissions);
        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }

    public function edit(Request $request)
    {
        if(!Auth::user()->can('admin.staff.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $admin = Admin::find($request->id);
        return response()->json($admin->load('permissions'));
    }

    public function update(Request $request)
    {
        if(!Auth::user()->can('admin.staff.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $staffData = Purify::clean($request->except('_token', '_method'));

        $rules = [
            'name' => 'required|max:191',
            'username' => 'required|alpha_dash|unique:admins,username,'.$request->staff_id,
            'email' => 'required|email|max:191|unique:admins,email,'.$request->staff_id,
            'password' => 'nullable|min:6',
            'status' => 'required'
        ];
        $Validator = Validator::make($staffData, $rules);

        if ($Validator->fails()) {
            session()->flash('error',$Validator->errors()->first());
            return back()->withErrors($Validator)->withInput();
        }
        $permissions = explode(',',join(',',$request->access));
        foreach ($permissions as $permissionName) {
            Permission::updateorCreate(['name' => $permissionName]);
        }


        $item = Admin::findOrFail($request->staff_id);
        $item->name = $request->name;
        $item->username = $request->username;
        $item->email = $request->email;
        $item->phone = time();
        if(isset($request->password)){
            $item->password = Hash::make($request->password);
        }
        $item->status = $request->status;

//        $permissions = explode(',',join(',',$request->access));
//        foreach ($permissions as $permissionName) {
//            Permission::updateorCreate(['name' => $permissionName]);
//        }
//        dd($permissions);

        $item->save();
        $permissions = (isset($request->access)) ? explode(',',join(',',$request->access)) : [];
        $item->syncPermissions($permissions);

        return response()->json([
            'success'=>'Saved Successfully',
        ]);
    }
}
