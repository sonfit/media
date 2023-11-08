<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallpapers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Mavinoo\Batch\Batch;
use Stevebauman\Purify\Facades\Purify;

class WallpapersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->except('compareImages');
    }

    public function index()
    {
        if(!Auth::user()->can('admin.wallpapers')){
            abort(403);
        }
        $data['title'] = 'Wallpapers';
        return view('admin.wallpapers.index', $data);
    }

    public function getIndex(Request $request)
    {
        if(!Auth::user()->can('admin.wallpapers')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $page = $request->input('page');
        $length = $request->input('length');
        $search = $request->input('search');
        $domainsQuery = Wallpapers::with('tags')->orderBy('wallpaper_image', 'desc');
        if($search !='null' ){
            $domainsQuery->where('wallpaper_name','like','%' . $search . '%')
            ->orwhereHas('tags', function($query) use ($search) {
                $query->where('tag_name', 'like','%' . $search . '%');
            });
        }
        $records = $domainsQuery->paginate($length, ['*'], 'page', $page);
        return json_encode($records);
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('admin.wallpapers.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'wallpaper_tags' => 'required',
            'wallpaper_upload.*' => 'required|image|mimes:jpeg,png,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        if ($request->hasFile('wallpaper_upload')) {
            DB::beginTransaction();
            try {
                $now = now();
                $monthYear = $now->format('FY');
                $path_origin = storage_path('app/public/wallpapers/originals/' . $monthYear . '/');
                $path_thumbnails = storage_path('app/public/wallpapers/thumbnails/' . $monthYear . '/');

                createDirectory($path_origin);
                createDirectory($path_thumbnails);

                $file = $request->file('wallpaper_upload');
//                foreach ($request->file('wallpaper_upload') as $file) {
                    $filename = uniqid('wallpaper_') . '.' . $file->getClientOriginalExtension();
                    list($img, $type, $mine) = $this->processImage($file, $path_origin, $path_thumbnails, $filename);
                    $hasher = new ImageHash(new DifferenceHash());
                    $hash = $hasher->hash($img)->toBits();
//                    $file->move($path_origin, $filename);
                    $wallpaper = Wallpapers::create([
                                    'wallpaper_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                                    'wallpaper_image' => $monthYear . '/' . $filename,
                                    'wallpaper_extension' => $mine,
                                    'wallpaper_hash' => $hash,
                                    'wallpaper_type' => $type,
                                ]);
                    $wallpaper->tags()->attach($request->input('wallpaper_tags'));
//                }
                DB::commit();
                return response()->json([
                    'success' => 'Saved Successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'error' => 'An error occurred while saving the wallpaper: ' . $e->getMessage(),
                ]);
            }
        }
    }

    private function processImage($file, $path_origin, $path_thumbnails, $filename) {
        $img = Image::make($file);
        if($img->mime() !== 'image/gif'){
            $thumb = Image::make($file);
            if ($img->width() == $img->height()) {
                $type = 'Square';
                $dimensions = [1300, 1300, 360, 360];
            } elseif ($img->width() > $img->height()) {
                $type = 'Landscape';
                $dimensions = [2400, 1300, 640, 360];
            } else {
                $type = 'Portrait';
                $dimensions = [1300, 2400 , 360, 640];
            }
            return [$this->resizeImage($img, $thumb, $path_origin, $path_thumbnails, $filename, $dimensions), $type, $img->mime()];
        }else{
            copy($file->getRealPath(), $path_origin.$filename);
            copy($file->getRealPath(), $path_thumbnails.$filename);
            return [$file, 'Square', $img->mime()];
        }

    }

    private function resizeImage($img, $thumb, $path_origin, $path_thumbnails, $filename, $dimensions) {
        list($width, $height, $thumbWidth, $thumbHeight) = $dimensions;
        // Resize and save thumbnail
        $img->resize($width,$height);
        $img->save($path_origin . '/' .  $filename);
        // Resize and save original image
        $thumb->resize($thumbWidth,$thumbHeight);
        $thumb->save($path_thumbnails . '/' .  $filename, 75);
        return $thumb;
    }

    public function edit($id)
    {
        if(!Auth::user()->can('admin.wallpapers.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        if(!Auth::user()->can('admin.ipblock.create')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $wallpaper = Wallpapers::find($id);
        return response()->json($wallpaper);
    }

    public function update(Request $request)
    {
        dd(123);
        $domainData = Purify::clean($request->except('_token', '_method'));
        $id = $request->domain_id;
        $rules = [
            'domain_web' => 'required|unique:domains,domain_web,'.$id,
            'domain_name' => 'required',
            'domain_project' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()->all()]);
        }
        $result = Domain::findOrFail($id);
        $result->domain_web = trim($domainData['domain_web']);
        $result->domain_name = $domainData['domain_name'];
        $result->domain_project = $domainData['domain_project'];
        $result->is_publish = $domainData['domain_status'] == 1 ? 1 : 0 ;
        $result->is_ads = $domainData['isAds_status'] == 1 ? 1 : 0 ;
        $result->save();
        return response()->json([
            'success'=>'Updated Successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if(!Auth::user()->can('admin.wallpapers.delete')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $id = $request->id;
        $wallpaper = Wallpapers::find($id);
        $wallpaper->delete();
        return response()->json(['success'=>'Delete Successfully.']);
    }

    public function deleteTag(Request $request)
    {
        if(!Auth::user()->can('admin.wallpapers.edit')){
            return response()->json([
                'error'=> 'Tài khoản không có quyền.',
            ]);
        }
        $wallpaper_id = $request->wallpaper_id;
        $tag_id = $request->tag_id;
        $wallpaper = Wallpapers::find($wallpaper_id);
        $wallpaper->tags()->detach([$tag_id]);
        return response()->json(['success' => 'Delete Successfully.']);
    }


    //===============================================================

    public function compareImages(Request $request){
        $order = $request->input('order', 'id');
        $value = $request->input('value', 5);
        $wallpaper_check = Wallpapers::where('wallpaper_status',0)->orderByDesc($order)->first();
        if(!$wallpaper_check){
            return 'Không còn ảnh để check';
        }
        $isDuplicateFound = false;
        echo 'wallpaper_check: '.$wallpaper_check->wallpaper_name.'<br>';
        Wallpapers::where('wallpaper_status','<>',0)->orderByDesc($order)->chunk(200, function ($wallpapers_compare) use ($wallpaper_check, $value, &$isDuplicateFound) {
            foreach ($wallpapers_compare as $item){
                $compareValue = $this->compare($wallpaper_check, $item);
                if ($compareValue < $value) {
                    echo 'wallpaper_compare: '.$wallpaper_check->wallpaper_name.'<br>';
                    $this->processDuplicateWallpaper($wallpaper_check, $item);
                    $isDuplicateFound = true;
                    return false; // This will break the foreach loop and prevent chunk() from fetching the next chunk.
                }
            }
            if ($isDuplicateFound) {
                return false; // This will prevent chunk() from fetching the next chunk if a duplicate is found.
            }
        });

        if (!$isDuplicateFound) {
            $wallpaper_check->wallpaper_status = 1;
            $wallpaper_check->save();
        }

        $time = $request->input('time', 2);
        if($request->input('action') == 'auto'){
            echo '<META http-equiv="refresh" content="'.$time.';URL=' . route('admin.wallpapers.compareImages') . '?order='.$order.'&action=auto&time='.$time.'">';
        }
    }

    private function processDuplicateWallpaper($wallpaper_check, $item) {

        $tags = $item->tags->pluck('id')->concat($wallpaper_check->tags->pluck('id'))->unique()->values()->toArray();
        $wallpaper_check->tags()->sync($tags);
        $wallpaper_check->wallpaper_status = 1;
        $item->delete();
        $wallpaper_check->save();
    }

    public function compare($image1, $image2): int
    {
        $hash1 = $image1->wallpaper_hash;
        $hash2 = $image2->wallpaper_hash;

        $length = max(strlen($hash1), strlen($hash2));
        // Add leading zeros so the bit strings are the same length.
        $hash1 = str_pad($hash1, $length, '0', STR_PAD_LEFT);
        $hash2 = str_pad($hash2, $length, '0', STR_PAD_LEFT);
        return count(array_diff_assoc(str_split($hash1), str_split($hash2)));
    }

}
