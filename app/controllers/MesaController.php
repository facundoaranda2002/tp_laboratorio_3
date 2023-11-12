<?php
require_once './models/Mesa.php';
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
}