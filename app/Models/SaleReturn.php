<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'return_number',
        'return_date',
        'note',
        'total_amount',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public static function generateReturnNumber()
    {
        $last = self::orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'RET-' . $nextId . '-' . rand(1000, 9999);
    }
}
