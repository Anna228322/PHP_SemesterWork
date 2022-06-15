<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Csv extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'filename',
        'user_id',
        'tag'
    ];
}
