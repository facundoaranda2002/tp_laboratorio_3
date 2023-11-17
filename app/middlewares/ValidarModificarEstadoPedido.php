<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarEstadoPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $parametrosBody = $request->getParsedBody();
        

        if(isset($parametrosBody['estado']) && isset($parametrosBody['clavePedido']))
        {   
            $estado = $parametrosBody['estado'];
            $clavePedido = $parametrosBody['clavePedido'];
            $lista = Producto::obtenerTodosPorClave($clavePedido);
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
            $payload = json_encode(array('mensaje' => 'No existe el parametro estado o la clave del pedido'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}