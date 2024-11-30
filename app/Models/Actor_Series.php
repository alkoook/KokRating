<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor_Series extends Model
{
    protected $table='actor_series';
    protected $fillable=['id', 'actor_id', 'series_id', 'created_at', 'updated_at'];
}