<?php

namespace WAZIRITALLCRUDGENERATOR\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
