<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'seller_id',
        'name',
        'bio',
        'image',
        'price',
    ];


    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function orders(){
        return $this->hasMany(OrderProduct::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'product_category');
    }
}
