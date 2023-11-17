<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function availableSlots()
    {
        $max = $this->max_slots;
        $pending = Order::where('product_id', $this->id)->where('status', 'pending')->count();

        return $max - $pending;
    }
}
