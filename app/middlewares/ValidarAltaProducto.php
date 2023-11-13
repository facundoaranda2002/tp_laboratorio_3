<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarAltaProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosParam = $request->getQueryParams();
        $parametrosBody = $request->getParsedBody();
        

        if(isset($parametrosParam['sector']) && isset($parametrosBody['tipo']))
        {   
            $sector = $parametrosParam['sector'];
            $tipo = $parametrosBody['tipo'];

            if($sector == $tipo)
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
            $payload = json_encode(array('mensaje' => 'No existe el parametro sector o el tipo'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}