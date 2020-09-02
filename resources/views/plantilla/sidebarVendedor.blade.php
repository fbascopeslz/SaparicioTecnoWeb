<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-dolly-flatbed"></i>
                <p>
                    Inventario
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">                
                <li @click="menu=3" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-boxes nav-icon"></i>
                        <p>Inventario</p>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-cash-register"></i>
                <p>
                    Ventas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li @click="menu=6" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-money-bill-wave nav-icon"></i>
                        <p>Ventas</p>
                    </a>
                </li>
                <li @click="menu=7" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-male nav-icon"></i>
                        <p>Clientes</p>
                    </a>
                </li>               
            </ul>
        </li>
    
        <li @click="menu=10" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-file-download"></i>
                <p>Reportes</p>
            </a>
        </li>
        
        <li @click="menu=11" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>Estadisticas</p>
            </a>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->