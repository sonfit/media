<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V4\CategoriesMusicsResource;
use App\Http\Resources\V4\CategoriesResource;
use App\Http\Resources\V4\CategoryMusicsResource;
use App\Http\Resources\V4\MusicForCategoryResource;
use App\Http\Resources\V4\MusicsResource;
use App\Http\Resources\V4\WallpapersResource;
use App\Models\Categories;
use App\Models\Musics;
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
            "onesignal_id"=> "",
            "onesignal_rest"=> "",
            "packagename"=> "",
            "privacy"=> "",
            "layout"=> "dark-layout",
            "server_key"=> "",
            "wallpaper_columns"=> "3",
            "show_view_count"=> "false",
            "show_categories"=> "true",
            "setting_icon"=> "",
            "home_icon"=> "",
            "categories_icon"=> "",
            "popular_icon"=> "",
            "favourite_icon"=> "",
            "back_icon"=> "",
            "download_icon"=> "",
            "set_wallpaper_icon"=> "",
            "favourite_enable_icon"=> "",
            "favourite_disable_icon"=> "",
            "background_color"=> "#191B21",
            "header_color"=> "#0F1013",
            "filter_icon"=> ""
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
            $row[] = [
                'name' => $key,
                'data' => WallpapersResource::collection($this->getWallpapersByCriteria($isBlock, ...$value)),
            ];
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
        $domain = getDomain();
        $wallpaper = $domain->getWallpaper(checkBlockIp() ? 0 : 1)->findOrFail($request['id']);
        $wallpaper->increment('wallpaper_view_count');
        return New WallpapersResource($wallpaper);
    }

    public function hashtag(Request $request){
        $page = $request->get('page', 1);
        $length = 20;
        $tagName = '%'.$request['query'].'%';
        $domain = getDomain();
        $wallpapers = $domain->wallpapers()
            ->whereHas('tags', function ($query) use ($tagName) {
                $query->where('tag_name', 'like',$tagName);
            })
            ->whereHas('categories', function ($query) {
                $query->where('category_checked_ip', checkBlockIp() ? 0 : 1);
            })
            ->where('wallpaper_status', 1)
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);

        return [
            'current_page' => $wallpapers->currentPage(),
            'last_page' => $wallpapers->lastPage(),
            'total' => $wallpapers->total(),
            'data' => WallpapersResource::collection($wallpapers),
        ];
    }


    //======================== MUSICS ===============================

    function checkSignSalt($data_info){
        $key = "viaviweb";
        $data_json = $data_info;
        $data_arr = json_decode(urldecode(base64_decode($data_json)), true);

        if (!isset($data_arr['sign']) || !isset($data_arr['salt'])) {
            return $this->respondWithErrorMessage("Invalid sign salt.");
        }

        $md5_salt = md5($key . $data_arr['salt']);

        if ($data_arr['sign'] != $md5_salt) {
            return $this->respondWithErrorMessage("Invalid sign salt.");
        }
        return $data_arr;
    }

    function respondWithErrorMessage($message) {
        $response = [
            'ONLINE_MP3_APP' => [
                [
                    'success' => -1,
                    'MSG' => $message
                ]
            ]
        ];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    public function app_details()
    {
        $this->checkSignSalt($_POST['data']);
        $domain = getDomain();
        domainLogin($domain);

        $ads = json_decode($domain->manage_ads, true);
        $status_ads = $domain->ad_switch;

        $ads[] = [
            "ad_id" => 1,
            "ads_name" => "Admob",
            'ads_info' => [
                'publisher_id' => ($ads && $ads['app_id']) ? $ads['app_id'] : '',
                'banner_on_off' => $status_ads,
                'banner_id' => ($ads && $ads['banner_ads_id']) ? $ads['banner_ads_id'] : '',
                'interstitial_on_off' => $status_ads,
                'native_on_off' => $status_ads,
                'interstitial_id' => ($ads && $ads['interstitial_ads_id']) ? $ads['interstitial_ads_id'] : '',
                'native_id' => ($ads && $ads['native_ads_id']) ? $ads['native_ads_id'] : '',
                'interstitial_clicks' => 5,
                'native_position' => 5,
            ],
            'status' => 'true',
        ];
        $page_list = [
            [
                'page_id' => 1,
                'page_title' => 'About Us',
                'page_content' => 'About Us',
            ],

            [
                'page_id' => 2,
                'page_title' => 'Terms Of Use',
                'page_content' => 'Terms Of Use',
            ],
            [
                'page_id' => 3,
                'page_title' => 'Privacy Policy',
                'page_content' => '',
            ],

        ];

        $response[] = array(
            'app_package_name' => "com.vietmmonet.zxcvabcd",
            'app_name' => $domain->domain_web,
            "app_email" => "info@" . $domain->domain_web,
//            'app_logo' => 'https://' . getDomain() . '/storage/sites/' . $site->id . '/' . $site->site_image,
            "app_company" => $domain->domain_web,
            "app_website" => $domain->domain_web,
            "app_contact" => "zxcv",
            'facebook_link' => 'https://facebook.com',
            'twitter_link' => "https://twitter.com",
            'instagram_link' => "https://instagram.com",
            'youtube_link' => "https://youtube.com",
            'google_play_link' => "zxcv",
            'apple_store_link' => "#ap",
            'app_version' => "1.1",
            'app_update_hide_show' => false,
            'app_update_version_code' => "",
            'app_update_desc' => "Please update new app",
            'app_update_link' => "",
            'app_update_cancel_option' => "true",
            'song_download' => "true",
            'ads_list' => $ads,
            'page_list' => $page_list,
            'success' => '1'
        );

        return \Response::json(array(
            'ONLINE_MP3_APP' => $response,
            'status_code' => 200
        ));
    }

    public function homeMusics(Request $request)
    {
        $get_data = $this->checkSignSalt($request->input('data'));
        $domain = getDomain();
        $is_block = checkBlockIp() ? 0 : 1;

        $categories = $this->categoriesMusic($domain,$request);
        $slide = $this->categoriesMusic($domain,$request);
//        $slide = $this->getMusicsByCriteria($domain,$request, $is_block,'id',true);
        $recently_songs = $this->getRecentlySongs($get_data);
        $trending_songs = $this->getMusicsByCriteria($domain,$request, $is_block, 'music_view_count');
        $popular_songs = $this->getMusicsByCriteria($domain,$request, $is_block, 'music_like_count');

        $category = $this->createSection('category', 'Category', 'category', CategoriesMusicsResource::collection($categories));
        $popular = $this->createSection('popular_songs', 'Popular Songs', 'song', MusicsResource::collection($popular_songs));

        $home_sections = [$category, $popular];

        $data = [
            'ONLINE_MP3_APP' => [
                'slider' => MusicForCategoryResource::collection($slide),
                'recently_songs' => $recently_songs,
                'trending_songs' => MusicsResource::collection($trending_songs),
                'popular_songs' => MusicsResource::collection($popular_songs),
                'home_sections' => $home_sections
            ],
            "status_code" => 200,
        ];

        return response()->json($data);
    }

    private function createSection($id, $title, $type, $content)
    {
        return [
            'home_id' => $id,
            'home_title' => $title,
            'home_type' => $type,
            'home_content' => $content,
        ];
    }

    private function getRecentlySongs($get_data)
    {
        if (isset($get_data['songs_ids'])) {
            $songs_ids = explode(',', $get_data['songs_ids']);
            $musics = Musics::whereIn('id', $songs_ids)->get();
            return MusicsResource::collection($musics);
        }
        return [];
    }

    private function categoriesMusic($domain,$request, $length = 10){
        $page = $request['page'] ?? 1;
        $isBlock = checkBlockIp() ? 0 : 1;
        return $domain->categories()
            ->where('category_checked_ip', $isBlock)
            ->withCount('musics')
            ->having('musics_count', '>', 0)
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
    }

    private function getMusicsByCriteria($domain, $request, $isBlock, $orderBy = 'id', $random = false){
        $length = 10;
        $page = $request['page'] ?? 1;

        $query = $domain
            ->getMusic($isBlock)
            ->where('music_status', 1);

        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }

        return $query->paginate($length, ['*'], 'page', $page);
    }

    public function home_recently_songs(){
        $get_data = $this->checkSignSalt($_POST['data']);
        $recently_songs = $this->getRecentlySongs($get_data);
        $data = [
            'ONLINE_MP3_APP' => $recently_songs,
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function getCategoriesMusic(Request $request){
        $domain = getDomain();
        $categories = $this->categoriesMusic($domain,$request);
        $data = [
            'ONLINE_MP3_APP' => CategoryMusicsResource::collection($categories),
            "total_records" => $categories->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }
    public function latest_songs(Request $request){
        $domain = getDomain();
        $is_block = checkBlockIp() ? 0 : 1;
        $latest_songs = $this->getMusicsByCriteria($domain,$request,$is_block);
        $data = [
            'ONLINE_MP3_APP' => MusicsResource::collection($latest_songs),
            "total_records" => $latest_songs->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function trending_songs(Request $request){
        $domain = getDomain();
        $is_block = checkBlockIp() ? 0 : 1;
        $trending_songs = $this->getMusicsByCriteria($domain,$request, $is_block, 'music_view_count');
        $data = [
            'ONLINE_MP3_APP' => MusicsResource::collection($trending_songs),
            "total_records" => $trending_songs->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function home_slider_songs(Request $request){
        $length = 10;
        $page = $request->page ?? 1;
        $get_data = $this->checkSignSalt($_POST['data']);
        $cate_id = $get_data['slider_id'];
        $musics = Categories::findOrFail($cate_id)
            ->musics()
            ->where('music_status',1)
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        $data = [
            'ONLINE_MP3_APP' => MusicsResource::collection($musics),
            "total_records" => $musics->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function home_collections(Request $request){
        $get_data = $this->checkSignSalt($_POST['data']);
        $domain = getDomain();
        $is_block = checkBlockIp() ? 0 : 1;
        $getResource = [];
        switch ($get_data['id']) {
            case 'category':
                $getResource = CategoryMusicsResource::collection($this->categoriesMusic($domain,$request));
                break;
            case 'popular_songs':
                $getResource =  MusicsResource::collection($this->getMusicsByCriteria($domain,$request, $is_block, 'music_like_count'));
                break;
        }

        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $getResource->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function song_by_category(Request $request){
        $length = 10;
        $page = $request->page ?? 1;
        $get_data = $this->checkSignSalt($_POST['data']);
        $cate_id = $get_data['category_id'];
        $category = Categories::findOrFail($cate_id);
        $musics = $category
            ->musics()
            ->where('music_status',1)
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        $category->increment('category_view_count');
        $data = [
            'ONLINE_MP3_APP' => MusicsResource::collection($musics),
            "total_records" => $musics->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function song_view(){
        $get_data = $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $musics = Musics::findOrFail($song_id);
        $musics->increment('music_view_count');
        $data = [
            'ONLINE_MP3_APP' => new MusicsResource($musics),
            "status_code" => 200
        ];
        return response()->json($data);
    }
    public function song_download(){
        $get_data = $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $musics = Musics::findOrFail($song_id);
        $musics->increment('music_download_count');
        $data = [
            'ONLINE_MP3_APP' => new MusicsResource($musics),
            "status_code" => 200
        ];
        return response()->json($data);
    }
    public function song_favourite(){
        $get_data = $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $musics = Musics::findOrFail($song_id);
        $musics->increment('music_like_count');
        $data = [
            'ONLINE_MP3_APP' => new MusicsResource($musics),
            "status_code" => 200
        ];
        return response()->json($data);
    }

}
