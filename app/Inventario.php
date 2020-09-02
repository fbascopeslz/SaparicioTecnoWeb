<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'stock', 
        'idproducto', 
        'idalmacen',        
    ];
}
