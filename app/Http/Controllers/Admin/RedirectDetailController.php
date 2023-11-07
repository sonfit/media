<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedirectDetail;
use Illuminate\Http\Request;

class RedirectDetailController extends Controller
{
    public function __construct()
    {
        $this->theme = template();
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->theme .='user.';

            return $next($request);
        });
    }
    //==================Admin===================

    public function index()
    {
        $data['title'] = 'Redirect History';
        return view('admin.redirect-history.index', $data);
    }

    public function getIndex(Request $request)
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

        $recordsQuery = RedirectDetail::with(['getDomains.user']);

        // Filter by domain_id
        if (isset($request->domain_id)) {
            $recordsQuery->whereIn('domain_id', $request->domain_id);
        }
        // Search by ip_address
        if (isset($searchValue)) {
            $searchTerm = '%' . $searchValue . '%';
            $recordsQuery->where('ip_address', 'like', $searchTerm);
        }

        $totalRecordswithFilter = $recordsQuery->count();

        $records = $recordsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if (!isset($searchValue) || !isset($request->domain_id)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = RedirectDetail::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $ipAddress = $record->is_block ==1 ? '<span class="badge  badge-pill  badge-danger">'.$record->ip_address.'</span>' : $record->ip_address;

            $data_arr[] = array(
                "id" => $record->id,
                "url_full" => '<span class="font-16 copyButtonName"  data-full_url="'.$record->url_full.'">'. $record->url_full.'</span>',
                "ip_address" => $ipAddress,
                "country" => $record->country,
                "platform_name" => $record->platform_name,
                "device_name_full" => $record->device_name_full,
                "is_robot" => $record->is_robot != 0 ? $record->robot :'',
                "count" => $record->count,
                "updated_at" => $record->updated_at->format('Y-m-d H:i:s')
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


    //==================User===================


    public function indexUser()
    {
        $data['title'] = 'Redirect History';
        return view($this->theme.'.redirect-history.index', $data);
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

        $recordsQuery = RedirectDetail::query();

        $recordsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('ip_address', 'like', $searchTerm);
            })
            ->whereHas('getDomains', function ($query) {
                return $query->where('user_id',  $this->user->id);
            });
//            ->whereHas('getDomains','user_id', $this->user->id);;

        $totalRecordswithFilter = $recordsQuery->count();

        $records = $recordsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = RedirectDetail::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $ipAddress = $record->is_block == 1 ? '<span class="badge  badge-pill  badge-danger">'.$record->ip_address.'</span>' : $record->ip_address;

            $data_arr[] = array(
                "id" => $record->id,
                "url_full" => '<span class="font-16 copyButtonName"  data-full_url="'.$record->url_full.'">'. $record->url_full.'</span>',
                "ip_address" => $ipAddress,
                "country" => $record->country,
                "platform_name" => $record->platform_name,
                "device_name_full" => $record->device_name_full,
                "is_robot" => $record->is_robot != 0 ? $record->robot :'',
                "count" => $record->count,
                "updated_at" => $record->updated_at->format('Y-m-d H:i:s')
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
}
