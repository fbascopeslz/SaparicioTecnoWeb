<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingreso';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'fecha', 
        'hora', 
        'totalCompra', 
        'tipoComprobante', 
        'estado',
        'idproveedor',
        'idusuario'
    ];

    public function usuario(){
        return $this->belongsTo('App\User');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor');
    }
    
}
