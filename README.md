# UpTask_MVC

UpTask del curso Desarrollo Web Completo de Udemy

![UpTask](https://github.com/JCervantesB/UpTask_MVC/blob/master/src/img/uptask.jpg?raw=true)

## Proyecto migrado a Bee Framework

> Este proyecto utiliza Bee Framework v1.1.3
> https://github.com/JCervantesB/Bee-Framework

## ¿Como utilizar este proyecto?

- Realiza un clon del repositorio.

`git clone https://github.com/JCervantesB/UpTask_BeeFramework.git`

- Entra a la carpeta del proyecto.

`cd ./UpTask_BeeFramework`

- Instalar node

`npm i`

- Instalar Composer (En este proyecto, composer se encuentra en el directorio "app")
~~~
cd ./UpTask_BeeFramework/app
composer install
~~~

- Editar el archivo: /app/config/bee_config.php

`define('IS_LOCAL'     , in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));`

- Para entornos en producción, es necesario editar la linea $_SERVER['REMOTE_ADDR'] donde "REMOTE_ADDR" es la url completa de tu sitio en producción P.Ej: https://tudominio.com/

`define('BASEPATH'     , IS_LOCAL ? '/' : '____EL BASEPATH EN PRODUCCIÓN___'); // Debe ser cambiada a la ruta de tu proyecto en producción y desarrollo`


** Este es el punto mas importante **

- Modificar la ruta IS_LOCAL ? '/' <- Si el path (ruta real) de tu directorio del proyecto se encuentra en una carpeta diferente a la de tu servidor apache.
P.Ej: Si tu rota es `www/uptask/`, entonces necesitas reemplazar  `'/'` por `'uptask/'`
Ya que Bee buscara siempre como ruta base, esta ubicación.

- En caso de despligue en producción.
Si tu sitio es `https://tudominio.com/uptask`
Es necesario modificar `'____EL BASEPATH EN PRODUCCIÓN___'` por `'uptask/'`
Si tu proyecto se encuentra en la raiz del hosting reemplazar `'____EL BASEPATH EN PRODUCCIÓN___'` por `'/'`

- Este proyecto no utiliza las funciones nativas de Bee Framework, solamente su estructura MVC, por lo que no es necesario configurar las bases de datos del archivo /app/config/bee_config.php

### Conexión a la base de datos.
- Este proyecto utiliza todas las bases de ActiveDirectory del proyecto UpTask Original https://github.com/JCervantesB/UpTask_MVC

- Es necesario editar el archivo app/includes/database.php para configurar las bases de datos.

`$db = mysqli_connect('localhost', 'root', '', 'uptask');`


### Configuraciones adicionales app/core/settings.php
- BeeFramework esta configurado para trabajar con PrePros como compilador de JS y CSS, por default esta apagado.
`define('PREPROS'     , false);`

- En caso de tener algun problema de conexión usando un puerto como :3000 
Cambie el puerto `80` por el que usted esta utilizando (no deberia haber conflictos si PREPROS es false)
`define('PORT'       , '80'); // Cambia el puerto en esta linea`

- Puede cambiar el nombre de su sitio desde la variable SITE_NAME
`define('SITE_NAME'   , 'UpTask'); // Esta variable afecta todas las vistas del sistema.`

- Puede definir versiones del sistio cambiando la variable SITE_VERSION
`define('SITE_VERSION', '1.0.2');          // Versión del sitio`

### HTACCESS
- Configuración en producción
> Para habilitar el redireccionamiento 301 http > https, descomentar las siguientes lineas.

~~~
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
~~~

## CHANGELOG

#### v1.0.8

- Se añadio comprobación a homeController para redirigir al usuario a Dashboard hay una session activa y no a Auth como se hacia originalmente al enviarlo hacia '/'

#### v1.0.7

- Se eliminaron algunos archivos de ejemplo de BeeFramework no necesarios.

#### v1.0.6

API's
- Se elimino la ruta /api/tareas y /api/tarea, ya que no pueden ser declaradas de la misma forma en que sea hacia con Uptask_MVC

Ahora los endpoints de todas las consultas API provienen de cada uno de los méthodos del controlador tareaController.php

> Consultar la estructura tareas.js

#### v1.0.5

Renombrado de controlador Login
- Se renombro el controlador loginController por authController para que la estructura de la URI fuera /auth/

#### v1.0.4

Classe View
- Se ha eliminado la Router.php y Render del proyecto original, ya que Bee utiliza su propio metodo para renderizar las vistas.

- Bee renderiza las vistas mediante la classe View alojada en app/classes/

- Bee requiere la variable `$data` para mostrar pasar informacion a las vistas, donde su estructura seria la siguiente:

~~~
$data =
      [
        'alertas'       => $alertas, // Pasando $alertas a una vista
        'title'         => 'Iniciar Sesión', // Pasa el titulo de la pagina a mostrar en la vista
      ];

// Posteriormente se envia $data a una vista

View::render('index', $data);

// donde el primer parametro de render() es el nombre de la vista a mostrar.
~~~

#### v1.0.3

- Cambios al ActiveRecord

En este proyecto la classe ActiveRecord.php pasa a ser la clase Model.php alojada en "app/classes" y todas las clases extienden de Model y ya no de ActiveRecord.

#### v1.0.2

- Bee automatiza todas las rutas siempre y cuando se siga la siguiente estructura.

- Controladores

Dentro de app/controllers se conviven todos los controladores del sitio, estos deben llevar un nombre en camelcase iniciando en minusculas + la palabra Controller: authController.

- Modelos

Todos los modelos conviven en la carpeta models, estos pueden ser nombrados en mayusculas o minusculas, pero debe incluir la palabra Model despues del nombre del modelo, P.Ej: TareaModel.

- Vistas

Todas las vistas conviven en la carpeta templates/views.
Bee buscara de forma automatica, un directorio cullo nombre sea el mismo que el controlador.

> P.Ej: authController buscara una carpeta en templates/views/auth y dentro de esta, todos los archivos php ligados a un método en el controlador, deberan llevar el nombre del método seguido de la palabra View. Ej: crearView.php

Donde crearView.php estara ligado al metodo crear del controlador authController

#### v1.0.1

- Se elimino el uso de los namespaces ya que en Bee opera de forma diferente (Solo requieres la clase).
- Bee requiere que todos sus controladores utilicen sintaxis camelcase en sus nombres, por lo que pasaron a iniciar con minuscula. P.Ej: TareaController.php > tareaController.php

#### v1.0.0

- Se inicio la migracion de UpTask MVC a BeeFramework 1.1.3