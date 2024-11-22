<?php
require_once './connection.php';

class Carrito
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function agregarProducto($codigoProducto, $codigoCliente, $cantidad, $precioTotal)
    {
        try {
            $sql = "INSERT INTO MMVK_CARRITO (CODIGO_PRODUCTO, CODIGO_CLIENTE, CANTIDAD_CARRITO, PRECIO_CARRITO, ESTADO_CARRITO) VALUES (:codigo_producto, :codigo_cliente, :cantidad_carrito, :precio_total, 2)";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
            oci_bind_by_name($stmt, ':cantidad_carrito', $cantidad);
            oci_bind_by_name($stmt, ':precio_total', $precioTotal);
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

    public function obtenerCarritoPendiente($codigoCliente)
    {
        try {
            $sql = "SELECT P.NOMBRE_PRODUCTO as nombre, C.CANTIDAD_CARRITO as cantidad, C.PRECIO_CARRITO as precio, C.CODIGO_PRODUCTO as codigo 
                    FROM MMVK_CARRITO C 
                    JOIN MMVK_PRODUCTO P ON C.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO 
                    WHERE C.CODIGO_CLIENTE = :codigo_cliente AND C.ESTADO_CARRITO = 2";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
            oci_execute($stmt);

            $carrito = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $carrito[] = $row;
            }

            oci_free_statement($stmt);
            return $carrito;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function actualizarEstadoProducto($codigoProducto, $codigoCliente, $nuevoEstado)
    {
        try {
            $sql = "UPDATE MMVK_CARRITO SET ESTADO_CARRITO = :nuevo_estado 
                    WHERE CODIGO_PRODUCTO = :codigo_producto AND CODIGO_CLIENTE = :codigo_cliente";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':nuevo_estado', $nuevoEstado);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);

            $result = oci_execute($stmt);

            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en actualizarEstadoProducto: " . $error['message']);
            }

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function completarCompra($codigoCliente)
    {
        try {
            $sql = "UPDATE MMVK_CARRITO SET ESTADO_CARRITO = 1 WHERE CODIGO_CLIENTE = :codigo_cliente AND ESTADO_CARRITO = 2";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);

            $result = oci_execute($stmt);

            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en completarCompra: " . $error['message']);
            }

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>