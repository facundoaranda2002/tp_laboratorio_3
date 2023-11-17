<?php

class Pedido
{
    public $idMesa;
    //public $imgMesa;
    public $estado;
    public $nombreCliente ;
    public $precio ;
    public $puntuacion;
    public $comentarios;
    public $clavePedido;
    public $idPedido ;
    
    
    
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, estado, nombreCliente, precio, puntuacion, comentarios, clavePedido) VALUES (:idMesa, :estado, :nombreCliente, :precio, :puntuacion, :comentarios, :clavePedido)");
        
        $clavePedido = $this->generarCodigoAlfanumericoAleatorio();

        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        //$consulta->bindValue(':imgMesa', $this->imgMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion', $this->puntuacion, PDO::PARAM_INT); 
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();

    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentarios, clavePedido, idPedido FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosSegunIdMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentarios, clavePedido, idPedido FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosSegunEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentarios, clavePedido, idPedido FROM pedidos WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($clavePedido) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, estado, nombreCliente, precio, puntuacion, comentarios, clavePedido, idPedido FROM pedidos WHERE clavePedido = :clavePedido");
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    

    public static function modificarPedido($idMesa, $estado, $nombreCliente, $precio, $puntuacion, $comentarios, $clavePedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, estado = :estado, nombreCliente = :nombreCliente, precio = :precio, puntuacion = :puntuacion, comentarios = :comentarios WHERE clavePedido = :clavePedido");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        //$consulta->bindValue(':imgMesa', $imgMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        //$consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($clavePedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE clavePedido = :clavePedido");
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function modificarEstadoDelPedido($estado, $clavePedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE clavePedido = :clavePedido");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function modificarComentariosYPuntuacionDelPedido($puntuacion, $comentarios, $clavePedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET puntuacion = :puntuacion, comentarios = :comentarios WHERE clavePedido = :clavePedido");
        $consulta->bindValue(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':clavePedido', $clavePedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    private function generarCodigoAlfanumericoAleatorio($longitud = 5) 
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        $max = strlen($caracteres) - 1;
    
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[random_int(0, $max)];
        }
    
        return $codigo;
    }

}