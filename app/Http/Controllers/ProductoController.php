<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Producto;
use App\Inventario;

use Illuminate\Support\Facades\DB;

use Cloudder;


class ProductoController extends Controller
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
            $productos = Producto::orderBy('id', 'desc')->paginate(5);
        } else {
            $productos = Producto::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);         
        }
        
        //$productos = Producto::all();

        return [
            'pagination' => [
                'total' => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page' => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from' => $productos->firstItem(),
                'to' => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }


    //Metodo para el Ingreso
    public function listarProductos(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            //return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $productos = Producto::orderBy('id', 'desc')->paginate(10);
        } else {
            $productos = Producto::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(10);         
        }        

        return ['productos' => $productos];
    }


    //Metodo para la Venta
    public function listarInventarioModal(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            //return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;        

        if ($buscar == '') {                    
            $inventario = DB::select("SELECT almacen.nombre AS almacen, 
                                            producto.nombre AS producto,
                                            producto.id AS idproducto, 
		                                    almacen.id AS idalmacen,
                                            producto.codigo, 
                                            producto.descripcion,
                                            producto.precio, 
                                            producto.image,
                                            inventario.stock
                                    FROM producto, inventario, almacen
                                    WHERE producto.id = inventario.idproducto AND
                                        inventario.idalmacen = almacen.id
                                    ORDER BY almacen.id ASC");
        } else {
            $inventario = DB::select("SELECT almacen.nombre AS almacen, 
                                            producto.nombre AS producto,
                                            producto.id AS idproducto, 
		                                    almacen.id AS idalmacen,
                                            producto.codigo, 
                                            producto.descripcion,
                                            producto.precio,
                                            producto.image,
                                            inventario.stock
                                    FROM producto, inventario, almacen
                                    WHERE producto.id = inventario.idproducto AND
                                        inventario.idalmacen = almacen.id AND ".
                                        $criterio . "LIKE %" . $buscar . "% ".    
                                        "ORDER BY almacen.id ASC");            
        }                                        

        return ['inventario' => $inventario];
    }


    //Inventario
    public function listarInventario(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $inventario = Inventario::join('producto', 'inventario.idproducto', '=', 'producto.id')
                    ->join('almacen', 'inventario.idalmacen', '=', 'almacen.id')
                    ->select('almacen.nombre AS almacen', 
                            'producto.nombre AS producto',
                            'producto.id AS idproducto', 
                            'almacen.id AS idalmacen',
                            'producto.codigo', 
                            'producto.descripcion',
                            'producto.precio', 
                            'producto.image',
                            'inventario.stock')
                    ->orderBy('almacen.id', 'desc')
                    ->paginate(5);
        } else {
            $inventario = Inventario::join('producto', 'inventario.idproducto', '=', 'producto.id')
                        ->join('almacen', 'inventario.idalmacen', '=', 'almacen.id')
                        ->select('almacen.nombre AS almacen', 
                                'producto.nombre AS producto',
                                'producto.id AS idproducto', 
                                'almacen.id AS idalmacen',
                                'producto.codigo', 
                                'producto.descripcion',
                                'producto.precio', 
                                'producto.image',
                                'inventario.stock')
                        ->where($criterio, 'LIKE', '%'.$buscar.'%')
                        ->orderBy('almacen.id', 'desc')
                        ->paginate(5);                                       
        }                

        return [
            'pagination' => [
                'total' => $inventario->total(),
                'current_page' => $inventario->currentPage(),
                'per_page' => $inventario->perPage(),
                'last_page' => $inventario->lastPage(),
                'from' => $inventario->firstItem(),
                'to' => $inventario->lastItem(),
            ],
            'inventario' => $inventario
        ];
    }    


    //Metodo para el Ingreso
    public function buscarProducto(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $filtro = $request->filtro;
        $productos = Producto::where('nombre', 'like', '%'.$filtro.'%')
                            ->orderBy('id', 'desc')
                            ->take(1)
                            ->get();
        
        return ['productos' => $productos];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        //Guardar imagen en Cloudinary
        $imagen = Cloudder::upload($request->imagen);
        $img = $imagen->getResult();
        $pathImagen = $img['url'];

        //return response()->json($pathImagen);
        
        //Guardar de forma local
        //$imageName = time().'.'.$request->imagen->getClientOriginalExtension();
        //$request->imagen->move(public_path('images'), $imageName);        
                
        if ($pathImagen) {
            $producto = new Producto();
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;            
            $producto->image = $pathImagen;
            $producto->save();
        }

    }

    
    public function update(Request $request)
    {   
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
       
        //Pregunta si llego ese parametro en la request
        if ($request->imagen) {
            //Guardar imagen en Cloudinary
            $imagen = Cloudder::upload($request->imagen);
            $img = $imagen->getResult();
            $pathImagen = $img['url'];
            if ($pathImagen) {
                $producto->image = $pathImagen;
            }
        }            
        
        $producto->save();
                
        //return response()->json($request);
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->estado = false;        
        $producto->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->estado = true;        
        $producto->save();
    }

}
