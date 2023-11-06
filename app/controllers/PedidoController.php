<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : idMesa imgMesa estado nombre precio comentarios
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $imgMesa = $parametros['imgMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $comentarios = $parametros['comentarios'];
        //$clave = $parametros['clave'];

        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        $pedido->imgMesa = $imgMesa;
        $pedido->estado = $estado;
        $pedido->nombreCliente = $nombreCliente;
        $pedido->precio = $precio;
        $pedido->comentarios = $comentarios;
        //$pedido->clave = $clave;


        $id = $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito", "id" => $id));

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
      /*
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          */
    }

    public function BorrarUno($request, $response, $args)
    {
      /*
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
        */
    }
}