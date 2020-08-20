<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Cliente;


class ClienteController extends Controller
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
            $clientes = Cliente::orderBy('id', 'desc')->paginate(5);
        } else {
            $clientes = Cliente::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);         
        }                

        return [
            'pagination' => [
                'total' => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page' => $clientes->perPage(),
                'last_page' => $clientes->lastPage(),
                'from' => $clientes->firstItem(),
                'to' => $clientes->lastItem(),
            ],
            'clientes' => $clientes
        ];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }              
                        
        $cliente = new Cliente();
        $cliente->nombres = $request->nombres;
        $cliente->tipodocumento = $request->tipodocumento;
        $cliente->numdocumento = $request->numdocumento;
        $cliente->direccion = $request->direccion;           
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->fechaunion = date('Y-m-d'); //obtener fecha actual

        $cliente->save();       
    }

    
    public function update(Request $request)
    {   
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $cliente = Cliente::findOrFail($request->id);
        $cliente->nombres = $request->nombres;
        $cliente->tipodocumento = $request->tipodocumento;
        $cliente->numdocumento = $request->numdocumento;
        $cliente->direccion = $request->direccion;           
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;        
                        
        $cliente->save();                        
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = false;        
        $cliente->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = true;        
        $cliente->save();
    }

}
