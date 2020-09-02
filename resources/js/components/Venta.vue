<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>            
        </ol>
        
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>

                <!--Listado Ventas-->
                <template v-if="listado == 1">                                    
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">                                        
                                        <option value="numcomprobante">Numero de Comprobante</option>
                                        <option value="fecha">Fecha</option>                                   
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVentas(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVentas(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">                    
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Total</th>                                                                                
                                        <th>Estado</th>
                                        <th>Numero Comprobante</th>
                                        <th>Cliente</th>
                                        <th>Usuario</th>                                                                                              
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVentas" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;

                                            <button type="button" @click="generarNotaDeVenta(venta.id)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                            </button> &nbsp;
                                            
                                            <template v-if="venta.estado == 'REALIZADO'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="anularVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>                             
                                        </td>
                                        <td v-text="venta.fecha"></td>
                                        <td v-text="venta.hora"></td>
                                        <td v-text="venta.total"></td>                                                                                
                                        <td v-text="venta.estado"></td>
                                        <td v-text="venta.numcomprobante"></td>
                                        <td v-text="venta.cliente"></td>
                                        <td v-text="venta.usuario"></td>                                                                                                                                                            
                                    </tr>                            
                                </tbody>
                            </table>
                        </div>

                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>       

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin-->

                <!--Nueva Venta-->
                <template v-else-if="listado == 0">                                    
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select                                     
                                        @search="selectCliente" 
                                        label="nombres"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        @input="getDatosCliente">

                                    </v-select>
                                </div>
                            </div>                                                   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="numComprobante" placeholder="Numero de Comprobante">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div v-show="errorVenta == 1" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                                
                        <div class="form-row form-group border">                                  
                            <div class="form-group col-md-3">                                        
                                <label>Producto</label>                                                                                  
                                <input type="text" readonly class="form-control" v-model="producto">                                                                                                                                                                                                                                              
                            </div> 
                            <div class="form-group col-md-3">                                    
                                <label>Almacen</label>                                                                                                                       
                                <input type="text" readonly class="form-control" v-model="almacen">                                                                                                                                                                                                                         
                            </div>
                            <div class="form-group col-md-3">                                                                       
                                <label>Stock</label>                                           
                                <input type="number" readonly value="0" class="form-control" v-model="stock">                                                                                                                                                             
                            </div>
                            <div class="form-group col-md-2">                                
                                <div class="form-inline">                                                                               
                                    <button @click="abrirModal()" class="btn btn-primary form control btnbuscar">Buscar</button>                                        
                                </div>                                
                            </div>                                                                                                                                  
                                                                                                                                                        
                            <div class="form-group col-md-3">                                        
                                <label>Precio</label>
                                <input type="number" value="0" step="any" class="form-control" v-model="precio" min="1">                                          
                            </div>                            
                            <div class="form-group col-md-3">                                        
                                <label>Cantidad</label>
                                <input type="number" value="0" class="form-control" v-model="cantidad" min="1">                                                                        
                            </div>
                            <div class="form-group col-md-3">                                        
                                <label>Descuento</label>
                                <input type="number" value="0" class="form-control" v-model="descuento" min="0.00" max="1.00">                                        
                            </div>
                            <div class="form-group col-md-2">                                                                   
                                <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>                                        
                            </div>     

                            <div class="col-md-12">
                                <div v-show="errorVentaDetalle == 1" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVentaDetalle" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Producto</th>
                                        <th>Almacen</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>

                                    <tbody v-if="arrayDetalles.length">
                                        <tr v-for="(detalle, index) in arrayDetalles" :key="index">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.producto"></td>
                                            <td v-text="detalle.almacen"></td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" value="0" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" value="0" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.descuento" type="number" value="0" class="form-control">
                                            </td>
                                            <td>
                                                {{ ((detalle.precio * detalle.cantidad) - (detalle.precio * detalle.cantidad * detalle.descuento)).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="6" align="right"><strong>MONTO TOTAL</strong></td>
                                            <td>Bs. {{ total = calcularTotal }}</td>
                                        </tr>                                                                 
                                    </tbody>

                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin-->

                <!--Ver Cabezera Ingreso y Detalles-->
                <template v-else-if="listado == 2">                                    
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>                                                   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante</label>
                                    <p v-text="numComprobante"></p>
                                </div>
                            </div>                        
                        </div>                        
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>                                        
                                        <th>Producto</th>
                                        <th>Almacen</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>

                                    <tbody v-if="arrayDetalles.length">
                                        <tr v-for="(detalle, index) in arrayDetalles" :key="index">                                            
                                            <td v-text="detalle.producto">                                                
                                            </td>
                                            <td v-text="detalle.almacen">                                                
                                            </td>
                                            <td v-text="detalle.precio">                                                
                                            </td>
                                            <td v-text="detalle.cantidad">                                                
                                            </td>
                                            <td v-text="detalle.descuento">                                                
                                            </td>
                                            <td>
                                                {{ ((detalle.precio * detalle.cantidad) - (detalle.precio * detalle.cantidad * detalle.descuento)).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>MONTO TOTAL</strong></td>
                                            <td>Bs. {{ total  }}</td>
                                        </tr>                                                                 
                                    </tbody>

                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>                                
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Modal para Inventario-->
        <div class="modal" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; overflow-y: scroll;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioInventario">                                        
                                        <option value="producto.nombre">Producto</option>
                                        <option value="almacen.nombre">Almacen</option>                                        
                                        <option value="producto.codigo">Codigo Producto</option>
                                    </select>
                                    <input type="text" v-model="buscarInventario" @keyup.enter="listarInventario(buscarInventario, criterioInventario)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarInventario(buscarInventario, criterioInventario)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <span>(*)Para visualizar todos los Productos haga una busqueda en blanco</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Almacen</th>
                                        <th>Producto</th>                                        
                                        <th>Codigo Producto</th>                               
                                        <th>Descripcion</th>
                                        <th>Precio Recomendado</th>
                                        <th>Imagen</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(inventario, index) in arrayInventario" :key="index">
                                        <td>
                                            <button type="button" @click="seleccionarInventario(inventario)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button> 
                                        </td>
                                        <td v-text="inventario.almacen"></td>
                                        <td v-text="inventario.producto"></td>                                        
                                        <td v-text="inventario.codigo"></td>
                                        <td v-text="inventario.descripcion"></td>
                                        <td v-text="inventario.precio"></td>                            
                                        <td><img class="preview" :src="inventario.image"></td>
                                        <td v-text="inventario.stock"></td>                                       
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>                        
                    </div>                             

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

    </main>
</template>

<script>
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';

    //SweetAlert2
    //import Swal from 'sweetalert2';
    import Swal from 'sweetalert2/dist/sweetalert2.js';
    import 'sweetalert2/src/sweetalert2.scss';

    import Vue from 'vue';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);


    export default {                  
        data() {
            return {               
                idVenta: 0,
                total: 0.0,                
                numComprobante: '',
                estado: 'REALIZADO',                
                idCliente: 0,
                idUsuario: 0,                

                arrayVentas: [],
                arrayDetalles: [],                                                

                arrayCliente: [],

                arrayInventario: [],
                idProducto: 0,
                producto: '',
                idAlmacen: 0,
                almacen: '',

                stock: 0,                                                                        
                precio: 0.0,
                cantidad: 0,
                descuento: 0.0,  
                
                //Mostrar Venta y Detalles
                cliente: '',
                
                //Para la busqueda en el modal Producto
                criterioInventario: 'producto.nombre',
                buscarInventario: '',

                //indica si se visualiza o no el listado
                listado: 1,

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //variable para los errores
                //bandera para ver si encontro error
                errorVenta: 0,
                //Array con los errores encontrados
                errorMostrarMsjVenta: [],
                
                errorVentaDetalle: 0,                
                errorMostrarMsjVentaDetalle: [],

                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,

                //Busqueda
                criterio: 'numcomprobante',
                buscar: '',

                //opciones para Vue loading overlay        
                optionsLoadingOverlay : {                        
                    canCancel: false,
                    color: '#007BFF',
                    height:	128,
                    width: 128
                },
                
                url: 'http://young-everglades-57516.herokuapp.com/',

            }
        },

        components: {
            vSelect
        },

        //propiedades computadas
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },

            //Calcula los elementos de la paginacion
            pagesNumber: function() {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            },

            calcularTotal: function() {
                var resultado = 0.0;
                for (let i = 0; i < this.arrayDetalles.length; i++) {
                    resultado += (this.arrayDetalles[i].precio * this.arrayDetalles[i].cantidad) - 
                        (this.arrayDetalles[i].precio * this.arrayDetalles[i].cantidad * this.arrayDetalles[i].descuento);                    
                }

                //Redondear a 2 decimales
                return resultado.toFixed(2);
            },        
        },

        methods: {                                
            listarVentas(page, buscar, criterio) {

                let loader = this.$loading.show(this.optionsLoadingOverlay);
             
                var me = this;

                var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayVentas = respuesta.ventas.data;
                        me.pagination = respuesta.pagination;

                        loader.hide();                        

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);

                        loader.hide();
                    })
                    .then(function () {
                        // always executed
                    });
            },

            selectCliente(search, loading) {
                let me = this;

                loading(true);

                var url = '/cliente/selectCliente?filtro=' + search;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        let respuesta = response.data;
                        q: search;
                        me.arrayCliente = respuesta.clientes;
                        loading(false);

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);

                        loading(false);
                    })
                    .then(function () {
                        // always executed
                    });
            },

            getDatosCliente(val1) {
                let me = this;
                me.loading = true;
                me.idCliente = val1.id;
                console.log(val1);
            },

            cambiarPagina(page, buscar, criterio) {
                let me = this;

                //Actualizar la pagina actual
                me.pagination.current_page = page;

                //Envia la peticion para visualizar la data de esa pagina
                me.listarVentas(page, buscar, criterio);
            },

            existeProductoYAlmacen(idProducto, idAlmacen) {            
                for (let i = 0; i < this.arrayDetalles.length; i++) {
                    if (this.arrayDetalles[i].idproducto == idProducto && this.arrayDetalles[i].idalmacen == idAlmacen) {
                        return true;
                    }                    
                }
                return false;                
            },

            validarVentaDetalle() {
                this.errorVentaDetalle = 0;                
                this.errorMostrarMsjVentaDetalle = [];
                                
                if (this.idProducto == 0) {
                    this.errorMostrarMsjVentaDetalle.push("Seleccione un Producto.");
                }              
                if (!this.cantidad) {
                    this.errorMostrarMsjVentaDetalle.push("El campo Cantidad no puede estar vacio.");
                }
                if (!this.precio) {
                    this.errorMostrarMsjVentaDetalle.push("El campo Precio no puede estar vacio.");
                }
                if (this.errorMostrarMsjVentaDetalle.length) {
                    this.errorVentaDetalle = 1;
                }

                return this.errorVentaDetalle;
            },

            agregarDetalle() {
                let me = this;

                if (this.validarVentaDetalle()) {
                    return;
                } else {
                    if (me.existeProductoYAlmacen(me.idProducto, me.idAlmacen)) {
                        //Alerta de SweetAlert                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Ese Articulo ya se encuentra agregado!',                            
                        })
                    } else {
                        me.arrayDetalles.push({
                            idproducto: me.idProducto,
                            producto: me.producto,
                            idalmacen: me.idAlmacen,
                            almacen: me.almacen,                           
                            cantidad: me.cantidad,
                            precio: me.precio,
                            descuento: me.descuento,
                        });

                        //Limpiar variables                        
                        me.idProducto = 0;
                        me.idAlmacen = 0;
                        me.producto = '';
                        me.almacen = '';
                        me.stock = 0;
                        me.cantidad = 0;
                        me.precio = 0.0;
                        me.descuento = 0.0;
                    }                                        
                }                 
            },
            
            eliminarDetalle(index) {
                let me = this;
                me.arrayDetalles.splice(index, 1);
            },

            seleccionarInventario(inventario){
                this.producto = inventario.producto;
                this.idProducto = inventario.idproducto;
                this.almacen = inventario.almacen;
                this.idAlmacen = inventario.idalmacen;
                this.stock = inventario.stock;
                this.precio = inventario.precio;                             

                this.cerrarModal();
            },

            listarInventario(buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = '/producto/listarInventarioModal' + '?buscar=' + buscar + '&criterio=' + criterio;;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayInventario = respuesta.inventario;
                        
                        loader.hide();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                        
                        loader.hide();
                    })
                    .then(function () {
                        // always executed
                    });
            },                                              

            validarVenta() {
                this.errorVenta = 0;                
                this.errorMostrarMsjVenta = [];
                                
                if (this.idCliente == 0) {
                    this.errorMostrarMsjVenta.push("Seleccione un Cliente.");
                }              
                if (!this.numComprobante) {
                    this.errorMostrarMsjVenta.push("El campo Numero de Comprobante no puede estar vacio.");
                }
                if (this.arrayDetalles.length <= 0) {
                    this.errorMostrarMsjVenta.push("Se debe agregar almenos un detalle.");
                }
                if (this.errorMostrarMsjVenta.length) {
                    this.errorVenta = 1;
                }

                return this.errorVenta;
            },    

            registrarVenta() {                
                //Verificar las validaciones
                if (this.validarVenta()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;                                       

                axios.post(
                    '/venta/registrar',
                    {
                        'idcliente': this.idCliente,
                        'total': this.total,                        
                        'numcomprobante': this.numComprobante,
                        'estado': this.estado,
                        'data': this.arrayDetalles,                        
                    }                    
                ).then(function (response) {
                    loader.hide();
                    
                    me.listado = 1;                                        
                    me.listarVentas(1, '', 'numcomprobante');

                    //Limpiar variables
                    me.idCliente = 0;
                    me.total = 0.0;                    
                    me.numComprobante = '';

                    me.idProducto = 0;
                    me.cantidad = 0;
                    me.precio = 0.0;
                    me.descuento = 0.0;
                    me.stock = 0.0;
                    me.arrayDetalles = [];                    

                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
            },

            anularVenta(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de anular esta Venta?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        let loader = this.$loading.show(this.optionsLoadingOverlay);

                        let me = this;

                        axios.put(
                            '/venta/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {
                            
                            loader.hide();
                            
                            me.listarVentas(1, '', 'numcomprobante');

                            swalWithBootstrapButtons.fire(
                                'Anulado!',
                                'La Venta ha sido anulado con exito.',
                                'success'
                            );

                            console.log(response);

                        }).catch(function (error) {
                            console.log(error);

                            loader.hide();
                        });
                                                
                    }
                });

            },

            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';                
            },

            abrirModal() {   
                this.arrayInventario = [];                             
                this.modal = 1;
                this.tituloModal = "Seleccione un Producto";

                //Llenar array Inventario
                this.listarInventario('', 'nombre');
            },

            mostrarDetalle() {
                this.listado = 0;
                
                let me = this;
                
                //Limpiar variables
                me.idCliente = 0;
                me.total = 0.0;                
                me.numComprobante = '';

                me.idProducto = 0;
                me.cantidad = 0;
                me.precio = 0.0;
                me.descuento = 0.0;
                me.arrayDetalles = [];
            },

            ocultarDetalle() {
                this.listado = 1;

                //Limpiar variables
                this.arrayDetalles = [];
                this.cliente = '';                
                this.numComprobante = '';
                this.total = 0.0;
            },

            verVenta(id) {                
                //Mostrar loading overlay
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                var me = this;
                me.listado = 2;

                //Obtener cabezera del Ingreso                
                var url = '/venta/obtenerCabezeraVenta?id=' + id;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;                                                
                        
                        me.cliente = respuesta.venta[0].cliente;                        
                        me.numComprobante = respuesta.venta[0].numcomprobante;
                        me.total = respuesta.venta[0].total;
                        
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);

                        //Ocultar loading overlay
                        loader.hide();
                    })
                    .then(function () {
                        // always executed
                    });


                //Obtener detalles del venta                
                var url2 = '/venta/obtenerDetallesVenta?id=' + id;
                axios.get(url2)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayDetalles = respuesta.detalles;                                                

                        //Ocultar loading overlay
                        loader.hide();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);

                        //Ocultar loading overlay
                        loader.hide();
                    })
                    .then(function () {
                        // always executed
                    });

            },

            generarNotaDeVenta(id) {            
                window.open(window.location.origin + '/venta/generarNotaDeVenta/' + id, '_blank');                                                                                        
            },
                                                    
        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarVentas(1, this.buscar, this.criterio);                        
        }
    }
    
</script>

<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3C29297A !important;
    }

    .preview {
        width: 100px;
        background-color: white;
        border: 1px solid #DDD;
        padding: 5px;
    }

    .div-error {
        display: flex;
        justify-content: center;        
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
        .btnbuscar {
            margin-top: 2rem;
        }
    }

</style>