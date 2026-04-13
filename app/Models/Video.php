<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'filename',
        'original_filename',
        'size',
        'status',
        'cloudinary_public_id',
        'cloudinary_url',
        'error_message',
    ];
}
