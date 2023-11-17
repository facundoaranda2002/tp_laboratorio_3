<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarEstadoMesa
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $parametrosBody = $request->getParsedBody();
        $estado = $parametrosBody['estado'];
        
        if ((($estado == "con cliente esperando pedido" || $estado == "con cliente comiendo" || $estado == "con cliente pagando") && $data->sector === 'mozo') || $data->sector === 'socio') 
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No estas habilitado para realizar la accion'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}