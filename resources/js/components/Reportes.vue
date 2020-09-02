<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">                   
                    <thead>
                        <tr>
                            <th>REPORTE</th>                        
                            <th>VER</th>
                            <th>DESCARGAR</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <tr>                        
                            <td>Reporte de todos los Productos</td>
                            <td><a href="#" target="_blank" ><button @click="reporteProductos(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#" target="_blank" ><button @click="reporteProductos(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr> 
                        <tr>                        
                            <td>Reporte del Inventario</td>
                            <td><a href="#" target="_blank" ><button @click="reporteInventario(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#" target="_blank" ><button @click="reporteInventario(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr>

                        <tr>                        
                            <td>Reporte de todas las Ventas</td>
                            <td><a href="#" target="_blank" ><button @click="reporteVentas(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#" target="_blank" ><button @click="reporteVentas(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr>                                                
                        <tr>
                            <td >
                                <div class="container">
                                    <div class="form-group">
                                        Reporte de Ventas por fecha
                                    </div>                                                                                                             
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div>desde la fecha:</div>
                                            <input v-model="fecha1Ventas" type="date">                                            
                                        </div>
                                        <div class="col-md-4">
                                            <div>hasta la fecha:</div>
                                            <input v-model="fecha2Ventas" type="date">                                             
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div v-show="errorFechasVentas == 1" class="form-group row div-error">
                                                <div class="text-center text-error">
                                                    <div v-for="error in errorMostrarMsjVentas" :key="error" v-text="error">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                            
                                </div>                                                                   
                            </td>
                            <td><a href="#"><button @click="reporteVentasRangoFechas(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#"><button @click="reporteVentasRangoFechas(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr>

                        <tr>                        
                            <td>Reporte de todos los Ingresos</td>
                            <td><a href="#" target="_blank" ><button @click="reporteIngresos(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#" target="_blank" ><button @click="reporteIngresos(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr>                                                
                        <tr>
                            <td >
                                <div class="container">
                                    <div class="form-group">
                                        Reporte de Ingresos por fecha
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div>desde la fecha:</div>
                                            <input v-model="fecha1Ingresos" type="date">                                            
                                        </div>
                                        <div class="col-md-4">
                                            <div>hasta la fecha:</div>
                                            <input v-model="fecha2Ingresos" type="date">                                             
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div v-show="errorFechasIngresos == 1" class="form-group row div-error">
                                                <div class="text-center text-error">
                                                    <div v-for="error in errorMostrarMsjIngresos" :key="error" v-text="error">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>                                                                   
                            </td>
                            <td><a href="#" ><button @click="reporteIngresosRangoFechas(0)" class="btn btn-block btn-primary btn-xs">Ver</button></a></td>
                            <td><a href="#" ><button @click="reporteIngresosRangoFechas(1)" class="btn btn-block btn-success btn-xs">Descargar</button></a></td>                        
                        </tr>                                                                           
                    </tbody>
                </table>
            </div>          
        </div>    
    </main>
</template>

<script>

    export default {
        
        data() {
            return {
                url: window.location.origin + '/',     
                
                fecha1Ventas: '',
                fecha2Ventas: '',
                fecha1Ingresos: '',
                fecha2Ingresos: '',

                errorFechasVentas: 0,
                errorMostrarMsjVentas: [],
                errorFechasIngresos: 0,
                errorMostrarMsjIngresos: [],

            }
        },    

        methods: {            
            reporteProductos(tipo) {
                if (tipo == 1) {
                    window.open(this.url + 'reporteProductos?tipo=1');    
                } else {
                    window.open(this.url + 'reporteProductos?tipo=0');    
                }                
            },
            
            reporteInventario(tipo) {
                if (tipo == 1) {
                    window.open(this.url + 'reporteInventario?tipo=1');    
                } else {
                    window.open(this.url + 'reporteInventario?tipo=0');    
                }                
            },

            reporteVentas(tipo) {
                if (tipo == 1) {
                    window.open(this.url + 'reporteVentas?tipo=1&opcion=0');    
                } else {
                    window.open(this.url + 'reporteVentas?tipo=0&opcion=0');    
                }                
            },

            reporteVentasRangoFechas(tipo) {
                this.errorFechasVentas = 0;
                this.errorMostrarMsjVentas = [];

                if (!this.fecha1Ventas) {
                    this.errorMostrarMsjVentas.push("Seleccione la fecha de Inicio");
                }

                if (!this.fecha2Ventas) {
                    this.errorMostrarMsjVentas.push("Seleccione la fecha de Fin");
                }

                if (this.errorMostrarMsjVentas.length) {
                    this.errorFechasVentas = 1;
                    return;
                } 

                if (tipo == 1) {
                    window.open(this.url + 'reporteVentas?tipo=1&opcion=1&fecha1=' + this.fecha1Ventas + '&fecha2=' + this.fecha2Ventas);    
                } else {
                    window.open(this.url + 'reporteVentas?tipo=0&opcion=1&fecha1=' + this.fecha1Ventas + '&fecha2=' + this.fecha2Ventas);    
                }                
            },

            reporteIngresos(tipo) {
                if (tipo == 1) {
                    window.open(this.url + 'reporteIngresos?tipo=1&opcion=0');    
                } else {
                    window.open(this.url + 'reporteIngresos?tipo=0&opcion=0');    
                }                
            },

            reporteIngresosRangoFechas(tipo) {
                this.errorFechasIngresos = 0;
                this.errorMostrarMsjIngresos = [];

                if (!this.fecha1Ingresos) {
                    this.errorMostrarMsjIngresos.push("Seleccione la fecha de Inicio");
                }

                if (!this.fecha2Ingresos) {
                    this.errorMostrarMsjIngresos.push("Seleccione la fecha de Fin");
                }

                if (this.errorMostrarMsjIngresos.length) {
                    this.errorFechasIngresos = 1;
                    return;
                } 

                if (tipo == 1) {
                    window.open(this.url + 'reporteIngresos?tipo=1&opcion=1&fecha1=' + this.fecha1Ingresos + '&fecha2=' + this.fecha2Ingresos);    
                } else {
                    window.open(this.url + 'reporteIngresos?tipo=0&opcion=1&fecha1=' + this.fecha1Ingresos + '&fecha2=' + this.fecha2Ingresos);    
                }                
            },
        },
       
       
    }
</script>