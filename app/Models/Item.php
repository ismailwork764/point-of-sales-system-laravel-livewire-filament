<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'string',
    ];

    public function salesItems()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function inventory(){
        return $this->hasOne(Inventory::class);
    }
}
