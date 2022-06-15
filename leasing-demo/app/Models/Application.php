<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'sum',
        'object_type',
        'lease_term',
        'contact_id',
        'csv_id',
    ];
}
