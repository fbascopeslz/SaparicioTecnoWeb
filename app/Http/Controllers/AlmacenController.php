<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Almacen;


class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            //return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $almacenes = Almacen::orderBy('id', 'desc')->paginate(5);
        } else {
            $almacenes = Almacen::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);         
        }
        
        //$productos = Producto::all();

        return [
            'pagination' => [
                'total' => $almacenes->total(),
                'current_page' => $almacenes->currentPage(),
                'per_page' => $almacenes->perPage(),
                'last_page' => $almacenes->lastPage(),
                'from' => $almacenes->firstItem(),
                'to' => $almacenes->lastItem(),
            ],
            'almacenes' => $almacenes
        ];
    }


    //Metodo para el Ingreso
    public function getAlmacenes(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $almacenes = Almacen::select('almacen.id', 'almacen.nombre')
        ->orderBy('id', 'asc')
        ->get();       

        return ['almacenes' => $almacenes];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }
        
        $almacen = new Almacen();        
        $almacen->nombre = $request->nombre;
        $almacen->direccion = $request->direccion;
        $almacen->latitud = $request->latitud;
        $almacen->longitud = $request->longitud;

        $almacen->save();

    }

    
    public function update(Request $request)
    {   
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $almacen = Almacen::findOrFail($request->id);
        $almacen->nombre = $request->nombre;
        $almacen->direccion = $request->direccion;
        $almacen->latitud = $request->latitud;
        $almacen->longitud = $request->longitud;                     
        
        $almacen->save();
                
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $almacen = Almacen::findOrFail($request->id);
        $almacen->estado = false;   
        $almacen->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $almacen = Almacen::findOrFail($request->id);
        $almacen->estado = true;        
        $almacen->save();
    }
}
