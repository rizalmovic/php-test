<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'qty', 'price', 'unit', 'status'];
    protected $hidden = ['id'];
}
