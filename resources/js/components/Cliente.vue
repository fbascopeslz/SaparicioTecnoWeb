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
                    <i class="fa fa-align-justify"></i> Clientes
                    <button type="button" @click="abrirModal('cliente', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombres">Nombre</option>
                                    <option value="numdocumento">Numero Documento</option>
                                    <option value="email">Email</option>                                   
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarClientes(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarClientes(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Numero Documento</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Email</th>
                                <th>Fecha Union</th>                              
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cliente in arrayClientes" :key="cliente.id">
                                <td>
                                    <button type="button" @click="abrirModal('cliente', 'actualizar', cliente)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    
                                    <template v-if="cliente.estado">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarCliente(cliente.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarCliente(cliente.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>

                                </td>
                                <td v-text="cliente.nombres"></td>
                                <td v-text="cliente.tipodocumento"></td>
                                <td v-text="cliente.numdocumento"></td>
                                <td v-text="cliente.direccion"></td>
                                <td v-text="cliente.telefono"></td>
                                <td v-text="cliente.email"></td>
                                <td v-text="cliente.fechaunion"></td>                                                                                                                           
                                <td>
                                    <div v-if="cliente.estado == true">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                            </tr>
                           
                        </tbody>
                    </table>
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
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal agregar/actualizar-->
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

                        <form method="post" enctype="multipart/form-data" class="form-horizontal">                        
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Completo</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombres" class="form-control" placeholder="Nombre Completo">
                                    <span class="help-block">(*) Ingrese el Nombre completo del Cliente</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo de Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipoDocumento" class="form-control">
                                        <option value="CI">CI</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                        <option value="NIT">NIT</option>
                                    </select>
                                    <span class="help-block">(*) Seleccione el Tipo de Documento del Cliente</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Numero de Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="numDocumento" @keypress="isNumber($event)" class="form-control" placeholder="Numero de Documento">
                                    <span class="help-block">(*) Ingrese el Numero de Documento del Cliente</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" @keypress="isNumber($event)" class="form-control" placeholder="Telefono">                                    
                                </div>
                            </div>      
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">                                    
                                </div>
                            </div>     
                                                    
                            <div v-show="errorCliente == 1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCliente" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>                                         
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarCliente()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarCliente()">Actualizar</button>
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
    //SweetAlert2
    import Swal from 'sweetalert2';

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
                idCliente: 0,
                nombres: '',
                tipoDocumento: 'CI',
                numDocumento: '',
                direccion: '',
                telefono: '',
                email: '',

                arrayClientes: [],

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //variable para los errores
                //bandera para ver si encontro error
                errorCliente: 0,
                //Array con los errores encontrados
                errorMostrarMsjCliente: [],

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
                criterio: 'nombres',
                buscar: '',

                //opciones para Vue loading overlay        
                optionsLoadingOverlay : {                        
                    canCancel: false,
                    color: '#007BFF',
                    height:	128,
                    width: 128
                },
            }
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
            }
        },

        methods: {
            //Validar que el input solo acepte numeros y punto
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            
            
            listarClientes(page, buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayClientes = respuesta.clientes.data;
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

            cambiarPagina(page, buscar, criterio) {
                let me = this;

                //Actualizar la pagina actual
                me.pagination.current_page = page;

                //Envia la peticion para visualizar la data de esa pagina
                me.listarClientes(page, buscar, criterio);
            },

            registrarCliente() {                
                //Verificar las validaciones
                if (this.validarCliente()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;                                       

                axios.post(
                    'cliente/registrar',
                    {
                        'nombres': this.nombres,
                        'tipodocumento': this.tipoDocumento,
                        'numdocumento': this.numDocumento,
                        'direccion': this.direccion,
                        'telefono': this.telefono,
                        'email': this.email
                    }                    
                ).then(function (response) {
                    loader.hide();

                    me.cerrarModal();
                    me.listarClientes(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
            },

            actualizarCliente() {

                //Verificar las validaciones
                if (this.validarCliente()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;
            
                axios.post(
                    'cliente/actualizar',
                    {
                        'id': this.idAlmacen,
                        'nombres': this.nombres,
                        'tipodocumento': this.tipoDocumento,
                        'numdocumento': this.numDocumento,
                        'direccion': this.direccion,
                        'telefono': this.telefono,
                        'email': this.email                        
                    }                                    
                ).then(function (response) {
                    loader.hide();

                    me.cerrarModal();
                    me.listarClientes(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
                
            },

            desactivarCliente(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de desactivar a este Cliente?',
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
                            'cliente/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {
                            loader.hide();                       
                            
                            me.listarClientes(1, '', 'nombre');

                            swalWithBootstrapButtons.fire(
                                'Desactivado!',
                                'El Cliente ha sido desactivado con exito.',
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

            activarCliente(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de activar a este Cliente?',
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
                            'cliente/activar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {
                            loader.hide();

                            me.listarClientes(1, '', 'nombre');
                            
                            swalWithBootstrapButtons.fire(
                                'Activado!',
                                'El Cliente ha sido activado con exito.',
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

                this.nombres = '';
                this.tipoDocumento = 'CI';
                this.numDocumento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';                                
                               
                //limpiar las validaciones
                this.errorCliente = 0;
                this.errorMostrarMsjCliente = [];
            },

            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case "cliente":
                        switch (accion) {
                            case "registrar":
                                this.modal = 1;
                                this.tituloModal = "Registrar Cliente";
                                this.tipoAccion = 1;

                                this.idCliente = 0;
                                this.nombres = '';
                                this.tipoDocumento = 'CI';
                                this.numDocumento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';                                                                                            
                                break;

                            case "actualizar":
                                //console.log(data);                              
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Cliente';
                                this.tipoAccion = 2;

                                this.idAlmacen = data['id'];                                
                                this.nombres = data['nombres'].trim();
                                this.tipoDocumento = data['tipodocumento'].trim();
                                this.numDocumento = data['numdocumento'].trim();
                                
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data['email'];                                                               
                                break;
                        
                            default:
                                break;
                        }
                        break;
                
                    default:
                        break;
                }
            },
                    
            validarCliente() {
                this.errorCliente = 0;                
                this.errorMostrarMsjCliente = [];

                if (!this.nombres) {
                    this.errorMostrarMsjCliente.push("El campo Nombres no puede estar vacio.");
                }

                if (!this.numDocumento) {
                    this.errorMostrarMsjCliente.push("El campo Numero de Documento no puede estar vacio.");
                }                                                             
                
                if (this.errorMostrarMsjCliente.length) {
                    this.errorCliente = 1;
                }

                return this.errorCliente;
            },                        

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarClientes(1, this.buscar, this.criterio);                        
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

</style>