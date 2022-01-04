<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h1>UpTask</h1>
        <div class="cerrar-menu">
            <img src="assets/images/cerrar.svg" alt="imagen cerrar menu" id="cerrar-menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear Proyecto') ? 'activo' : ''; ?>" href="dashboard/crear">Crear proyectos</a>
        <a class="<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="dashboard/perfil">Perfil</a>
    </nav>    

    <div class="cerrar-sesion-mobile">
        <a href="/auth/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>
</aside>