<div class="contenedor olvide">
<?php include_once __DIR__ . '../../../includes/inc_header.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Enviar instrucciones para activar tu cuenta UpTask</p>
        <?php include_once __DIR__ . '../../../includes/alertas.php' ?>

        <form action="/auth/reenviar" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                />
            </div>

            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/auth/crear">¿Aún no tienes una cuenta? obtener una</a>            
        </div>
    </div><!--Contenedor-sm -->
</div>

<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>