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
~~~
define('IS_LOCAL'     , in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
~~~
-- Para entornos en producción, es necesario editar la linea $_SERVER['REMOTE_ADDR'] donde "REMOTE_ADDR" es la url completa de tu sitio en producción P.Ej: https://tudominio.com/

~~~
define('BASEPATH'     , IS_LOCAL ? '/' : '____EL BASEPATH EN PRODUCCIÓN___'); // Debe ser cambiada a la ruta de tu proyecto en producción y desarrollo
~~~

** Este es el punto mas importante **

-- Modificar la ruta IS_LOCAL ? '/' <- Si el path (ruta real) de tu directorio del proyecto se encuentra en una carpeta diferente a la de tu servidor apache.
P.Ej: Si tu rota es www/uptask/, entonces necesitas reemplazar  '/' por 'uptask/'
Ya que Bee buscara siempre como ruta base, esta ubicación.

-- En caso de despligue en producción.
Si tu sitio es https://tudominio.com/uptask
Es necesario modificar '____EL BASEPATH EN PRODUCCIÓN___' por 'uptask/'
Si tu proyecto se encuentra en la raiz del hosting reemplazar '____EL BASEPATH EN PRODUCCIÓN___' por '/'
