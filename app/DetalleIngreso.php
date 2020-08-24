<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalleingreso';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidad', 
        'preciocompra', 
        'idingreso', 
        'idproducto', 
        'idalmacen',        
    ];
    
}
