<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\CategoriesResource;
use App\Http\Resources\V2\WallpapersResource;
use App\Models\Categories;
use App\Models\Wallpapers;
use Illuminate\Http\Request;

class V2Controller extends Controller
{
    public function getData(){
        $get_method = $this->checkSignSalt($_POST['data']);

//        dd($get_method);
        if( $get_method['method_name']=="get_app_details"){
            $data = $this->get_app_details();
        }elseif ( $get_method['method_name']=="get_home"){
            $data = $this->get_home($get_method);
        }elseif ( $get_method['method_name']=="get_wallpaper"){
            $data = $this->get_wallpaperByCategories($get_method);
        }elseif ( $get_method['method_name']=="get_category"){
            $data = $this->categories();
        }elseif ( $get_method['method_name']=="get_wallpaper_most_viewed"){
            $data =  WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'wallpaper_view_count'));
        }elseif ( $get_method['method_name']=="get_latest"){
            $data =  WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'updated_at'));
        }elseif ( $get_method['method_name']=="get_recent_post"){
            $data =  WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'created_at'));
        }elseif ( $get_method['method_name']=="get_wallpaper_most_rated"){
            $data =  WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'wallpaper_feature'));
        }elseif ( $get_method['method_name']=="get_single_wallpaper"){
            $data =  $this->get_single_wallpaper($get_method);
        }
        $set['HD_WALLPAPER'] = $data;
        header('Content-Type: application/json; charset=utf-8');
        echo str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    function checkSignSalt($data_info)
    {
        $key = "zxcv@vietmmo";
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
            'HD_WALLPAPER' => [
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

    private function get_app_details() {
        $jsonObj = array();
        $domain = getDomain();
        domainLogin($domain);
        $data = $domain->toArray();
        $ads = json_decode($data['manage_ads'], true);
        $type = explode(',', 'Portrait');
        $appInfo = [
            'ios_bundle_identifier' => 'com.abcdzxcv.hdwallpapers',
            'package_name' => 'com.abcdzxcv.hdwallpapers',
            'app_name' => $data['domain_name'] ?? '',
            'app_logo' => 'icon144.png',
            'app_version' => '1.0.0',
            'app_author' => "vietmmo",
            'app_contact' => '+84 9227777522',
            'app_email' => 'info@vietmmo.net',
            'app_website' => $data['domain_web'],
            'app_description' => $data['domain_name'],
            'app_developed_by' => $data['domain_name'],
            'app_privacy_policy' => $data['site_policy'] ?? '',
            'publisher_id' => ($ads && $ads['app_id']) ? $ads['app_id'] : '',
            'interstital_ad' => $data['is_ads'] == 1 ? 'true' : 'false',
            'interstital_ad_id' => ($ads && $ads['interstitial_ads_id']) ? $ads['interstitial_ads_id'] : '',
            'interstital_ad_click' => '12',
            'banner_ad' => $data['is_ads'] == 1 ? 'true' : 'false',
            'banner_ad_id' => ($ads && $ads['banner_ads_id']) ? $ads['banner_ads_id'] : '',
            'admob_nathive_ad' => $data['is_ads'] == 1 ? 'true' : 'false',
            'admob_native_ad_id' => ($ads && $ads['native_ads_id']) ? $ads['native_ads_id'] : '',
            'admob_native_ad_click' => '12',
            'facebook_interstital_ad' => 'false',
            'facebook_interstital_ad_id' => '1393008281089270_1393009821089116',
            'facebook_interstital_ad_click' => '5',
            'facebook_banner_ad' => 'false',
            'facebook_banner_ad_id' => '1393008281089270_1393010137755751',
            'facebook_native_ad' => 'false',
            'facebook_native_ad_id' => '1393008281089270_1393009201089178',
            'facebook_native_ad_click' => '12',
            'publisher_id_ios' => '',
            'interstital_ad_ios' => 'false',
            'interstital_ad_id_ios' => '',
            'interstital_ad_click_ios' => '5',
            'banner_ad_ios' => 'false',
            'banner_ad_id_ios' => '',
            'gif_on_off' => 'true',
        ];
        foreach (['Portrait', 'Landscape', 'Square'] as $appType) {
            $appInfo[strtolower($appType)] = in_array($appType, $type) || empty($type) ? 'true' : 'false';
        }

        $appInfo += [
            'app_update_status' => 'false',
            'app_new_version' => '',
            'app_update_desc' => '',
            'app_redirect_url' => '',
            'cancel_update_status' => 'false',
        ];

        $jsonObj[] = $appInfo;
        return $jsonObj;
    }

    private function get_home($get_method){
        $row['featured_wallpaper'] =   WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'wallpaper_feature'));
        $row['wallpaper_category'] = $this->categories();
        $row['latest_wallpaper'] =  WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'updated_at'));
        $row['popular_wallpaper'] = WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'wallpaper_view_count'));
        $row['recent_wallpapers'] = WallpapersResource::collection($this->getWallpapersByCriteria($get_method,checkBlockIp() ? 0 : 1, 'created_at'));
        return $row;
    }

    private function getWallpapersByCriteria($get_method,$isBlock, $orderBy, $random = false, $page = 1, $length = 20) {
        $domain = getDomain();
        $query = $domain
            ->getWallpaper($isBlock)
            ->where('wallpaper_extension', '<>', 'image/gif')
            ->where('wallpaper_type',$get_method['type'])
            ->where('wallpaper_status',1);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        $limit= ($page-1) * $length ;
        return $query->paginate($length, ['*'], 'page', $page);
//            ->skip($limit)->take($length)->get();
    }

    public function get_wallpaperByCategories($get_method){
        $type = trim($get_method['type']);
        $page = $get_method['page'] ?? 1;
        $length = 20;
        $limit= ($page-1) * $length ;
        $cate_id = $get_method['cat_id'];
        $wallpapers = Categories::findOrFail($cate_id)
            ->wallpapers()
            ->where('wallpaper_extension', '<>', 'image/gif')
            ->where('wallpaper_status',1)
            ->where('wallpaper_type',$type)
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);
        return WallpapersResource::collection($wallpapers);
    }

    private function get_single_wallpaper($get_method){
        $wallpaper = Wallpapers::findOrFail($get_method['wallpaper_id']);
        $wallpaper->wallpaper_view_count = $wallpaper->wallpaper_view_count + 1;
        $wallpaper->save();
        return new WallpapersResource($wallpaper);
    }

    public function categories(){
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories =  $domain->categories()
            ->where('category_checked_ip',$isBlock)
            ->withCount('wallpapers')
            ->having('wallpapers_count', '>', 0)
            ->inRandomOrder()
            ->get();
        return CategoriesResource::collection($categories);
    }


}

