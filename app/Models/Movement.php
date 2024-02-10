<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'value','user_id','category_id','description','tipo'
    ];
    protected $hidden = [
        'deleted_at','updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
}
