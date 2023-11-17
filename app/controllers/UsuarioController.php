<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args) // POST : sueldo sector fechaIngreso nombreUsuario
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $sector = $parametros['sector'];
        // $fechaIngreso = $parametros['fechaIngreso'];
        $nombreUsuario = $parametros['nombreUsuario'];
        //$idProducto = $parametros['idProducto'];
        $password = $parametros['password']; 

        $user = new Usuario();
        $user->sueldo = $sueldo;
        $user->sector = $sector;
        // $user->fechaIngreso = $fechaIngreso;
        $user->nombreUsuario = $nombreUsuario;
        //$user->idProducto = $idProducto;
        $user->password = $password;

        $id = $user->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args) // GET :  idUsuario
    {
        $idUsuario = $args['idUsuario']; 

        $user = Usuario::obtenerUsuario($idUsuario);
        $payload = json_encode($user);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) // PUT  sueldo nombreUsuario sector fechaIngreso idProducto
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $nombreUsuario = $parametros['nombreUsuario'];
        $sector = $parametros['sector'];
        $fechaIngreso = $parametros['fechaIngreso'];
        //$idProducto = $parametros['idProducto'];
        $password = $parametros['password'];
        $idUsuario = $args['idUsuario'];

        Usuario::modificarUsuario($sueldo, $sector, $fechaIngreso, $nombreUsuario, $password, $idUsuario);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // DELETE idUsuario
    {

        $idUsuario = $args['idUsuario'];
        Usuario::borrarUsuario($idUsuario);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function LoginUsuario($request, $response, $args) // POST nombreUsuario password
    {
      $parametros = $request->getParsedBody();

      $nombreUsuario = $parametros['nombreUsuario'];
      $password = $parametros['password'];

      $existe = false;
      $lista = Usuario::obtenerTodos();

      foreach ($lista as $usuario) 
      {
        if($usuario->nombreUsuario == $nombreUsuario && $usuario->password == $password)
        {
          $existe = true;
          $idUsuario = $usuario->idUsuario;
          $sector = $usuario->sector;
          break;
        }
      }
      if($existe)
      {
        $datos=array('idUsuario' => $idUsuario,'sector' => $sector);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      }
      else
      {
        $payload = json_encode(array('error' => 'Nombre de usuario o contraseña incorrectos'));
      }

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
    }

    public function LoggerAdmin($request, $response, $args) // POST nombreUsuario password
    {
      $parametros = $request->getParsedBody();

      $nombreUsuario = $parametros['nombreUsuario'];
      $password = $parametros['password'];

      $user = new Usuario();
      $user->sueldo = null;
      $user->sector = 'socio';
      // $user->fechaIngreso = $fechaIngreso;
      $user->nombreUsuario = $nombreUsuario;
      //$user->idProducto = $idProducto;
      $user->password = $password;

      $id = $user->crearUsuario();

      $datos=array('idUsuario' => $id,'sector' => 'socio');
      $token = AutentificadorJWT::CrearToken($datos);
      $payload = json_encode(array('jwt' => $token));

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
    }

}