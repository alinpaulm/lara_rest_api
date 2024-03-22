<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Http\Resources\V1\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return Album::all();
        // return AlbumResource::collection(Album::all());
        // return AlbumResource::collection(Album::paginate());

        // NOTE:
        // $request->user() <=> Auth::user()
        // $request->user()->id <=> Auth::user()->id <=> Auth::id()
        return AlbumResource::collection(
            Album::where('user_id', $request->user()->id)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request)
    {
        // $album = Album::create($request->all());

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $album = Album::create($data);

        // return $album;
        return new AlbumResource($album);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Album $album)
    {
        $this->authorize($request->user()->id, $album->user_id);

        // return $album;
        return new AlbumResource($album);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        $this->authorize($request->user()->id, $album->user_id);
        
        // $album->update($request->all());
        $album->update($request->validated());

        // return $album;
        return new AlbumResource($album);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Album $album)
    {
        $this->authorize($request->user()->id, $album->user_id);

        $album->delete();

        return response('', 204);
    }    
}
