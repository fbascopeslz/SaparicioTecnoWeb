<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use DB;



class EstadisticasController extends Controller
{
    //Producto vs Cantidad
    public function estadisticaProductosMasVendidos(Request $request)
    {        
        $tipo = $request->tipo;
        $sql = '';

        switch ($tipo) {
            case 1:
                $fecha = $request->fecha;
                $sql = " and venta.fecha = '" . $fecha . "'\n";
                break;
            case 2:
                $fecha1 = $request->fecha1;
                $fecha2 = $request->fecha2;
                $sql = " and venta.fecha >= '" . $fecha1 . "' and venta.fecha <= '" . $fecha2 . "'\n";
                break;            
            default:
                # code...
                break;
        }            

        $estadistica = DB::select("SELECT producto.nombre as producto, SUM(detalleventa.cantidad) as cantidad\n" .
                                " FROM producto, venta, detalleventa\n" .
                                " WHERE producto.id = detalleventa.idproducto and\n" .
                                " detalleventa.idventa = venta.id\n" . $sql .
                                " GROUP BY producto.nombre\n" .
                                " ORDER BY cantidad DESC\n".
                                " LIMIT 10");
                                                                                                    
        return ['estadistica' => $estadistica];          
    }


    //Cliente vs NumeroVentas
    public function estadisticaClientesMasFieles(Request $request)
    {                            
        $estadistica = DB::select("SELECT cliente.nombres as cliente, COUNT(*) as numeroventas
                                    FROM venta, cliente
                                    WHERE venta.idcliente = cliente.id
                                    GROUP BY cliente.nombres
                                    ORDER BY numeroventas DESC
                                    LIMIT 10");
                                                                                                    
        return ['estadistica' => $estadistica];          
    }


    //Proveedor vs NumeroCompras
    public function estadisticaProveedoresMasSolicitados(Request $request)
    {                            
        $estadistica = DB::select("SELECT proveedor.nombres as proveedor, COUNT(*) as numerocompras
                                    FROM ingreso, proveedor
                                    WHERE ingreso.idproveedor = proveedor.id
                                    GROUP BY proveedor.nombres
                                    ORDER BY numerocompras DESC
                                    LIMIT 10");
                                                                                                    
        return ['estadistica' => $estadistica];          
    }


    //EstadisticasFinancieras
    public function estadisticasFinancieras(Request $request)
    {
        $anio = $request->anio;

        //Mes Vs Ingreso y Gasto
        $estadistica1 = DB::select("SELECT TO_CHAR(venta.fecha, 'MM') as mes, "
                                    . "SUM(CAST(venta.total as decimal)) as ingreso, "
                                    . "SUM(CAST(ingreso.totalcompra as decimal)) as gasto\n" .
                                "FROM venta, ingreso\n" .
                                "WHERE TO_CHAR(venta.fecha, 'YYYY') = '" . $anio ."' and "
                                    . "TO_CHAR(ingreso.fecha, 'YYYY') = '" . $anio ."'\n" .
                                "GROUP BY TO_CHAR(venta.fecha, 'MM')\n" .
                                "ORDER BY mes ASC");

        //Utilidad = Ingreso - Gasto        
        $estadistica2 = DB::select("SELECT CAST(tabla.ingreso as decimal), "
                                    . "CAST(tabla.gasto as decimal), "
                                    . "(CAST((tabla.ingreso - tabla.gasto) as decimal)) as utilidad\n" .
                                    "FROM(\n" .
                                        "SELECT SUM(venta.total) as ingreso, SUM(ingreso.totalcompra) as gasto\n" .
                                        "FROM venta, ingreso\n" .
                                        "WHERE TO_CHAR(venta.fecha, 'YYYY') = '" . $anio ."' and TO_CHAR(ingreso.fecha, 'YYYY') = '" . $anio ."'" .
                                    ") as tabla");                                


        return ['estadistica1' => $estadistica1, 'estadistica2' => $estadistica2];
    }

}
