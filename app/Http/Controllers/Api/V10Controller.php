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

        $json = '{
"items": [
    {
      "id": "3422",
      "slug": "live-vehicle-train-32-hd",
      "title": "Live-Vehicle-Train-32-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:26.420Z",
      "updated_at": "2026-02-02T05:02:26.420Z"
    },
    {
      "id": "3421",
      "slug": "live-vehicle-train-25-hd",
      "title": "Live-Vehicle-Train-25-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:25.675Z",
      "updated_at": "2026-02-02T05:02:25.675Z"
    },
    {
      "id": "3420",
      "slug": "live-vehicle-car-237-hd",
      "title": "Live-Vehicle-Car-237-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:24.835Z",
      "updated_at": "2026-02-02T05:02:24.835Z"
    },
    {
      "id": "3419",
      "slug": "live-vehicle-car-175-hd",
      "title": "Live-Vehicle-Car-175-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:23.831Z",
      "updated_at": "2026-02-02T05:02:23.831Z"
    },
    {
      "id": "3418",
      "slug": "live-vehicle-car-147-hd",
      "title": "Live-Vehicle-Car-147-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:11.977Z",
      "updated_at": "2026-02-02T05:02:11.977Z"
    },
    {
      "id": "3417",
      "slug": "live-vehicle-car-146-hd",
      "title": "Live-Vehicle-Car-146-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:11.240Z",
      "updated_at": "2026-02-02T05:02:11.240Z"
    },
    {
      "id": "3416",
      "slug": "live-vehicle-car-145-hd",
      "title": "Live-Vehicle-Car-145-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:10.530Z",
      "updated_at": "2026-02-02T05:02:10.530Z"
    },
    {
      "id": "3415",
      "slug": "live-vehicle-boat-46-hd",
      "title": "Live-Vehicle-Boat-46-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:09.775Z",
      "updated_at": "2026-02-02T05:02:09.775Z"
    },
    {
      "id": "3414",
      "slug": "live-tanjiro-kamado-demon-slayer-33-hd",
      "title": "Live-Tanjiro Kamado-Demon Slayer-33-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:08.331Z",
      "updated_at": "2026-02-02T05:02:08.331Z"
    },
    {
      "id": "3413",
      "slug": "live-street-street-light-23-hd",
      "title": "Live-Street-Street Light-23-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:07.548Z",
      "updated_at": "2026-02-02T05:02:07.548Z"
    },
    {
      "id": "3412",
      "slug": "live-street-indicator-light-24-hd",
      "title": "Live-Street-Indicator Light-24-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-24-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-24-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-24-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:06.730Z",
      "updated_at": "2026-02-02T05:02:06.730Z"
    },
    {
      "id": "3411",
      "slug": "live-street-indicator-light-22-hd",
      "title": "Live-Street-Indicator Light-22-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-22-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-22-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Indicator Light-22-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:05.817Z",
      "updated_at": "2026-02-02T05:02:05.817Z"
    },
    {
      "id": "3410",
      "slug": "live-street-fench-25-hd",
      "title": "Live-Street-Fench-25-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Fench-25-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Fench-25-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Fench-25-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:05.004Z",
      "updated_at": "2026-02-02T05:02:05.004Z"
    },
    {
      "id": "3409",
      "slug": "live-shiori-anime-girl-19-hd",
      "title": "Live-Shiori-Anime Girl-19-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Shiori-Anime Girl-19-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Shiori-Anime Girl-19-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Shiori-Anime Girl-19-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:03.810Z",
      "updated_at": "2026-02-02T05:02:03.810Z"
    },
    {
      "id": "3408",
      "slug": "live-rengoku-demon-slayer-31-hd",
      "title": "Live-Rengoku-Demon Slayer-31-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Rengoku-Demon Slayer-31-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Rengoku-Demon Slayer-31-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Rengoku-Demon Slayer-31-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:03.053Z",
      "updated_at": "2026-02-02T05:02:03.053Z"
    },
    {
      "id": "3407",
      "slug": "live-one-piece-zoro-14-hd",
      "title": "Live-One Piece-Zoro-14-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Zoro-14-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Zoro-14-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Zoro-14-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:02.139Z",
      "updated_at": "2026-02-02T05:02:02.139Z"
    },
    {
      "id": "3406",
      "slug": "live-one-piece-luffy-straw-hat-2-hd",
      "title": "Live-One Piece-Luffy-Straw Hat-2-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Straw Hat-2-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Straw Hat-2-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Straw Hat-2-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:01.121Z",
      "updated_at": "2026-02-02T05:02:01.121Z"
    },
    {
      "id": "3405",
      "slug": "live-one-piece-luffy-gear-5-7-hd",
      "title": "Live-One Piece-Luffy-Gear 5-7-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Gear 5-7-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Gear 5-7-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Gear 5-7-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:02:00.257Z",
      "updated_at": "2026-02-02T05:02:00.257Z"
    },
    {
      "id": "3404",
      "slug": "live-one-piece-luffy-ace-35-hd",
      "title": "Live-One Piece-Luffy-Ace-35-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Ace-35-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Ace-35-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-Ace-35-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:01:59.491Z",
      "updated_at": "2026-02-02T05:01:59.491Z"
    },
    {
      "id": "3403",
      "slug": "live-one-piece-luffy-4-hd",
      "title": "Live-One Piece-Luffy-4-hd.mp4",
      "description": "",
      "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-4-hd.mp4",
      "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-4-hd-thumbnail.mp4",
      "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-One Piece-Luffy-4-hd.mp4",
      "tags": [],
      "category": "live",
      "resolution": null,
      "created_at": "2026-02-02T05:01:58.706Z",
      "updated_at": "2026-02-02T05:01:58.706Z"
    }
  ]
}';

        $array = json_decode($json, true);

        return response()->json([
            "page" => 1,
            "pageSize" => 20,
            "total" => 3448,
            "nextCursor" => "1770008518706000000003403",
             "items" => $array['items']
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
