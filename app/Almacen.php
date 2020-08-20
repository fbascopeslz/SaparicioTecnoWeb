<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacen';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['nombre', 'direccion', 'latitud', 'longitud'];
}
