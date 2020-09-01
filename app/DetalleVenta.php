<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalleventa';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidad', 
        'precioventa',
        'descuento', 
        'idingreso', 
        'idproducto', 
        'idalmacen',        
    ];
}
