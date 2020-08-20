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

Route::get('/', function () {
    return view('contenido/contenido');
});


Route::get('/producto', 'ProductoController@index');
Route::post('/producto/registrar', 'ProductoController@store');
Route::post('/producto/actualizar', 'ProductoController@update');
Route::put('/producto/desactivar', 'ProductoController@desactivar');
Route::put('/producto/activar', 'ProductoController@activar');

Route::get('/almacen', 'AlmacenController@index');
Route::post('/almacen/registrar', 'AlmacenController@store');
Route::post('/almacen/actualizar', 'AlmacenController@update');
Route::put('/almacen/desactivar', 'AlmacenController@desactivar');
Route::put('/almacen/activar', 'AlmacenController@activar');

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

Route::get('/rol', 'RolController@index');
Route::get('/rol/selectRol', 'RolController@selectRol');

Route::get('/usuario', 'UserController@index');
Route::post('/usuario/registrar', 'UserController@store');
Route::post('/usuario/actualizar', 'UserController@update');
Route::put('/usuario/desactivar', 'UserController@desactivar');
Route::put('/usuario/activar', 'UserController@activar');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
