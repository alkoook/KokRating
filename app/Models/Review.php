<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;

class Review extends Model
{
    protected $table='reviews';
    protected $fillable=['id', 'content', 'rating', 'reviewable_type', 'reviewable_id', 'user_id', 'created_at', 'updated_at'];

    public function reviewable()
{
    return $this->morphTo();  // العلاقة polymorphic التي تعني أن المراجعة يمكن أن تكون لفيلم أو لمسلسل
}
public function User(){
    return $this->belongsTo(User::class);
}
public function likes(){
    return $this->hasMany(Like::class);
}
}