<?php

class authController extends Controller
{
  function __construct()
  {
    if (Auth::validate()) {
      Flasher::new('Ya hay una sesión abierta.');
      Redirect::to('home/flash');
    }
  }

  /**
   * Función para mostrar la pagina inicial y el login del usuario
   */
  function index()
  {
    $noConfirmado = false;

    $alertas = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new UsuarioModel($_POST);

      $alertas = $usuario->validarLogin();

      if (empty($alertas)) {
        // Verificar que el usuario exista
        $usuario = UsuarioModel::where('email', $usuario->email);

        if (!$usuario) {
          UsuarioModel::setAlerta('error', 'El usuario no existe');
        } elseif ($usuario && empty($usuario->confirmado)) {
          UsuarioModel::setAlerta('error', 'El usuario aún no está confirmado');
          $noConfirmado = true;
        } else {
          if (password_verify($_POST['password'], $usuario->password)) {
            session_start();
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;

            // Redireccionar al proyecto
            header('Location: /dashboard');
          } else {
            UsuarioModel::setAlerta('error', 'Password incorrecto');
          }
        }
      }
    }    

    $alertas = UsuarioModel::getAlertas();

    $data =
      [
        'alertas'       => $alertas,
        'noConfirmado'  => $noConfirmado,
        'title'         => 'Iniciar Sesión',
        'padding'       => '0px',
        'bg'            => 'dark'
      ];

    View::render('index', $data);
  }

  function login_social() {
    // Validar que el correo exista
    $usuario = UsuarioModel::where('email', $_POST['email']);
    if(!$usuario) {
      
      $respuesta = [
        'tipo' => 'exito',
        'mensaje' => 'El usuario no existe, registrando usuario nuevo'
      ];

      $usuario = new UsuarioModel($_POST);
      $usuario->guardar();

      $_SESSION['id'] = $usuario->id;
      $_SESSION['nombre'] = $usuario->nombre;
      $_SESSION['email'] = $usuario->email;
      $_SESSION['login'] = true;

      echo json_encode($respuesta);
      return;

    } else {
      
      $_SESSION['id'] = $usuario->id;
      $_SESSION['nombre'] = $usuario->nombre;
      $_SESSION['email'] = $usuario->email;
      $_SESSION['login'] = true;

      $respuesta = [
        'tipo' => 'exito',
        'mensaje' => 'El usuario existe'
      ];
      echo json_encode($respuesta);
      return;
    }
    
  }

  /**
   * Función cerrar sesión del usuario
   */
  function logout()
  {
    session_start();

    session_destroy();

    header('Location: /');
  }

  /**
   * Función para crear una cuenta
   */
  function crear()
  {
    $usuario = new UsuarioModel();
    $alertas = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario->sincronizar($_POST);
      $alertas = $usuario->validarNuevaCuenta();

      if (empty($alertas)) {
        $existeUsuario = UsuarioModel::where('email', $usuario->email);
        if ($existeUsuario) {
          UsuarioModel::setAlerta('error', 'El Usuario ya esta registrado');
          $alertas = UsuarioModel::getAlertas();
        } else {
          //Hashear Password
          $usuario->hashPassword();

          //Eliminar Password2
          unset($usuario->password2);
          // Generar Token
          $usuario->crearToken();
          // Enviar el email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          //debuguear($email);
          $email->enviarConfirmacion();
          // Crear el usuario
          $resultado = $usuario->guardar();

          if ($resultado) {
            header('Location: /auth/mensaje');
          }
        }
      }
    }

    

    $data =
      [
        'usuario' => $usuario,
        'alertas' => $alertas,
        'title'   => 'Crear cuenta',
        'padding' => '0px',
        'bg'      => 'dark'
      ];

    View::render('crear', $data);
  }

  /**
   * Funcion para enviar un mensaje de confirmacion
   */
  function mensaje()
  {
    $data =
      [
        'title'   => 'Exito',
        'padding' => '0px',
        'bg'      => 'dark'
      ];
    View::render('mensaje', $data);
  }

  /**
   * Funcion para solicitar una nueva contraseña
   */
  function olvide()
  {
    $alertas = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new UsuarioModel($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        $usuario = UsuarioModel::where('email', $usuario->email);

        if ($usuario && $usuario->confirmado === "1") {
          // Generar un nuevo token
          $usuario->crearToken();
          unset($usuario->password2);

          // Actualizar el usuario
          $usuario->guardar();
          // Enviar un email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarInstrucciones();
          // Imprimir una alaerta
          UsuarioModel::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
          //debuguear($usuario);
        } else {
          // No encontro el usuario
          UsuarioModel::setAlerta('error', 'El usuario no existe o no esta confirmado');
        }
      }
    }
    $alertas = UsuarioModel::getAlertas();

    $data =
      [
        'alertas' => $alertas,
        'title'   => 'Recuperar cuenta',
        'padding' => '0px',
        'bg'      => 'dark'
      ];

    View::render('olvide', $data);
  }

  /**
   * Funcion para recuperar la cuenta
   * Comprueba que el token sea correcto
   */
  function reestablecer()
  {
    $alertas = [];
    $token = s($_GET['token']);
    $mostrar = true; // Ocultara el formulario si no encuentra el password

    if (!$token) header('Location /');

    // Identificar el usuario con este token
    $usuario = UsuarioModel::where('token', $token);

    if (empty($usuario)) {
      UsuarioModel::setAlerta('error', 'Token no válido');
      $mostrar = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Añadir el nuevo password
      $usuario->sincronizar($_POST);

      // Validar password
      $alertas = $usuario->validarPassword();

      if (empty($alertas)) {
        // Hashear password
        $usuario->hashPassword();
        //Eliminar token
        $usuario->token = null;
        // Eliminar password 2 del modelo
        unset($usuario->password2);

        // Guardar el password del usuario
        $resultado = $usuario->guardar();

        if ($resultado) {
          header('Location: /');
        }
      }
    }

    $alertas = UsuarioModel::getAlertas();

    $data =
      [
        'mostrar' => $mostrar,
        'alertas' => $alertas,
        'title'   => 'Reestablecer Password',
        'padding' => '0px',
        'bg'      => 'dark'
      ];

    View::render('reestablecer', $data);
  }

  /**
   * Funcion para reenviar instrucciones de confirmacion
   */
  function reenviar()
  {
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new UsuarioModel($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        $usuario = UsuarioModel::where('email', $usuario->email);

        if ($usuario && $usuario->confirmado === "0") {
          // Generar un nuevo token
          $usuario->crearToken();
          unset($usuario->password2);

          // Actualizar el usuario
          $usuario->guardar();
          // Enviar un email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarConfirmacion();
          // Imprimir una alaerta
          UsuarioModel::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
          //debuguear($usuario);
        } else {
          // No encontro el usuario
          UsuarioModel::setAlerta('error', 'El usuario no existe.');
        }
      }
    }
    $alertas = UsuarioModel::getAlertas();

    $data =
      [
        'alertas' => $alertas,
        'title'   => 'Reenviar instrucciones',
        'padding' => '0px',
        'bg'      => 'dark'
      ];
    
    View::render('reenviar', $data);
  }

  /**
   * Funcion para confirmar la cuenta
   * Verifica y activa la cuenta del usuario usando el token
   */
  function confirmar()
  {
    $token = s($_GET['token']);
    $alertas = [];
    if (!$token) header('Location: /');

    //Encontrar al usuario con este token
    $usuario = UsuarioModel::where('token', $token);

    if (empty($usuario)) {
      //No se encontro el usuario con ese token
      UsuarioModel::setAlerta('error', 'Token no válido');
    } else {
      // Confirmar el usuario
      $usuario->confirmado = 1;
      $usuario->token = null;
      unset($usuario->password2);

      $usuario->guardar();
      UsuarioModel::setAlerta('exito', 'Cuenta validada correctamente');
    }
    $alertas = UsuarioModel::getAlertas();

    $data =
      [
        'alertas' => $alertas,
        'title'   => 'Confirmación de cuenta',
        'padding' => '0px',
        'bg'      => 'dark'
      ];

    View::render('confirmar', $data);
  }
}