<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public function floorcall()
    {
        return $this->hasMany(Floorcall::class);
    }
    public function doorfault(){
        return $this->hasMany(DoorFault::class);
    }

}
