<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\V0\RingtonesResource;
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

    public function wallpapersByCategory($id){
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $category = Categories::findOrFail($id);
        $category->increment('category_view_count');
        $wallpapers = $category
            ->wallpapers()
            ->where('wallpaper_extension','image/jpeg')
            ->where('wallpaper_status',1)
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        return WallpapersResource::collection($wallpapers);
    }

    public function wallpaper($id){
        $domain = getDomain();
        $wallpaper = $domain->wallpapers()->findOrFail($id);
        $wallpaper->increment('wallpaper_view_count');
        return (new WallpapersResource($wallpaper))->resolve();
    }

    public function getWallpapersByCriteria($isBlock, $orderBy, $random = false) {
        $length = 12;
        $page = $_GET['page'] ?? 1;
        $domain = getDomain();
        $query = $domain
            ->getWallpaper($isBlock)
            ->where('wallpaper_extension','image/jpeg')
            ->where('wallpaper_status',1);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        return [
            $query
                ->paginate($length, ['*'], 'page', $page),
            $domain
        ];
    }

    public function getFeatured(){
        list($wallpapers,$domain) = $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_feature');
        $wallpapers =  WallpapersResource::collection($wallpapers);
        domainLogin($domain);
        return response()->json([
            'message'=>'save ip successs',
            'ad_switch'=> $domain->is_ads,
            'data'=> $wallpapers,
        ]);
    }

    public function getPopularity(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_view_count')[0]
        );
    }

    public function getNewest(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id')[0]
        );
    }

    public function getSaved(){
        return WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id',true)[0]
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


    //================= RINGTONES ============================

    public function getRingtonesByCriteria($isBlock, $orderBy, $random = false) {
        $length = 12;
        $page = $_GET['page'] ?? 1;
        $domain = getDomain();
        $query = $domain
            ->getRingtone($isBlock);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        return [
            $query
                ->paginate($length, ['*'], 'page', $page),
            $domain
        ];
    }

    public function getFeaturedRingtones(){
        list($ringtones,$domain) = $this->getRingtonesByCriteria(checkBlockIp() ? 0 : 1, 'ringtone_feature');
        $ringtones =  RingtonesResource::collection($ringtones);
        domainLogin($domain);
        return response()->json([
            'message'=>'save ip successs',
            'ad_switch'=> $domain->is_ads,
            'data'=> $ringtones,
        ]);
    }

    public function getNewestRingtones(){
        return RingtonesResource::collection(
            $this->getRingtonesByCriteria(checkBlockIp() ? 0 : 1, 'id')[0]
        );
    }

    public function getPopularityRingtones(){
        return RingtonesResource::collection(
            $this->getRingtonesByCriteria(checkBlockIp() ? 0 : 1, 'ringtone_view_count')[0]
        );
    }
    public function getMostDownloadRingtones(){
        return RingtonesResource::collection(
            $this->getRingtonesByCriteria(checkBlockIp() ? 0 : 1, 'ringtone_download_count')[0]
        );
    }

    public function ringtonesByCategory($id){
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $category = Categories::findOrFail($id);
        $category->increment('category_view_count');
        $ringtones = $category
            ->ringtones()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        return RingtonesResource::collection($ringtones);
    }

    public function ringtone($id){
        $domain = getDomain();
        $ringtone = $domain->ringtones()->findOrFail($id);
        $ringtone->increment('ringtone_view_count');
        return (new WallpapersResource($ringtone))->resolve();
    }
//    public function categories(){
//        $domain = getDomain();
//        $isBlock = checkBlockIp() ? 0 : 1;
//        $categories =  $domain->categories()
//            ->where('category_checked_ip',$isBlock)
//            ->withCount('wallpapers','ringtones')
//            ->having('wallpapers_count', '>', 0)
//            ->orhaving('ringtones_count', '>', 0)
//            ->inRandomOrder()
//            ->get();
//        return CategoriesResource::collection($categories);
//    }

}
