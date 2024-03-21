<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResizeImageRequest;
use App\Http\Resources\V1\ImageManipulationResource;
use App\Models\Album;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ImageManipulationResource::collection(ImageManipulation::paginate());
    }
    
    public function getByAlbum(Album $album)
    {
        $where = [
            'album_id' => $album->id
        ];

        return ImageManipulationResource::collection(
            ImageManipulation::where($where)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function resize(ResizeImageRequest $request)
    {
        // $all = $request->all();
        $all = $request->validated();

        /** @var UploadedFile|string $image */
        $image = $all['image'];

        unset($all['image']); // cuz he saves the image in the 'data' column and he doesn't want the image to be there
        $data = [
            'type' => ImageManipulation::TYPE_RESIZE,
            'data' => json_encode($all),
            'user_id' => null
        ];

        if (isset($all['album_id'])) {
            // TODO authorization

            $data['album_id'] = $all['album_id'];
        }
        
        // create random dir in public/images
        $dir = 'images/' . Str::random() . '/';
        $absolutePath = public_path($dir);        
        File::makeDirectory($absolutePath);

        if ($image instanceof UploadedFile) {
            $data['name'] = $image->getClientOriginalName();
            $filename = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath . $data['name'];

            $data['path'] = $dir . $data['name'];
            $image->move($absolutePath, $data['name']);
        } else {
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath . $data['name'];

            $data['path'] = $dir . $data['name'];
            copy($image, $originalPath);
        }

        $w = $all['w'];
        $h = $all['h'] ?? false; // height is optional

        list($image, $width, $height) = $this->getWidthAndHeight($w, $h, $originalPath);

        $resizedFilename = $filename . '-resized.' . $extension;
        $image->resize($width, $height)->save($absolutePath . $resizedFilename);

        $data['output_path'] = $dir . $resizedFilename;

        $imageManipulation = ImageManipulation::create($data);

        // return $imageManipulation;
        return new ImageManipulationResource($imageManipulation);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImageManipulation $image)
    {
        return new ImageManipulationResource($image);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImageManipulation $image)
    {
        $image->delete();

        return response('', 204);
    }

    protected function getWidthAndHeight($w, $h, $originalPath)
    {
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if (str_ends_with($w, '%')) {
            $ratioW = (float)(str_replace('%', '', $w));
            $ratioH = $h ? (float)(str_replace('%', '', $h)) : $ratioW;
            $newWidth = $originalWidth * $ratioW / 100;
            $newHeight = $originalHeight * $ratioH / 100;
        } else {
            $newWidth = (float)$w;

            /**
             * $originalWidth  -  $newWidth
             * $originalHeight -  $newHeight
             * -----------------------------
             * $newHeight =  $originalHeight * $newWidth/$originalWidth
             */
            $newHeight = $h ? (float)$h : ($originalHeight * $newWidth / $originalWidth);
        }

        return [$image, $newWidth, $newHeight];
    }
}
