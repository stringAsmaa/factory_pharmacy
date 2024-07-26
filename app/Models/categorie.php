<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class categorie extends Model
{
    use HasFactory;

    protected $fillable=[
'trade_name',
'categorie'

    ];


    public function categorie(): HasMany
    {
        return $this->hasMany(Factory::class);
    }


}
