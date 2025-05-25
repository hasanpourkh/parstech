<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'email'];

    public function person()
    {
        // فرض: ستون id در customers و persons یکی است
        return $this->belongsTo(Person::class, 'id', 'id');
    }
}
