<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarEstadoProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosParam = $request->getQueryParams();
        $parametrosBody = $request->getParsedBody();
        

        if(isset($parametrosParam['sector']) && isset($parametrosBody['estado']) && isset($parametrosBody['idProducto']))
        {   
            $sector = $parametrosParam['sector'];
            //$estado = $parametrosBody['estado'];
            $idProducto = $parametrosBody['idProducto'];
            $producto = Producto::obtenerProducto($idProducto);

            if($sector == $producto->tipo)
            {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos del sector correspondiente'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No existe el parametro sector, el estado o el id'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}