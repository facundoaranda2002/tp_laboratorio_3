<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : idMesa imgMesa estado nombre precio comentarios
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        //$imgMesa = $parametros['imgMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $puntuacion = $parametros['puntuacion'];
        $comentarios = $parametros['comentarios'];
        //$clavePedido = $parametros['clavePedido'];

        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        //$pedido->imgMesa = $imgMesa;
        $pedido->estado = $estado;
        $pedido->nombreCliente = $nombreCliente;
        $pedido->precio = $precio;
        $pedido->puntuacion = $puntuacion;
        $pedido->comentarios = $comentarios;
        //$pedido->clavePedido = $clavePedido;


        $id = $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // GET :  clavePedido
    {
        $clavePedido = $args['clavePedido']; 

        $pedido = Pedido::obtenerPedido($clavePedido);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoCliente($request, $response, $args) // GET :  clavePedido idMesa
    {
      $parametrosParam = $request->getQueryParams();
      
      $clavePedido = $parametrosParam['clavePedido'];
      $idMesa = $parametrosParam['idMesa'];

      $pedido = Pedido::obtenerPedido($clavePedido);
      if ($pedido->idMesa == $idMesa) 
      {
        $tiempoMaximo = 0;
        $primeraIteracion = true;
        $productosPedido = Producto::obtenerTodosPorClave($clavePedido);
        foreach ($productosPedido as $producto)
        {
          if($primeraIteracion || $producto->tiempo > $tiempoMaximo)
          {
            $primeraIteracion = false;
            $tiempoMaximo = $producto->tiempo;
          }
        }
        $payload = json_encode(array("Pedido:" => $clavePedido,"Tiempo Restante:"=> $tiempoMaximo));
      } 
      else 
      {
        $payload = json_encode(array("mensaje" => "No existe ese Pedido en esa Mesa"));
      }
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosTiempo($request, $response, $args) // GET 
    {
        $lista = array();
        $listaPedidos = Pedido::obtenerTodos();
        foreach( $listaPedidos as $pedido)
        {
          $tiempoMaximo = 0;
          $primeraIteracion = true;
          $productosPedido = Producto::obtenerTodosPorClave($pedido->clavePedido);
          
          foreach ($productosPedido as $producto)
          {
            if($primeraIteracion || $producto->tiempo > $tiempoMaximo)
            {
              $primeraIteracion = false;
              $tiempoMaximo = $producto->tiempo;
            }
          }
          array_push($lista, array("clavePedido" => $pedido->clavePedido, "estado" => $pedido->estado, "tiempo" => $tiempoMaximo));
        }

        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosSegunEstado($request, $response, $args) // GET estado
  {
    $parametrosParam = $request->getQueryParams();
    $estado = $parametrosParam['estado'];

    $lista = Pedido::obtenerTodosSegunEstado($estado);
    $payload = json_encode(array("listaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];
        $nombreCliente = $parametros['nombreCliente'];
        $precio = $parametros['precio'];
        $puntuacion = $parametros['puntuacion'];
        $comentarios = $parametros['comentarios'];
        $clavePedido = $parametros['clavePedido'];
        //$idPedido = $parametros['idPedido'];

        Pedido::modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentarios, $clavePedido);

        $payload = json_encode(array("mensaje" => "Pedido Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUnoCliente($request, $response, $args) // PUT :  clavePedido idMesa puntuacion comentarios
    {
      $parametros = $request->getParsedBody();
      $clavePedido = $parametros['clavePedido'];
      $idMesa = $parametros['idMesa'];
      $puntuacion = $parametros['puntuacion'];
      $comentarios = $parametros['comentarios'];

      $pedido = Pedido::obtenerPedido($clavePedido);
      if ($pedido->idMesa == $idMesa) 
      {
        Pedido::modificarComentariosYPuntuacionDelPedido($puntuacion, $comentarios, $clavePedido);
        $payload = json_encode(array("mensaje" => "Se Agregaron los comentarios y la puntuacion"));
      } 
      else 
      {
        $payload = json_encode(array("mensaje" => "No existe ese Pedido en esa Mesa"));
      }
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstado($request, $response, $args) // PUT  estado clavePedido
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $clavePedido = $parametros['clavePedido'];

        Pedido::modificarEstadoDelPedido($estado, $clavePedido);

        $payload = json_encode(array("mensaje" => "El estado del pedido se modifico con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function SubirFoto($request, $response, $args) // POST : clavePedido + foto
    {
      $parametros = $request->getParsedBody();

      $clavePedido = $parametros['clavePedido'];

      $pedidoAux = Pedido::obtenerPedido($clavePedido);

      $carpeta_archivos =  "../src/fotos/";
      $nombre_archivo = $pedidoAux->idMesa . "_" . $pedidoAux->clavePedido . "_" . $pedidoAux->nombreCliente;
      $ruta_destino = $carpeta_archivos . $nombre_archivo . ".jpg";

      if (move_uploaded_file($_FILES['archivo']['tmp_name'],  $ruta_destino))
      {
          $payload = json_encode(array("mensaje" => "Foto Subida con exito"));
      }
      else
      {
          $payload = json_encode(array("mensaje" => "No se pudo guardar la Foto"));
      }  

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $clavePedido = $args['clavePedido'];
        Pedido::borrarPedido($clavePedido);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}