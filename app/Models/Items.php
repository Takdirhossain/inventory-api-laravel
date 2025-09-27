<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'name', 'price', 'is_cylinder', 'image', 'description'
    ];

    protected $casts = [
        'is_cylinder' => 'boolean',
    ];
}
