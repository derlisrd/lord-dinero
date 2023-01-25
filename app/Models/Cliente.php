<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['doc','nombre','tel','email','nacimiento'];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

}
