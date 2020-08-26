<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function(){
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
}); //Rutas para invitado


Route::group(['middleware' => ['auth']], function(){ //Rutas para usuario autenticado
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main'); //Alias de la ruta
    

    Route::group(['middleware' => ['Administrador']], function(){
        Route::get('/usuario', 'UserController@index');
        Route::post('/usuario/registrar', 'UserController@store');
        Route::post('/usuario/actualizar', 'UserController@update');
        Route::put('/usuario/desactivar', 'UserController@desactivar');
        Route::put('/usuario/activar', 'UserController@activar');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::post('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProductos', 'ProductoController@listarProductos');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::post('/cliente/actualizar', 'ClienteController@update');
        Route::put('/cliente/desactivar', 'ClienteController@desactivar');
        Route::put('/cliente/activar', 'ClienteController@activar');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::post('/proveedor/actualizar', 'ProveedorController@update');
        Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
        Route::put('/proveedor/activar', 'ProveedorController@activar');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/almacen', 'AlmacenController@index');
        Route::post('/almacen/registrar', 'AlmacenController@store');
        Route::post('/almacen/actualizar', 'AlmacenController@update');
        Route::put('/almacen/desactivar', 'AlmacenController@desactivar');
        Route::put('/almacen/activar', 'AlmacenController@activar');
        Route::get('/almacen/getAlmacenes', 'AlmacenController@getAlmacenes');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabezeraIngreso', 'IngresoController@obtenerCabezeraIngreso');
        Route::get('/ingreso/obtenerDetallesIngreso', 'IngresoController@obtenerDetallesIngreso');


        Route::get('/reporteVentas', 'ReportesController@reporteVentas')->name('reporteVentas');
        Route::get('/reporteIngresos', 'ReportesController@reporteIngresos')->name('reporteIngresos');
        Route::get('/reporteProductos', 'ReportesController@reporteProductos')->name('reporteProductos');
        Route::get('/reporteInventario', 'ReportesController@reporteInventario')->name('reporteInventario');


        Route::get('/estadisticaProductosMasVendidos', 'EstadisticasController@estadisticaProductosMasVendidos');
        Route::get('/estadisticaClientesMasFieles', 'EstadisticasController@estadisticaClientesMasFieles');
        Route::get('/estadisticaProveedoresMasSolicitados', 'EstadisticasController@estadisticaProveedoresMasSolicitados');
        Route::get('/estadisticasFinancieras', 'EstadisticasController@estadisticasFinancieras');


    }); //Rutas para Administrador
        
    Route::group(['middleware' => ['Vendedor']], function(){
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::post('/cliente/actualizar', 'ClienteController@update');
        Route::put('/cliente/desactivar', 'ClienteController@desactivar');
        Route::put('/cliente/activar', 'ClienteController@activar');

    }); //Rutas para Vendedor

    Route::group(['middleware' => ['Almacenero']], function(){
        
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProductos', 'ProductoController@listarProductos');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::post('/proveedor/actualizar', 'ProveedorController@update');
        Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
        Route::put('/proveedor/activar', 'ProveedorController@activar');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/almacen', 'AlmacenController@index');
        Route::post('/almacen/registrar', 'AlmacenController@store');
        Route::post('/almacen/actualizar', 'AlmacenController@update');
        Route::put('/almacen/desactivar', 'AlmacenController@desactivar');
        Route::put('/almacen/activar', 'AlmacenController@activar');
        Route::get('/almacen/getAlmacenes', 'AlmacenController@getAlmacenes');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabezeraIngreso', 'IngresoController@obtenerCabezeraIngreso');
        Route::get('/ingreso/obtenerDetallesIngreso', 'IngresoController@obtenerDetallesIngreso');
        
        
    }); //Rutas para Almacenero                        

});



//Route::get('/home', 'HomeController@index')->name('home');
