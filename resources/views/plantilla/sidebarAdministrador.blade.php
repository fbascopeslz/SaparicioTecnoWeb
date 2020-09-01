<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

        <li @click="menu=0" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Inicio</p>
            </a>
        </li>

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-dolly-flatbed"></i>
                <p>
                    Inventario
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li @click="menu=1" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-wine-bottle nav-icon"></i>
                        <p>Productos</p>
                    </a>
                </li>
                <li @click="menu=2" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-warehouse nav-icon"></i>
                        <p>Almacenes</p>
                    </a>
                </li>
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
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                    Compras
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li @click="menu=4" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-truck-loading nav-icon"></i>
                        <p>Ingresos</p>
                    </a>
                </li>
                <li @click="menu=5" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-truck nav-icon"></i>
                        <p>Proveedores</p>
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
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tasks"></i>
                <p>
                    Administracion
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li @click="menu=8" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <li @click="menu=9" class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-tag nav-icon"></i>
                        <p>Roles</p>
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