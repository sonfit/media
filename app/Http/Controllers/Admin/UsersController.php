<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Fund;
use App\Models\KYC;
use App\Models\Language;
use App\Models\ListType;
use App\Models\Payout;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use Upload, Notify;

    public function index()
    {
        return view('admin.users.list');
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

        $domainsQuery = User::query();
        $domainsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('fullname', 'like', $searchTerm)
                    ->orwhere('username', 'like', $searchTerm)
                    ->orwhere('username', 'like', $searchTerm)
                    ->orwhere('last_login_ip', 'like', $searchTerm);
            });

        $totalRecordswithFilter = $domainsQuery->count();

        $records = $domainsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = User::select('count(*) as allcount')->count();
        }

        $list_type = ListType::all();

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="'.route('admin.user.edit',$record->id).'" data-id="'.$record->id.'" class="btn"><i class="fa fa-edit text-warning"></i></a>';
            $btn .= ' <a class="loginAccount" data-toggle="modal" data-target="#signIn" data-route="'.route('admin.user.login-as-user',$record->id).'"><i class="fas fa-sign-in-alt text-primary pr-2" aria-hidden="true"></i> </a>';
            $status = $record->status == 1 ? "success" :  "danger" ;

            $type= '';
//            foreach ($record->getRedirects as $redirect){
//                $redirects .= '<span class="badge badge-pill badge-purple m-1 font-16">'.$redirect->redirect_url.'</span>';
//            }

            foreach ($list_type as $list){
                $color = $record->list_type_id ? (in_array($list->id, $record->list_type_id) ? 'success' : 'secondary'): 'secondary';
                $type .= '<span
                data-user="'.$record->id.'"
                data-type_id="'.$list->id.'"
                class="badge badge-pill badge-'.$color.' m-1 font-16 user-type">'.$list->name.'</span>';
            }

            $data_arr[] = array(
                "id" => $record->id,
                "list_type_id" => $type,
                "fullname" => '<h5 class="text-dark mb-0 font-16 font-medium">'.$record->fullname.'</h5><span class="text-muted font-14"><i class="fa fa-user mr-2"></i>'.$record->username.'</span><span class="text-muted font-14"><i class="fa fa-envelope ml-3 mr-2"></i>'.$record->email.'</span>',
                "last_login_ip" => '<h5 class="text-dark mb-0 font-16 font-medium"><i class="text-muted fa fa-id-card mr-2"></i>'.$record->last_login_ip. '</h5><span class="text-muted font-14"><i class="fa fa-clock mr-2"></i>'.$record->last_login.'</span>' ,
                "status" => '<span class="badge  badge-pill  badge-'.$status.'">'.$status.'</span>',
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

    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $users = User::latest()
            ->when(isset($search['search']), function ($query) use ($search) {
                return $query->where('email', 'LIKE', "%{$search['search']}%")
                    ->orWhere('username', 'LIKE', "%{$search['search']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->paginate(config('basic.paginate'));
        return view('admin.users.list', compact('users', 'search'));
    }

    public function userCreate()
    {
        return view('admin.users.add-user');
    }

    public function userStore(Request $request)
    {
        $userData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'fullname' => 'sometimes|required',
            'username' => 'sometimes|required|unique:users,username',
            'email' => 'sometimes|required|email|unique:users,email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ];
        if(config('basic.strong_password') == 0){
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        }else{
            $rules['password'] = ["required",'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }
        $message = [
            'fullname.required' => 'Name is required',
        ];

        $Validator = Validator::make($userData, $rules, $message);

        if ($Validator->fails()) {
            return back()->withErrors($Validator)->withInput();
        }

        $user = new User();
        $user->fullname = $userData['fullname'];
        $user->username = $userData['username'];
        $user->email = $userData['email'];
        $user->password =  Hash::make($userData['password']);
        $user->phone = $userData['phone'];
        $user->limit_domain = $userData['limit_domain'];
        $user->limit_redirect = $userData['limit_redirect'];
        $user->limit_domain_redirect = $userData['limit_domain_redirect'];
        $user->status = ($userData['status'] == 'on') ? 0 : 1;
        $user->two_fa_verify = ($userData['two_fa_verify'] == 'on') ? 1 : 0;
        $user->save();

        session()->flash('success', 'User Added Successfully');
//        return redirect()->back();
        return redirect()->route('admin.user.edit',$user->id);

    }

    public function activeMultiple(Request $request)
    {
//        dd($request->all());
        if ($request->strIds == null) {
            session()->flash('error', 'You did not select any user.');
            return response()->json(['error' => 1]);
        } else {
            User::whereIn('id', array_map('intval',$request->strIds))->update([
                'status' => 1,
            ]);
            session()->flash('success', 'User Status Has Been Active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            User::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);

            session()->flash('success', 'User Status Has Been Deactive');
            return response()->json(['success' => 1]);
        }
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        $languages = Language::all();
        $types = ListType::all();
        return view('admin.users.edit-user', compact('user','languages','types'));
    }

    public function userUpdate(Request $request)
    {
        $id = $request->user_id;
        $languages = Language::all()->map(function ($item){
            return $item->id;
        });
        $userData = Purify::clean($request->except('_token', '_method'));
        $user = User::findOrFail($id);
        $rules = [
            'fullname' => 'sometimes|required',
            'username' => 'sometimes|required|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'fullname.required' => 'Name is required',
        ];

        $Validator = Validator::make($userData, $rules, $message);

        if ($Validator->fails()) {
            return back()->withErrors($Validator)->withInput();
        }

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = $this->uploadImage($request->image, config('location.user.path'), config('location.user.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        $user->fullname = $userData['fullname'];
        $user->username = $userData['username'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->limit_domain = $userData['limit_domain'];
        $user->limit_redirect = $userData['limit_redirect'];
        $user->limit_domain_redirect = $userData['limit_domain_redirect'];
        $user->language_id = $userData['language_id'];
        $user->address = $userData['address'];
        $user->status = ($userData['status'] == 'on') ? 0 : 1;
        $user->two_fa_verify = ($userData['two_fa_verify'] == 'on') ? 1 : 0;

        $user->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);

    }

    public function passwordUpdate(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:5|same:password_confirmation',
        ]);
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        $this->sendMailSms($user, 'PASSWORD_CHANGED', [
            'password' => $request->password
        ]);
        return back()->with('success', 'Updated Successfully.');
    }

    public function loginAsUser($id)
    {
        Auth::guard('web')->loginUsingId($id);
        session(['impersonated_by' => $id]);
        return redirect()->route('user.dashboard_user.home');
    }

    public function logoutAsUser()
    {
        Auth::guard('web')->logout();
        session()->forget('impersonated_by');
        return redirect()->route('admin.users');
    }

    public function loggedIn()
    {
        $logs = UserLogin::orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.users.logged_in', compact('logs'));
    }

    public function singleLoggedIn($id)
    {
        $logs = UserLogin::where('user_id', $id)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.users.logged_in', compact('logs'));
    }

}
