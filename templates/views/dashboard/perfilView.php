<?php include_once __DIR__ . '../../../includes/inc_header_dashboard.php' ?>
<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="contenedor-sm">
<?php include_once __DIR__ . '../../../includes/alertas.php' ?>

    <a href="/dashboard/cambiar-password" class="enlace">Cambiar Password</a>

    <form method="POST" class="formulario" action="/dashboard/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
                type="text"
                value="<?php echo $d->usuario->nombre; ?>"
                name="nombre"
                placeholder="Tu nombre"
            />
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="text"
                value="<?php echo $d->usuario->email; ?>"
                name="email"
                placeholder="Tu email"
            />
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>