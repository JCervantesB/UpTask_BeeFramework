<div class="contenedor reestablecer">
<?php include_once __DIR__ . '../../../includes/inc_header.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php include_once __DIR__ . '../../../includes/alertas.php' ?>
        <?php if($d->mostrar) { ?>

        <form class="formulario" method="POST">
            
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Nuevo Password"
                    name="password"
                />
            </div>

            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <?php } ?>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/auth/crear">¿Aún no tienes una cuenta? obtener una</a>            
        </div>
    </div><!--Contenedor-sm -->
</div>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>