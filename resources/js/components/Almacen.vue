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
                    <i class="fa fa-align-justify"></i> Almacenes
                    <button type="button" @click="abrirModal('almacen', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="direccion">Direccion</option>                                    
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarAlmacenes(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarAlmacenes(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Latitud</th>
                                <th>Longitud</th>                                
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="almacen in arrayAlmacenes" :key="almacen.id">
                                <td>
                                    <button type="button" @click="abrirModal('almacen', 'actualizar', almacen)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    
                                    <template v-if="almacen.estado">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarAlmacen(almacen.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarAlmacen(almacen.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>

                                </td>
                                <td v-text="almacen.nombre"></td>
                                <td v-text="almacen.direccion"></td>
                                <td v-text="almacen.latitud"></td>
                                <td v-text="almacen.longitud"></td>                                                                                            
                                <td>
                                    <div v-if="almacen.estado == true">
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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Almacen">
                                    <span class="help-block">(*) Ingrese el nombre del Almacen</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Direccion del almacen">
                                    <span class="help-block">(*) Ingrese la direccion del Almacen</span>
                                </div>
                            </div>                        
                        
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Ubicacion</label>
                                <div class="col-md-9">
                                    <GmapMap
                                        :center="posicion"
                                        :zoom="zoom"
                                        style="width: 500px; height: 300px">
                                        <GmapMarker                                                                                       
                                            :position="posicion"
                                            :clickable="true"
                                            :draggable="true"                                            
                                            v-on:dragend="actualizarMarcador"/>
                                    </GmapMap>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <span class="help-block">Latitud</span>
                                            <input type="number" v-model="posicion.lat" class="form-control" placeholder="Latitud" readonly>                                                                         
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <span class="help-block">Longitud</span>
                                            <input type="number" v-model="posicion.lng" class="form-control" placeholder="Longitud" readonly>
                                        </div>
                                    </div>
                                </div>                                                                
                            </div>

                            <div v-show="errorAlmacen == 1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjAlmacen" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>                                         
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarAlmacen()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarAlmacen()">Actualizar</button>
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
                posicion: {
                    lat: -17.776210, lng: -63.195079
                },
                zoom: 11,

                idAlmacen: 0,
                nombre: '',
                direccion: '',
                //latitud: 0.0,
                //longitud: 0.0,                 

                arrayAlmacenes: [],

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //variable para los errores
                //bandera para ver si encontro error
                errorAlmacen: 0,
                //Array con los errores encontrados
                errorMostrarMsjAlmacen: [],

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
                criterio: 'nombre',
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
            actualizarMarcador(e){
                this.posicion.lat = e.latLng.lat();
                this.posicion.lng = e.latLng.lng();

                //console.log(e.latLng.lat());
                //console.log(e.latLng.lng());                       
            },

            listarAlmacenes(page, buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = '/almacen?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayAlmacenes = respuesta.almacenes.data;
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
                me.listarAlmacenes(page, buscar, criterio);
            },

            registrarAlmacen() {                
                //Verificar las validaciones
                if (this.validarAlmacen()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;                                       

                axios.post(
                    '/almacen/registrar',
                    {
                        'nombre': this.nombre,
                        'direccion': this.direccion,
                        'latitud': this.posicion.lat,
                        'longitud': this.posicion.lng
                    }                    
                ).then(function (response) {
                    loader.hide();

                    me.cerrarModal();
                    me.listarAlmacenes(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
            },

            actualizarAlmacen() {

                //Verificar las validaciones
                if (this.validarAlmacen()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;
            
                axios.post(
                    '/almacen/actualizar',
                    {
                        'id': this.idAlmacen,
                        'nombre': this.nombre,
                        'direccion': this.direccion,
                        'latitud': this.posicion.lat,
                        'longitud': this.posicion.lng
                    }                                    
                ).then(function (response) {
                    loader.hide();

                    me.cerrarModal();
                    me.listarAlmacenes(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
                
            },

            desactivarAlmacen(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de desactivar este Almacen?',
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
                            '/almacen/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {   
                            loader.hide();                      
                            
                            me.listarAlmacenes(1, '', 'nombre');

                            swalWithBootstrapButtons.fire(
                                'Desactivado!',
                                'El Almacen ha sido desactivado con exito.',
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

            activarAlmacen(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de activar este Almacen?',
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
                            '/almacen/activar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {
                            loader.hide(); 

                            me.listarAlmacenes(1, '', 'nombre');
                            
                            swalWithBootstrapButtons.fire(
                                'Activado!',
                                'El Almacen ha sido activado con exito.',
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
                
                this.nombre = '';
                this.direccion = '';
                
                //reiniciar configuracion del mapa
                this.posicion = {
                    lat: -17.776210, lng: -63.195079
                },                                

                //limpiar las validaciones
                this.errorProducto = 0;
                this.errorMostrarMsjProducto = [];
            },

            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case "almacen":
                        switch (accion) {
                            case "registrar":
                                this.modal = 1;
                                this.tituloModal = "Registrar Almacen";
                                this.tipoAccion = 1;

                                this.idAlmacen = 0;
                                this.nombre = '';
                                this.direccion = '';                                                                                            
                                break;

                            case "actualizar":
                                //console.log(data);                              
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Almacen';
                                this.tipoAccion = 2;

                                this.idAlmacen = data['id'];                                
                                this.nombre = data['nombre'].trim();
                                this.direccion = data['direccion'].trim();
                                this.posicion.lat = parseFloat(data['latitud']);
                                this.posicion.lng = parseFloat(data['longitud']);                                
                                break;
                        
                            default:
                                break;
                        }
                        break;
                
                    default:
                        break;
                }
            },
                    
            validarAlmacen() {
                this.errorAlmacen = 0;                
                this.errorMostrarMsjAlmacen = [];

                if (!this.nombre) {
                    this.errorMostrarMsjAlmacen.push("El campo Nombre no puede estar vacio.");
                }

                if (!this.direccion) {
                    this.errorMostrarMsjAlmacen.push("El campo Direccion no puede estar vacio.");
                }                                                             
                
                if (this.errorMostrarMsjAlmacen.length) {
                    this.errorAlmacen = 1;
                }

                return this.errorAlmacen;
            },                        

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarAlmacenes(1, this.buscar, this.criterio);
                        
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

    .file-upload-form, .image-preview {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        padding: 20px;
    }
    img.preview {
        width: 150px;
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

</style>