<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Ingreso;
use App\DetalleIngreso;


class IngresoController extends Controller
{
    public function index(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ingresos = Ingreso::join('proveedor', 'ingreso.idproveedor', '=', 'proveedor.id')
            ->join('usuario', 'ingreso.idusuario', '=', 'usuario.id')
            ->select('ingreso.id', 'ingreso.fecha', 'ingreso.hora', 
                'ingreso.totalcompra', 'ingreso.tipocomprobante', 'ingreso.numcomprobante', 
                'ingreso.estado', 'proveedor.nombres as proveedor', 'usuario.usuario as usuario')
            ->orderBy('ingreso.id', 'desc')->paginate(5);
        } else {
            $ingresos = Ingreso::join('proveedor', 'ingreso.idproveedor', '=', 'proveedor.id')
            ->join('usuario', 'ingreso.idusuario', '=', 'usuario.id')
            ->select('ingreso.id', 'ingreso.fecha', 'ingreso.hora', 
                'ingreso.totalcompra', 'ingreso.tipocomprobante', 'ingreso.numcomprobante', 
                'ingreso.estado', 'proveedor.nombres as proveedor', 'usuario.usuario as usuario')
            ->where('ingreso.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('ingreso.id', 'desc')->paginate(5);                   
        }                

        return [
            'pagination' => [
                'total' => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page' => $ingresos->perPage(),
                'last_page' => $ingresos->lastPage(),
                'from' => $ingresos->firstItem(),
                'to' => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }    


    public function obtenerCabezeraIngreso(Request $request) 
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;        

        $ingreso = Ingreso::join('proveedor', 'ingreso.idproveedor', '=', 'proveedor.id')
        ->join('usuario', 'ingreso.idusuario', '=', 'usuario.id')
        ->select('ingreso.id', 'ingreso.fecha', 'ingreso.hora', 
            'ingreso.totalcompra', 'ingreso.tipocomprobante', 'ingreso.numcomprobante', 
            'ingreso.estado', 'proveedor.nombres as proveedor', 'usuario.usuario as usuario')
        ->where('ingreso.id', '=', $id)
        ->orderBy('ingreso.id', 'desc')
        ->take(1)
        ->get();
                   
        return ['ingreso' => $ingreso];
    }


    public function obtenerDetallesIngreso(Request $request) 
    {

        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;        

        $detalles = DetalleIngreso::join('producto', 'detalleingreso.idproducto', '=', 'producto.id')
        ->join('almacen', 'detalleingreso.idalmacen', '=', 'almacen.id')     
        ->select('detalleingreso.cantidad', 'detalleingreso.preciocompra as precio', 'producto.nombre as producto', 'almacen.nombre as almacen')
        ->where('detalleingreso.idingreso', '=', $id)
        ->orderBy('detalleingreso.id', 'desc')        
        ->get();
                   
        return ['detalles' => $detalles];
    }
    

    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }              
                    
        try {
            DB::beginTransaction();

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id; //id del usuario logeado

            $ingreso->fecha = date('Y-m-d');
            $ingreso->hora = date('H:i:s');
            $ingreso->totalcompra = $request->totalcompra;
            $ingreso->tipocomprobante = $request->tipocomprobante;
            $ingreso->numcomprobante = $request->numcomprobante;           
            $ingreso->estado = $request->estado;
            
            $ingreso->save();


            $detalles = $request->data; //array de detalles
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->cantidad = $det['cantidad'];
                $detalle->preciocompra = $det['precio'];
                $detalle->idingreso = $ingreso->id;
                $detalle->idproducto = $det['idproducto'];
                $detalle->idalmacen = $det['idalmacen'];
                $detalle->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }               
    }
    
        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'ANULADO';        
        $ingreso->save();
    }

}
