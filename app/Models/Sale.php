<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'buyer_id', 'seller_id', 'date', 'total_amount'];

    public function buyer()
    {
        return $this->belongsTo(Person::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(Person::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
