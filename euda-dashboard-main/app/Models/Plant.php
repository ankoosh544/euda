<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'topic',
        'datastore',
        'state',
        'city',
        'cap',
        'address',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function getCompleteAddress() {
        return $this->address.", ".$this->cap.", ".$this->city.", ".$this->state;
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}