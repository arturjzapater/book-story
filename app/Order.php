<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name', 'c_o', 'address', 'postal_code', 'email', 'phone'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
