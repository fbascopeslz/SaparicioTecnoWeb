<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['nombres', 'tipodocumento', 'numdocumento', 'direccion', 'telefono', 'email', 'fechaunion', 'estado'];
}
