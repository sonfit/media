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
        $json = '{
    "live": [
      {
        "id": "3422",
        "slug": "live-vehicle-train-32-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-32-hd.mp4",
        "created_at": "2026-02-02T05:02:26.420Z"
      },
      {
        "id": "3421",
        "slug": "live-vehicle-train-25-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Train-25-hd.mp4",
        "created_at": "2026-02-02T05:02:25.675Z"
      },
      {
        "id": "3420",
        "slug": "live-vehicle-car-237-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-237-hd.mp4",
        "created_at": "2026-02-02T05:02:24.835Z"
      },
      {
        "id": "3419",
        "slug": "live-vehicle-car-175-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-175-hd.mp4",
        "created_at": "2026-02-02T05:02:23.831Z"
      },
      {
        "id": "3418",
        "slug": "live-vehicle-car-147-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-147-hd.mp4",
        "created_at": "2026-02-02T05:02:11.977Z"
      },
      {
        "id": "3417",
        "slug": "live-vehicle-car-146-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-146-hd.mp4",
        "created_at": "2026-02-02T05:02:11.240Z"
      },
      {
        "id": "3416",
        "slug": "live-vehicle-car-145-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Car-145-hd.mp4",
        "created_at": "2026-02-02T05:02:10.530Z"
      },
      {
        "id": "3415",
        "slug": "live-vehicle-boat-46-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Vehicle-Boat-46-hd.mp4",
        "created_at": "2026-02-02T05:02:09.775Z"
      },
      {
        "id": "3414",
        "slug": "live-tanjiro-kamado-demon-slayer-33-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Tanjiro Kamado-Demon Slayer-33-hd.mp4",
        "created_at": "2026-02-02T05:02:08.331Z"
      },
      {
        "id": "3413",
        "slug": "live-street-street-light-23-hd",
        "category": "live",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd-thumbnail.mp4",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd.mp4",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/live/Live-Street-Street Light-23-hd.mp4",
        "created_at": "2026-02-02T05:02:07.548Z"
      }
    ],
    "naruto": [
      {
        "id": "3446",
        "slug": "ultrahigh-fidelity-realism-2k-202602231218",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231218-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231218.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231218.jpeg",
        "created_at": "2026-02-24T10:33:17.122Z"
      },
      {
        "id": "3445",
        "slug": "ultrahigh-fidelity-realism-2k-202602231216",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231216-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231216.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231216.jpeg",
        "created_at": "2026-02-24T10:33:17.038Z"
      },
      {
        "id": "3444",
        "slug": "ultrahigh-fidelity-realism-2k-202602231214",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231214-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231214.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231214.jpeg",
        "created_at": "2026-02-24T10:33:16.952Z"
      },
      {
        "id": "3443",
        "slug": "ultrahigh-fidelity-realism-2k-202602231213",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231213-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231213.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231213.jpeg",
        "created_at": "2026-02-24T10:33:16.865Z"
      },
      {
        "id": "3442",
        "slug": "ultrahigh-fidelity-realism-2k-202602231202",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231202-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231202.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Ultrahigh_fidelity_realism_2k_202602231202.jpeg",
        "created_at": "2026-02-24T10:33:16.781Z"
      },
      {
        "id": "2448",
        "slug": "naruto-uzumaki-naruto-59-hd",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Uzumaki Naruto-59-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Uzumaki Naruto-59-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Uzumaki Naruto-59-hd.jpeg",
        "created_at": "2026-01-06T01:34:29.411Z"
      },
      {
        "id": "2447",
        "slug": "naruto-sasuke-uchiha-97-hd",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-97-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-97-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-97-hd.jpeg",
        "created_at": "2026-01-06T01:34:29.315Z"
      },
      {
        "id": "2446",
        "slug": "naruto-sasuke-uchiha-9-hd",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-9-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-9-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-9-hd.jpg",
        "created_at": "2026-01-06T01:34:29.218Z"
      },
      {
        "id": "2445",
        "slug": "naruto-sasuke-uchiha-4-hd",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-4-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-4-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-4-hd.jpeg",
        "created_at": "2026-01-06T01:34:29.120Z"
      },
      {
        "id": "2444",
        "slug": "naruto-sasuke-uchiha-17-hd",
        "category": "naruto",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-17-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-17-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/naruto/Naruto-Sasuke Uchiha-17-hd.jpg",
        "created_at": "2026-01-06T01:34:29.021Z"
      }
    ],
    "one piece": [
      {
        "id": "3456",
        "slug": "use-the-uploaded-2k-202602231300",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231300-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231300.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231300.jpeg",
        "created_at": "2026-02-24T10:33:20.752Z"
      },
      {
        "id": "3455",
        "slug": "use-the-uploaded-2k-202602231258",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231258-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231258.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231258.jpeg",
        "created_at": "2026-02-24T10:33:20.653Z"
      },
      {
        "id": "3454",
        "slug": "use-the-uploaded-2k-202602231257",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231257-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231257.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231257.jpeg",
        "created_at": "2026-02-24T10:33:20.556Z"
      },
      {
        "id": "3453",
        "slug": "use-the-uploaded-2k-202602231255",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231255-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231255.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231255.jpeg",
        "created_at": "2026-02-24T10:33:20.458Z"
      },
      {
        "id": "3452",
        "slug": "use-the-uploaded-2k-202602231253",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231253-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231253.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231253.jpeg",
        "created_at": "2026-02-24T10:33:20.360Z"
      },
      {
        "id": "3451",
        "slug": "use-the-uploaded-2k-202602231252",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231252-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231252.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231252.jpeg",
        "created_at": "2026-02-24T10:33:20.262Z"
      },
      {
        "id": "3450",
        "slug": "use-the-uploaded-2k-202602231250",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231250-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231250.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231250.jpeg",
        "created_at": "2026-02-24T10:33:20.164Z"
      },
      {
        "id": "3449",
        "slug": "use-the-uploaded-2k-202602231248",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231248-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231248.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231248.jpeg",
        "created_at": "2026-02-24T10:33:20.065Z"
      },
      {
        "id": "3448",
        "slug": "use-the-uploaded-2k-202602231246",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231246-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231246.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231246.jpeg",
        "created_at": "2026-02-24T10:33:19.967Z"
      },
      {
        "id": "3447",
        "slug": "use-the-uploaded-2k-202602231245",
        "category": "one piece",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231245-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231245.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/one piece/Use_the_uploaded_2k_202602231245.jpeg",
        "created_at": "2026-02-24T10:33:19.867Z"
      }
    ],
    "dragon ball": [
      {
        "id": "3441",
        "slug": "use-the-uploaded-2k-202602231239",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231239-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231239.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Use_the_uploaded_2k_202602231239.jpeg",
        "created_at": "2026-02-24T10:33:03.099Z"
      },
      {
        "id": "3440",
        "slug": "use-the-uploaded-2k-202602231237",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231237-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231237.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Use_the_uploaded_2k_202602231237.jpeg",
        "created_at": "2026-02-24T10:33:02.999Z"
      },
      {
        "id": "3439",
        "slug": "use-the-uploaded-2k-202602231235",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231235-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Use_the_uploaded_2k_202602231235.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Use_the_uploaded_2k_202602231235.jpeg",
        "created_at": "2026-02-24T10:33:02.872Z"
      },
      {
        "id": "3438",
        "slug": "ultrahigh-fidelity-realism-2k-202602231232",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231232-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231232.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231232.jpeg",
        "created_at": "2026-02-24T10:33:02.776Z"
      },
      {
        "id": "3437",
        "slug": "ultrahigh-fidelity-realism-2k-202602231229",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231229-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231229.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231229.jpeg",
        "created_at": "2026-02-24T10:33:02.683Z"
      },
      {
        "id": "3436",
        "slug": "ultrahigh-fidelity-realism-2k-202602231227",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231227-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231227.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231227.jpeg",
        "created_at": "2026-02-24T10:33:02.586Z"
      },
      {
        "id": "3435",
        "slug": "ultrahigh-fidelity-realism-2k-202602231224",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231224-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231224.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231224.jpeg",
        "created_at": "2026-02-24T10:33:02.480Z"
      },
      {
        "id": "3434",
        "slug": "ultrahigh-fidelity-realism-2k-202602231222",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231222-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231222.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231222.jpeg",
        "created_at": "2026-02-24T10:33:02.335Z"
      },
      {
        "id": "3433",
        "slug": "ultrahigh-fidelity-realism-2k-202602231221",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231221-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Ultrahigh_fidelity_realism_2k_202602231221.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon-ball/Ultrahigh_fidelity_realism_2k_202602231221.jpeg",
        "created_at": "2026-02-24T10:33:02.237Z"
      },
      {
        "id": "1284",
        "slug": "dragon-ball-vegito-super-saiyan-6-hd",
        "category": "dragon ball",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Dragon Ball-Vegito-Super Saiyan-6-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Dragon Ball-Vegito-Super Saiyan-6-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/dragon ball/Dragon Ball-Vegito-Super Saiyan-6-hd.jpeg",
        "created_at": "2026-01-06T01:32:34.021Z"
      }
    ],
    "warriors": [
      {
        "id": "3432",
        "slug": "use-the-uploaded-2k-202602231505",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231505-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231505.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231505.jpeg",
        "created_at": "2026-02-24T10:27:20.931Z"
      },
      {
        "id": "3431",
        "slug": "use-the-uploaded-2k-202602231352",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231352-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231352.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231352.jpeg",
        "created_at": "2026-02-24T10:27:20.835Z"
      },
      {
        "id": "3430",
        "slug": "use-the-uploaded-2k-202602231347",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231347-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231347.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231347.jpeg",
        "created_at": "2026-02-24T10:27:20.741Z"
      },
      {
        "id": "3429",
        "slug": "use-the-uploaded-2k-202602231345",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231345-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231345.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231345.jpeg",
        "created_at": "2026-02-24T10:27:20.646Z"
      },
      {
        "id": "3428",
        "slug": "use-the-uploaded-2k-202602231341",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231341-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231341.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231341.jpeg",
        "created_at": "2026-02-24T10:27:20.551Z"
      },
      {
        "id": "3427",
        "slug": "use-the-uploaded-2k-202602231318",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231318-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231318.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231318.jpeg",
        "created_at": "2026-02-24T10:27:20.458Z"
      },
      {
        "id": "3426",
        "slug": "use-the-uploaded-2k-202602231315",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231315-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231315.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231315.jpeg",
        "created_at": "2026-02-24T10:27:20.348Z"
      },
      {
        "id": "3425",
        "slug": "use-the-uploaded-2k-202602231313",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313.jpeg",
        "created_at": "2026-02-24T10:27:20.251Z"
      },
      {
        "id": "3424",
        "slug": "use-the-uploaded-2k-202602231313--1-",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313 (1)-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313 (1).jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231313 (1).jpeg",
        "created_at": "2026-02-24T10:27:20.156Z"
      },
      {
        "id": "3423",
        "slug": "use-the-uploaded-2k-202602231306",
        "category": "warriors",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231306-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231306.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/warriors/Use_the_uploaded_2k_202602231306.jpeg",
        "created_at": "2026-02-24T10:27:20.049Z"
      }
    ],
    "3d": [
      {
        "id": "3085",
        "slug": "3d-1",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-1.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-1.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-1.jpg",
        "created_at": "2026-01-06T10:50:55.565Z"
      },
      {
        "id": "3084",
        "slug": "3d-3",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-3.png",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-3.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-3.png",
        "created_at": "2026-01-06T10:50:38.979Z"
      },
      {
        "id": "3082",
        "slug": "3d-2",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-2.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-2.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-2.jpg",
        "created_at": "2026-01-06T10:49:30.554Z"
      },
      {
        "id": "192",
        "slug": "3d-tokyo-ghoul-ken-8-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Tokyo Ghoul-Ken-8-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Tokyo Ghoul-Ken-8-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Tokyo Ghoul-Ken-8-hd.jpeg",
        "created_at": "2026-01-06T01:30:38.375Z"
      },
      {
        "id": "191",
        "slug": "3d-shoto-todoroki-20-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Shoto Todoroki-20-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Shoto Todoroki-20-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Shoto Todoroki-20-hd.jpeg",
        "created_at": "2026-01-06T01:30:38.277Z"
      },
      {
        "id": "190",
        "slug": "3d-pokemon-pikachu-41-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-Pikachu-41-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-Pikachu-41-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-Pikachu-41-hd.jpeg",
        "created_at": "2026-01-06T01:30:38.180Z"
      },
      {
        "id": "189",
        "slug": "3d-pokemon-13-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-13-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-13-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-Pokemon-13-hd.jpeg",
        "created_at": "2026-01-06T01:30:38.081Z"
      },
      {
        "id": "188",
        "slug": "3d-one-piece-zoro-11-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Zoro-11-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Zoro-11-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Zoro-11-hd.jpeg",
        "created_at": "2026-01-06T01:30:37.983Z"
      },
      {
        "id": "187",
        "slug": "3d-one-piece-luffy-52-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-52-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-52-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-52-hd.jpeg",
        "created_at": "2026-01-06T01:30:37.883Z"
      },
      {
        "id": "186",
        "slug": "3d-one-piece-luffy-51-hd",
        "category": "3d",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-51-thumb.jpeg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-51-hd.jpeg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/3d/3D-One Piece-Luffy-51-hd.jpeg",
        "created_at": "2026-01-06T01:30:37.782Z"
      }
    ],
    "abstract": [
      {
        "id": "3088",
        "slug": "abstract-3D-3",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-3.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-3.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-3.jpg",
        "created_at": "2026-01-06T10:57:06.793Z"
      },
      {
        "id": "3087",
        "slug": "abstract-3D-2",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-2.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-2.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/abstract-2.jpg",
        "created_at": "2026-01-06T10:57:06.789Z"
      },
      {
        "id": "3086",
        "slug": "abstract-3D-1",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-1.png",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-1.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-1.png",
        "created_at": "2026-01-06T10:57:06.779Z"
      },
      {
        "id": "312",
        "slug": "abstract-macos-sonoma-stock-macbook-air-2023-11573-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-macOS Sonoma-Stock-MacBook Air 2023-11573-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-macOS Sonoma-Stock-MacBook Air 2023-11573-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-macOS Sonoma-Stock-MacBook Air 2023-11573-hd.jpg",
        "created_at": "2026-01-06T01:30:50.437Z"
      },
      {
        "id": "311",
        "slug": "abstract-imac-2023-stock-5k-13249-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Stock-5K-13249-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Stock-5K-13249-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Stock-5K-13249-hd.jpg",
        "created_at": "2026-01-06T01:30:50.337Z"
      },
      {
        "id": "310",
        "slug": "abstract-imac-2023-red-aesthetic-stock-5k-13242-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Red aesthetic-Stock-5K-13242-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Red aesthetic-Stock-5K-13242-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Red aesthetic-Stock-5K-13242-hd.jpg",
        "created_at": "2026-01-06T01:30:50.238Z"
      },
      {
        "id": "309",
        "slug": "abstract-imac-2023-purple-abstract-stock-5k-13246-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Purple abstract-Stock-5K-13246-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Purple abstract-Stock-5K-13246-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Purple abstract-Stock-5K-13246-hd.jpg",
        "created_at": "2026-01-06T01:30:50.137Z"
      },
      {
        "id": "308",
        "slug": "abstract-imac-2023-orange-stock-5k-13245-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Orange-Stock-5K-13245-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Orange-Stock-5K-13245-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Orange-Stock-5K-13245-hd.jpg",
        "created_at": "2026-01-06T01:30:50.036Z"
      },
      {
        "id": "307",
        "slug": "abstract-imac-2023-official-stock-5k-13247-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Official-Stock-5K-13247-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Official-Stock-5K-13247-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Official-Stock-5K-13247-hd.jpg",
        "created_at": "2026-01-06T01:30:49.938Z"
      },
      {
        "id": "306",
        "slug": "abstract-imac-2023-golden-yellow-stock-5k-13244-hd",
        "category": "abstract",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Golden yellow-Stock-5K-13244-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Golden yellow-Stock-5K-13244-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/abstract/Abstract-iMac 2023-Golden yellow-Stock-5K-13244-hd.jpg",
        "created_at": "2026-01-06T01:30:49.838Z"
      }
    ],
    "ai": [
      {
        "id": "32",
        "slug": "ai-25-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-25-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-25-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-25-hd.png",
        "created_at": "2026-01-05T12:46:20.368Z"
      },
      {
        "id": "31",
        "slug": "ai-24-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-24-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-24-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-24-hd.png",
        "created_at": "2026-01-05T12:46:20.317Z"
      },
      {
        "id": "30",
        "slug": "ai-23-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-23-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-23-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-23-hd.png",
        "created_at": "2026-01-05T12:46:20.253Z"
      },
      {
        "id": "29",
        "slug": "ai-22-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-22-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-22-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-22-hd.png",
        "created_at": "2026-01-05T12:46:20.202Z"
      },
      {
        "id": "28",
        "slug": "ai-21-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-21-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-21-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-21-hd.png",
        "created_at": "2026-01-05T12:46:20.077Z"
      },
      {
        "id": "27",
        "slug": "ai-20-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-20-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-20-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-20-hd.png",
        "created_at": "2026-01-05T12:46:20.014Z"
      },
      {
        "id": "26",
        "slug": "ai-19-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-19-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-19-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-19-hd.png",
        "created_at": "2026-01-05T12:46:19.949Z"
      },
      {
        "id": "25",
        "slug": "ai-18-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-18-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-18-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-18-hd.png",
        "created_at": "2026-01-05T12:46:19.899Z"
      },
      {
        "id": "24",
        "slug": "ai-17-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-17-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-17-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-17-hd.png",
        "created_at": "2026-01-05T12:46:19.811Z"
      },
      {
        "id": "23",
        "slug": "ai-16-hd",
        "category": "ai",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-16-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-16-hd.png",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/ai/AI-16-hd.png",
        "created_at": "2026-01-05T12:46:19.761Z"
      }
    ],
    "animals": [
      {
        "id": "432",
        "slug": "animals-wild-tiger-kanha-national-park-india-8110-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Tiger-Kanha National Park-India-8110-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Tiger-Kanha National Park-India-8110-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Tiger-Kanha National Park-India-8110-hd.jpg",
        "created_at": "2026-01-06T01:31:03.819Z"
      },
      {
        "id": "431",
        "slug": "animals-wild-horses-pair-brown-horses-5694-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Horses-Pair-Brown Horses-5694-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Horses-Pair-Brown Horses-5694-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-Wild Horses-Pair-Brown Horses-5694-hd.jpg",
        "created_at": "2026-01-06T01:31:03.722Z"
      },
      {
        "id": "430",
        "slug": "animals-white-tiger-wild-animal-big-cat-5716-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Wild animal-Big cat-5716-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Wild animal-Big cat-5716-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Wild animal-Big cat-5716-hd.jpg",
        "created_at": "2026-01-06T01:31:03.624Z"
      },
      {
        "id": "429",
        "slug": "animals-white-tiger-roaring-zoo-rare-animals-5367-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Roaring-Zoo-Rare Animals-5367-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Roaring-Zoo-Rare Animals-5367-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Roaring-Zoo-Rare Animals-5367-hd.jpg",
        "created_at": "2026-01-06T01:31:03.526Z"
      },
      {
        "id": "428",
        "slug": "animals-white-tiger-black-background-5k-5368-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Black background-5K-5368-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Black background-5K-5368-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Black background-5K-5368-hd.jpg",
        "created_at": "2026-01-06T01:31:03.426Z"
      },
      {
        "id": "427",
        "slug": "animals-white-tiger-bamboo-leaves-zoo-5577-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Bamboo Leaves-Zoo-5577-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Bamboo Leaves-Zoo-5577-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White tiger-Bamboo Leaves-Zoo-5577-hd.jpg",
        "created_at": "2026-01-06T01:31:03.325Z"
      },
      {
        "id": "426",
        "slug": "animals-white-peacock-girly-backgrounds-9745-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White peacock-Girly backgrounds-9745-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White peacock-Girly backgrounds-9745-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White peacock-Girly backgrounds-9745-hd.jpg",
        "created_at": "2026-01-06T01:31:03.224Z"
      },
      {
        "id": "425",
        "slug": "animals-white-swiss-shepherd-dog-breed-dog-9102-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swiss Shepherd Dog-Breed Dog-9102-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swiss Shepherd Dog-Breed Dog-9102-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swiss Shepherd Dog-Breed Dog-9102-hd.jpg",
        "created_at": "2026-01-06T01:31:03.121Z"
      },
      {
        "id": "424",
        "slug": "animals-white-swan-love-birds-heart-shape-5487-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swan-Love Birds-Heart shape-5487-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swan-Love Birds-Heart shape-5487-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Swan-Love Birds-Heart shape-5487-hd.jpg",
        "created_at": "2026-01-06T01:31:03.021Z"
      },
      {
        "id": "423",
        "slug": "animals-white-butterflies-mystical-forest-moss-5689-hd",
        "category": "animals",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Butterflies-Mystical Forest-Moss-5689-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Butterflies-Mystical Forest-Moss-5689-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/animals/Animals-White Butterflies-Mystical Forest-Moss-5689-hd.jpg",
        "created_at": "2026-01-06T01:31:02.907Z"
      }
    ],
    "anime": [
      {
        "id": "3091",
        "slug": "anime-3",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-3.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-3.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-3.jpg",
        "created_at": "2026-01-06T11:01:11.572Z"
      },
      {
        "id": "3090",
        "slug": "anime-2",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-2.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-2.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-2.jpg",
        "created_at": "2026-01-06T11:01:11.571Z"
      },
      {
        "id": "3089",
        "slug": "anime-1",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-1.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-1.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-1.jpg",
        "created_at": "2026-01-06T11:01:11.571Z"
      },
      {
        "id": "546",
        "slug": "anime-zenitsu-agatsuma-ai-art-12533-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Zenitsu Agatsuma-AI art-12533-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Zenitsu Agatsuma-AI art-12533-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Zenitsu Agatsuma-AI art-12533-hd.jpg",
        "created_at": "2026-01-06T01:31:15.173Z"
      },
      {
        "id": "545",
        "slug": "anime-visored-ichigo-kurosaki-bleach-10580-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Visored-Ichigo Kurosaki-Bleach-10580-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Visored-Ichigo Kurosaki-Bleach-10580-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Visored-Ichigo Kurosaki-Bleach-10580-hd.jpg",
        "created_at": "2026-01-06T01:31:15.074Z"
      },
      {
        "id": "544",
        "slug": "anime-vegeta-super-saiyan-blue-13988-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Vegeta-Super Saiyan Blue-13988-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Vegeta-Super Saiyan Blue-13988-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Vegeta-Super Saiyan Blue-13988-hd.jpg",
        "created_at": "2026-01-06T01:31:14.976Z"
      },
      {
        "id": "543",
        "slug": "anime-tanjiro-kamado-water-breathing-12363-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12363-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12363-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12363-hd.jpg",
        "created_at": "2026-01-06T01:31:14.879Z"
      },
      {
        "id": "542",
        "slug": "anime-tanjiro-kamado-water-breathing-12114-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12114-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12114-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Water Breathing-12114-hd.jpg",
        "created_at": "2026-01-06T01:31:14.783Z"
      },
      {
        "id": "541",
        "slug": "anime-tanjiro-kamado-faceless-minimalist-11930-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Faceless-Minimalist-11930-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Faceless-Minimalist-11930-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Faceless-Minimalist-11930-hd.jpg",
        "created_at": "2026-01-06T01:31:14.686Z"
      },
      {
        "id": "540",
        "slug": "anime-tanjiro-kamado-cute-anime-ai-art-12045-hd",
        "category": "anime",
        "thumbnail_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Cute anime-AI art-12045-thumb.jpg",
        "main_image_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Cute anime-AI art-12045-hd.jpg",
        "asset_url": "https://assets.livewallpaperparallax.com/wallpapers/anime/Anime-Tanjiro Kamado-Cute anime-AI art-12045-hd.jpg",
        "created_at": "2026-01-06T01:31:14.589Z"
      }
    ]
  }';

        $array = json_decode($json, true);

        return response()->json([
            "page" => 1,
            "limit" => 10,
            "total" => "45",
            "categories" =>
                $array

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
