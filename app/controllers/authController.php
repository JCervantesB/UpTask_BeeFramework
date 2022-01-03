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
      $usuario = new usuarioModel($_POST);

      $alertas = $usuario->validarLogin();

      if (empty($alertas)) {
        // Verificar que el usuario exista
        $usuario = usuarioModel::where('email', $usuario->email);

        if (!$usuario) {
          usuarioModel::setAlerta('error', 'El usuario no existe');
        } elseif ($usuario && empty($usuario->confirmado)) {
          usuarioModel::setAlerta('error', 'El usuario aún no está confirmado');
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
            usuarioModel::setAlerta('error', 'Password incorrecto');
          }
        }
      }
    }
    $alertas = usuarioModel::getAlertas();

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
    $usuario = new usuarioModel();
    $alertas = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario->sincronizar($_POST);
      $alertas = $usuario->validarNuevaCuenta();

      if (empty($alertas)) {
        $existeUsuario = usuarioModel::where('email', $usuario->email);
        if ($existeUsuario) {
          usuarioModel::setAlerta('error', 'El Usuario ya esta registrado');
          $alertas = usuarioModel::getAlertas();
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
      $usuario = new usuarioModel($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        $usuario = usuarioModel::where('email', $usuario->email);

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
          usuarioModel::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
          //debuguear($usuario);
        } else {
          // No encontro el usuario
          usuarioModel::setAlerta('error', 'El usuario no existe o no esta confirmado');
        }
      }
    }
    $alertas = usuarioModel::getAlertas();

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
    $usuario = usuarioModel::where('token', $token);

    if (empty($usuario)) {
      usuarioModel::setAlerta('error', 'Token no válido');
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

    $alertas = usuarioModel::getAlertas();

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
      $usuario = new usuarioModel($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        $usuario = usuarioModel::where('email', $usuario->email);

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
          usuarioModel::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
          //debuguear($usuario);
        } else {
          // No encontro el usuario
          usuarioModel::setAlerta('error', 'El usuario no existe.');
        }
      }
    }
    $alertas = usuarioModel::getAlertas();

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
    $usuario = usuarioModel::where('token', $token);

    if (empty($usuario)) {
      //No se encontro el usuario con ese token
      usuarioModel::setAlerta('error', 'Token no válido');
    } else {
      // Confirmar el usuario
      $usuario->confirmado = 1;
      $usuario->token = null;
      unset($usuario->password2);

      $usuario->guardar();
      usuarioModel::setAlerta('exito', 'Cuenta validada correctamente');
    }
    $alertas = usuarioModel::getAlertas();

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
