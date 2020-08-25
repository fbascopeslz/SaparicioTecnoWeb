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
                    <i class="fa fa-align-justify"></i> Productos
                    <button type="button" @click="abrirModal('producto', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="codigo">Codigo</option>
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarProductos(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarProductos(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>                               
                                <th>Imagen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in arrayProductos" :key="producto.id">
                                <td>
                                    <button type="button" @click="abrirModal('producto', 'actualizar', producto)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    
                                    <template v-if="producto.estado">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>

                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarProducto(producto.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>

                                </td>
                                <td v-text="producto.codigo"></td>
                                <td v-text="producto.nombre"></td>
                                <td v-text="producto.descripcion"></td>
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
                                <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="codigo" class="form-control" placeholder="Codigo del Producto">
                                    <span class="help-block">(*) Ingrese el codigo del Producto</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Producto">
                                    <span class="help-block">(*) Ingrese el nombre del Producto</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripcion del Producto">
                                    <span class="help-block">(*) Ingrese la descripcion del Producto</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="precio" class="form-control" placeholder="Precio del Producto">
                                    <span class="help-block">(*) Ingrese el precio del Producto</span>
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Imagen</label>
                                <div class="col-md-9">                                    
                                    
                                    <div class="file-upload-form">
                                        <input type="file" accept="image/*" class="form-control" v-on:change="uploadImage" ref="imagenProducto">
                                    </div>
                                    <div class="image-preview" v-if="previewImage">
                                        <img class="preview" :src="previewImage">
                                    </div>

                                </div>
                            </div>

                            <div v-show="errorProducto == 1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>                                         

                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
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

    export default {
        
        data() {
            return {
                idProducto: 0,
                codigo: '',
                nombre: '',
                descripcion: '',
                precio: 0.0,               
                //archivo imagen
                imagen: null,

                arrayProductos: [],

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //path para el img
                previewImage: null,

                //variable para los errores
                //bandera para ver si encontro error
                errorProducto: 0,
                //Array con los errores encontrados
                errorMostrarMsjProducto: [],

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
                criterio: 'codigo',
                buscar: ''
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
            listarProductos(page, buscar, criterio) {
                let me = this;

                var url = '/producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayProductos = respuesta.productos.data;
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

            cambiarPagina(page, buscar, criterio) {
                let me = this;

                //Actualizar la pagina actual
                me.pagination.current_page = page;

                //Envia la peticion para visualizar la data de esa pagina
                me.listarProductos(page, buscar, criterio);
            },

            registrarProducto() {
                //e.preventDefault();

                //Verificar las validaciones
                if (this.validarProducto()) {
                    return;
                }

                let me = this;

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('imagen', this.imagen);
                formData.append('codigo', this.codigo);
                formData.append('nombre', this.nombre);
                formData.append('descripcion', this.descripcion);
                formData.append('precio', this.precio);                      

                axios.post(
                    '/producto/registrar', 
                    formData,
                    config                 
                ).then(function (response) {

                    me.cerrarModal();
                    me.listarProductos(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);
                });

            },

            actualizarProducto() {

                //Verificar las validaciones
                if (this.validarProducto()) {
                    return;
                }

                let me = this;

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                
                //Si cargo una nueva imagen se enviara el archivo al servidor para que se guarde en Cloudinary
                //Si no, no se enviara ese parametro
                if (this.imagen != null) {                   
                    formData.append('imagen', this.imagen);
                }
        
                formData.append('id', this.idProducto);
                formData.append('codigo', this.codigo);
                formData.append('nombre', this.nombre);
                formData.append('descripcion', this.descripcion);
                formData.append('precio', this.precio);                
                                               
                axios.post(
                    '/producto/actualizar',
                    formData,
                    config                                        
                ).then(function (response) {

                    me.cerrarModal();
                    me.listarProductos(1, '', 'nombre');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);
                });
                
            },

            desactivarProducto(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de desactivar este Producto?',
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
                            '/producto/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {                         
                            
                            me.listarProductos(1, '', 'nombre');

                            swalWithBootstrapButtons.fire(
                                'Desactivado!',
                                'El Producto ha sido desactivado con exito.',
                                'success'
                            );

                            console.log(response);

                        }).catch(function (error) {
                            console.log(error);
                        });
                                                
                    }
                });

            },

            activarProducto(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de activar este Producto?',
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
                            '/producto/activar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) { 

                            me.listarProductos(1, '', 'nombre');
                            
                            swalWithBootstrapButtons.fire(
                                'Activado!',
                                'El Producto ha sido activado con exito.',
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

                this.codigo = '';
                this.nombre = '';
                this.descripcion = '';
                this.precio = 0.0;               
                //limpiar archivo de la imagen
                this.imagen = null;

                //limpiar ruta de la imagen
                this.previewImage = null;                
                //limpiar el input de la imagen
                this.$refs.imagenProducto.value = '';

                //limpiar las validaciones
                this.errorProducto = 0;
                this.errorMostrarMsjProducto = [];
            },

            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case "producto":
                        switch (accion) {
                            case "registrar":
                                this.modal = 1;
                                this.tituloModal = "Registrar Producto";
                                this.tipoAccion = 1;

                                this.codigo = '';
                                this.nombre = '';
                                this.descripcion = '';
                                this.precio = 0.0;                                
                                this.imagen = null;
                                
                                break;

                            case "actualizar":
                                //console.log(data);                              
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Producto';
                                this.tipoAccion = 2;

                                this.idProducto = data['id'];
                                this.codigo = data['codigo'];
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                this.precio = data['precio'];                           
                                this.previewImage = data['imagen'];

                                break;
                        
                            default:
                                break;
                        }
                        break;
                
                    default:
                        break;
                }
            },
        
            uploadImage(e) {
                //pregunta si esta cargada la imagen en el input para ponerla en el src del img
                if (e.target.files && e.target.files[0]) {                    
                    const image = e.target.files[0];
                    const reader = new FileReader();                                    
                    reader.onload = e => {
                        //la imagen en base64 al img
                        this.previewImage = e.target.result;

                        //console.log(this.previewImage);
                    };
                    reader.readAsDataURL(image);                                     
                    
                    //el archivo imagen
                    this.imagen = e.target.files[0];  
                    //console.log(this.imagen);            
                }
            },

            validarProducto() {
                this.errorProducto = 0;                
                this.errorMostrarMsjProducto = [];

                if (!this.codigo) {
                    this.errorMostrarMsjProducto.push("El campo Codigo no puede estar vacio.");
                }

                if (!this.nombre) {
                    this.errorMostrarMsjProducto.push("El campo Nombre no puede estar vacio.");
                }

                if (this.precio == 0.0) {
                    this.errorMostrarMsjProducto.push("El campo Precio no puede estar vacio.");
                }

                /*
                //Antes de validar convertimos la variable a numero ya sea entero o flotante 
                this.stock = Number(this.stock);
                if (!Number.isInteger(this.stock)) {                    
                    this.errorMostrarMsjProducto.push("El campo Stock debe ser un numero entero.");
                }
                */


                //Si es para la vista Actualizar
                if (this.tipoAccion == 2) {
                    //Pregunta si se cargo alguna imagen y se procede a verificar
                    if (this.imagen != null) {                                    
                        if (!(/\.(jpg|png|gif)$/i).test(this.imagen.name)) {
                            this.errorMostrarMsjProducto.push("Solo se permiten imagenes en fomato .jpg|.png|.gif");
                        }
                    }
                }

                //Si es para la vista registrar
                if (this.tipoAccion == 1) {
                    //Validar la imagen
                    if (this.imagen == null) {                    
                        this.errorMostrarMsjProducto.push("El campo Imagen no puede estar vacio.");
                    } else {                    
                        if (!(/\.(jpg|png|gif)$/i).test(this.imagen.name)) {
                            this.errorMostrarMsjProducto.push("Solo se permiten imagenes en fomato .jpg|.png|.gif");
                        }
                    }
                }

                
                if (this.errorMostrarMsjProducto.length) {
                    this.errorProducto = 1;
                }

                return this.errorProducto;
            },                        

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarProductos(1, this.buscar, this.criterio);
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