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
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>

                <!--Listado Ingresos-->
                <template v-if="listado == 1">                                    
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipocomprobante">Tipo de Comprobante</option>
                                        <option value="numcomprobante">Numero de Comprobante</option>
                                        <option value="fecha">Fecha</option>                                   
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngresos(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngresos(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                        <th>Total Compra</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Numero Comprobante</th>
                                        <th>Estado</th>
                                        <th>Proveedor</th>
                                        <th>Usuario</th>                                                                                              
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngresos" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            
                                            <template v-if="ingreso.estado == 'REALIZADO'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="anularIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>                             
                                        </td>
                                        <td v-text="ingreso.fecha"></td>
                                        <td v-text="ingreso.hora"></td>
                                        <td v-text="ingreso.totalcompra"></td>
                                        <td v-text="ingreso.tipocomprobante"></td>
                                        <td v-text="ingreso.numcomprobante"></td>
                                        <td v-text="ingreso.estado"></td>
                                        <td v-text="ingreso.proveedor"></td>
                                        <td v-text="ingreso.usuario"></td>                                                                                                                                                            
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

                <!--Nuevo Ingreso-->
                <template v-else-if="listado == 0">                                    
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select                                     
                                        @search="selectProveedor" 
                                        label="nombres"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        @input="getDatosProveedor">

                                    </v-select>
                                </div>
                            </div>                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipoComprobante">                                    
                                        <option value="NOTAVENTA">Nota Venta</option>
                                        <option value="TICKET">Ticket</option>                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="numComprobante" placeholder="Numero de Comprobante">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div v-show="errorIngreso == 1" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Producto <span style="color: red;" v-show="idProducto==0">(*Seleccione)</span></label>
                                    <div class="form-inline">                                       
                                        <input type="text" readonly class="form-control" v-model="producto">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>                                        
                                    </div>
                                    <label>(*)Presione el boton para la busqueda</label>
                                </div>                                
                            </div>
                            <div class="col-md-3">                                
                                <div class="form-group">
                                    <label>Almacen <span style="color: red;" v-show="idAlmacen==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <select class="form-control" v-model="idAlmacen">
                                            <option value="0">Seleccione un Almacen</option>
                                            <option v-for="almacen in arrayAlmacenes" :key="almacen.id" :value="almacen.id" v-text="almacen.nombre"></option>
                                        </select>                                        
                                    </div>
                                    <label>(*)Seleccione una opcion</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color: red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio" min="1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color: red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad" min="1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">                                
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
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
                                                <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{(detalle.precio * detalle.cantidad).toFixed(2)}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>MONTO TOTAL</strong></td>
                                            <td>Bs. {{ totalCompra = calcularTotal }}</td>
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
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Ingreso</button>
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
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
                                </div>
                            </div>                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipoComprobante"></p>
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
                                            <td>
                                                {{(detalle.precio * detalle.cantidad).toFixed(2)}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>MONTO TOTAL</strong></td>
                                            <td>Bs. {{ totalCompra  }}</td>
                                        </tr>                                                                 
                                    </tbody>

                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">No hay Productos agregados</td>
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

        <!--Modal para Productos-->
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
                                    <select class="form-control col-md-3" v-model="criterioProducto">
                                        <option value="codigo">Codigo</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscarProducto" @keyup.enter="listarProductos(buscarProducto, criterioProducto)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProductos(buscarProducto, criterioProducto)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <span>(*)Para visualizar todos los Productos haga una busqueda en blanco</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Codigo</th>
                                        <th>Nombre</th>                                        
                                        <th>Precio</th>                               
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td>
                                            <button type="button" @click="seleccionarProducto(producto)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button> 
                                        </td>
                                        <td v-text="producto.codigo"></td>
                                        <td v-text="producto.nombre"></td>                                        
                                        <td v-text="producto.precio"></td>                            
                                        <td><img class="preview" :src="producto.image"></td>                                
                                        <td>
                                            <div v-if="producto.estado == true">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
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
    import Swal from 'sweetalert2';

    export default {                  
        data() {
            return {               
                idIngreso: 0,
                totalCompra: 0.0,
                tipoComprobante: '',
                numComprobante: '',
                estado: 'REALIZADO',                
                idProveedor: 0,
                idUsuario: 0,                

                arrayIngresos: [],
                arrayDetalles: [],

                idAlmacen: 0,
                almacen: '',
                
                arrayAlmacenes: [],

                arrayProveedor: [],

                arrayProducto: [],
                idProducto: 0,
                producto: '',

                //nombreProducto: '',
                                        
                precio: 0.0,
                cantidad: 0,     
                
                //MostrarIngreso y detalles
                proveedor: '',
                
                //Para la busqueda en el modal Producto
                criterioProducto: 'nombre',
                buscarProducto: '',

                //indica si se visualiza o no el listado
                listado: 1,

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //variable para los errores
                //bandera para ver si encontro error
                errorIngreso: 0,
                //Array con los errores encontrados
                errorMostrarMsjIngreso: [],

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
                buscar: ''
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
                    resultado += (this.arrayDetalles[i].precio * this.arrayDetalles[i].cantidad);                    
                }

                //Redondear a 2 decimales
                return resultado.toFixed(2);;
            },
        },

        methods: {                                
            listarIngresos(page, buscar, criterio) {
                let me = this;

                var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayIngresos = respuesta.ingresos.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },

            selectProveedor(search, loading) {
                let me = this;

                loading(true);

                var url = '/proveedor/selectProveedor?filtro=' + search;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        let respuesta = response.data;
                        q: search
                        me.arrayProveedor = respuesta.proveedores;
                        loading(false);

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },

            getDatosProveedor(val1) {
                let me = this;
                me.loading = true;
                me.idProveedor = val1.id;
                console.log(val1);
            },

            /*
            buscarProductos() {
                let me = this;                

                var url = '/producto/buscarProducto?filtro=' + this.nombreProducto;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayProducto = respuesta.productos;

                        if (me.arrayProducto.length > 0) {
                            me.producto = me.arrayProducto[0]['nombre'];
                            me.idProducto = me.arrayProducto[0]['id'];                            
                        } else {
                            me.producto = 'No existe el Producto';
                            me.idProducto = 0;
                        }

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },    
            */        

            cambiarPagina(page, buscar, criterio) {
                let me = this;

                //Actualizar la pagina actual
                me.pagination.current_page = page;

                //Envia la peticion para visualizar la data de esa pagina
                me.listarIngresos(page, buscar, criterio);
            },

            existeProductoYAlmacen(idProducto, idAlmacen) {            
                for (let i = 0; i < this.arrayDetalles.length; i++) {
                    if (this.arrayDetalles[i].idproducto == idProducto && this.arrayDetalles[i].idalmacen == idAlmacen) {
                        return true;
                    }                    
                }
                return false;                
            },

            buscarNombreAlmacenById(idAlmacen){
                for (let i = 0; i < this.arrayAlmacenes.length; i++) {
                    if (this.arrayAlmacenes[i].id == idAlmacen) {
                        return this.arrayAlmacenes[i].nombre;
                    }                                        
                }
                return '';
            },

            agregarDetalle() {
                let me = this;
                if (me.idProducto == 0 || me.idAlmacen == 0 || me.cantidad == 0 || me.precio ==0) {
                    
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
                            almacen: me.buscarNombreAlmacenById(me.idAlmacen),                           
                            cantidad: me.cantidad,
                            precio: me.precio
                        });

                        //Limpiar variables
                        //me.nombreProducto = '';
                        me.idProducto = 0;
                        me.idAlmacen = 0;
                        me.producto = '';
                        me.almacen = '';
                        me.cantidad = 0;
                        me.precio = 0;
                    }                                        
                }                 
            },
            
            eliminarDetalle(index) {
                let me = this;
                me.arrayDetalles.splice(index, 1);
            },


            /*
            agregarDetalleModal(data = []) {
                let me = this;

                if (me.existeProducto(data['id'])) {
                    //Alerta de SweetAlert                        
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ese Producto ya se encuentra agregado!',                            
                    })
                } else {
                    me.arrayDetalles.push({
                        idproducto: data['id'],
                        producto: data['nombre'],
                        cantidad: 1,
                        precio: 1
                    });                    
                }
            },
            */


            seleccionarProducto(producto){
                this.producto = producto.nombre;
                this.idProducto = producto.id;

                this.cerrarModal();
            },

            listarProductos(buscar, criterio) {
                let me = this;

                var url = '/producto/listarProductos?' + 'buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayProducto = respuesta.productos.data;                        
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },                                              

            validarIngreso() {
                this.errorIngreso = 0;                
                this.errorMostrarMsjIngreso = [];
                                
                if (this.idProveedor == 0) {
                    this.errorMostrarMsjIngreso.push("Seleccione un Proveedor.");
                }

                if (this.tipoComprobante == 0) {
                    this.errorMostrarMsjIngreso.push("Seleccione un tipo de Comprobante.");
                }

                if (!this.numComprobante) {
                    this.errorMostrarMsjIngreso.push("El campo Numero de Comprobante no puede estar vacio.");
                }

                if (this.arrayDetalles.length <= 0) {
                    this.errorMostrarMsjIngreso.push("Se debe agregar almenos un detalle.");
                }
                
                if (this.errorMostrarMsjIngreso.length) {
                    this.errorIngreso = 1;
                }

                return this.errorIngreso;
            },    

            registrarIngreso() {                
                //Verificar las validaciones
                if (this.validarIngreso()) {
                    return;
                }

                let me = this;                                       

                axios.post(
                    '/ingreso/registrar',
                    {
                        'idproveedor': this.idProveedor,
                        'totalcompra': this.totalCompra,
                        'tipocomprobante': this.tipoComprobante,
                        'numcomprobante': this.numComprobante,
                        'estado': this.estado,
                        'data': this.arrayDetalles,                        
                    }                    
                ).then(function (response) {
                    me.listado = 1;                                        
                    me.listarIngresos(1, '', 'numcomprobante');

                    //Limpiar variables
                    me.idProveedor = 0;
                    me.totalCompra = 0.0;
                    me.tipoComprobante = 'NOTAVENTA';
                    me.numComprobante = '';

                    me.idProducto = 0;
                    me.cantidad = 0;
                    me.precio = 0.0;
                    me.arrayDetalles = [];

                
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);
                });
            },

            anularIngreso(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de anular este Ingreso?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        let me = this;

                        axios.put(
                            '/ingreso/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {                         
                            
                            me.listarIngresos(1, '', 'numcomprobante');

                            swalWithBootstrapButtons.fire(
                                'Anulado!',
                                'El Ingreso ha sido anulado con exito.',
                                'success'
                            );

                            console.log(response);

                        }).catch(function (error) {
                            console.log(error);
                        });
                                                
                    }
                });

            },


            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';                
            },

            abrirModal() {   
                this.arrayProducto = [];                             
                this.modal = 1;
                this.tituloModal = "Seleccione uno o varios Productos";

                //Llenar array Productos
                this.listarProductos('', 'nombre');
            },


            getAlmacenes() {
                let me = this;

                var url = '/almacen/getAlmacenes';
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayAlmacenes = respuesta.almacenes;
                        
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },


            mostrarDetalle() {
                this.listado = 0;
                
                let me = this;
                
                //Limpiar variables
                me.idProveedor = 0;
                me.totalCompra = 0.0;
                me.tipoComprobante = 'NOTAVENTA';
                me.numComprobante = '';

                me.idProducto = 0;
                me.cantidad = 0;
                me.precio = 0.0;
                me.arrayDetalles = [];

                //llenar el ArrayAlmacenes
                this.getAlmacenes();
            },

            ocultarDetalle() {
                this.listado = 1;

                //Limpiar variables
                this.arrayDetalles = [];
                this.proveedor = '';
                this.tipoComprobante = '';
                this.numComprobante = '';
                this.totalCompra = 0.0;

            },

            verIngreso(id) {
                var me = this;
                me.listado = 2;

                //Obtener cabezera del Ingreso                
                var url = '/ingreso/obtenerCabezeraIngreso?id=' + id;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;                                                
                        
                        me.proveedor = respuesta.ingreso[0].proveedor;
                        me.tipoComprobante = respuesta.ingreso[0].tipocomprobante;
                        me.numComprobante = respuesta.ingreso[0].numcomprobante;
                        me.totalCompra = respuesta.ingreso[0].totalcompra;

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });


                //Obtener detalles del Ingreso                
                var url2 = '/ingreso/obtenerDetallesIngreso?id=' + id;
                axios.get(url2)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayDetalles = respuesta.detalles;                                                

                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });

            },
                                                    
        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarIngresos(1, this.buscar, this.criterio);                        
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
    }

</style>