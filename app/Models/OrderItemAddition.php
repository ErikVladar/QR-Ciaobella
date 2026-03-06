<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAddition extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'pizza_addition_id', 'addition_name', 'addition_price'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function pizzaAddition()
    {
        return $this->belongsTo(PizzaAddition::class);
    }
}
