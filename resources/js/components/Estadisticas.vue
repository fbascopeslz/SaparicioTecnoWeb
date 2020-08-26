<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>

    <!-- Top Productos Mas Vendidos -->
    <div class="container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Productos Mas Vendidos</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="productosMasVendidos">                                                
                            </canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Producto vs Cantidad</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="form-group">
                        <h5>RANGO DE FECHAS</h5>
                    </div>                    
                    <div class="form-group">
                        <div>desde la fecha:</div>
                        <input v-model="fecha1" type="date">                                            
                        <div>hasta la fecha:</div>           
                        <input v-model="fecha2" type="date">
                    </div>                    
                    <div class="form-group">
                        <button @click="cambiarTipo1ProductosMasVendidos()" class="btn btn-primary">Ingresar Fechas</button>
                    </div>
                </div>                        
                <div class="form-group">
                    <div class="form-group">
                        <h5>FECHA ESPECIFICA</h5>
                    </div>                        
                    <div class="form-group">                                
                        <input v-model="fecha" type="date">                                
                    </div>                        
                    <div class="form-group">
                        <button @click="cambiarTipo2ProductosMasVendidos()" class="btn btn-primary">Ingresar Fecha</button>
                    </div>
                </div>
                <div class="form-group">
                    <div v-show="errorProductosMasVendidos == 1" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjProductosMasVendidos" :key="error" v-text="error">

                            </div>
                        </div>
                    </div>
                </div>

            </div>            
        </div>
    </div>

    <!-- Clientes mas fieles -->
    <div class="container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Cientes Mas Fieles</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="clientesMasFieles">                                                
                            </canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Cliente vs Numero de Ventas</p>
                    </div>
                </div>
            </div>                                    
        </div>
    </div>

    <!-- Proveedores mas solicitados -->
    <div class="container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Proveedores Mas Solicitados</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="proveedoresMasSolicitados">                                                
                            </canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Proveedor vs Numero de Compras</p>
                    </div>
                </div>
            </div>                               
        </div>
    </div>

    <!-- Financieras -->
    <div class="container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Estadisticas Financieras de Ingresos y Gastos</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="financierasLineChart">                                                
                            </canvas>
                        </div>

                        <div class="ct-chart">
                            <canvas id="financierasPieChart">                                                
                            </canvas>
                        </div>

                    </div>
                    <div class="card-footer">
                        <p>Mes Vs Ingreso y Gasto</p>
                        <p>Utilidad = Ingreso - Gasto</p>
                    </div>
                </div>                
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="form-group">
                        <h5>Año en especifico</h5>
                    </div>                    
                    <div class="form-group">
                        <div>Seleccione un año:</div>
                        <select class="form-control" v-model="anio">                                    
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                        </select>                                                              
                    </div>                    
                    <div class="form-group">
                        <button @click="estadisticasFinancieras()" class="btn btn-primary">Ingresar Año</button>
                    </div>
                </div>                                                                                                                                            
            </div>            
        </div>
    </div>

    

</main>
</template>
<script>

    import Chart from 'chart.js';

    export default {
        data (){
            return {                        
                //estadisticaProductosMasVendidos
                arrayRespuesta1: [],
                arrayProductos: [],
                arrayCantidades: [],
                tipo: 0,
                fecha: '',
                fecha1: '',
                fecha2: '',

                ctx: null,
                myDoughnutChart: null,

                errorProductosMasVendidos: 0,
                errorMostrarMsjProductosMasVendidos: [],

                //estadisticaClientesMasFieles
                arrayRespuesta2: [],
                arrayClientes: [],
                arrayNumeroVentas: [],

                //estadisticaProveedoresMasSolicitados
                arrayRespuesta3: [],
                arrayProveedores: [],
                arrayNumeroCompras: [],

                //estadisticasFinancieras
                anio: '2020',
                arrayRespuesta4: [],
                arrayRespuesta5: [],              
                arrayMeses: [],
                arrayIngresos: [],
                arrayGastos: [],
                utilidad: 0,
                ingreso: 0,
                gasto: 0,

                lineChart: null,
                refCanvasLineChart: null,

                refCanvasPieChart: null,

            }
        },
        methods : {
            getNColores(n) {
                var colores = [                                                            
                    'rgba(255, 0, 0, 0.8)', //rojo
                    'rgba(255, 255, 0, 0.8)', //amarillo
                    'rgba(0, 0, 255, 0.8)', //azul
                    'rgba(255, 0, 255, 0.8)', //fucsia
                    'rgba(0, 128, 0, 0.8)', //verde
                    'rgba(0, 255, 255, 0.8)', //aqua                                
                    'rgba(128, 0, 128, 0.8)', //morado
                    'rgba(128, 128, 128, 0.8)', //gray
                    'rgba(128, 0, 128, 0.8)', //marron
                    'rgba(0, 255, 0, 0.8)', //lima
                ];
                var respuesta = [];
                for (let i = 0; i < n; i++) {
                    respuesta.push(colores[i]);                    
                }
                return respuesta;
            },


            cambiarTipo1ProductosMasVendidos(){                
                this.errorProductosMasVendidos = 0;
                this.errorMostrarMsjProductosMasVendidos = [];
                
                if (!this.fecha1) {                    
                    this.errorMostrarMsjProductosMasVendidos.push('Seleccione la fecha de Inicio');                    
                }

                if (!this.fecha2) {                    
                    this.errorMostrarMsjProductosMasVendidos.push('Seleccione la fecha de Fin');                    
                }

                if (this.errorMostrarMsjProductosMasVendidos.length) {
                    this.errorProductosMasVendidos = 1;
                    return;
                }                                            
                
                this.tipo = 2;
                this.estadisticaProductosMasVendidos();
            },

            cambiarTipo2ProductosMasVendidos(){                
                this.errorProductosMasVendidos = 0;
                this.errorMostrarMsjProductosMasVendidos = [];
                
                if (!this.fecha) {                    
                    this.errorMostrarMsjProductosMasVendidos.push('Seleccione una fecha');                    
                }                

                if (this.errorMostrarMsjProductosMasVendidos.length) {
                    this.errorProductosMasVendidos = 1;
                    return
                }                                            
                
                this.tipo = 1;
                this.estadisticaProductosMasVendidos();
            },

            estadisticaProductosMasVendidos(){
                let me = this;

                var url = '/estadisticaProductosMasVendidos';
                switch (this.tipo) {
                    case 0:
                        url = url + '?tipo=0';
                        break;
                    case 1:
                        url = url + '?tipo=1&fecha=' + this.fecha;
                        break;
                    case 2:
                        url = url + '?tipo=2&fecha1=' + this.fecha1 + '&fecha2=' + this.fecha2;
                        break;                
                    default:
                        break;
                }                
                
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayRespuesta1 = respuesta.estadistica;

                    me.arrayProductos = [];
                    me.arrayCantidades = [];
                    
                    me.arrayRespuesta1.map(function(x){
                        me.arrayProductos.push(x.producto);
                        me.arrayCantidades.push(x.cantidad);
                    });

                    me.cargarGraficaProductosMasVendidos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cargarGraficaProductosMasVendidos() {                
                
                this.myDoughnutChart = new Chart(this.ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: this.arrayCantidades,
                            backgroundColor: this.getNColores(this.arrayCantidades.length),
                        }],                        
                        labels: this.arrayProductos
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Productos Mas Vendidos'
                        },
                        animation: {
                            animationScale: true,
                            animationRotate: true
                        },
                        
                    }                   
                });
            },


            estadisticaClientesMasFieles(){
                let me = this;

                var url = '/estadisticaClientesMasFieles';                              
                
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayRespuesta2 = respuesta.estadistica;
                    
                    me.arrayRespuesta2.map(function(x){
                        me.arrayClientes.push(x.cliente);
                        me.arrayNumeroVentas.push(x.numeroventas);
                    });

                    me.cargarGraficaClientesMasFieles();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cargarGraficaClientesMasFieles() {                                
                var ctx = document.getElementById('clientesMasFieles').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.arrayClientes,
                        datasets: [{
                            label: 'Numero de Ventas',
                            data: this.arrayNumeroVentas,
                            backgroundColor: this.getNColores(this.arrayClientes.length),
                            borderColor: this.getNColores(this.arrayClientes.length),
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },


            estadisticaProveedoresMasSolicitados(){
                let me = this;

                var url = '/estadisticaProveedoresMasSolicitados';                              
                
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayRespuesta3 = respuesta.estadistica;
                    
                    me.arrayRespuesta3.map(function(x){
                        me.arrayProveedores.push(x.proveedor);
                        me.arrayNumeroCompras.push(x.numerocompras);
                    });

                    me.cargarGraficaProveedoresMasSolicitados();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cargarGraficaProveedoresMasSolicitados() {                                
                var ctx = document.getElementById('proveedoresMasSolicitados').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.arrayProveedores,
                        datasets: [{
                            label: 'Numero de Compras',
                            data: this.arrayNumeroCompras,
                            backgroundColor: this.getNColores(this.arrayNumeroCompras.length),
                            borderColor: this.getNColores(this.arrayNumeroCompras.length),
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },



            mesToNombreMes(mes) {
                switch(mes) {
                    case '01':
                        return "Enero";
                    case '02':
                        return "Febrero";
                    case '03':
                        return "Marzo";
                    case '04':
                        return "Abril";
                    case '05':
                        return "Mayo";
                    case '06':
                        return "Junio";
                    case '07':
                        return "Julio";
                    case '08':
                        return "Agosto";
                    case '09':
                        return "Septiembre";
                    case '10':
                        return "Octubre";
                    case '11':
                        return "Noviembre";
                    case '12':
                        return "Deciembre";
                    default:
                        return "Mes";                
                }
            },

            estadisticasFinancieras() {
                
                //limpiar variables
                this.arrayRespuesta4 = [];
                this.arrayRespuesta5 = [];        
                this.arrayMeses = [];
                this.arrayIngresos = [];
                this.arrayGastos = [];
                this.utilidad = 0;
                this.ingreso = 0;
                this.gasto = 0;
            

                let me = this;

                var url = '/estadisticasFinancieras?anio=' + me.anio;                              
                
                axios.get(url).then(function (response) {
                    console.log(response);

                    var respuesta = response.data;
                    me.arrayRespuesta4 = respuesta.estadistica1;
                    me.arrayRespuesta5 = respuesta.estadistica2;
                    
                    me.arrayRespuesta4.map(function(x){
                        me.arrayMeses.push(me.mesToNombreMes(x.mes));
                        me.arrayIngresos.push(x.ingreso);
                        me.arrayGastos.push(x.gasto);
                    });

                    me.utilidad = me.arrayRespuesta5[0].utilidad;
                    me.ingreso = me.arrayRespuesta5[0].ingreso;
                    me.gasto = me.arrayRespuesta5[0].gasto;

                    me.cargarGraficasFinancieras();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

/*
            actualizarGraficasFinancieras() {                                                                                          
                
                this.lineChart.data.datasets[0] = new Chart(this.refCanvasLineChart, {
                        type: 'line',
                        data: {
                            datasets: [
                                {
                                    label: 'Ingresos',
                                    backgroundColor: 'rgba(0, 128, 0, 0.8)', //verde
                                    borderColor: 'rgba(0, 128, 0, 1.0)', //verde
                                    data: this.arrayIngresos,
                                    fill: false,
                                },
                                {
                                    label: 'Gastos',
                                    backgroundColor: 'rgba(255, 0, 0, 0.8)', //rojo
                                    borderColor: 'rgba(255, 0, 0, 1.0)', //rojo
                                    data: this.arrayGastos,
                                    fill: false,
                                }
                            ],

                            // These labels appear in the legend and in the tooltips when hovering different arcs
                            labels: this.arrayMeses,
                        },
                       
            },     
            */   
            
            cargarGraficasFinancieras() {                                                                                                          
                this.lineChart = new Chart(this.refCanvasLineChart, {
                    type: 'line',
                    data: {
                        datasets: [
                            {
                                label: 'Ingresos',
                                backgroundColor: 'rgba(0, 128, 0, 0.8)', //verde
                                borderColor: 'rgba(0, 128, 0, 1.0)', //verde
                                data: this.arrayIngresos,
                                fill: false,
                            },
                            {
                                label: 'Gastos',
                                backgroundColor: 'rgba(255, 0, 0, 0.8)', //rojo
                                borderColor: 'rgba(255, 0, 0, 1.0)', //rojo
                                data: this.arrayGastos,
                                fill: false,
                            }
                        ],

                        // These labels appear in the legend and in the tooltips when hovering different arcs
                        labels: this.arrayMeses,
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Ingresos y Gastos Por Mes'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Meses'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Cantidad'
                                }
                            }]
                        }
                    }                    
                });                

                
                var pieChart = new Chart(this.refCanvasPieChart, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: [this.ingreso, this.gasto, this.utilidad],
                            backgroundColor: [
                                'rgba(0, 128, 0, 0.8)', //verde
                                'rgba(255, 0, 0, 0.8)', //rojo
                                'rgba(0, 0, 255, 0.8)', //azul                                                                                            
                            ],
                            label: 'Dataset 1'
                        }],
                        labels: [
                            'Ingresos',
                            'Gastos',
                            'Utilidad',                       
                        ]
                    },
                    options: {
                        responsive: true
                    }                    
                });                

            },

        },
        mounted() {
            this.ctx = document.getElementById('productosMasVendidos').getContext('2d');

            this.refCanvasLineChart = document.getElementById('financierasLineChart').getContext('2d');
            this.refCanvasPieChart = document.getElementById('financierasPieChart').getContext('2d');
            
            this.estadisticaProductosMasVendidos();
            this.estadisticaClientesMasFieles();
            this.estadisticaProveedoresMasSolicitados();
            this.estadisticasFinancieras();
        }
    }
</script>
