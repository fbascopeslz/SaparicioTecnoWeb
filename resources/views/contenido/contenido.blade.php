{{-- extends -> hereda de la plantilla 'principal.blade.php' --}}
{{-- section -> crea una seccion --}}

@extends('principal')
@section('contenido')

    @if(Auth::check())

        <template v-if="menu==0">
            <h1>Escritorio</h1>
        </template>

        @if(Auth::user()->idrol == 1)
            <template v-if="menu==1">
                <Producto></Producto>
            </template>

            <template v-if="menu==2">
                <Almacen></Almacen>
            </template>

            <template v-if="menu==3">
            <h1>Inventario</h1>
            </template>

            <template v-if="menu==4">
                <Ingreso></Ingreso>
            </template>

            <template v-if="menu==5">
                <Proveedor></Proveedor>
            </template>

            <template v-if="menu==6">
                <Venta></Venta>
            </template>

            <template v-if="menu==7">
                <Cliente></Cliente>
            </template>

            <template v-if="menu==8">
                <Usuario></Usuario>
            </template>

            <template v-if="menu==9">
                <Rol></Rol>
            </template>

            <template v-if="menu==10">
                <Reportes></Reportes>
            </template>

            <template v-if="menu==11">
                <Estadisticas></Estadisticas>
            </template>              

        @elseif(Auth::user()->idrol == 2)
            <template v-if="menu==2">
                <Almacen></Almacen>
            </template>

            <template v-if="menu==3">
                <h1>Inventario</h1>
            </template>

            <template v-if="menu==4">
                <Ingreso></Ingreso>
            </template>

            <template v-if="menu==5">
                <Proveedor></Proveedor>
            </template>

            <template v-if="menu==11">
                <h1>Reporte Ingresos</h1>
            </template>
            
        @elseif(Auth::user()->idrol == 3)
            <template v-if="menu==3">
                <h1>Inventario</h1>
            </template>

            <template v-if="menu==6">
                <h1>Ventas</h1>
            </template>

            <template v-if="menu==7">
                <Cliente></Cliente>
            </template>

            <template v-if="menu==8">
                <h1>Nota de venta</h1>
            </template>

            <template v-if="menu==12">
                <h1>Reporte Ventas</h1>
            </template>        
        @else

        @endif
        
    @endif    
    
@endsection