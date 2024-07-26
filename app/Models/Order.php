<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory;


//علاقة الكل للكل بين المستودع و الطلبات
public function factory(){
    return $this->belongsToMany(Factory::class,'factory_order')->withTimestamps();
}

//علاقة واحد للكل بين الصيدلي والطلبات
public function pharmacist(): BelongsTo
{
    return $this->belongsTo(Pharmacist::class);
}




}
