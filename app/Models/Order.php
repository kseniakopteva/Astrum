<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
    public function inProcess()
    {
        return $this->status === 'working';
    }
    public function isComplete()
    {
        return $this->status === 'complete';
    }
}
