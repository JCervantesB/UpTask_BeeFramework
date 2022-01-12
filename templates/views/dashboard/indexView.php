<?php include_once __DIR__ . '../../../includes/inc_header_dashboard.php' ?>
<?php include_once __DIR__ . '/header-dashboard.php' ?>
<?php use Model\Tarea; ?>
    <?php setlocale(LC_TIME,"es_MX"); ?>
    <?php if(count($d->proyectos) === 0) { ?>
        <p class="no-proyectos">No hay proyectos aún <a href="/dashboard/crear">Comienza creando uno</a></p>
    <?php } else { ?>
        <ul class="listado-proyectos">
            <?php foreach($d->proyectos as $proyecto) { ?>                 
                <div class="caja">
                    <a href="/dashboard/proyecto?id=<?php echo $proyecto->url; ?>">                    
                        <div class="proyecto">
                            <?php echo $proyecto->proyecto; ?>
                        </div>                        
                        <!-- Crear una barra de progreso -->
                        <div class="barra-progreso">
                            <small>Tareas pendientes: <?php echo $proyecto->totalTareasPendientes; ?> de <?php echo $proyecto->totalTareas; ?> </small>
                            <div class="porcentaje" style="width: <?php echo $proyecto->porcentaje; ?>%">
                                <small>Progreso: <?php echo $proyecto->porcentaje; ?>%</small>
                            </div>
                        </div>
                    </a>                    

                </div>               
            <?php } ?>
        </ul>
    <?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php include_once __DIR__ . '../../../includes/inc_footer.php' ?>