<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V4\CategoriesResource;
use App\Http\Resources\V4\WallpapersResource;
use App\Models\Categories;
use App\Models\Wallpapers;
use Illuminate\Http\Request;

class V4Controller extends Controller
{
    public function admob(){
        $domain = getDomain();
        domainLogin($domain);
        $ads = json_decode($domain->manage_ads,true);

        return [
            'provider' => 'ADMOB',
            'admob_banner' => ($ads && $ads['banner_ads_id']) ? $ads['banner_ads_id'] :  'ca-app-pub-3940256099942544/6300978111' ,
            'admob_reward' => ($ads && $ads['rewarded_ads_id']) ? $ads['rewarded_ads_id'] :  'ca-app-pub-3940256099942544/5224354917',
            'admob_open' => ($ads && $ads['open_ads_id']) ? $ads['open_ads_id'] : 'ca-app-pub-3940256099942544/3419835294',
            'admob_native' => ($ads && $ads['native_ads_id']) ? $ads['native_ads_id'] :  'ca-app-pub-3940256099942544/2247696110',
            'admob_interstitial' => ($ads && $ads['interstitial_ads_id']) ? $ads['interstitial_ads_id'] :  'ca-app-pub-3940256099942544/1033173712',
            'applovin_banner' => ''  ,
            'applovin_interstitial' => ''  ,
            'applovin_reward' =>  ''  ,
            'startapp_id' => ''  ,
            'ironsource_id' => ''  ,
            'banner_enable' => $domain->is_ads,
            'interstitial_enable' => $domain->is_ads,
            'reward_enable' => $domain->is_ads,
            'open_enable' => $domain->is_ads,
        ];
    }
    public function settings(){

        $settings = [

        ];

        return json_encode($settings);

    }

    public function home(){
        $isBlock = checkBlockIp() ? 0 : 1;
        $criteria = [
            'latest' => ['id', '<>'],
            'popular' => ['wallpaper_view_count', '<>'],
            'random' => ['updated_at', '<>', true],
            'downloaded' => ['wallpaper_download_count', '<>'],
            'live' => ['created_at', '=']
        ];
        $row = [];
        foreach ($criteria as $key => $value) {
            $row[][$key] = WallpapersResource::collection($this->getWallpapersByCriteria($isBlock, ...$value));
        }
        return $row;
    }


    private function getWallpapersByCriteria($isBlock, $orderBy, $operator, $random = false, $page = 1, $length = 10) {
        $domain = getDomain();
        $query = $domain
            ->getWallpaper($isBlock)
            ->where('wallpaper_extension', $operator, 'image/gif')
            ->where('wallpaper_status',1);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        return $query->paginate($length, ['*'], 'page', $page);
    }

    public function categories(){
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories =  $domain->categories()
            ->where('category_checked_ip',$isBlock)
            ->withCount('wallpapers')
            ->having('wallpapers_count', '>', 0)
            ->inRandomOrder()
            ->paginate(5);
        $result['current_page'] = $categories->currentPage();
        $result['last_page'] = $categories->lastPage();
        $result['total'] = $categories->total();
        $result['data'] = CategoriesResource::collection($categories);
        return $result;
    }

    public function wallpaper(){
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'created_at','<>'));
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = $wallpapers;
        return $dataResult;
    }

    public function popular(){
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_view_count','<>'));
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = $wallpapers;
        return $dataResult;
    }

    public function download(){
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_download_count','<>'));
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = $wallpapers;
        return $dataResult;
    }

    public function random(){
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id','<>',true));
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = $wallpapers;
        return $dataResult;
    }
    public function live(){
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id','=',true));
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = $wallpapers;
        return $dataResult;
    }

    public function cid(){
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $cate_id = \request()->id;
        $wallpapers = Categories::findOrFail($cate_id)
            ->wallpapers()
            ->where('wallpaper_status',1)
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = WallpapersResource::collection($wallpapers);
        return$dataResult;
    }

    public function viewWallpaper(Request $request){
        $data = Wallpapers::with('tags')->findOrFail($request['id']);
        $data->increment('wallpaper_view_count');
        return New WallpapersResource($data);
    }

    public function hashtag(Request $request){
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $tagName = '%'.$request['query'].'%';
        $domain = getDomain();
        $wallpapers = $domain->wallpapers()->whereHas('tags', function ($query) use ($tagName) {
            $query->where('tag_name', 'like',$tagName);
            })
            ->where('wallpaper_status',1)
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        $dataResult['current_page'] = $wallpapers->currentPage();
        $dataResult['last_page'] = $wallpapers->lastPage();
        $dataResult['total'] = $wallpapers->total();
        $dataResult['data'] = WallpapersResource::collection($wallpapers);
        return $dataResult;

    }
}
