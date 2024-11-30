<?php

namespace App\Models;
use App\Models\Series;
use App\Models\Movie;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table='categories';
    protected $fillable=['id','name'];

    public function movies()
{
    return $this->morphedByMany(Movie::class, 'categoryable');
}

public function Series()
{
    return $this->morphedByMany(Series::class, 'categoryable');
}
}