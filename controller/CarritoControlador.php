<?php
require_once './model/carrito.php';
require_once './connection.php';

class CarritoControlador {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function AgregarAlCarrito($codigoProducto, $idCliente, $cantidad = 1) {
        $carrito = new Carrito($this->db);
        $carrito->agregarProducto($codigoProducto, $idCliente, $cantidad);
    }

    public function obtenerCarritoPendiente($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->obtenerCarritoPendiente($codigoCliente);
    }

    public function actualizarEstadoProducto($codigoProducto, $codigoCliente, $nuevoEstado) {
        $carrito = new Carrito($this->db);
        return $carrito->actualizarEstadoProducto($codigoProducto, $codigoCliente, $nuevoEstado);
    }

    public function completarCompra($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->completarCompra($codigoCliente);
    }
}
?>
