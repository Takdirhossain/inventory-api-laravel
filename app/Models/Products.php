<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'twelve_kg',
        'twentyfive_kg',
        'thirtythree_kg',
        'thirtyfive_kg',
        'fourtyfive_kg',
        'others_kg',
        'empty_twelve_kg',
        'empty_twentyfive_kg',
        'empty_thirtythree_kg',
        'empty_thirtyfive_kg',
        'empty_fourtyfive_kg',
        'empty_others_kg',
        'price',
        'date',
        'is_package',
    ];
}
