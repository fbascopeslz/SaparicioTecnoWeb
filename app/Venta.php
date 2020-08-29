<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'fecha', 
        'hora', 
        'total',          
        'estado',
        'numcomprobante',
        'idcliente',
        'idusuario'
    ];

    public function usuario(){
        return $this->belongsTo('App\User');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente');
    }
}
