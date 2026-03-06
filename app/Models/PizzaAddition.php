<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaAddition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_additions', 'pizza_addition_id', 'order_item_id');
    }
}
