<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerCliente
{
     public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getQueryParams();

        $sector = $parametros['sector'];

        if ($sector === 'cliente' || $sector === 'socio' || $sector === 'mozo') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No sos Cliente'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}