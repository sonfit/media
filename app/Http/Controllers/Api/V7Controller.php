<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallpapers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class V7Controller extends Controller
{
    public function getJson()
    {
        $basic = $this->basic();

        $defaultServer = [
            'adres' =>  $basic['domainWeb'],
            'search_tips' =>  $basic['domainWeb'],
            'img_lista' => $basic['wallpaperThumbUrl'].'/[ID]',
            'img_duze' => $basic['wallpaperThumbUrl'].'/[ID]',
            "img_share" => $basic['wallpaperUrl'].'/[ID]',
            "img_pobierz" => $basic['wallpaperUrl'].'/[ID]',
            "img_ustaw_na_ekranie" =>$basic['wallpaperUrl'].'/[ID]',
            "img_info" => $basic['domainWeb']
        ];

        $servers = [
            array_merge($defaultServer, ["pring<" => 9, "ping+" => 0])
        ];

        $data = [
            'connect' => "OK",
            'date' => time(),
            'przewijanie_prawo_lewo' => true,
            'przekieruj' => '',
            'adres_wyjdz' => $basic['domainWeb'],
            'reklama_full_ilosc' => 30,

            'reklama_full_pobierz' =>  $basic['isAds'],
            'reklama_full_ustaw' =>  $basic['isAds'],
            'reklama_full_ustaw_po' =>  $basic['isAds'],
            'reklama_full_share' =>  $basic['isAds'],
            'reklama_full_po_ptaszek' =>  $basic['isAds'],
            'reklama_lista' =>  $basic['isAds'],
            'reklama_podglad' =>  $basic['isAds'],

            'domyslny_server' => $defaultServer,
            'servers' => $servers,
            "Lista_new" => $this->getWallpapersByCriteria($basic['isBlock'], 'id','id'),
            "Lista_like" => $this->getWallpapersByCriteria($basic['isBlock'], 'wallpaper_like_count', 'id'),
            "Lista_download" => $this->getWallpapersByCriteria($basic['isBlock'], 'wallpaper_download_count', 'id'),
            "kadr_new" => $this->getWallpapersByCriteria($basic['isBlock'], 'id', 'wallpaper_view_count'),
            "kadr_like" => $this->getWallpapersByCriteria($basic['isBlock'], 'wallpaper_like_count', 'wallpaper_view_count'),
            "kadr_download" => $this->getWallpapersByCriteria($basic['isBlock'], 'wallpaper_download_count', 'wallpaper_view_count'),
        ];
        return $data;
    }

    public function getJsonV8()
    {
        $basic = $this->basic();

        $defaultServer = [
            'adres' => $basic['domainWeb'],
            'images_big' => $basic['wallpaperUrl'].'/[ID]',
            'images_set_wallpapers' => $basic['wallpaperUrl'].'/[ID]',
            'images_pobierz' => $basic['wallpaperThumbUrl'].'/[ID]',
            "img_share" => $basic['wallpaperUrl'].'/[ID]',
            "if_less_than" =>0,
            "ping_add" =>0,
        ];

        $servers = [
            array_merge($defaultServer, ["server_status<" => route('v8.status')])
        ];

        $data = [
            'data_gen' => time(),
            'disable_reports' => false,
            'guzik_pobierz' => false,
            'pokaz_wyjscie' => false,
            'pokaz_wyjscie_glosowanie' => false,
            'reklama_full_opcja_przerwa_sekund' => 0,
            'reklama_full_opcja_pokaz' => '11111',
            'reklama_full_loading_ms' => 300,
            'reklama_full_ilosc' => 30,

            'reklama_full_set_glowny' =>  $basic['isAds'] ? "przed" : false,
            'reklama_full_ustaw' =>  $basic['isAds'] ? "przed" : false,
            'reklama_full_pobierz' =>  $basic['isAds'] ? "przed" : false,
            'reklama_full_share' =>  $basic['isAds'] ? "przed" : false,
            'reklama_full_wiecej' =>  $basic['isAds'] ? "przed" : false,
            'reklama_dol' =>  $basic['isAds'] ? "przed" : false,
            'reklama_nad_guziki' =>  $basic['isAds'] ? "przed" : false,
            'blokuj_i_przekieruj' => '',

            'default_server' => $defaultServer,
            'serwery' => $servers,
            "new" => $this->getWallpapersByCriteria($basic['isBlock'], 'id','id'),
            "top" => $this->getWallpapersByCriteria($basic['isBlock'], 'wallpaper_like_count', 'id'),
        ];
        return $data;
    }

    private function getWallpapersByCriteria($isBlock, $orderBy,$pluck) {
        $domain = getDomain();
        $query = $domain
            ->getWallpaper($isBlock)
            ->where('wallpaper_extension', '<>', 'image/gif')
            ->where('wallpaper_status',1)
            ->orderByDesc($orderBy)
            ->get()->pluck($pluck)->toArray();
        return  implode(',',$query);
    }

    private function basic(){
        $domain = getDomain();
        domainLogin($domain);
        $isBlock = checkBlockIp() ? 0 : 1;
        $isAds = $domain->is_ads == 1;
        $domainWeb = $domain->domain_web;
        $wallpaperUrl = url('/api/wallpaper');
        $wallpaperThumbUrl = url('/api/wallpaperThumb');

        return [
            'isBlock' => $isBlock,
            'isAds' => $isAds,
            'domainWeb' => $domainWeb,
            'wallpaperUrl' => $wallpaperUrl,
            'wallpaperThumbUrl' => $wallpaperThumbUrl,
        ];
    }


    public function status(){
        return 'ok';
    }

    public function action(Request $request)
    {
        $wallpaper = Wallpapers::findOrFail($request->id);
        $wallpaper->increment('wallpaper_like_count', $request->lubi ? 1 : 0);
        $wallpaper->increment('wallpaper_download_count', $request->pobierz ? 1 : 0);
        return response()->json(['mgs' => 'success']);
    }


    public function showWallpaper($id){
        $wallpaper = Wallpapers::findOrFail($id);
        $wallpaper->increment('wallpaper_view_count');
        return response()->file(public_path('/storage/wallpapers/originals/').$wallpaper->wallpaper_image);
    }

    public function showWallpaperThumb($id){
        $wallpaper = Wallpapers::findOrFail($id);
        $wallpaper->increment('wallpaper_view_count');
        return response()->file(public_path('/storage/wallpapers/thumbnails/').$wallpaper->wallpaper_image);
    }

}
