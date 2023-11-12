<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarEstadoPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosParam = $request->getQueryParams();
        $parametrosBody = $request->getParsedBody();
        

        if(isset($parametrosParam['sector']) && isset($parametrosBody['estado']) && isset($parametrosBody['clave']))
        {   
            $sector = $parametrosParam['sector'];
            $estado = $parametrosBody['estado'];
            $clave = $parametrosBody['clave'];
            $lista = Producto::obtenerTodosPorClave($clave);
            $pasaValidacion = true;

            foreach($lista as $producto)
            {
                if($producto->estado != $estado)
                {
                    $pasaValidacion = false;
                    break;
                }
            }

            if($pasaValidacion)
            {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No todos los productos estan en el mismo estado para cambiar el estado del pedido'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No existe el parametro sector, el estado o la clave del pedido'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}