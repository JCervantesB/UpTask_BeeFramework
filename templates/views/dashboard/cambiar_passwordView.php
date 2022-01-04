<?php include_once __DIR__ . '../../../includes/inc_header_dashboard.php' ?>
<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="contenedor-sm">
<?php include_once __DIR__ . '../../../includes/alertas.php' ?>
    <a href="/dashboard/perfil" class="enlace">Volver al perfil</a>


    <form method="POST" class="formulario" action="/dashboard/cambiar-password">
        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input 
                type="password"
                name="password_actual"
                placeholder="Tu password actual"
            />
        </div>
        <div class="campo">
            <label for="nombre">Password Nuevo</label>
            <input 
                type="password"
                name="password_nuevo"
                placeholder="Tu password nuevo"
            />
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>