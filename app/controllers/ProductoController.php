<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : estado tiempo tipo nombre
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $clavePedido = $parametros['clavePedido'];

        $prod = new Producto();
        $prod->estado = $estado;
        $prod->tiempo = $tiempo;
        $prod->tipo = $tipo;
        $prod->nombre = $nombre;
        $prod->clavePedido = $clavePedido;

        $id = $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  idProducto
    {
        $idProducto = $args['idProducto']; 

        $prod = Producto::obtenerProducto($idProducto);
        $payload = json_encode($prod);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        

        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $clavePedido = $parametros['clavePedido'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarProducto($estado, $tiempo, $tipo, $nombre, $clavePedido, $idProducto);

        $payload = json_encode(array("mensaje" => "Producto Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstado($request, $response, $args) // PUT  estado idProducto
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarEstadoDelProducto($estado, $idProducto);

        $payload = json_encode(array("mensaje" => "El estado del producto fue modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        Producto::borrarProducto($idProducto);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}