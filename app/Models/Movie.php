<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Movie extends Model
{
    use HasFactory;
    protected $table='movies';
    protected $fillable=['id', 'name', 'description', 'country','image', 'duration', 'year', 'created_at', 'updated_at'];

    public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');  // المراجعات المرتبطة بهذا الفيلم
}

public function Actors(){
    return $this->belongsToMany(Actor::class,'actor_movie');
}

public function categories()
{
    return $this->morphToMany(Category::class, 'categoryable');
}

}