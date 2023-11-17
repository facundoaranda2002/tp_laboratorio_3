<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarEstadoYTiempoProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosBody = $request->getParsedBody();
        
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        if(isset($parametrosBody['estado']) && isset($parametrosBody['tiempo']) && isset($parametrosBody['idProducto']))
        {   
            $idProducto = $parametrosBody['idProducto'];

            $producto = Producto::obtenerProducto($idProducto);

            if($data->sector === "socio" || $data->sector === $producto->sector)
            {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No perteneces al sector correspondiente'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No existe el parametro estado, tiempo o id'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}