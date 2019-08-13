<?php

namespace App\Http\Controllers\API\Admin;

use App\Photo;
use App\Category;
use App\Tag;
use App\Http\Resources\Photo as PhotoResource;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => Photo::get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'file' => 'required',
            'files.*' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:10240'
        ]);

        $save_path = 'skinali/'. $request->get('id');
            
        // use imagick configuration
        // Image::configure(array('driver' => 'imagick'));
        
        $photo = Photo::where('id', $request->get('id'))->firstOrFail();

        // Original
        $original = Image::make($request->file('file'))->encode('jpg', 100);
        Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '-original.jpg', $original);
        $photo->properties = [
            'images' => [
                'original' => Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '-original.jpg', $original)
            ]
        ];
        

        // create resize image
        $skinali = Image::make($request->file('file'))
                ->resize(1200, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg', 100);

        $skinali_preview = Image::make($request->file('file'))
                ->resize(600, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg', 90);

        $skinali_lazy = Image::make($request->file('file'))
                ->resize(20, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg', 90);

        Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '.jpg', $skinali);
        Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '_preview.jpg', $skinali_preview);
        Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '_lazy.jpg', $skinali_lazy);

        // WEBP Resize
        // $skinali_webp = Image::make($request->file('file'))
        //         ->resize(1200, null, function ($constraint) { $constraint->aspectRatio(); } )
        //         ->encode('webp', 100);

        // $skinali_preview_webp = Image::make($request->file('file'))
        //         ->resize(600, null, function ($constraint) { $constraint->aspectRatio(); } )
        //         ->encode('webp', 90);

        // $skinali_lazy_webp = Image::make($request->file('file'))
        //         ->resize(20, null, function ($constraint) { $constraint->aspectRatio(); } )
        //         ->encode('webp', 90);

        // Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '.webp', $skinali_webp);
        // Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '_preview.webp', $skinali_preview_webp);
        // Storage::disk('s3')->put($save_path . '/skinali-' . $request->get('id') . '_lazy.webp', $skinali_lazy_webp);


        $photo->image_path = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.jpg', $skinali);
        $photo->image_preview_path = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.jpg', $skinali_preview);
        $photo->image_lazy = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.jpg', $skinali_lazy);

        // webp save
        // $photo->image_path_webp = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.webp', $skinali_webp);
        // $photo->image_preview_path_webp = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.webp', $skinali_preview_webp);
        // $photo->image_lazy_webp = Storage::disk('s3')->url($save_path . '/skinali-' . $request->get('id') . '.webp', $skinali_lazy_webp);
        $photo->save();
        return response()->json([
            'success' => true,
            'data' => $photo
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = new PhotoResource(Photo::where('id', $id)
                ->with('photoCategory')
                ->with('photoTag')
                ->firstOrFail());

        $category = Category::where('active', 1)->get();
        $tags = Tag::get()->pluck('name');

        return response()->json([
            'data' => $photo,
            'category' => $category,
            'tags' => $tags,
        ] ,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id == 0) 
        {
            $photo = Photo::create();
            return response()->json([
                'data' => $photo->id,
            ], 200);
        }

        $request->validate([
            'name' => 'required',
            'about' => 'required',
            'photo_tags.*' => 'required',
            'photo_category.*' => 'required',
            'keywords' => 'required',
            'description' => 'required'
        ]);

        $photo = Photo::with('photoCategory')
                    ->with('photoTag')
                    ->findOrFail($id);

        $photo->slug = null;
        $photo->update($request->only('name', 'slug', 'about', 'active', 'keywords', 'description', 'name_photo'));

        $photo->photoTag()->detach();

        foreach ($request->photo_tags as $tag) {
            $newTag = Tag::firstOrNew(['name' => $tag]);
            $newTag->name = $tag;
            $newTag->save();
            $photo->photoTag()->syncWithoutDetaching( $newTag->id );
        }

        $photo->photoCategory()->sync($request->photo_category);
        $photo->photoCategory->pluck('id');

        $category = Category::where('active', 1)->get();
        $tags = Tag::get()->pluck('name');

        return response()->json([
            'data' => $photo,
            'category' => $category,
            'tags' => $tags,
        ] ,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Photo::destroy($id);
        $storage = Storage::disk('s3')->deleteDirectory('skinali/' . $id);
        return response()->json([
            
        ], 200);
    }
}
