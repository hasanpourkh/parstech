<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    // فرض می‌کنیم کلید customers.id همان persons.id است، در غیر این صورت فیلد درست را جایگزین کن
    public function person()
    {
        return $this->belongsTo(Person::class, 'id', 'id');
    }
}
