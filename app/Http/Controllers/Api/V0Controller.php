<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\WallpapersResource;
use App\Models\Categories;
use App\Models\Wallpapers;
use Illuminate\Http\Request;

class V0Controller extends Controller
{
    public function categories(){
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories =  $domain->categories()
            ->where('category_checked_ip',$isBlock)
            ->withCount('wallpapers','ringtones')
            ->having('wallpapers_count', '>', 0)
            ->orhaving('ringtones_count', '>', 0)
            ->inRandomOrder()
            ->get();
        return CategoriesResource::collection($categories);
    }

    public function wallpapersByCategories($id){
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $limit= ($page-1) * $length ;
        $wallpapers = Categories::findOrFail($id)
            ->wallpapers()
            ->with(['categories' => function($query) use ($id) {
                $query->where('categories.id', $id);
            }])
            ->where('wallpaper_status',1)
            ->distinct()
            ->inRandomOrder()
            ->skip($limit)
            ->take($length)
            ->get();
        return WallpapersResource::collection($wallpapers);
    }


    public function wallpaper($id){
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories = getDomain()->categories()->where('category_checked_ip',$isBlock)->get();
        $wallpapers = Wallpapers::findOrFail($id);
        $wallpapers->increment('wallpaper_view_count');
        $wallpapers->categories =  $categories;
        return (new WallpapersResource($wallpapers))->resolve();
    }


    public function getWallpapersByCriteria($isBlock, $orderBy, $random = false, $page = 1, $length = 20) {
        $domain = getDomain();
        $query = $domain->getWallpaper($isBlock);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        $limit= ($page-1) * $length ;
        return $query
            ->skip($limit)
            ->take($length)
            ->get();
    }

    public function getFeatured(){
        $wallpapers =  WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_feature',true)
        );
        return response()->json([
            'message'=>'save ip successs',
            'ad_switch'=> 1,
            'data'=> $wallpapers,
        ]);
    }

    public function getPopularity(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_view_count')
        );
    }

    public function getNewest(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id')
        );
    }

    public function getSaved(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id',true)
        );
    }

    public function likeWallpaper(Request $request)
    {
        $response['save_wallpaper'] = ['success' => 'Save Wallpaper Successfully'];
        $wallpaper = Wallpapers::where('id', $request->wallpaper_id)->first();
        $wallpaper->increment('wallpaper_like_count');
        return response()->json($response);
    }

    public function disLikeWallpaper(Request $request)
    {
        $response['save_wallpaper'] = ['success' => 'Completely Delete this Wallpaper out of your List'];
        $wallpaper = Wallpapers::where('id', $request->wallpaper_id)->first();
        $wallpaper->decrement('wallpaper_like_count');
        return response()->json($response);
    }

}
