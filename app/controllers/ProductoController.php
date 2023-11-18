<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : estado tiempo sector nombre
    {
        $parametros = $request->getParsedBody();

        //$estado = $parametros['estado'];
        //$tiempo = $parametros['tiempo'];
        $sector = $parametros['sector'];
        $nombre = $parametros['nombre'];
        $clavePedido = $parametros['clavePedido'];

        $prod = new Producto();
        $prod->estado = "pendiente";
        $prod->tiempo = null;
        $prod->sector = $sector;
        $prod->nombre = $nombre;
        $prod->clavePedido = $clavePedido;

        $id = $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  idProducto
    {
        $idProducto = $args['idProducto']; 

        $prod = Producto::obtenerProducto($idProducto);
        $payload = json_encode($prod);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerSegunEstadoYSector($request, $response, $args) // GET estado
    {
      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);
      $data = AutentificadorJWT::ObtenerData($token);

      $parametrosParam = $request->getQueryParams();
      $estado = $parametrosParam['estado'];

      $lista = Producto::obtenerTodosSegunSuEstadoYSector($estado, $data->sector);

      $payload = json_encode(array("listaProductos" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        

        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];
        $sector = $parametros['sector'];
        $nombre = $parametros['nombre'];
        $clavePedido = $parametros['clavePedido'];
        $idUsuario = $parametros['idUsuario'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarProducto($estado, $tiempo, $sector, $nombre, $clavePedido, $idUsuario, $idProducto);

        $payload = json_encode(array("mensaje" => "Producto Modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstadoYTiempo($request, $response, $args) // PUT  estado idProducto
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];
        $idProducto = $parametros['idProducto'];

        Producto::modificarEstadoYTiempoDelProducto($estado, $tiempo, $data->idUsuario, $idProducto);

        $payload = json_encode(array("mensaje" => "El estado y el tiempo del producto fueron modificados con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        Producto::borrarProducto($idProducto);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args)
    {
            
      if($archivo = fopen("csv/productos.csv", "w"))
      {
        $lista = Producto::obtenerTodos();
        foreach( $lista as $producto )
        {
            fputcsv($archivo, [$producto->estado, $producto->tiempo, $producto->sector, $producto->nombre, $producto->clavePedido, $producto->idUsuario, $producto->idProducto]);
        }
        fclose($archivo);
        $payload =  json_encode(array("mensaje" => "La lista de productos se guardo correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo abrir el archivo de productos"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CargarCSV($request, $response, $args)
    {
      if(($archivo = fopen("csv/productos.csv", "r")) !== false)
      {
        Producto::borrarProductos();
        while (($filaProducto = fgetcsv($archivo, 0, ',')) !== false) //esto seria como un while !feof
        {
          $nuevoProducto = new Producto();
          $nuevoProducto->estado = $filaProducto[0];
          $nuevoProducto->tiempo = $filaProducto[1];
          $nuevoProducto->sector = $filaProducto[2];
          $nuevoProducto->nombre = $filaProducto[3];
          $nuevoProducto->clavePedido = $filaProducto[4];
          $nuevoProducto->idUsuario = $filaProducto[5];
          $nuevoProducto->idProducto = $filaProducto[6];
          $nuevoProducto->crearProductoCSV();
        }
        fclose($archivo);
        $payload  =  json_encode(array("mensaje" => "Los productos se cargaron correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo leer el archivo de productos"));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}