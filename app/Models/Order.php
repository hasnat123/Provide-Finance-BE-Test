<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'invoice_number',
        'total',
        'status',
        'shipping_address',
        'products'
    ];

    protected $attributes = [
        'status' => 'PENDING', // Set default value for status
        'total' => 0.00, // Set default value for total
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->products as $product) {
            $total += $product->price * $product->pivot->quantity;
        }

        return $total;
    }
}
