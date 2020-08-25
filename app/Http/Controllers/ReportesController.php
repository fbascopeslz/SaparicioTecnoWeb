<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Dompdf\Dompdf;



class ReportesController extends Controller
{
    public function getVentas()
    {
        /*
        $ventas = DB::select("SELECT\n"
                        + "venta.id,\n"
                        + "venta.fecha,\n"
                        + "venta.hora,\n"
                        + "venta.total,\n"
                        + "venta.estado,\n"
                        + "venta.numComprobante,\n"
                        + "cliente.nombres AS cliente,\n"
                        + "usuario.usuario AS usuario\n"
                        + "FROM venta, cliente, usuario\n"
                        + "WHERE venta.idcliente = cliente.id and "
                        + "venta.idusuario = usuario.id\n"
                        + "ORDER BY venta.id ASC");

        $dompdf = new Dompdf();

        $dompdf = \PDF::loadView('pdf.ventaspdf', ['ventas' => $ventas]);  
        
        return $pdf->download('ventas.pdf');
        */
    }
}
