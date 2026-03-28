<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name','image_path','has_prilohy'];

    protected $casts = [
        'has_prilohy' => 'boolean',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }
}

