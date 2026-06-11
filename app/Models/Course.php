<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name', 'description', 'hours', 'price', 'start_date', 'end_date', 'img'
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'end_date'   => 'date:d-m-Y',
        'price'      => 'decimal:2', 
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}