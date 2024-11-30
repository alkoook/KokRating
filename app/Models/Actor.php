<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{   use HasFactory;

    protected $table='actors';
    protected $fillable=['id', 'name', 'birth_date', 'country', 'photo', 'biography', 'created_at', 'updated_at'];


    public function Movies(){
        return $this->belongsToMany(Movie::class,'actor_movie');
    }

    public function Series(){
        return $this->belongsToMany(Series::class,'actor_series');
    }
}