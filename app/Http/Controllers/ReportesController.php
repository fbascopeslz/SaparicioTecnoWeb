<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use DB;


class ReportesController extends Controller
{
    public function reporteVentas(Request $request)
    {        
        $opcion = $request->opcion;
        $sql = "";

        if ($opcion == 1) {
            $sql .= " and venta.fecha >= '" . $request->fecha1 . "' and venta.fecha <= '" . $request->fecha2 ."'\n";
        } 
        
        $ventas = DB::select("SELECT\n"
                        . "venta.id,\n"
                        . "venta.fecha,\n"
                        . "venta.hora,\n"
                        . "venta.total,\n"
                        . "venta.estado,\n"
                        . "venta.numcomprobante,\n"
                        . "cliente.nombres AS cliente,\n"
                        . "usuario.usuario AS usuario\n"
                        . "FROM venta, cliente, usuario\n"
                        . "WHERE venta.idcliente = cliente.id and "
                        . "venta.idusuario = usuario.id\n" . $sql
                        . "ORDER BY venta.id ASC");                        

        $pdf = PDF::loadView('pdf.plantillareporteventas', ['ventas'=>$ventas]);
        $pdf->setPaper('A4', 'landscape');

        if ($request->tipo == 1) {
            return $pdf->download('reporteventas.pdf');
        } else {
            return $pdf->stream('reporteventas.pdf');
        }                
    }

    public function reporteIngresos(Request $request)
    {        
        $opcion = $request->opcion;
        $sql = "";

        if ($opcion == 1) {
            $sql .= " and ingreso.fecha >= '" . $request->fecha1 . "' and ingreso.fecha <= '" . $request->fecha2 ."'\n";
        }

        $ingresos = DB::select("SELECT\n"
                        . "ingreso.id,\n"
                        . "ingreso.fecha,\n"
                        . "ingreso.hora,\n"
                        . "ingreso.totalcompra,\n"
                        . "ingreso.tipocomprobante,\n"
                        . "ingreso.numcomprobante,\n"
                        . "ingreso.estado,\n"                        
                        . "proveedor.nombres AS proveedor,\n"
                        . "usuario.usuario AS usuario\n"
                        . "FROM ingreso, proveedor, usuario\n"
                        . "WHERE ingreso.idproveedor = proveedor.id and "
                        . "ingreso.idusuario = usuario.id\n" . $sql
                        . "ORDER BY ingreso.id ASC");

        $pdf = PDF::loadView('pdf.plantillareporteingresos', ['ingresos'=>$ingresos]);
        $pdf->setPaper('A4', 'landscape');

        if ($request->tipo == 1) {
            return $pdf->download('reporteingresos.pdf');
        } else {
            return $pdf->stream('reporteingresos.pdf');
        }                
    }


    public function reporteProductos(Request $request)
    {        
        $tipo = $request->tipo;

        $productos = DB::select("SELECT\n"
                                . "producto.id,\n"
                                . "producto.codigo,\n"
                                . "producto.nombre,\n"
                                . "producto.descripcion,\n"
                                . "producto.estado,\n"
                                . "producto.precio,\n"                                
                                . "producto.image\n"
                            . "FROM producto\n"
                            . "ORDER BY producto.id ASC");

        $pdf = PDF::loadView('pdf.plantillareporteproductos', ['productos'=>$productos]);
        $pdf->setPaper('A4', 'landscape');

        if ($request->tipo == 1) {
            return $pdf->download('reporteproductos.pdf');
        } else {
            return $pdf->stream('reporteproductos.pdf');
        }                
    }


    public function reporteInventario(Request $request)
    {        
        $tipo = $request->tipo;

        $inventario = DB::select("SELECT almacen.nombre AS almacen, 
                                        producto.nombre AS producto, 
                                        producto.codigo, 
                                        producto.descripcion, 
                                        inventario.stock
                                FROM producto, inventario, almacen
                                WHERE producto.id = inventario.idproducto AND
                                    inventario.idalmacen = almacen.id
                                ORDER BY almacen.id ASC");

        $pdf = PDF::loadView('pdf.plantillareporteinventario', ['inventario'=>$inventario]);
        $pdf->setPaper('A4', 'landscape');

        if ($request->tipo == 1) {
            return $pdf->download('reporteinventario.pdf');
        } else {
            return $pdf->stream('reporteinventario.pdf');
        }                
    }    

}
