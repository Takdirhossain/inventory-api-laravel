<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'customer_name',
        'customer_id',
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
        'price_12_kg',
        'price_25_kg',
        'price_33_kg',
        'price_35_kg',
        'price_45_kg',
        'date',
        'is_due_bill',
        'price',
        'pay',
        'due',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
