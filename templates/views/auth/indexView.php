<link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/6.0.0/firebase-ui-auth.css" />
<div class="contenedor login">
    <?php include_once __DIR__ . '../../../includes/inc_header.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <?php include_once __DIR__ . '../../../includes/alertas.php' ?>
        <?php if ($d->noConfirmado) { ?>
            <a class="boton" href="/auth/reenviar">
                <input type="submit" class="boton boton-sm" value="Reenviar Confirmación">
            </a>
        <?php } ?>
        <form action="/auth" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" />
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" />
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="redes-sociales">
            <a href="/auth" class="btn btn-primary btn-block btn-with-icon" id="btnloginf">
                <div class="red-social">
                    <span class="icon wd-40"><i class="fa fa-facebook"></i></span>
                    <span class="pd-x-15">Login con Facebook</span>
                </div>
            </a>
            <a href="/auth" class="btn btn-danger btn-block btn-with-icon" id="btnloging">
                <div class="red-social">
                    <span class="icon wd-40"><i class="fa fa-google-plus"></i></span>
                    <span class="pd-x-15">Login con Gmail</span>
                </div>
            </a>
            <a href="/auth" class="btn btn-dark btn-block btn-with-icon" id="btnloginh">
                <div class="red-social">
                    <span class="icon wd-40"><i class="fa fa-github"></i></span>
                    <span class="pd-x-15">Login con Github</span>
                </div>
            </a>
        </div>

        <div class="acciones">
            <a href="/auth/crear">¿Aún no tienes una cuenta? obtener una</a>
            <a href="/auth/olvide">¿Olvidaste tu Password?</a>
        </div>
    </div>
    <!--Contenedor-sm -->
</div>

<script src="https://www.gstatic.com/firebasejs/ui/6.0.0/firebase-ui-auth.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="/assets//js/login.js"></script>