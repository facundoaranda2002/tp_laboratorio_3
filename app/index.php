<?php

// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/UsuarioController.php';
require_once './middlewares/LoggerSocio.php';
require_once './middlewares/LoggerCliente.php';
require_once './middlewares/LoggerMozo.php';
require_once './middlewares/ValidarModificarEstadoMesa.php';
require_once './middlewares/ValidarAltaProducto.php';
require_once './middlewares/ValidarModificarProducto.php';
require_once './middlewares/ValidarModificarEstadoProducto.php';
require_once './middlewares/ValidarBorrarProducto.php';
require_once './middlewares/ValidarModificarEstadoPedido.php';



// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
//$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new LoggerMozo());
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno')->add(new LoggerMozo());
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(new LoggerMozo());
    $group->put('/{idMesa}', \MesaController::class . ':ModificarUno')->add(new ValidarModificarEstadoMesa());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')->add(new LoggerSocio());
  });

$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{clave}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->put('/ModificarEstado', \PedidoController::class . ':ModificarEstado')->add(new ValidarModificarEstadoPedido());
  $group->delete('/{clave}', \PedidoController::class . ':BorrarUno');
})->add(new LoggerMozo());

$app->group('/Producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos')->add(new LoggerMozo());
  $group->get('/{idProducto}', \ProductoController::class . ':TraerUno')->add(new LoggerMozo());
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new ValidarAltaProducto());
  $group->put('[/]', \ProductoController::class . ':ModificarUno')->add(new ValidarModificarProducto());
  $group->put('/ModificarEstado', \ProductoController::class . ':ModificarEstado')->add(new ValidarModificarEstadoProducto());
  $group->delete('/{idProducto}', \ProductoController::class . ':BorrarUno')->add(new ValidarBorrarProducto());
}); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{idUsuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('/{idUsuario}', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{idUsuario}', \UsuarioController::class . ':BorrarUno');
})->add(new LoggerSocio()); 

$app->run();


