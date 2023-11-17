<?php
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : estado
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];

        $mesa = new Mesa();
        $mesa->estado = $estado;

        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // GET :  idMesa
    {
        $id = $args['idMesa']; 

        $mesa = Mesa::obtenerMesa($id);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosEstado($request, $response, $args) // GET estado
    {
      $parametrosParam = $request->getQueryParams();
      $estado = $parametrosParam['estado'];

      $lista = Mesa::obtenerTodosEstado($estado);
      $payload = json_encode(array("listaMesa" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)//PUT Estado
    {
      $parametros = $request->getParsedBody();

      $idMesa = $args['idMesa']; 
      $estado = $parametros['estado'];

      Mesa::modificarMesa($idMesa, $estado);

      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE idMesa
    {

        $idMesa = $args['idMesa'];
        Mesa::borrarMesa($idMesa);

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMasUsada($request, $response, $args) // GET
    {

      $listaMesas = Mesa::obtenerTodos();
      $primeraIteracion = true;
      $mesaMasUsada = null;
      

      foreach ($listaMesas as $mesa) 
      {
        $listaPedidos = Pedido::obtenerTodosSegunIdMesa($mesa->idMesa);
        $cantidad = count($listaPedidos);
        if($primeraIteracion || $maximoPedidos < $cantidad)
        {
          $primeraIteracion = false;
          $maximoPedidos = $cantidad;
          $mesaMasUsada = $mesa;
        }
      }
      

      $payload = json_encode(array("Mesa mas usada" => $mesaMasUsada, "Cantida de usos" => $maximoPedidos));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

  public function MesaMejoresComentarios($request, $response, $args) // GET
  {

    $listaMesas = Mesa::obtenerTodos();
    $primeraIteracion = true;
    $mejorComentario = null;
    $mejorPuntuacion = 0;
    $mejorMesa = null;
     
    foreach ($listaMesas as $mesa) 
    {
      $listaPedidos = Pedido::obtenerTodosSegunIdMesa($mesa->idMesa);
      foreach ($listaPedidos as $pedido) 
      {
        if($pedido->estado == "finalizado" && ($primeraIteracion || $pedido->puntuacion > $mejorPuntuacion))
        {
          $primeraIteracion = false;
          $mejorComentario = $pedido->comentarios;
          $mejorPuntuacion = $pedido->puntuacion;
          $mejorMesa = $pedido->idMesa;
        }
      }
    }

    $payload = json_encode(array("Mejor comentario" => $mejorComentario, "Mesa:" => $mejorMesa));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}