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
            
        }
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
           
        }
    }
    
}