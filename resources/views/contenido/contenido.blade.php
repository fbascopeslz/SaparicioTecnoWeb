{{-- extends -> hereda de la plantilla 'principal.blade.php' --}}
{{-- section -> crea una seccion --}}

@extends('principal')
@section('contenido')
    <template v-if="menu==0">
        <h1>Contenido del Menu 0</h1>
    </template>

    <template v-if="menu==1">
        <Producto></Producto>
    </template>

    <template v-if="menu==2">
        <Almacen></Almacen>
    </template>

    <template v-if="menu==3">
        <h1>Contenido del Menu 3</h1>
    </template>

    <template v-if="menu==4">
        <Proveedor></Proveedor>
    </template>

    <template v-if="menu==5">
        <h1>Contenido del Menu 5</h1>
    </template>

    <template v-if="menu==6">
        <Cliente></Cliente>
    </template>

    <template v-if="menu==7">
        <Usuario></Usuario>
    </template>

    <template v-if="menu==8">
        <Rol></Rol>
    </template>

    <template v-if="menu==9">
        <h1>Contenido del Menu 9</h1>
    </template>

    <template v-if="menu==10">
        <h1>Contenido del Menu 10</h1>
    </template>
    
    <template v-if="menu==11">
        <h1>Contenido del Menu 11</h1>
    </template>

    <template v-if="menu==12">
        <h1>Contenido del Menu 12</h1>
    </template>
    
@endsection