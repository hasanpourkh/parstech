<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'legal_name',
        'national_id',
        'economic_code',
        'registration_number',
        'address',
        'phone',
        'email',
        'website',
        'is_active'
    ];
}
