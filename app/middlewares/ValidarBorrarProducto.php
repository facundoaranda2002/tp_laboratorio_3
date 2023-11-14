<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ValidarBorrarProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosParam = $request->getQueryParams();
        //$parametrosBody = $request->getParsedBody();
        $routeContext = RouteContext::fromRequest($request);                                                                                                             
        $route = $routeContext->getRoute();                                                                                                                              
        // Resolve user ID in this scope                                                                                                                                 
        $idProducto = $route->getArgument('idProducto');    
        

        if(isset($parametrosParam['sector']))
        {   
            $sector = $parametrosParam['sector'];
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