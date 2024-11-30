<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    protected $table='series';
    protected $fillable=[ 'id', 'name', 'description','country', 'image', 'episode','country', 'year', 'created_at', 'updated_at'];

    public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');  // المراجعات المرتبطة بهذا الفيلم
}

public function Actors(){
    return $this->belongsToMany(Actor::class,'actor_series');
}
public function categories()
{
    return $this->morphToMany(Category::class, 'categoryable');
}

}
