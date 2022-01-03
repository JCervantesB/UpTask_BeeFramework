<div class="contenedor login">
<?php include_once __DIR__ . '../../../includes/inc_header.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <?php include_once __DIR__ . '../../../includes/alertas.php' ?>
        <?php if($d->noConfirmado) { ?>
            <a class="boton" href="/auth/reenviar">
            <input type="submit" class="boton boton-sm" value="Reenviar Confirmación">
        </a>
        <?php } ?>
        <form action="/auth" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                />
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                />
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/auth/crear">¿Aún no tienes una cuenta? obtener una</a>
            <a href="/auth/olvide">¿Olvidaste tu Password?</a>
        </div>
    </div><!--Contenedor-sm -->
</div>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>