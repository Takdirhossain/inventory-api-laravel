<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    public function sales()
    {
        return $this->hasMany(Sales::class, 'customer_id', 'id');
    }
    public function payed(){
        return $this->hasMany(Sales::class, 'customer_id', 'pay');
    }
    public function due(){
        return $this->hasMany(Sales::class, 'customer_id', 'due');
    }
}
