<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor_Movie extends Model
{
    protected $table='actor_movie';
    protected $fillable=['id', 'actors_id', 'movie_id', 'created_at', 'updated_at'];
}