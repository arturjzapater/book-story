<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'author', 'description', 'pages', 'price', 'length', 'width',
    ];
}
