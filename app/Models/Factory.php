<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = [
        'scientific_name',
        'trade_name',
        'categorie',
        'company',
        'amount',
        'exspiry_date',
        'price',
        'path'
    ];

    public function order(){
        return $this->belongsToMany(Order::class,'factory_order')->withTimestamps();
    }
    



    public function categorie(){
        return $this->belongsToMany(categorie::class);
    }
    





    public function status(): HasMany
    {
        return $this->hasMany(Status::class);
    }


    
}
