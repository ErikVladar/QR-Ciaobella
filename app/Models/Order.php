<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total', 'status', 'table_number', 'payment_status', 'payment_method', 'waiter_status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
