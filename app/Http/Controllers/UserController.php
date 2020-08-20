<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;


class UserController extends Controller
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
            $usuarios = User::join('rol', 'usuario.idrol', '=', 'rol.id')
            ->select('usuario.id', 'usuario.nombres', 'usuario.tipodocumento', 
                'usuario.numdocumento', 'usuario.direccion', 'usuario.telefono', 
                'usuario.email', 'usuario.usuario', 'usuario.password', 
                'usuario.estado', 'usuario.idrol', 'rol.nombre as rol')
            ->orderBy('usuario.id', 'desc')->paginate(5);
        } else {
            $usuarios = User::join('rol', 'usuario.idrol', '=', 'rol.id')
            ->select('usuario.id', 'usuario.nombres', 'usuario.tipodocumento', 
                'usuario.numdocumento', 'usuario.direccion', 'usuario.telefono', 
                'usuario.email', 'usuario.usuario', 'usuario.password', 
                'usuario.estado', 'usuario.idrol', 'rol.nombre as rol')
            ->where('usuario.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('usuario.id', 'desc')->paginate(5);        
        }                

        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }              
                        
        $usuario = new User();
        $usuario->nombres = $request->nombres;
        $usuario->tipodocumento = $request->tipodocumento;
        $usuario->numdocumento = $request->numdocumento;
        $usuario->direccion = $request->direccion;           
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->password = md5($request->password);
        $usuario->idrol = $request->idrol;

        $usuario->save();       
    }

    
    public function update(Request $request)
    {   
        
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->nombres = $request->nombres;
        $usuario->tipodocumento = $request->tipodocumento;
        $usuario->numdocumento = $request->numdocumento;
        $usuario->direccion = $request->direccion;           
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->password = md5($request->password);
        $usuario->idrol = $request->idrol;        
                        
        $usuario->save();       
                      
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->estado = false;        
        $usuario->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->estado = true;        
        $usuario->save();
    }
}
