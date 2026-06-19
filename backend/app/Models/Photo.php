<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'image_path',
        'processing_status',
        'album_number',
    ];
}
