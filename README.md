
TP Programacion 3 Aranda Facundo.
==============================

## Instructivo

Este instructivo trata de dar una guia para realizar las acciones que se encuentran en la correccion paso a paso:

- El usuario Logger Admin se hace para establecer un socio que pueda dar de alta a otros usuarios en caso de no haber ninguno.
- El alta de cada producto del pedido lo debe hacer el mozo despues de dar de alta el pedido por lo cual requerira el token correspondiente.
- Las fotos se guardaran en la carpeta src/fotos contando con idMesa_clavePedido_nombreUsuario como nombre del archivo y las realizara el mozo por lo cual requerira el token correspondiente.
- Los empleados de sectores que no sean socio u mozo solo podran listar los productos segun su sector correspondiente y el estado en que se encuentra el producto, arrancando en estado pendiente cada producto, para listar estos productos y modificar el estado de los mismos asi como su tiempo de demora se necesitara del token correspondiente al sector.
- El cliente no necesitara de tokens para sus acciones ya que no cuenta como un usuario(empleado).
- Las acciones sobre las mesas las hace exclusivamente el socio, salvo cuando hay que cambiar el estado ya que ahi puede influir el mozo tambien(tokens correspondientes).
- IMPORTANTE: para ir cambiando el estado del pedido(mozo) antes se tendran que modificar los estados de todos los productos referentes al mismo en el estado exacto al que se quiere modificar el pedido(la aplicacion da el aviso si es que faltan productos por modificar su estado).
- Referido a esto, para poder traer el mejor comentario de la mesa se evaluaran los pedidos en estado "finalizado" por lo cual tambien habra que cambiar a ese estado a los productos correspondientes al pedido.
- El guardado del archivo csv se realiza en la carpeta csv, asi mismo se realizara la carga del csv hacia la base de datos desde los archivos que se encuentran en la misma carpeta previamente nombrada.

¡¡¡ GRACIAS POR LEER c: !!!


## Correr localmente via PHP

- Acceder por linea de comandos a la carpeta del proyecto y luego instalar Slim framework via Compose

```sh
cd C:\<ruta-del-repo-clonado>
composer update
php -S localhost:666 -t app
```

- Abrir desde http://localhost:666/

### 2022 - UTN FRA


