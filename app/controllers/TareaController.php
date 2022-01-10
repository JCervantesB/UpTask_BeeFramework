<?php

class tareaController extends Controller{

    function __construct()
    {
        if (Auth::validate()) {
            Flasher::new('Ya hay una sesión abierta.');
            Redirect::to('home/flash');
        }
    }

    public static function index() {
        $proyectoId = $_GET['id'];
        if(!$proyectoId) header('Location: /dashboard');

        $proyecto = ProyectoModel::where('url', $proyectoId);

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            Flasher::new('No existe el proyecto.');
            Redirect::to('error');
        }

        $tareas = TareaModel::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);
    }
   

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyectoId = $_POST['proyectoId'];
            $proyecto = ProyectoModel::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al crear la tarea'
                ];

                echo json_encode($respuesta);
                return;
            } 
            
            // Todo bien: Instanciar y crear nueva tarea
            $tarea = new TareaModel($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente'
            ];
            
            echo json_encode($respuesta);
        }        
    }

    public static function actualizar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar que el proyecto exista
            $proyecto = ProyectoModel::where('url', $_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];

                echo json_encode($respuesta);
                return;
            } 

            $tarea = new TareaModel($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
           // Validar que el poryecto Exista
           $proyecto = ProyectoModel::where('url', $_POST['proyectoId']);

           if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
               $repuesta = [
                   'tipo' => 'error',
                   'mensaje' => 'Hubo un Error al actualizar la tarea'
               ];
               echo json_encode($repuesta);    
               return;            
           } 

           $tarea = new TareaModel($_POST);
           $resultado = $tarea->eliminar();

           $resultado = [
               'resultado' => $resultado,
               'mensaje' => 'Eliminado correctamente'
           ];

           echo json_encode($resultado);
       
        }
    }
    
}