<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $guarded = [];

    public function goods()
    {
        return $this->hasMany(Good::class, 'location');
    }
}
