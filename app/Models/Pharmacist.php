<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

class Pharmacist extends Model
{
    use HasFactory;

//
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }


    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }


}
