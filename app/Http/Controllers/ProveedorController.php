<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Proveedor;


class ProveedorController extends Controller
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
            $proveedores = Proveedor::orderBy('id', 'desc')->paginate(5);
        } else {
            $proveedores = Proveedor::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);         
        }                

        return [
            'pagination' => [
                'total' => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page' => $proveedores->perPage(),
                'last_page' => $proveedores->lastPage(),
                'from' => $proveedores->firstItem(),
                'to' => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];
    }


    //metodo para el Ingreso
    public function selectProveedor(Request $request) 
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            //return redirect('/');
        }

        $filtro = $request->filtro;
        $proveedores = Proveedor::where('proveedor.nombres', 'like', '%'.$filtro.'%')
        ->orWhere('proveedor.numdocumento', 'like', '%'.$filtro.'%')
        ->select('proveedor.id', 'proveedor.nombres', 'proveedor.numdocumento')
        ->orderBy('proveedor.nombres', 'asc')
        ->get();

        return ['proveedores' => $proveedores];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }              
                        
        $proveedor = new Proveedor();
        $proveedor->nombres = $request->nombres;
        $proveedor->tipodocumento = $request->tipodocumento;
        $proveedor->numdocumento = $request->numdocumento;
        $proveedor->direccion = $request->direccion;           
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->contacto = $request->contacto;
        $proveedor->telefonocontacto = $request->telefonocontacto;

        $proveedor->save();       
    }

    
    public function update(Request $request)
    {   
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->nombres = $request->nombres;
        $proveedor->tipodocumento = $request->tipodocumento;
        $proveedor->numdocumento = $request->numdocumento;
        $proveedor->direccion = $request->direccion;           
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->contacto = $request->contacto;
        $proveedor->telefonocontacto = $request->telefonocontacto;        
                        
        $proveedor->save();                        
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = false;        
        $proveedor->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = true;        
        $proveedor->save();
    }
}
