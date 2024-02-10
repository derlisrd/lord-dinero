<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon', 'description','user_id',
    ];

    protected $hidden = [
        'updated_at'
    ];


    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

}
