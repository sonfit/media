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
//            ->where('category_checked_ip', $isBlock)
            ->withCount('wallpapers')
            ->having('wallpapers_count', '>', 0)
            ->inRandomOrder()
            ->paginate($limit);
    }

    public function categories(Request $request)
    {
        $limit = $request->input('limit', 5);
        $categories = $this->getActiveCategories($limit, checkBlockIp() ? 0 : 1);

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
}
