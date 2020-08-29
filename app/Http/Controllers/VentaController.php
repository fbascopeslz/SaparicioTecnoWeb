<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Venta;
use App\DetalleVenta;


class VentaController extends Controller
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
            $ventas = Venta::join('cliente', 'venta.idcliente', '=', 'cliente.id')
            ->join('usuario', 'venta.idusuario', '=', 'usuario.id')
            ->select('venta.id', 'venta.fecha', 'venta.hora', 
                'venta.total', 'venta.estado', 'venta.numcomprobante', 
                'cliente.nombres as cliente', 'usuario.usuario as usuario')
            ->orderBy('venta.id', 'desc')->paginate(5);
        } else {
            $ventas = venta::join('cliente', 'venta.idcliente', '=', 'cliente.id')
            ->join('usuario', 'venta.idusuario', '=', 'usuario.id')
            ->select('venta.id', 'venta.fecha', 'venta.hora', 
                'venta.total', 'venta.estado', 'venta.numcomprobante', 
                'cliente.nombres as cliente', 'usuario.usuario as usuario')
            ->where('venta.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('venta.id', 'desc')->paginate(5);                   
        }                

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }    


    public function obtenerCabezeraVenta(Request $request) 
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;        

        $venta = Venta::join('cliente', 'venta.idcliente', '=', 'cliente.id')
        ->join('usuario', 'venta.idusuario', '=', 'usuario.id')
        ->select('venta.id', 'venta.fecha', 'venta.hora', 
            'venta.total', 'venta.estado', 'venta.numcomprobante', 
            'cliente.nombres as cliente', 'usuario.usuario as usuario')
        ->where('venta.id', '=', $id)
        ->orderBy('venta.id', 'desc')
        ->take(1)
        ->get();
                   
        return ['venta' => $venta];
    }


    public function obtenerDetallesVenta(Request $request) 
    {

        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;        

        $detalles = DetalleVenta::join('producto', 'detalleventa.idproducto', '=', 'producto.id')
        ->join('almacen', 'detalleventa.idalmacen', '=', 'almacen.id')     
        ->select('detalleventa.cantidad', 'detalleventa.precioventa as precio', 'detalleventa.descuento as descuento', 
                'producto.nombre as producto', 'almacen.nombre as almacen')
        ->where('detalleventa.idventa', '=', $id)
        ->orderBy('detalleventa.id', 'desc')        
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

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id; //id del usuario logeado

            $venta->fecha = date('Y-m-d');
            $venta->hora = date('H:i:s');
            $venta->total = $request->total;            
            $venta->estado = $request->estado;
            $venta->numcomprobante = $request->numcomprobante;           
                        
            $venta->save();


            $detalles = $request->data; //array de detalles
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->cantidad = $det['cantidad'];
                $detalle->precioventa = $det['precioventa'];
                $detalle->descuento = $det['descuento'];
                $detalle->idventa = $venta->id;
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

        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'ANULADO';        
        $venta->save();
    }

}
