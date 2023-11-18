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

require_once './middlewares/AuthMiddleware.php';

require_once './middlewares/LoginSocio.php';
require_once './middlewares/LoginMozo.php';

require_once './middlewares/ValidarModificarEstadoMesa.php';
require_once './middlewares/ValidarModificarProducto.php';
require_once './middlewares/ValidarModificarEstadoYTiempoProducto.php';
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
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new LoginSocio());
    $group->get('/TraerUno/{idMesa}', \MesaController::class . ':TraerUno')->add(new LoginSocio());
    $group->get('/MasUsada', \MesaController::class . ':MesaMasUsada')->add(new LoginSocio());
    $group->get('/MejorComentario', \MesaController::class . ':MesaMejoresComentarios')->add(new LoginSocio());
    $group->get('/Estado', \MesaController::class . ':TraerTodosEstado')->add(new LoginSocio());
    $group->get('/GuardarCSV', \MesaController::class . ':GuardarCSV');
    $group->get('/CargarCSV', \MesaController::class . ':CargarCSV');
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(new LoginSocio());
    $group->put('/{idMesa}', \MesaController::class . ':ModificarUno')->add(new ValidarModificarEstadoMesa());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')->add(new LoginSocio());
  })->add(new AuthMiddleware());

$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/EstadoPedido', \PedidoController::class . ':TraerTodosSegunEstado');
  $group->get('/Tiempo', \PedidoController::class . ':TraerTodosTiempo');
  $group->get('/TraerUno/{clavePedido}', \PedidoController::class . ':TraerUno');
  $group->get('/GuardarCSV', \PedidoController::class . ':GuardarCSV');
  $group->get('/CargarCSV', \PedidoController::class . ':CargarCSV');
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->post('/SubirFoto', \PedidoController::class . ':SubirFoto');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->put('/ModificarEstado', \PedidoController::class . ':ModificarEstado')->add(new ValidarModificarEstadoPedido());
  $group->delete('/{clavePedido}', \PedidoController::class . ':BorrarUno');
})->add(new LoginMozo())->add(new AuthMiddleware());

$app->group('/Producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos')->add(new LoginMozo());
  $group->get('/TraerUno/{idProducto}', \ProductoController::class . ':TraerUno')->add(new LoginMozo());
  $group->get('/MostrarEstadoSector', \ProductoController::class . ':TraerSegunEstadoYSector');
  $group->get('/GuardarCSV', \ProductoController::class . ':GuardarCSV');
  $group->get('/CargarCSV', \ProductoController::class . ':CargarCSV');
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new LoginMozo());
  $group->put('[/]', \ProductoController::class . ':ModificarUno')->add(new ValidarModificarProducto())->add(new LoginMozo());
  $group->put('/ModificarEstadoYTiempo', \ProductoController::class . ':ModificarEstadoYTiempo')->add(new ValidarModificarEstadoYTiempoProducto());
  $group->delete('/{idProducto}', \ProductoController::class . ':BorrarUno')->add(new LoginMozo());
})->add(new AuthMiddleware()); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/TraerUno/{idUsuario}', \UsuarioController::class . ':TraerUno');
  $group->get('/GuardarCSV', \UsuarioController::class . ':GuardarCSV');
  $group->get('/CargarCSV', \UsuarioController::class . ':CargarCSV');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('/{idUsuario}', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{idUsuario}', \UsuarioController::class . ':BorrarUno');
})->add(new LoginSocio())->add(new AuthMiddleware()); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->post('/LoggerAdmin', \UsuarioController::class . ':LoggerAdmin');
  $group->post('/Login', \UsuarioController::class . ':LoginUsuario');
});

$app->group('/Cliente', function (RouteCollectorProxy $group) {
  $group->put('/ModificarCliente', \PedidoController::class . ':ModificarUnoCliente');
  $group->get('/TraerUno', \PedidoController::class . ':TraerUnoCliente');
});




$app->run();


