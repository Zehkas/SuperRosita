<?php
require_once './connection.php';

class Carrito {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function agregarProducto($codigoProducto, $codigoCliente, $cantidad) {
        try {
            $sql = "BEGIN MMVK_PK_CARRITO(:codigo_producto, :estado_carrito, :cantidad_carrito, :precio_carrito, :codigo_producto, :codigo_cliente); END;";
            $stmt = oci_parse($this->db, $sql);

            $estado_carrito = 2; // Estado pendiente
            $precio_carrito = 0; // Asigna un precio si es necesario

            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':estado_carrito', $estado_carrito);
            oci_bind_by_name($stmt, ':cantidad_carrito', $cantidad);
            oci_bind_by_name($stmt, ':precio_carrito', $precio_carrito);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);

            $result = oci_execute($stmt);

            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en agregarProducto: " . $error['message']);
            }

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
