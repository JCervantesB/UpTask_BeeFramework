<?php 

class apiController extends Controller {
    public static function index() {
        $proyectoId = $_GET['id'];        

        if(!$proyectoId) header('Location: /dashboard');  

        $proyecto = ProyectoModel::where('url', $proyectoId);

        session_start();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        $tareas = TareaModel::belongsTo('proyectoId', $proyecto->id);

        // Mostrar tareas
        echo json_encode(['tareas' => $tareas], JSON_UNESCAPED_UNICODE);
    }
}