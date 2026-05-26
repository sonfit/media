<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainLoginLogs;
use App\Models\ListType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainLoginLogsController extends Controller
{

    public function index()
    {
        if(!Auth::user()->can('admin.domainLoginLogs')){
            abort(403);
        }
        $data['title'] = 'Domain Login Logs';
        $data['list_type'] = ListType::all();

        return view('admin.domain_log.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.domainLoginLogs')){
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

        $domainsLogsQuery = DomainLoginLogs::with('domain','ip_block');
        $domainsLogsQuery
            ->when(isset($searchValue), function ($query) use ($searchValue) {
                $searchTerm = '%' . $searchValue . '%';
                $query->where('ip_address', 'like', $searchTerm)
                    ->orWhereHas('domain', function($query) use ($searchTerm) {
                        $query->where('domain_web', 'like', $searchTerm );
                    });
            });

        $totalRecordswithFilter = $domainsLogsQuery->count();

        $records = $domainsLogsQuery
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        if (!isset($searchValue)) {
            $totalRecords = $totalRecordswithFilter;
        } else {
            $totalRecords = DomainLoginLogs::select('count(*) as allcount')->count();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $ip_address = $record->ip_block ? '<span class="badge badge-pill badge-danger m-1 font-16">'.$record->ip_address.'</span> <i class="fa fa-times close-icon removeIplistBlock" data-id_ip="'.$record->ip_block->id.'"></i>' : '<span class="badge badge-pill m-1 font-16 addIplistBlock">'.$record->ip_address.'</span>';
            $data_arr[] = array(
                "id" => $record->id,
                "domain_id" => $record->domain->domain_web,
                "ip_address" => $ip_address,
                "device_name" => $record->device_name,
                "platform_name" => $record->platform_name,
                "country" => $record->country,
                "count" => $record->count,
                "updated_at" => date_format($record->updated_at,'Y-m-d H:i:s'),
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
}
