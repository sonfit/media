<?php

namespace App\Http\Controllers;

use App\Http\Resources\DomainResource;
use App\Http\Resources\TagsResource;
use App\Http\Resources\UserResource;
use App\Models\Domain;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUsers(){
        $searchValue = \request()->q;
        $users = User::latest()
            ->where('status',1)
            ->where('username', 'like', '%' . $searchValue . '%')
            ->orwhere('fullname', 'like', '%' . $searchValue . '%')
            ->orwhere('email', 'like', '%' . $searchValue . '%')
            ->get();
        $result = UserResource::collection($users);
        return response()->json($result);
    }

    public function getDomains(){
        $searchValue = \request()->q;
        $domains = Domain::latest()
            ->where('is_publish',1)
            ->where('domain_web', 'like', '%' . $searchValue . '%')
            ->get();
        $result = DomainResource::collection($domains);
        return response()->json($result);
    }

    public function getTags(){
        $searchValue = \request()->q;
        $domains = Tags::latest()
            ->where('tag_name', 'like', '%' . $searchValue . '%')
            ->get();
        $result = TagsResource::collection($domains);
        return response()->json($result);
    }


    public function getDomainsbyUser(Request $request)
    {
        $user_id = $request->user_id;
        if (is_array($user_id)) {
            // Nếu là mảng, sử dụng nó trực tiếp
            $userIds = $user_id;
        }else{
            $userIds[] = $user_id;
        }
        $domains = Domain::latest()
            ->where('is_publish',1)
            ->whereHas('user', function ($query) use ($userIds) {
                return $query->whereIn('id',  $userIds );
            })
            ->get();
        $result = DomainResource::collection($domains);
        return response()->json($result);
    }
}
