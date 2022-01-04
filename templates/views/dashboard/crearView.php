<?php include_once __DIR__ . '../../../includes/inc_header_dashboard.php' ?>
<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '../../../includes/alertas.php' ?>

    <form class="formulario" method="POST" action="/dashboard/crear">
        <?php include_once __DIR__ . '/formulario-proyecto.php' ?>
        <input class="boton" type="submit" value="Crear Proyecto">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>