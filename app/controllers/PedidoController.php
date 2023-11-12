<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : idMesa imgMesa estado nombre precio comentarios
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        //$imgMesa = $parametros['imgMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $puntuacion = $parametros['puntuacion'];
        $comentarios = $parametros['comentarios'];
        //$clave = $parametros['clave'];

        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        //$pedido->imgMesa = $imgMesa;
        $pedido->estado = $estado;
        $pedido->nombreCliente = $nombreCliente;
        $pedido->precio = $precio;
        $pedido->puntuacion = $puntuacion;
        $pedido->comentarios = $comentarios;
        //$pedido->clave = $clave;


        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // GET :  clave
    {
        $clave = $args['clave']; 

        $pedido = Pedido::obtenerPedido($clave);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $puntuacion = $parametros['puntuacion'];
        $comentarios = $parametros['comentarios'];
        $clave = $parametros['clave'];

        Pedido::modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentarios, $clave);

        $payload = json_encode(array("mensaje" => "Pedido Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstado($request, $response, $args) // PUT  estado clave
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $clave = $parametros['clave'];

        Pedido::modificarEstadoDelPedido($estado, $clave);

        $payload = json_encode(array("mensaje" => "El estado del pedido se modifico con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $clave = $args['clave'];
        Pedido::borrarPedido($clave);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}