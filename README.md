# e-jewelry

## Índice

[Prólogo](#prólogo)\
[Instalación](#instalación)
- [Requerimientos](#requerimientos)
- [Instrucciones](#instrucciones)

[Configuración](#configuración)
- [Variables de entorno](#variables-de-entorno)
- [Clave de la aplicación](#clave-de-la-aplicación)
- [Base de datos](#base-de-datos)
- [Servicio de correo](#servicio-de-correo)
- [Driver de colas](#driver-de-colas)

[Preparación de la base de datos](#preparación-de-la-base-de-datos)\
[Enlace de almacenamiento](#enlace-de-almacenamiento)\
[Sistema de autenticación](#sistema-de-autenticación)
- [Configuración de passport](#configuración-de-passport)

[Comprobación de las características](#comprobación-de-las-características)

- [Lanzamiento de los tests](#lanzamiento-de-los-test)

[Puesta en marcha del aplicativo](#puesta-en-marcha-del-aplicativo)

- [Ejecución de la aplicación](#ejecución-de-la-aplicación)
- [Lanzamiento de los trabajadores de la tienda](#lanzamiento-de-los-trabajadores-de-la-tienda)

[Ingrese a su tienda](#ingrese-a-su-tienda)

[Administración](#administración)
- [Descarga de lista de productos](#descarga-de-lista-de-productos)
- [Carga masiva de productos](#carga-masiva-de-productos)
- [Reglas para la conciliación de carga de productos](#reglas-para-la-conciliación-de-carga-de-productos)
- [Generación de reportes de productos para envío](#generación-de-reportes-de-productos-para-envío)
- [Descarga de reporte de productos para envío](#descarga-de-reporte-de-productos-para-envío)

[API](#api)
- [Sistema Oauth2 de passport](#sistema-oauth2-de-passport)
- [Intercambio de un código de autorización por un token de autorización](#intercambio-de-un-código-de-autorización-por-un-token-de-autorización)
- [End-points accesibles](#end-points-accesibles)
- [Product Index](#product-index)
- [Product store](#product-store)
- [Product Edit](#product-edit)
[Ciclo de vida de un usuario](#ciclo-de-vida-de-un-usuario)
  - [Registro](#registro)
  - [Expulsión](#expulsión)
- [Ciclo de vida de un producto](#ciclo-de-vida-de-un-producto)
  - [Creación de un producto](#creación-de-un-producto)
  - [Habilitar o deshabilitar un producto](#habilitar-o-deshabilitar-un-producto)
  - [Vista de administración de productos](#vista-de-administración-de-productos)
    - [Búsqueda](#búsqueda)
    - [Navegar entre las páginas de productos creados](#navegar-entre-las-páginas-de-productos-creados)
    - [Ver un producto en detalle](#ver-un-producto-en-detalle)
    - [Editar un producto](#editar-un-producto)
- [Concepto de item de carrito](#concepto-de-item-de-carrito)
- [Ciclo de vida de un cartItem](#ciclo-de-vida-de-un-cartitem)
  - [Creación](#creación)
  - [Recolección](#recolección)
  - [Retomado de vitrina](#retomado-de-vitrina)
  - [Orden de pago](#orden-de-pago)
  - [Despacho](#despacho)
  - [Dead](#dead)
- [Ciclo de vida de una orden](#ciclo-de-vida-de-una-orden)
- [Creación](#creación)
- [Procesamiento](#procesamiento)
- [Actualización](#actualización)
  [Sistema de control de acceso](#sistema-de-control-de-acceso)
- [Permisos](#permisos)
  - [Asignar un permiso a un role](#asignar-un-permiso-a-un-role)
  - [Asignar un permiso a un usuario](#asignar-un-permiso-a-un-usuario)
- [Roles](#roles)
  - [Crear un role](#crear-un-role)
  - [Asignar permisos a un role](#asignar-permisos-a-un-role)


## Prólogo

e-jewelry es un comercio electrónico desarrollado en PHP sobre el framework de Laravel. Su funcionalidad está alineada
con los requerimientos funcionales marcados en el reto que fue presentado por Evertec en su bootcamp de PHP. Estas son:

- Gestión de usuarios con diferentes roles y permisos.
- Gestión de productos con diferentes categorías y estados.
- Gestión de logs para el registro de actividad de clientes de la aplicación.
- Gestión de pagos mediante la pasarela PlaceToPay de Evertec.
- Gestión de inventarios.
- Gestión de reportes administrativos.

Para el 16 de julio de 2023 es publicada su versión alpha, con un sentimiento de satisfacción e 
inmensa gratitud hacia los tutores del bootcamp; Desarrolladores de Evertec que, sin ser
profesionales en docencia, hicieron un trabajo magistral llevándome de la mano a aprender sobre 
las tecnologías aquí utilizadas. MUCHAS GRACIAS a todos aquellos que hasta ahora han aportado 
al desarrollo de este código.
## Instalación
e-jewelry implementa Composer para la gestión de dependencias PHP, y NodeJS como gestor de dependencias de JavaScript, 
lo que facilita la instalación de la aplicación.

A continuación, son expuestas las instrucciones y requerimientos sistemáticos para poner en marcha el algoritmo en un 
sistema servidor web.

### Requerimientos:

- PHP 8.1+ (requerido)
- MYSQL 8.0+ (requerido)

### Instrucciones

- Tome una copia del repositorio ejecutando el siguiente comando:\
  ```$ git clone git@github.com:ElisaulPerezP/e-jewelry.git```

- Instalación de dependencias PHP:\
  ```$ composer install```

- Instalación de dependencias JavaScript:\
  ```$ npm install```

- Preparacion de assets:\
  ```$ npm run build```

## Configuración

A continuación son expuestas las pautas a seguir para tener la aplicación corriendo en tu entorno, con tu base de datos,
tu servicio de correo y algunas cosas más.

### Variables de entorno

En el directorio raíz encontrará dos archivos importantes, `.env.example` y `.env.testing.example`. Debe realizar una 
copia de estos archivos, los nuevs archivo debe llamarse `.env` y `.env.testing` y ubicarse en el repositorio raíz 
también, para conseguirlo puede usar los siguientes comandos de consola:

```$ cp .env.example .env```

```$ cp .env.testing.example .env.testing```

Edite el archivo `.env` colocando las variables de entorno de la forma indicada a continuación. Estas le permitirán a
la aplicación interactuar con su base de datos, su proveedor de servicio de correo y con la pasarela de pagos, 
y controlador de colas de trabajo. Tenga en cuenta que solo necesita agregar los datos faltantes, ya que `.env.example`
tiene los valores estándar, al igual que `.env.testing.example`.

#### Clave de la aplicación

Este campo le permitirá a su aplicación identificarse para establecer comunicación con otras aplicaciones o servicios. Para generar la clave de la aplicación, ejecute el siguiente comando en el directorio raíz del proyecto:

$ php artisan key:generate

Después de ejecutar el comando, si todo salió bien, se llenará automáticamente el campo APP_KEY en su archivo .env. Debería ser parecido (no igual) a esto:
```php
APP_KEY=base64:aBfDU+/NdjceIpPiScGUaMz1aAH6RVcmoR0oJyPOKUc=
```

#### Base de datos
Debe crear dos (2) nuevos esquemas en su base de datos para ser usado por la aplicacion. 
Uno de ellos será usado por la aplicación, y debe estar reportado en el archivo ```.env``` de la siguiente manera:

``` php
DB_CONNECTION= (ejemplo: mysql)
DB_HOST= (ejemplo: 127.0.0.1)
DB_PORT= (ejemplo: 3306)
DB_DATABASE= (ejemplo: jewelry)
DB_USERNAME= (ejemplo: root)
DB_PASSWORD= (ejemplo: asdf123?#)
```
El otro esquema será usado phpUnit para testear la aplicación, y debe estar reportado en el archivo ```.env.testing``` de la siguiente manera:
``` php
DB_CONNECTION= (ejemplo: mysql)
DB_HOST= (ejemplo: 127.0.0.1)
DB_PORT= (ejemplo: 3306)
DB_DATABASE= (ejemplo: jewelry_testing)
DB_USERNAME= (ejemplo: root)
DB_PASSWORD= (ejemplo: asdf123?#)
```
Nota: De no ser configurado el archivo ```.env.testing``` , la aplicación presentará fallos debido a la bases de datos. 

#### Servicio de correo
```php
MAIL_MAILER= (ejemplo: log)
MAIL_HOST= (ejemplo: sandbox.smtp.mailtrap.io)
MAIL_PORT= (ejemplo: 2525)
MAIL_USERNAME= (ejemplo: 7b1254812907fb) <- para pruebas, obtenga su propio usuario en mailtrap
MAIL_PASSWORD= (ejemplo: 3a3628ead73327) <- para pruebas, obtenga su propia clave en mailtrap
MAIL_ENCRYPTION= (ejemplo: tls)
MAIL_FROM_ADDRESS= (ejemplo: "hello@example.com")
MAIL_FROM_NAME= (ejemplo: "${APP_NAME}")
```
#### Driver de colas

Para el ambiente de desarrollo es recomendable mantener el controlador en sync, no obstante, 
el sistema está optimizado para trabajar en producción con `database` como selector de driver.
Si es su selección, debe asegurarse de que es soportado por el sistema que se encuentre sirviendo
el aplicativo, y en él poner en marcha a los trabajadores que atienden las colas respectivas, 
más adelante se especificará con detalle su configuración. La variable en mención es:
```php
QUEUE_CONNECTION=sync
```

#### Credenciales de la pasarela de pagos
e-jewelry está diseñada para realizar pagos únicamente a travéz de la pasarela de pagos
Place To Pay, póngase en contacto con la empresa mediante la pagina:
https://sites.placetopay.com/ 
Para contratar el servicio, o solicitar credenciales para realizar pruebas. Le serán 
concedidas las credenciales que debera registrar en su archivo ```.env```.
```php
PLACETOPAY_LOGIN=
PLACETOPAY_TRANKEY=
PLACETOPAY_URL=
```

### Preparación de la base de datos

Para migrar las tablas requeridas y sembrarlas con información ficticia que le permitirá 
probar el funcionamiento de la aplicación, ejecute el siguiente comando:

```$ php artisan migrate --seed```

### Enlace de almacenamiento

Es necesario generar una conexión entre los directorios public y storage del proyecto, 
esto para el correcto funcionamiento de la aplicación, no hay de qué preocuparse, 
se realiza de manera automática; ejecute el siguiente comando de artisan:

```$ php artisan storage:link```

## Sistema de autenticación

### Configuración de Passport

Para que el sistema de seguridad 'passport', encargado de autorizar las conexiones a 
las rutas API, funcione correctamente, es necesario proporcionarle sus claves de 
encriptación. Esto se puede lograr ejecutando el siguiente comando:

```$ php artisan passport:keys```

## Comprobación de las características

### Lanzamiento de los test

Para asegurarse de que todo salió bien, ejecute las pruebas con el siguiente comando:

```$ php artisan test```

## Puesta en marcha del aplicativo

### Ejecución de la aplicación

Para ejecutar la aplicación, debes lanzar el servicio utilizando el comando:

```$ php artisan serve```

Este comando lanzará el servicio mediante el servidor de php, puedes lanzarlo 
mediante diversos servidores.

### Lanzamiento de los trabajadores de la tienda

Para que las tareas rutinarias se lleven a cabo, y solo en caso de que hayas seleccionado 
`QUEUE_CONNECTION=database`, debes poner en marcha los procesos encargados de revisar la tabla 
de jobs y ejecutar las rutinas que les conciernen. Esta aplicación tiene tres colas para su 
funcionamiento, los siguientes workers atenderán esas colas. Ejecuta los siguientes comandos 
para atender las colas de: `default` `shelf-stocker` y `payment-status`:
```
$ php artisan queue:work database
$ php artisan queue:work database --queue=shelf-stocker
$ php artisan queue:work database --queue=payment-status
```
Estos trabajadores se encargan de generar reportes, enviar correos electrónicos, recoger los productos de 
los carritos para volver a colocarlos en la vitrina y comunicarse con la pasarela de pagos 
para actualizar los estados de las órdenes de compra.

Nota: De haber seleccionado `QUEUE_CONNECTION=sync` las tareas serán atendidas de forma inmediata por el sistema, asi
que no es necesario ejecutar workers para que sea atendida ninguna tarea. 

## Ingrese a su tienda

Al correr las migraciones ha generado un usuario especial, su correo es `admin@jewelry.com` y 
su contraseña es: `password`. Este usuario tiene role 'admin', y con él tiene acceso a todas 
las funcionalidades de la aplicación, incluso, puede crear a otros roles, esto será expuesto más 
adelante, por ahora ingrese con estas credenciales para conocer su nueva tienda de joyas.

# Administración:

## Descarga de lista de productos
En la sección de productos, el administrador encontrará el botón de ```Descargar lista de productos```, este botón
iniciará la descarga de un archivo de excel, con extension xlsx, y que contendrá todos los productos de la tienda. 
Podrá editar cualquier campo del archivo para ser subido de nuevo. 
## Carga masiva de productos
Al subir el archivo con productos, el administrador estará modificando los productos existentes y creando nuevos
productos.
### Reglas para la conciliación de carga de productos
A continuación se describe el funcionamiento del importe de productos.
- De subirse un producto con una ID nueva, el sistema creará un producto nuevo
- De subirse un producto con una ID existente, el sistema evaluará si ha habido cambios en el nombre, la descripción o 
el precio. 
De ser así, se creará un producto nuevo, y el stock del producto antiguo será migrado al producto nuevo. 
- De subirse un producto con una ID existente, sin cambios en los campos de nombre, descripción, o precio, serán alterados los 
campos en el producto existente. 

## Generación de reportes de productos para envío
En la pestaña de Administración encontrará el botón de ```Articulos para despacho``` al ingresar, podrá ver los 
artículos que fueron pagados y se encuentran esperando para ser despachados. En esta vista el administrador podrá cambiar
el estado de los artículos a ```enviado```. Encontrará en esta vista el botón de ```Descargar lista de productos para despacho```
La acción de este botón agenda el job de la generación del archivo de productos para el despacho, cuando es terminada su generación
el administrador recibirá un email anunciando que el archivo está disponible para la descarga. 
## Descarga de reporte de productos para envío
En la vista de administración, puede ver el botón de ```Ver reportes``` allí podrá ver los reportes generados, y descargarlos. 

## API
e-jewelry es una aplicación compuesta por un back-end realizado en Laravel y un front-end realizado en Vue.js. Por
tanto, todos sus recursos pueden ser consumidos por un cliente autorizado por un usuario con permisos. Los permisos
del cliente serán heredados del usuario.

### Sistema Oauth2 de Passport
Esta aplicación implementa Laravel Passport para la gestión de seguridad en su API. Para consumir un recurso de la
API usted debe contar con un token de autorización. Para obtenerlo, siga los siguientes pasos:

1. Ingrese a la pestaña de "Desarrolladores".
2. Cree un cliente pulsando el botón "Crear cliente", introduzca el nombre y pulse "Crear".
  - Si desea revocarlo, pulse el botón "Revocar".
  - Tome nota de los campos "ID", el "Secret" y "Redirect", más tarde los necesitará.
3. Cree un código de autorización pulsando el botón "Crear código".
4. Otorgue su autorización pulsando el botón "Authorize".
5. Copie el código de autorización presentado. Ese código no se podrá acceder nuevamente.
6. Entregue ese código al cliente que va a consumir los recursos de la API en su nombre.
7. El cliente deberá intercambiar el código de autorización por un token de autorización antes de consumir los recursos.

#### Intercambio de un código de autorización por un token de autorización
Para facilitar el uso de la API, se postean a continuación los vínculos de acceso con las colecciones Postman
asociadas al consumo de la API. El siguiente vínculo muestra cómo comunicarse con la ruta de intercambio de código
de autorización:\
[Enlace a la colección de intercambio de código de autorización](https://crimson-capsule-508960.postman.co/workspace/5db2496d-c262-481b-85f4-4fafc33bdcaf/request/27477039-245cac40-3e5a-4505-bf37-6f7687c684d6?ctx=documentation)
### End-points accesibles

Las características de la aplicación permiten consumir de forma segura los siguientes endpoints.
#### Product Index
[Enlace a la colección product Index](https://crimson-capsule-508960.postman.co/workspace/5db2496d-c262-481b-85f4-4fafc33bdcaf/request/27477039-9840cdb4-c913-4c1b-a17a-681bf93c1275?ctx=documentation)
#### Product store
[Enlace a la colección Product store](https://crimson-capsule-508960.postman.co/workspace/5db2496d-c262-481b-85f4-4fafc33bdcaf/request/27477039-671913e0-e174-40b0-a916-3ab8e4c4a1e5?ctx=documentation)
#### Product Edit
[Enlace al Product Edit](https://crimson-capsule-508960.postman.co/workspace/5db2496d-c262-481b-85f4-4fafc33bdcaf/request/27477039-81209743-6a01-4a37-b20c-c227747f89e1?ctx=documentation)


## Ciclo de vida de un Usuario

### Registro
Cualquier persona podrá acceder a su tienda, para ello deberá registrarse. El registro se realiza mediante el vínculo en
la esquina superior derecha: en español 'Registrarse'; Para hacerlo debe ingresar su nombre, su email, su contraseña y
una confirmación de la contraseña. Una vez hecho, será enviado un email a través del servicio de emails al correo
ingresado, y la cuenta del usuario será habilitada justo cuando haya confirmado la recepción del mail dando clic en el
enlace que lo conducirá a la vitrina de la tienda.

### Expulsión
En la pestaña de navegación, el administrador verá la pestaña de usuarios, en ella podrá ver en una tabla los usuarios
registrados, podrá acceder a los detalles de cada usuario, actualizar sus datos, otorgarle y revocar permisos, y
asignarle roles diferentes; concerniente a su ciclo de vida, podrá expulsar a un usuario cambiando su estado a inhabilitado,
o reintegrarlo al habilitarlo nuevamente.

## Ciclo de vida de un producto
El usuario administrador o quienes sean autorizados por él podrán crear productos nuevos. A través de la interacción
con la pestaña de productos será conducido a la vista de administración de productos, quien use esta pestaña podrá
realizar las acciones expuestas a continuación.

### Creación de un producto
Un usuario con los permisos suficientes podrá crear un producto, esto se puede realizar por varios medios: la vista de
creación de un producto, quien consume la ruta API de creación de productos, o por medio de un archivo de actualización
de productos. Ambos casos serán abordados con detalle más adelante. En cuanto a lo que atañe a este punto de la
exploración de la aplicación, el botón NUEVO PRODUCTO conducirá a un usuario a la vista de creación de un producto,
donde podrá subir una foto del producto y completar el formulario para crear un producto nuevo.

### Habilitar o deshabilitar un producto
Para mantener la consistencia en todos los sistemas de la aplicación, los productos no pueden ser eliminados, en su
lugar pueden ser desactivados, impidiendo que sean mostrados a los compradores en la vitrina.

## Vista de administración de productos
### Búsqueda
Las búsquedas pueden ser realizadas introduciendo una palabra o frase en el único cuadro de texto de la vista de
administración de productos. Para efectuar la consulta debe oprimir enter.

### Navegar entre las páginas de productos creados
Para optimizar la experiencia del usuario, los productos enviados en cada consulta están restringidos por un
paginador, este componente puede usarse al dar clic en los botones, que de izquierda a derecha tienen la funcionalidad
de mostrar: primera página, página anterior, página según el número, página siguiente y página final.

### Ver un producto en detalle
Al pulsar el botón DETALLE, el usuario en la vista de administración de productos será redirigido a una vista de detalle
de producto, en la cual podrá regresar pulsando el botón de Atrás.

### Editar un producto
Al pulsar el botón de EDITAR, el usuario será redirigido a una vista donde los valores asociados a un producto aparecen
por defecto en los cuadros de edición, allí podrá cambiar su imagen, su nombre, su descripción, su precio, su stock  y
su estado. Para confirmar debe pulsar GUARDAR, si los campos son consistentes con el tipo de dato requerido, el
producto será actualizado, de lo contrario, se mostrará un mensaje.
# Concepto de item de carrito

El funcionamiento de este e-commerce se acoge al funcionamiento de un comercio con infraestructura real para proteger la
experiencia de compra de un usuario, con este fin se crea el concepto de itemCart como es manejado aquí. El itemCart es
el homólogo a un producto real en un carrito de compras de cualquier supermercado, corresponde así a uno o varios
ejemplares de existencia de un producto en una tienda, su manejo será entonces por medio de estados.

Para mayor claridad, es pertinente exponer un ejemplo: Suponga una tienda en que puede tomar productos de una vitrina,
por ejemplo, una lata de carne, el producto es "lata de carne de la marca x"; usted ahora tiene en su poder una unidad, 
ningún otro comprador va a quitarla de su poder hasta que usted efectúe su compra, <QUIERO UN CAFÉ!> usted deja su 
carrito abandonado y se va, al paso del tiempo un trabajador toma todos los productos que están en su carrito y los va
a disponer en la vitrina nuevamente, no sin antes registrar cuáles y cuántos productos tenía usted en su carrito, pues 
usted es cliente de la tienda, debe estar por ahí, piensa el trabajador.

A su regreso del café, usted encuentra una nota en su carrito con los productos que estaban en él, los toma de nuevo,
pues aún hay existencias. A la hora de acercarse a la caja para pagar, usted puede decir cuáles de los productos quiere
realmente pagar; aquellos que no, los abandona en el carrito, hay alguien que se encargará de ellos, el trabajador va a
recogerlos y repetir el proceso. Aquellos que sean aprobados por usted para el pago serán registrados por el cajero... 
<OH NO, HE DEJADO OLVIDADA MI BILLETERA EN EL COCHE>, pues el cajero guardará sus productos por el tiempo que se lo
permita el administrador, si se tarda mucho, quien recoje los carritos abandonados pasará y los colocará en vitrina, 
a su regreso serán colectados para el pago, de no haber disponibilidad de alguno, le será informado.

Que bueno que regresó, gracias por su pago, sus productos pasan a envíos inmediatamente, y llegan a la 
puerta de su casa.
## Ciclo de vida de un cartItem

### Creación
Un cartItem nace cuando un comprador pulsa el botón de agregar al carrito en la vitrina de la tienda, o en su defecto
cuando la ruta API dispuesta para tal fin es accedida. El nacimiento de un itemCart va acompañado de la etiqueta de
estado: `in_cart`.

### Recolección
Un cartItem es recolectado cuando pasa mucho tiempo desde su última actualización, el worker que atiende la cola de 
shelf-stocker dispone del stock asociado, sumándolo al producto asociado y cambiando su etiqueta de estado a: `collected`.

### Retomado de vitrina
Un cartItem puede ser puesto de nuevo en su poder si luego de ser recolectado por el shelf-stocker, usted coloca un 
número en el campo de cantidad asociado a ese producto en su vista de carrito; de no haber existencias, le será 
mostrado un mensaje.

### Orden de pago
Uno o un conjunto de cartItems que usted seleccione mediante los selectores en la vista de carrito serán afectados 
en backend, su estado cambiará a `selected` y estará disponible el total de la compra. Una vez seleccionado algún 
itemCart, podrá generar una orden de pago para ser procesada por el sistema de pagos.

### Despacho
Si el pago fue exitoso, el worker que atiende la cola paymen-status cambiará el estado de los cartItems a `paid` y
serán entregados a un trabajador físico, quien podrá gestionar su envío.

### Dead
Para terminar con su vida, el administrador podrá cambiar el estado de un cartItem a `dispached`, en cuyo estado, solo
tiene interés histórico.
# Ciclo de vida de una orden

Orden de pago es el modelo más complejo de la aplicación en cuanto a relaciones, sin embargo, asociado a él hay un 
estado que toma valores de `pending`, `rejected`, `approved`; no son tantos como en cartItem, haciendo su ciclo de 
vida más sencillo.

### Creación
Una orden es creada cuando un usuario con cartItems en estado `selected` y cantidades diferentes de cero solicita 
realizar el pago. Inmediatamente la orden es creada con el estado `pending` asociado.

### Procesamiento
Inmediatamente después de su creación, la orden es procesada por el servicio `PlaceToPayPaymentService`, quien tiene 
la autoridad para cambiar su estado según el resultado de la intención de pago. De resultar exitoso el pago, 
`PlaceToPayPaymentService` cambiará su estado a `approved`, de lo contrario, a `rejected`, dando por terminado 
su ciclo de vida.

### Actualización
El worker `paymen-status` atiende un job que es programado por el kernel de consola cada 5 minutos, este job: 
`PaymentStatusChecker`, se encarga de realizar la consulta al servicio de actualización de estado de PlaceToPay para 
cada orden en estado pendiente, y registrar su cambio. Una vez su estado es `approved` o `rejected`, su ciclo de vida 
ha llegado a su fin.

# Sistema de control de acceso
Los permisos de acceso están gestionados mediante Laravel Spatie Permissions, un conjunto de vistas fueron integradas
para facilitar la gestión del sistema.

## Permisos
Los permisos del sistema son elementos fijos que pueden relacionarse con modelos, como roles y usuarios. La lista de
permisos puede ser consultada en la vista de permisos, para acceder a ella vaya a la pestaña de administración y luego 
pulse el botón permisos.

### Asignar un permiso a un role
Para asignar un permiso a un rol debe ir a la vista de administración de roles, para acceder a ella vaya a la pestaña 
de administración y luego pulse el botón de roles, allí podrá ver la gestión de roles, allí pulse el botón de permisos 
asociado al rol que desea modificar, seleccione los permisos a asignar, y habrá asignado los permisos a un rol.

### Asignar un permiso a un usuario
También puede asignar permisos a un usuario, para ello vaya a la pestaña de usuarios y pulse el botón permisos, allí 
puede ver los permisos otorgados a un usuario, seleccionar o desmarcar para alterar su lista de permisos.

## Roles
Un rol es un modelo al que pueden ser asignados permisos.

### Crear un role
Para crear un rol vaya a la vista de administración de roles, pulsando en la pestaña de administración, y luego en el 
botón de roles, una vez allí pulse el botón CREAR ROL, añada un nombre y seleccione la guarda para la cual está 
autorizado el rol. Pulse crear, y proceda con la asignación de permisos.

### Asignar permisos a un role
Para asignar permisos a un rol vaya a la vista de administración de roles, pulsando en la pestaña de administración, 
luego en el botón de roles, y luego en el botón de permisos asociado al rol a administrar. Seleccione los permisos que 
desea incluir en el rol, ¡y está hecho!

### El permiso `user.dev web`
Este es un permiso especial, otorgado a un usuario para darle acceso a la generación de códigos de autorización para 
consumir la API mediante una aplicación externa. Este permiso habilita la vista de la pestaña 'Desarrolladores'.
