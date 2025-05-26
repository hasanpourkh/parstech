<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    protected $table = 'shareholders'; // اگر جدول دیتابیس اسمش چیز دیگری است اصلاح کن
    // اگر فیلدهای خاصی داری، این خط را باز کن:
    // protected $fillable = ['full_name', 'percent', ...];
}
