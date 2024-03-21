<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManipulation extends Model
{
    use HasFactory;

    const TYPE_RESIZE = 'resize';
    const UPDATED_AT = null; // we're gonna get an error if we don't tell the model that we don't have the updated_at column

    protected $fillable = ['name', 'path', 'type', 'data', 'output_path', 'user_id', 'album_id'];
}
