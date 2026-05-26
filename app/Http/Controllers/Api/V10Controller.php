<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class V10Controller extends Controller
{
    public function index()
    {
        return response()->json(["status" => "ok", "message" => "V10 API is healthy"]);
    }

    public function categories()
    {
        return response()->json([
            "page" => 1,
            "limit" => 6,
            "total" => 44,
            "categories" => [
                ["name_slug" => "live", "display_name" => "Live"],
                ["name_slug" => "naruto", "display_name" => "naruto"],
                ["name_slug" => "one piece", "display_name" => "One Piece"],
                ["name_slug" => "dragon ball", "display_name" => "dragon ball"],
                ["name_slug" => "warriors", "display_name" => "Warriors"],
                ["name_slug" => "3d", "display_name" => "3D"]
            ]
        ]);
    }

    public function wallpapers()
    {
        return response()->json([
            "page" => 1,
            "pageSize" => 20,
            "total" => 3448,
            "nextCursor" => "1770008518706000000003403",
            "items" => [
                [
                    "id" => "3422",
                    "slug" => "live-vehicle-train-32-hd",
                    "title" => "Live-Vehicle-Train-32-hd.mp4",
                    "description" => "",
                    "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                    "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
                    "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                    "tags" => [],
                    "category" => "live",
                    "resolution" => null,
                    "created_at" => "2026-02-02T05:02:26.420Z",
                    "updated_at" => "2026-02-02T05:02:26.420Z"
                ],
                [
                    "id" => "3421",
                    "slug" => "live-vehicle-train-25-hd",
                    "title" => "Live-Vehicle-Train-25-hd.mp4",
                    "description" => "",
                    "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
                    "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd-thumbnail.mp4",
                    "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
                    "tags" => [],
                    "category" => "live",
                    "resolution" => null,
                    "created_at" => "2026-02-02T05:02:25.675Z",
                    "updated_at" => "2026-02-02T05:02:25.675Z"
                ]
            ]
        ]);
    }

    public function wallpaper($idOrSlug)
    {
        return response()->json([
            "id" => $idOrSlug,
            "slug" => "live-vehicle-train-32-hd",
            "title" => "Live-Vehicle-Train-32-hd.mp4",
            "description" => "",
            "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
            "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
            "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
            "tags" => [],
            "category" => "live",
            "resolution" => null,
            "created_at" => "2026-02-02T05:02:26.420Z",
            "updated_at" => "2026-02-02T05:02:26.420Z"
        ]);
    }

    public function wallpapersByCategories()
    {
        return response()->json([
            "page" => 1,
            "limit" => 10,
            "total" => "45",
            "categories" => [
                "live" => [
                    [
                        "id" => "3422",
                        "slug" => "live-vehicle-train-32-hd",
                        "category" => "live",
                        "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
                        "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                        "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                        "created_at" => "2026-02-02T05:02:26.420Z"
                    ],
                    [
                        "id" => "3421",
                        "slug" => "live-vehicle-train-25-hd",
                        "category" => "live",
                        "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd-thumbnail.mp4",
                        "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
                        "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
                        "created_at" => "2026-02-02T05:02:25.675Z"
                    ]
                ]
            ]
        ]);
    }

    public function preload()
    {
        return response()->json([
            "page" => 1,
            "limit" => 6,
            "total" => 44,
            "limit_wallpaper" => 6,
            "categories" => [
                [
                    "name_category" => "live",
                    "id" => "31",
                    "display_name" => "Live",
                    "description" => "",
                    "wallpapers" => [
                        [
                            "id" => "3422",
                            "slug" => "live-vehicle-train-32-hd",
                            "category" => "live",
                            "thumbnail_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
                            "main_image_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                            "asset_url" => "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
                            "created_at" => "2026-02-02T05:02:26.420Z"
                        ]
                    ]
                ]
            ]
        ]);
    }
}
