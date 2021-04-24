<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $guarded = [];

    public function getStatusAttribute()
    {
        if ($this->location == ""){
            return "in_flight";
        }else{
           return "docked";
        }
    }

    public function getFlightLocationAttribute()
    {
        return $this->location;
    }
}
