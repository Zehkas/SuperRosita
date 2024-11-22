<?php
require_once './model/carrito.php';
require_once './connection.php';

class CarritoControlador {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function AgregarAlCarrito($codigoProducto, $idCliente, $cantidad = 1, $precio) {
        $carrito = new Carrito($this->db);
        $carrito->agregarProducto($codigoProducto, $idCliente, $cantidad, $precio);
    }

    public function obtenerCarritoPendiente($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->obtenerCarritoPendiente($codigoCliente);
    }

    public function obtenerHistorialCompras($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->obtenerHistorialCompras($codigoCliente);
    }

    public function obtenerDevoluciones($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->obtenerDevoluciones($codigoCliente);
    }

    public function obtenerTodasDevoluciones() {
        $carrito = new Carrito($this->db);
        return $carrito->obtenerTodasDevoluciones();
    }

    public function actualizarEstadoProducto($codigoCarrito, $nuevoEstado) {
        $carrito = new Carrito($this->db);
        return $carrito->actualizarEstadoProducto($codigoCarrito, $nuevoEstado);
    }

    public function completarCompra($codigoCliente) {
        $carrito = new Carrito($this->db);
        return $carrito->completarCompra($codigoCliente);
    }

    public function eliminarProducto($codigoCarrito) {
        return $this->actualizarEstadoProducto($codigoCarrito, 3);
    }

    public function iniciarReembolso($codigoCarrito, $descripcion) {
        $carrito = new Carrito($this->db);
        $carrito->iniciarReembolso($codigoCarrito, $descripcion);
        $this->actualizarEstadoProducto($codigoCarrito, 5);
    }

    public function editarReembolso($codigoCarrito, $descripcion) {
        $carrito = new Carrito($this->db);
        return $carrito->editarReembolso($codigoCarrito, $descripcion);
    }

    public function cancelarReembolso($codigoCarrito) {
        $carrito = new Carrito($this->db);
        return $carrito->cancelarReembolso($codigoCarrito);
    }

    public function aprobarReembolso($codigoCarrito) {
        return $this->actualizarEstadoProducto($codigoCarrito, 4);
    }

    public function rechazarReembolso($codigoCarrito) {
        return $this->actualizarEstadoProducto($codigoCarrito, 6);
    }
}
?>