<?php

class dashboardController extends Controller
{

    function __construct()
    {
        if (Auth::validate()) {
            Flasher::new('Ya hay una sesión abierta.');
            Redirect::to('home/flash');
        }
    }

    public static function index()
    {
        //session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = ProyectoModel::belongsTo('propietarioId', $id);
        
        // Obtener el total de tareas de cada proyecto, total de tareas completas y total de tareas pendientes
        foreach ($proyectos as $proyecto) {
            $proyecto->tareas = TareaModel::belongsTo('proyectoId', $proyecto->id);
            $proyecto->totalTareas = count($proyecto->tareas);
            $proyecto->totalTareasCompletadas = 0;
            $proyecto->totalTareasPendientes = 0;
            foreach ($proyecto->tareas as $tarea) {
                if ($tarea->estado == 1) {
                    $proyecto->totalTareasCompletadas++;
                } else {
                    $proyecto->totalTareasPendientes++;
                }
            }
            // Obtener el procentaje de tareas completadas y si tareas completadas es igual a 0, mostrar 0%
            if ($proyecto->totalTareasCompletadas == 0) {
                $proyecto->porcentaje = 0;
            } else {
                $proyecto->porcentaje = round(($proyecto->totalTareasCompletadas / $proyecto->totalTareas) * 100);
            }

        }


       

        $data = [
            'proyectos' => $proyectos,
            'title' => 'Dashboard',
            'padding' => '0px',
            'bg' => 'dark'
        ];
        View::render('index', $data);
    }
    public static function crear()
    {

        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new ProyectoModel($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar una url única
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                // Obtener fecha de creación
                $proyecto->fecha = date('Y-m-d');
                // Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                // Guardar el proyecto
                $proyecto->guardar();
                // Redireccionar
                header('Location: /dashboard/proyecto?id=' . $proyecto->url);
            }
        }

        $data = [
            'alertas' => $alertas,
            'title' => 'Crear Proyecto',
            'padding' => '0px',
            'bg' => 'dark'
        ];
        View::render('crear', $data);
    }

    public static function proyecto()
    {
        //session_start();
        isAuth();

        $token = $_GET['id'];
        if (!$token) {
            header('Location: /dashboard');
        }
        // Revisar que la persona que visita el proyecto, es quien lo Creo
        $proyecto = ProyectoModel::where('url', $token);
        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $data = [
            'proyecto' => $proyecto,
            'title' => $proyecto->proyecto,
            'padding' => '0px',
            'bg' => 'dark'
        ];
        View::render('proyecto', $data);
    }

    public static function perfil()
    {
        //session_start();
        isAuth();
        $alertas = [];

        $usuario = UsuarioModel::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {
                // Verificar el correo del usuario
                $existeUsuario = UsuarioModel::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de error
                    UsuarioModel::setAlerta('error', 'Email no valido o ya se encuentra registrado');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el usuario
                    $usuario->guardar();
                    // Mostrar mensaje de exito
                    UsuarioModel::setAlerta('exito', 'Guardado correctamente');
                    $alertas = $usuario->getAlertas();

                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $data = [
            'alertas' => $alertas,
            'usuario' => $usuario,
            'title' => 'Perfil',
            'padding' => '0px',
            'bg' => 'dark'
        ];
        View::render('perfil', $data);
    }

    public static function cambiar_password()
    {
        //session_start();
        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = UsuarioModel::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();

            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if ($resultado) {
                    $usuario->password = $usuario->password_nuevo;
                    // Eliminar propiedades No necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    // Hashear nuevo password
                    $usuario->hashPassword();
                    // Asignar el nuevo password
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        UsuarioModel::setAlerta('exito', 'Password guardado correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    UsuarioModel::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $data = [
            'alertas' => $alertas,
            'title' => 'Cambiar Password',
            'padding' => '0px',
            'bg' => 'dark'
        ];
        View::render('cambiar_password', $data);
    }
}
