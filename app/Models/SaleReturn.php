<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $fillable = [
        'sale_id', 'return_number', 'return_date', 'user_id', 'note', 'total_amount'
    ];

    public static function generateReturnNumber()
    {
        $prefix = 'RET-' . date('Ymd');
        $last = static::where('return_number', 'like', $prefix . '%')->orderByDesc('id')->first();
        $next = $last ? ((int)substr($last->return_number, strlen($prefix))) + 1 : 1;
        return $prefix . sprintf('%03d', $next);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class, 'sale_return_id');
    }
}
