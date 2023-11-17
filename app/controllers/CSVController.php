<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class CsvController
{
    public function Guardar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $entidad = $parametros['entidad'];

        switch ($entidad)
        {
            case 'mesa':
                if($archivo = fopen("csv/mesas.csv", "w"))
                {
                    $lista = Mesa::obtenerTodos();
                    foreach( $lista as $mesa )
                    {
                        fputcsv($archivo, [$mesa->idMesa, $mesa->estado]);
                    }
                    fclose($archivo);
                    echo "La lista de mesas se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de mesas". "</br>";
                }
                break;
            case 'usuario':
                if($archivo = fopen("csv/usuarios.csv", "w"))
                {
                    $lista = Usuario::obtenerTodos();
                    foreach( $lista as $usuario )
                    {
                        fputcsv($archivo, [$usuario->sueldo, $usuario->sector, $usuario->fechaIngreso, $usuario->nombreUsuario, $usuario->password, $usuario->idUsuario]);
                    }
                    fclose($archivo);
                    echo "La lista de usuarios se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de usuarios". "</br>";
                }
                break;
            case 'pedido':
                if($archivo = fopen("csv/pedidos.csv", "w"))
                {
                    $lista = Pedido::obtenerTodos();
                    foreach( $lista as $pedido )
                    {
                        fputcsv($archivo, [$pedido->idMesa, $pedido->estado, $pedido->nombreCliente, $pedido->precio, $pedido->puntuacion, $pedido->comentarios, $pedido->clavePedido, $pedido->idPedido]);
                    }
                    fclose($archivo);
                    echo "La lista de pedidos se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de pedidos". "</br>";
                }
                break;
            case 'producto':
                if($archivo = fopen("csv/productos.csv", "w"))
                {
                    $lista = Producto::obtenerTodos();
                    foreach( $lista as $producto )
                    {
                        fputcsv($archivo, [$producto->estado, $producto->tiempo, $producto->sector, $producto->nombre, $producto->clavePedido, $producto->idUsuario, $producto->idProducto]);
                    }
                    fclose($archivo);
                    echo "La lista de productos se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de productos". "</br>";
                }
                break;
            default:
                echo "No existe el nombre de la entidad". "</br>";
                break;
        }
        $payload = json_encode(array("mensaje" => "archivo creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarCSV($request, $response, $args)
    {
        $parametrosParam = $request->getQueryParams();
        $entidad = $parametrosParam['entidad'];

        switch ($entidad)
        {
            case 'mesa':
                if(($archivo = fopen("csv/mesas.csv", "r")) !== false)
                {
                    $listaMesas = [];
                    //$i=0;
                    while (($filaMesa = fgetcsv($archivo, 0, ',')) !== false) //esto seria como un while !feof
                    {
                        $nuevaMesa = new Mesa();
                        $nuevaMesa->idMesa = $filaMesa[0];
                        $nuevaMesa->estado = $filaMesa[1];
                        $nuevaMesa->crearMesaCSV();
                    }
                    fclose($archivo);
                    echo "Los mesas se cargaron correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo leer el archivo de mesas". "</br>";
                }
                break;
            case 'usuario':
                if($archivo = fopen("csv/usuarios.csv", "r"))
                {
                    $lista = Usuario::obtenerTodos();
                    foreach( $lista as $usuario )
                    {
                        fputcsv($archivo, [$usuario->sueldo, $usuario->sector, $usuario->fechaIngreso, $usuario->nombreUsuario, $usuario->passrord, $usuario->idUsuario]);
                    }
                    fclose($archivo);
                    echo "La lista de usuarios se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de usuarios". "</br>";
                }
                break;
            case 'pedido':
                if($archivo = fopen("csv/pedidos.csv", "r"))
                {
                    $lista = Pedido::obtenerTodos();
                    foreach( $lista as $pedido )
                    {
                        fputcsv($archivo, [$pedido->idMesa, $pedido->estado, $pedido->nombreCliente, $pedido->precio, $pedido->puntuacion, $pedido->comentarios, $pedido->clavePedido, $pedido->idPedido]);
                    }
                    fclose($archivo);
                    echo "La lista de pedidos se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de pedidos". "</br>";
                }
                break;
            case 'producto':
                if($archivo = fopen("csv/productos.csv", "r"))
                {
                    $lista = Producto::obtenerTodos();
                    foreach( $lista as $producto )
                    {
                        fputcsv($archivo, [$producto->estado, $producto->tiempo, $producto->sector, $producto->nombre, $producto->clavePedido, $producto->idUsuario, $producto->idProducto]);
                    }
                    fclose($archivo);
                    echo "La lista de productos se guardo correctamente". "</br>";
                }
                else
                {
                    echo "No se pudo abrir el archivo de productos". "</br>";
                }
                break;
            default:
                echo "No existe el nombre de la entidad". "</br>";
                break;
        }
        $payload = json_encode(array("mensaje" => "archivo creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}