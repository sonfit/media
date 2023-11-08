<?php

use App\Models\Domain;
use App\Models\IPLIST;
use Carbon\Carbon;

use \Illuminate\Support\Str;
use Torann\GeoIP\Facades\GeoIP;
use hisorange\BrowserDetect\Parser as Browser;

function template($asset = false)
{
    $activeTheme = config('basic.theme');
    if ($asset) return 'assets/themes/' . $activeTheme . '/';
    return 'themes.' . $activeTheme . '.';
}


function recursive_array_replace($find, $replace, $array)
{
    if (!is_array($array)) {
        return str_replace($find, $replace, $array);
    }
    $newArray = [];
    foreach ($array as $key => $value) {
        $newArray[$key] = recursive_array_replace($find, $replace, $value);
    }
    return $newArray;
}

function menuActive($routeName, $type = null)
{
    $class = 'active';
    if ($type == 3) {
        $class = 'selected';
    } elseif ($type == 2) {
        $class = 'has-arrow active';
    } elseif ($type == 1) {
        $class = 'in';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function getFile($image, $clean = '')
{
    return file_exists($image) && is_file($image) ? asset($image) . $clean : asset(config('location.default'));
}

function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}

function loopIndex($object)
{
    return ($object->currentPage() - 1) * $object->perPage() + 1;
}

function strRandom($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse($date)->diffForHumans();
}

function dateTime($date, $format = 'd M, Y h:i A')
{
    return date($format, strtotime($date));
}

if (!function_exists('putPermanentEnv')) {
    function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}



function code($length = 6)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}



function wordTruncate($string, $offset = 0, $length = null): string
{
    $words = explode(" ", $string);
    isset($length) ? array_splice($words, $offset, $length) : array_splice($words, $offset);
    return implode(" ", $words);
}



function slug($title)
{
    return \Illuminate\Support\Str::slug($title);
}

function title2snake($string)
{
    return Str::title(str_replace(' ', '_', $string));
}

function snake2Title($string)
{
    return Str::title(str_replace('_', ' ', $string));
}

function kebab2Title($string)
{
    return Str::title(str_replace('-', ' ', $string));
}



function flagLanguage($data)
{
    return  '{'.rtrim($data, ',').'}';
}

function resourcePaginate($data,$callback){
    return $data->setCollection($data->getCollection()->map($callback));
}


function clean($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function camelToWord($str) {
    $arr =  preg_split('/(?=[A-Z])/',$str);
    return trim(join(' ',$arr));
}


function in_array_any($needles, $haystack) {
    return (bool) array_intersect($needles, $haystack);
}


function adminAccessRoute($search) {
    $list = collect(config('role'))->pluck('access')->flatten()->intersect(auth()->guard('admin')->user()->admin_access);

    if (is_array($search)) {
         $list = $list->intersect($search);
         if(0 < count($list)){
             return true;
         }
         return  false;
    } else {

        return $list->search(function($item) use ($search) {
            if($search == $item){
                return true;
            }
            return false;
        });
    }
}

function basicControl(){

    $general = \Illuminate\Support\Facades\Cache::get('ConfigureSetting');
    if (!$general) {
        $general = App\Models\Configure::firstOrCreate(['id' => 1]);
        \Illuminate\Support\Facades\Cache::put('ConfigureSetting', $general);
    }
    return $general;
}

if (!function_exists('getRoute')) {
    function getRoute($route, $params = null)
    {
        return isset($params) ? route($route, $params) : route($route);
    }
}

if (!function_exists('isMenuActive')) {
    function isMenuActive($routes, $type = 0)
    {
        $class = [
            '0' => 'active',
            '1' => 'style=display:block',
            '2' => true
        ];

        if (is_array($routes)) {
            foreach ($routes as $key => $route) {
                if (request()->routeIs($route)) {
                    return $class[$type];
                }
            }
        } elseif (request()->routeIs($routes)) {
            return $class[$type];
        }

        if ($type == 1){
            return 'style=display:none';
        }
        else{
            return false;
        }
    }
}

if (!function_exists('getTitle')) {
    function getTitle($title)
    {
        return ucwords(preg_replace('/[^A-Za-z0-9]/', ' ', $title));
    }
}

function hex2rgba($color, $opacity = false) {
    $default = 'rgb(0,0,0)';
    //Return default if no color provided
    if(empty($color))
        return $default;
    //Sanitize $color if "#" is provided
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }
    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }
    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);
    //Check if opacity is set(rgba or rgb)
    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }
    //Return rgb(a) color string
    return $output;
}

function colors(){

    $general = \Illuminate\Support\Facades\Cache::get('colors');
    if (!$general) {
        $general = App\Models\Color::firstOrCreate(['id' => 1]);
        \Illuminate\Support\Facades\Cache::put('colors', $general);
    }
    return $general;
}

function getDomain(){
    $domain = $_SERVER['SERVER_NAME'];
    return Domain::where('domain_web',$domain)->first();
}

function checkBlockIp(){
    $ipPrefix = getIpPrefix(getIp());
    $blockIps = IPLIST::where('ip_address', 'LIKE', "$ipPrefix%")->exists();
    return $blockIps;
}

function getIp() {
    $ip_headers = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
        'HTTP_CF_CONNECTING_IP'
    );

    foreach ($ip_headers as $header) {
        if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
            return $_SERVER[$header];
        }
    }
    return 'UNKNOWN';
}

function getIpPrefix($ip_address){
    $parts = explode('.', $ip_address);
    return implode('.', array_slice($parts, 0, 3));
}

function checkBlockIpClient($user, $ip_prefix)
{
    $allIpBlock = $user->getAllIpBlocks();
    $ip = $allIpBlock->first(function ($value, $key) use ($ip_prefix) {
        return strpos($value, $ip_prefix) === 0;
    });
    if ($ip) {
        $block = [
            'block' =>true,
        ] ;
    } else {
        $block = [
            'block' =>false,
        ] ;
    }
    return $block;
}

function transformArray($inputArray,$inputKey, $inputValue)
{
    //strtolower
    $resultArray = [];
    foreach ($inputArray[$inputKey] as $key => $isoCode) {
        if($isoCode !== null && $inputArray[$inputValue][$key] !== null || $isoCode ==='other'){
            $resultArray[$isoCode] = $inputArray[$inputValue][$key];
        }
    }

    return $resultArray;
}


const DEFAULT_PASSWORD = '3ba3f5f43b92602683c19aee62a20342';
const IV = '1234567890123456';
const METHOD = 'aes-256-cbc';

function encryptAES($data, $password = DEFAULT_PASSWORD) {
    $encrypted = openssl_encrypt($data, METHOD, $password, OPENSSL_RAW_DATA, IV);
    return base64_encode($encrypted);
}

function decryptAES($data, $password = DEFAULT_PASSWORD) {
    $decoded = base64_decode($data);
    return openssl_decrypt($decoded, METHOD, $password, OPENSSL_RAW_DATA, IV);
}

function createDirectory($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}


function domainLogin($domain){
    $geoIP =  new GeoIP();
    $agent = new Browser();
    $ip_address = getIp();
    $ip_prefix = getIpPrefix(getIp());
    $location = $geoIP::getLocation($ip_address);
    $domainLogin = [
        'domain_id' => $domain->id,
        'ip_address' => $ip_address,
        'ip_prefix' => $ip_prefix,
        'device_name' => $agent->deviceType(),
        'browser' => $agent->browserFamily(),
        'device_name_full' => $agent->userAgent(),
        'platform_name' => $agent->platformFamily(),
        'country' => (string) $location['country'],
        'created_at' => Carbon::now()->startOfDay()
    ];
    return $domain->iplist()->updateOrCreate($domainLogin)->increment('count', 1);
}



