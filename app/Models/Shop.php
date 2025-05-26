<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'name', 'logo', 'address', 'phone', 'economic_code', 'national_id', 'email', 'website', 'support_phone', 'description'
    ];
}
