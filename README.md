# e-jewelry

e-jewelry es un comercio electrónico desarrollado en PHP sobre el framework de Laravel. Su funcionalidad está alineada con las necesidades funcionales expuestas en el reto presentado por Evertec en su bootcamp de PHP. Estas son:

- Gestión de usuarios con diferentes roles y permisos.
- Gestión de productos con diferentes categorías y estados.
- Gestión de logs para el registro de actividad de clientes de la aplicación.
- Gestión de pagos mediante el consumo de PlaceToPay de Evertec.
- Gestión de inventarios.
- Gestión de reportes financieros.

e-jewelry implementa Composer para la gestión de dependencias PHP, y NodeJS como gestor de dependencias de JavaScript, lo que facilita la instalación de la aplicación.

## Requerimientos para su ejecución:
- PHP 8.1+ (required)
- MYSQL 8.0+ (required)

## Instalación

- Tome una copia del repositorio:
$ git clone git@github.com:ElisaulPerezP/e-jewelry.git

- Instalación de dependencias PHP:
$ composer install

- Instalación de dependencias JavaScript:
$ npm install

## Configuración

### Archivo de variables de entorno

En el directorio raíz encontrará el archivo .env.example. Debe realizar una copia de este archivo, y el nuevo archivo debe llamarse .env y ubicarse en el repositorio raíz también.

Edite el archivo .env y coloque las variables de entorno. Estas le permitirán a la aplicación interactuar con su base de datos, su proveedor de servicio de correo y con la pasarela de pagos. Tenga en cuenta que solo necesita agregar los datos faltantes, ya que .env.example tiene los valores estándar.

#### Base de datos
DB_CONNECTION= (ejemplo: mysql)
DB_HOST= (ejemplo: 127.0.0.1)
DB_PORT= (ejemplo: 3306)
DB_DATABASE= (ejemplo: jewelry)
DB_USERNAME= (ejemplo: root)
DB_PASSWORD= (ejemplo: asdf123?#)

#### Servicio de correo
MAIL_MAILER= (ejemplo: log)
MAIL_HOST= (ejemplo: sandbox.smtp.mailtrap.io)
MAIL_PORT= (ejemplo: 2525)
MAIL_USERNAME= (ejemplo: 7b1254812907fb) <- para pruebas, obtenga su propio usuario en mailtrap
MAIL_PASSWORD= (ejemplo: 3a3628ead73327) <- para pruebas, obtenga su propia clave en mailtrap
MAIL_ENCRYPTION= (ejemplo: tls)
MAIL_FROM_ADDRESS= (ejemplo: "hello@example.com")
MAIL_FROM_NAME= (ejemplo: "${APP_NAME}")

#### Clave de la aplicación

Este campo le permitirá a su aplicación identificarse para establecer comunicación con otras aplicaciones o servicios. Para generar la clave de la aplicación, ejecute el siguiente comando en el directorio raíz del proyecto:

$ php artisan key:generate

Después de ejecutar el comando, si todo salió bien, se llenará automáticamente el campo APP_KEY en su archivo .env. Debería ser parecido (no igual) a esto:

APP_KEY=base64:aBfDU+/NdjceIpPiScGUaMz1aAH6RVcmoR0oJyPOKUc=

#### Preparación de la base de datos

Para migrar y sembrar la base de datos y probar la aplicación, ejecute el siguiente comando:

$ php artisan migrate --seed

#### Enlace de almacenamiento

Para crear un enlace simbólico entre las carpetas public y storage, ejecute el siguiente comando:

$ php artisan storage:link

#### Configuración de Passport

Para que el sistema de seguridad 'passport', encargado de autorizar las conexiones a las rutas API, funcione, es necesario proporcionarle sus claves de encriptación. Esto se puede lograr ejecutando el siguiente comando:

$ php artisan passport:keys

#### Prueba

Para asegurarse de que todo salió bien, ejecute las pruebas con el siguiente comando:

$ php artisan test

#### Ejecución de la aplicación

Para ejecutar la aplicación, debes lanzar el servicio utilizando el comando php artisan serve.

Nota: Debes tener un servidor configurado para este propósito, por ejemplo, Apache.

#### Contratación del personal de la tienda

Para que las tareas rutinarias se lleven a cabo, debes ejecutar los procesos encargados de revisar la tabla de jobs.
Esta aplicación tiene tres colas para su funcionamiento, y cada uno de los siguientes workers atenderá esas colas. 
Ejecuta los siguientes comandos para atender las colas de 'mailer', 'shelf-stocker' y 'payment-status':

$ php artizan queue:work database --queue=mailer
$ php artizan queue:work database --queue=shelf-stocker
$ php artizan queue:work database --queue=paymen-status

Estos trabajadores se encargan de enviar correos electrónicos,
recoger los productos de los carritos para volver a colocarlos en la vitrina
y comunicarse con la pasarela de pagos para actualizar los estados de las órdenes de compra.

#### Primeros pasos de las funcionalidades.

Ingresa a la aplicación a través del enlace que se emitió durante el lanzamiento de la aplicación.
Si la base de datos se ha sembrado correctamente, podrás ingresar con los siguientes datos:

Email: admin@jewelry.com
Contraseña: password

(WIP)

## Contribuciones al desarrollo

Si desea unirse al desarrollo de esta aplicación, puede hacerlo solicitando un pull request.
