<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V10\CategoriesResource;
use App\Http\Resources\V10\WallpapersResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class V10Controller extends Controller
{
    public function index()
    {
        return response()->json(["status" => "ok", "message" => "V10 API is healthy"]);
    }

    private function getActiveCategories($limit, $isBlock)
    {
        return getDomain()->categories()
            ->where('category_checked_ip', $isBlock)
            ->withCount('wallpapers')
            ->having('wallpapers_count', '>', 0)
            ->inRandomOrder()
            ->paginate($limit);
    }

    public function categories(Request $request)
    {
        $limit = $request->input('limit', 5);
        $categories = $this->getActiveCategories($limit, checkBlockIp() ? 0 : 1);
//        dd(checkBlockIp());

        return response()->json([
            'page' => $categories->currentPage(),
            'limit' => (int)$limit,
            'total' => $categories->total(),
            'categories' => CategoriesResource::collection($categories)
        ]);
    }

    public function wallpapers(Request $request)
    {
        // Lấy page và pageSize từ Request. Ưu tiên dùng 'cursor' làm page nếu client gửi cursor
        $page = $request->input('cursor', $request->input('page', 1));
        $pageSize = $request->input('pageSize', 20);

        $wallpapers = WallpapersResource::collection(
            $this->getWallpapersByCriteria(checkBlockIp() ? 0 : 1, 'created_at', '<>', false, $page, $pageSize)
        );

        return response()->json([
            'page' => $wallpapers->currentPage(),
            'pageSize' => $wallpapers->perPage(), // perPage() trả về số lượng item trên 1 trang
            'total' => $wallpapers->total(),
            'nextCursor' => $wallpapers->hasMorePages() ? (string)($wallpapers->currentPage() + 1) : null,
            'items' => $wallpapers
        ]);
    }

    private function getWallpapersByCriteria($isBlock, $orderBy, $operator, $random = false, $page = 1, $length = 10, $category = null) {
        if ($category) {
            $query = $category->wallpapers();
        } else {
            $domain = getDomain();
            $query = $domain->getWallpaper($isBlock);
        }

        $query = $query->where('wallpaper_extension', $operator, 'image/gif')
                       ->where('wallpaper_status', 1);

        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderByDesc($orderBy);
        }

        if (is_null($page)) {
            return $query->take($length)->get();
        }

        return $query->paginate($length, ['*'], 'page', $page);
    }

    public function wallpaper($idOrSlug)
    {
        $domain = getDomain();
        $wallpaper = $domain->getWallpaper(checkBlockIp() ? 0 : 1)->findOrFail($idOrSlug);
        $wallpaper->increment('wallpaper_view_count');

        return response()->json((new WallpapersResource($wallpaper))->resolve());
    }

    private function buildCategoryWallpapersResponse(Request $request, $formatAsArray = false)
    {
        $isBlock = checkBlockIp() ? 0 : 1;
        $limit = $request->input('limit', 10);
        $limitWallpaper = $request->input('limit_wallpaper', 10);

        $categories = $this->getActiveCategories($limit, $isBlock);

        $categoriesData = [];
        foreach ($categories as $category) {
            $wallpapers = $this->getWallpapersByCriteria($isBlock, 'created_at', '<>', true, null, $limitWallpaper, $category);
            $resolvedWallpapers = WallpapersResource::collection($wallpapers)->resolve();

            if ($formatAsArray) {
                $categoriesData[] = [
                    "name_category" => $category->category_name,
                    "id" => (string)$category->id,
                    "display_name" => ucfirst($category->category_name),
                    "description" => $category->category_description ?? "",
                    "wallpapers" => $resolvedWallpapers
                ];
            } else {
                $categoriesData[$category->category_name] = $resolvedWallpapers;
            }
        }

        $response = [
            'page' => $categories->currentPage(),
            'limit' => (int)$limit,
            'total' => $formatAsArray ? $categories->total() : (string)$categories->total(),
            'categories' => $formatAsArray ? $categoriesData : (object)$categoriesData
        ];

        if ($formatAsArray) {
            $response['limit_wallpaper'] = (int)$limitWallpaper;
        }

        return response()->json($response);
    }

    public function wallpapersByCategories(Request $request)
    {
        return $this->buildCategoryWallpapersResponse($request, false);
    }

    public function preload(Request $request)
    {
        return $this->buildCategoryWallpapersResponse($request, true);
    }


    public function admob(){
        $domain = getDomain();
        domainLogin($domain);
        $ads = json_decode($domain->manage_ads,true);

        $defaultConfigJson = '{"listTempleNative":["Normal_full","Normal","Normal_cta_top","Normal_logoicon_topmiddle","Normal_small_cta_bot","Medium_cta_bot","Medium_cta_right","Small_cta_right","Small_cta_bot","Only_cta"],"des":"default config","listConfig":[{"nameConfig":"inter_splash_high","isOn":true,"type":"inter","adUnitId":"ca-app-pub-3940256099942544/1033173712","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"Mỗi lần mở app -> sau khi show splash screen -> show this ad"},{"nameConfig":"inter_splash","isOn":true,"type":"inter","adUnitId":"ca-app-pub-3940256099942544/1033173712","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho inter_splash_high"},{"nameConfig":"open_splash_high","isOn":true,"type":"aoa","adUnitId":"ca-app-pub-3940256099942544/9257395921","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"Mỗi lần mở app -> sau khi show splash screen -> show this ad"},{"nameConfig":"open_splash","isOn":true,"type":"aoa","adUnitId":"ca-app-pub-3940256099942544/9257395921","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho open_splash_high"},{"nameConfig":"banner_splash","isOn":true,"type":"banner","adUnitId":"ca-app-pub-3940256099942544/6300978111","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"mỗi lần mở app thì show banner ở bottom splash"},{"nameConfig":"open_resume","isOn":true,"type":"aoa","adUnitId":"ca-app-pub-3940256099942544/9257395921","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show ads open app resume ngoại trừ các màn: splash, language, onboarding"},{"nameConfig":"native_language","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show ads này ở bottom tab screen native language"},{"nameConfig":"native_language_selected_high","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show ads này ở bottom tab screen native language selected"},{"nameConfig":"native_language_selected","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho native_language_selected_high"},{"nameConfig":"native_onboarding_2_high","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show native này tại bottom của screen onboarding thứ 2"},{"nameConfig":"native_onboarding_2","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho native_onboarding_2_high"},{"nameConfig":"native_onboarding_3_high","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show native này tại bottom của screen onboarding thứ 3"},{"nameConfig":"native_onboarding_3","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho native_onboarding_3_high"},{"nameConfig":"native_style_high","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show ad này nếu match được tại bottom của style"},{"nameConfig":"native_style","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho native_style_high"},{"nameConfig":"banner_home","isOn":true,"type":"banner","adUnitId":"ca-app-pub-3940256099942544/6300978111","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show ở bottom của các tab"},{"nameConfig":"native_home","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show native giữa hàng 1 và 2 của item"},{"nameConfig":"inter_back","isOn":true,"type":"inter","adUnitId":"ca-app-pub-3940256099942544/1033173712","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"inter on back"},{"nameConfig":"inter_see_more","isOn":true,"type":"inter","adUnitId":"ca-app-pub-3940256099942544/1033173712","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show inter khi user click > ở các category"},{"nameConfig":"banner_list_item","isOn":true,"type":"banner","adUnitId":"ca-app-pub-3940256099942544/6300978111","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"banner đặt ở chỗ show tất cả các item của chủ đề được chọn, type là collapsible banner"},{"nameConfig":"inter_item","isOn":true,"type":"inter","adUnitId":"ca-app-pub-3940256099942544/1033173712","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"inter show khi click vào item"},{"nameConfig":"native_item","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Small_cta_bot","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"native small khi show tại item (chỗ tab có thể scroll được)"},{"nameConfig":"native_full_scroll_item","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal_full","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"native full screen khi lướt đến n wallpaper"},{"nameConfig":"native_popup","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"native show ở bottom các popup"},{"nameConfig":"reward_apply_wallpaper","isOn":true,"type":"rewarded","adUnitId":"ca-app-pub-3940256099942544/5224354917","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"fallback cho reward_apply_wallpaper_high"},{"nameConfig":"reward_apply_wallpaper_high","isOn":true,"type":"rewarded","adUnitId":"ca-app-pub-3940256099942544/5224354917","ctaColor":"","layoutTemplate":"","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"show khi set wallpaper"},{"nameConfig":"native_success","isOn":true,"type":"native","adUnitId":"ca-app-pub-3940256099942544/2247696110","ctaColor":"","layoutTemplate":"Normal","backGroundColor":"","textContentColor":"","textCTAColor":"","des":"native chèn ở màn success"}]}';

        $configData = json_decode($defaultConfigJson, true);

        $admob_banner = ($ads && isset($ads['banner_ads_id'])) ? $ads['banner_ads_id'] : 'ca-app-pub-3940256099942544/6300978111';
        $admob_interstitial = ($ads && isset($ads['interstitial_ads_id'])) ? $ads['interstitial_ads_id'] : 'ca-app-pub-3940256099942544/1033173712';
        $admob_open = ($ads && isset($ads['open_ads_id'])) ? $ads['open_ads_id'] : 'ca-app-pub-3940256099942544/3419835294';
        $admob_native = ($ads && isset($ads['native_ads_id'])) ? $ads['native_ads_id'] : 'ca-app-pub-3940256099942544/2247696110';
        $admob_reward = ($ads && isset($ads['rewarded_ads_id'])) ? $ads['rewarded_ads_id'] : 'ca-app-pub-3940256099942544/5224354917';
        
        $isAdsOn = (bool)$domain->is_ads;

        foreach ($configData['listConfig'] as &$item) {
            $item['isOn'] = $isAdsOn;
            switch ($item['type']) {
                case 'banner':
                    $item['adUnitId'] = $admob_banner;
                    break;
                case 'inter':
                    $item['adUnitId'] = $admob_interstitial;
                    break;
                case 'aoa':
                    $item['adUnitId'] = $admob_open;
                    break;
                case 'native':
                    $item['adUnitId'] = $admob_native;
                    break;
                case 'rewarded':
                    $item['adUnitId'] = $admob_reward;
                    break;
            }
        }

        // Return the modified configuration directly
        return response()->json($configData);
    }
}
