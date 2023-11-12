<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerSocio
{
     public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getQueryParams();

        $sector = $parametros['sector'];

        if ($sector === 'socio') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No sos Socio'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}