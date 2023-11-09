<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V9\CategoriesResource;
use App\Http\Resources\V9\gifWallpapersResource;
use App\Http\Resources\V9\RingtonesCategoriesResource;
use App\Http\Resources\v9\RingtonesResource;
use App\Http\Resources\V9\WallpapersResource;
use App\Models\Categories;
use App\Models\Ringtones;
use App\Models\Wallpapers;
use Illuminate\Http\Request;

class V9Controller extends Controller
{
    public $_content_type = "application/json";
    public $_code = 200;

    public function __construct() {
        $this->inputs();
    }

    public function get_request_method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function response($data, $status) {
        $this->_code = ($status)?$status:200;
        $this->set_headers();
        echo $data;
        exit;
    }

    public function set_headers() {
        header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
        header("Content-Type:".$this->_content_type);
    }

    private function get_status_message() {
        $status = array(
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            404 => 'Not Found',
            406 => 'Not Acceptable',
            401 => 'Unauthorized'
        );
        return ($status[$this->_code])?$status[$this->_code]:$status[500];
    }

    public function get_request_header() {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function inputs(){
        $this->_header = $this->get_request_header();
        switch($this->get_request_method()){
            case "POST":
                $this->_request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->_request = $this->cleanInputs($_GET);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"),$this->_request);
                $this->_request = $this->cleanInputs($this->_request);
                break;
            default:
                $this->response('',406);
                break;
        }
    }

    public function cleanInputs($data) {

        $clean_input = array();
        if(is_array($data)){

            foreach($data as $k => $v){
                $clean_input[$k] = $this->cleanInputs($v);
            }
        }else{
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }
    public function json($data) {
        if(is_array($data)){
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }

    public function get_app_details(){
        if($this->get_request_method() != "GET") $this->response('',406);
        $domain = getDomain();
        domainLogin($domain);
        $ads = json_decode($domain->manage_ads,true);
        $jsonObj = array();
        $jsonObj['app_name'] = $domain->domain_name;
        $jsonObj['app_logo'] = $domain->domain_name;
        $jsonObj['app_website'] = $domain->domain_web;
        $jsonObj['app_description'] = '';
        $jsonObj['app_privacy_policy'] = '';

        $jsonObj['ads'] = $domain->is_ads == 1 ;
        $jsonObj['click'] = 20;
        $jsonObj['publisher_id'] = ($ads && $ads['app_id']) ? $ads['app_id'] :  null;
        $jsonObj['interstital_ad_id'] = ($ads && $ads['interstitial_ads_id']) ? $ads['interstitial_ads_id'] :  'ca-app-pub-3940256099942544/1033173712';
        $jsonObj['banner_ad_id'] = ($ads && $ads['banner_ads_id']) ? $ads['banner_ads_id'] :  'ca-app-pub-3940256099942544/6300978111';
        $jsonObj['native_ad_id'] = ($ads && $ads['native_ads_id']) ? $ads['native_ads_id'] :  'ca-app-pub-3940256099942544/2247696110';
        $jsonObj['open_ad_id'] = ($ads && $ads['open_ads_id']) ? $ads['open_ads_id'] : 'ca-app-pub-3940256099942544/3419835294';
        $set['video-status-image'][] = $jsonObj;
        $this->response($this->json($set), 200);
    }


    public function get_featured_img_cat(){
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories =  $domain->categories()
            ->where('category_checked_ip',$isBlock)
            ->withCount('wallpapers')
            ->having('wallpapers_count', '>', 0)
            ->inRandomOrder()
            ->paginate(5);
        $getResource= CategoriesResource::collection($categories);
        $set['video-status-image'] = $getResource;
        $this->response($this->json($set), 200);
    }

    public function get_img_cat(){
        return $this->get_featured_img_cat();
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

    public function get_trending_gif_List(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = gifWallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_like_count','=',false,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalgif'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }

    public function get_home_gif_List(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = gifWallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id','=',true,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalgif'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }

    public function get_home_img_list(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id','<>',true,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalimage'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }

    public function get_home_img_list_recent(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'id','<>',false,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalimage'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }

    public function get_home_img_list_popular(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = WallpapersResource::collection($this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'wallpaper_view_count','<>',false,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalimage'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }

    public function get_img_list(){
        $cate_id = $_GET['cat_id'];
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $category = Categories::findOrFail($cate_id);
        $category->increment('category_view_count');
        $wallpapers = $category
            ->wallpapers()
            ->where('wallpaper_status',1)
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);

        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalimage'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = WallpapersResource::collection($wallpapers);
        $this->response($this->json($dataResult), 200);
    }

    public function wallpaper_download_count() {
        $image_id = $_POST['wallpaper_id'];
        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->increment('wallpaper_download_count');
            $set['video-status-image'] = array( 'success' => '1', 'message' => 'wallpaper downloads count updated');
            $this->response($this->json($set), 200);
        } else {
            $respon = array( 'success' => 'failed', '0' => 'Oops, API Key is Incorrect!');
            $this->response($this->json($respon), 404);
        }
    }

    public function wallpaper_view_count() {
        $image_id = $_POST['wallpaper_id'];
        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->increment('wallpaper_view_count');
            $set['video-status-image'] = array( 'success' => '1', 'message' => 'wallpaper view count updated','view'=>$wallpaper);;

            $this->response($this->json($set), 200);
        } else {
            $respon = array( 'success' => 'failed', '0' => 'Oops, API Key is Incorrect!');
            $this->response($this->json($respon), 404);
        }

    }


    public function get_img_search(){
        $search = '%' .$_GET['search_value'].'%' ;
        $page =  $_GET['page'] ?? 1;
        $length = 10;
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $data = $domain
            ->getWallpaper($isBlock)
            ->where('wallpaper_name','like',$search)
            ->where('wallpaper_status',1)
            ->paginate($length, ['*'], 'page', $page);;
        $getResource = WallpapersResource::collection($data);
        $set['page'] = $page;
        $set['totalimage'] = $data->total();
        $set['limit'] = '10';
        $set['success'] = '1';
        $set['video-status-image'] = $getResource;
        $this->response($this->json($set), 200);
    }

    public function get_ringtone_search(){
        $search = '%' .$_GET['search_value'].'%' ;
        $page =  $_GET['page'] ?? 1;
        $length = 10;
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $data = $domain
            ->getRingtone($isBlock)
            ->where('ringtone_name','like',$search)
            ->paginate($length, ['*'], 'page', $page);
        $getResource = RingtonesResource::collection($data);
        $set['page'] = $page;
        $set['totalimage'] = $data->total();
        $set['limit'] = '10';
        $set['success'] = '1';
        $set['video-status-image'] = $getResource;
        $this->response($this->json($set), 200);
    }

    private function getRingtonesByCriteria($isBlock, $orderBy,  $random = false, $page = 1, $length = 10) {
        $domain = getDomain();
        $query = $domain
            ->getRingtone($isBlock);
        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }
        return $query->paginate($length, ['*'], 'page', $page);
    }

    public function get_ringtone_List(){
        $cate_id = $_GET['cat_id'];
        $page = $_GET['page'] ?? 1;
        $length = 20;
        $category = Categories::findOrFail($cate_id);
        $category->increment('category_view_count');
        $ringtones = $category
            ->ringtones()
            ->distinct()
            ->inRandomOrder()
            ->paginate($length, ['*'], 'page', $page);

        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalringtone'] = $ringtones->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = WallpapersResource::collection($ringtones);
        $this->response($this->json($dataResult), 200);
    }

    public function get_home_ringtone_List(){
        $page =  $_GET['page'] ?? 1;
        $wallpapers = RingtonesResource::collection($this->getRingtonesByCriteria(checkBlockIp() ? 0 : 1, 'id',true,$page));
        $dataResult['page'] = $page;
        $dataResult['limit'] = 10;
        $dataResult['totalringtone'] = $wallpapers->total();
        $dataResult['success'] = 1;
        $dataResult['video-status-image'] = $wallpapers;
        $this->response($this->json($dataResult), 200);
    }
    public function get_featured_ringtone_cat(){
        return $this->get_ringtone_cate();
    }

    public function get_ringtone_cat(){
        $domain = getDomain();
        $isBlock = checkBlockIp() ? 0 : 1;
        $categories =  $domain->categories()
            ->where('category_checked_ip',$isBlock)
            ->withCount('ringtones')
            ->having('ringtones_count', '>', 0)
            ->inRandomOrder()
            ->paginate(5);
        $getResource= RingtonesCategoriesResource::collection($categories);
        $set['video-status-image'] = $getResource;
        $this->response($this->json($set), 200);
    }


    public function ringtone_download_count() {
        $ringtone_id = $_POST['ringtone_id'];
        $ringtone = Ringtones::find($ringtone_id);
        if ($ringtone) {
            $ringtone->increment('ringtone_download_count');
            $set['video-status-image'] = array( 'success' => '1', 'message' => 'wallpaper downloads count updated');
            $this->response($this->json($set), 200);
        } else {
            $respon = array( 'success' => 'failed', '0' => 'Oops, API Key is Incorrect!');
            $this->response($this->json($respon), 404);
        }
    }




}
