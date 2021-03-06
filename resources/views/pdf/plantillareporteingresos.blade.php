<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reporte Ingresos</title>
    <style>              
        div.listadoContenedor{    
			text-align: center;                 
            padding: 25px;
            margin: 15px;
            border-top: 1px solid black;
            border-bottom: 1px solid black;            
        }        
		table { 
			margin: auto; 
		}
        div.listadoContenedor tr th {
            text-align: center;
			position: relative;
            padding: 10px;			
            background-color: deepskyblue;
        }
        div.listadoContenedor tr td{
			text-align: center;
            position: relative;
            padding: 10px;           
        }     
        body
        {            
			padding: 15px;
            border:3px solid gray;
			/*background-color: cadetblue;*/
        }        
        .imgLogo
        { 
            position: absolute;
            
        }        
        div.reporteContact{
            position: absolute;
            top: 0px;
            left: 10px;           
        }        		
		.column {
			float: left;
			width: 33.33%;
			text-align: center;
		}		
		/* Clear floats after the columns */
		.row:after {		  
		  display: table;
		  clear: both;
		}
    </style>
</head>
<body>     
	<div class="row">	
		<div class="column" style="text-align: left;">	
			<p>Telefono: <b>75304132</b></p>
			<p>Correo: <b>saparicio@gmail.com</b></p>
			<p>Direccion: <b>Calle Los Tusequis N 123</b></p>
		</div>				
		<div class="column">
			<h1>LISTA DE INGRESOS</h1>
			<h2>{{date('d-m-Y')}}</h2>
			<h3>{{date('H:i:s')}}</h3>
		</div>		
		<div class="column" style="text-align: right;">
			<img src="logo/logo.png">
		</div>					
	</div>	

	<div class="listadoContenedor" style="clear: both;">    
		<table>
			<thead>
				<tr>                      
					<th>Fecha</th>	
					<th>Hora</th>
					<th>Total Compra</th>
					<th>Tipo Comprobante</th>
					<th>Numero Comprobante</th>
					<th>Estado</th>					
					<th>Proveedor</th>
					<th>Usuario</th>					
				</tr>            				
			</thead>
			<tbody>
				@foreach ($ingresos as $ingreso)					
				<tr>         
					<td>{{ $ingreso->fecha }}</td>
					<td>{{ $ingreso->hora }}</td>
					<td>{{ $ingreso->totalcompra }}</td>					
					<td>{{ $ingreso->tipocomprobante }}</td>
					<td>{{ $ingreso->numcomprobante }}</td>
					<td>{{ $ingreso->estado }}</td>
					<td>{{ $ingreso->proveedor }}</td>
					<td>{{ $ingreso->usuario }}</td>										
				</tr>
				@endforeach        								
			</tbody>                   
		</table>
	</div>

</body>
