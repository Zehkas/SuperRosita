<?php
require_once './connection.php';

class Producto {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Método para agregar un nuevo producto
    public function agregarProducto($nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento) {
        $sql = "INSERT INTO MMVK_PRODUCTO(NOMBRE_PRODUCTO, PESO_PRODUCTO, FECHA_FABRICACION_PRODUCTO, PRECIO_COMPRA_PRODUCTO, PRECIO_VENTA_PRODUCTO, EXISTENCIA_PRODUCTO, CODIGO_PAIS_ORIGEN_PRODUCTO, CODIGO_DEPARTAMENTO)
                VALUES (:nombre, :peso, :fecha_fabricacion, :precio_compra, :precio_venta, :existencia, :codigo_pais, :codigo_departamento)";
        
        $stmt = oci_parse($this->db, $sql);

        // Asignar los parámetros
        oci_bind_by_name($stmt, ':nombre', $nombre);
        oci_bind_by_name($stmt, ':peso', $peso);
        oci_bind_by_name($stmt, ':fecha_fabricacion', $fecha_fabricacion);
        oci_bind_by_name($stmt, ':precio_compra', $precio_compra);
        oci_bind_by_name($stmt, ':precio_venta', $precio_venta);
        oci_bind_by_name($stmt, ':existencia', $existencia);
        oci_bind_by_name($stmt, ':codigo_pais', $codigo_pais);
        oci_bind_by_name($stmt, ':codigo_departamento', $codigo_departamento);

        try {
            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error al agregar producto: " . $error['message']);
            }
            oci_free_statement($stmt);
            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método para editar un producto existente
    public function editarProducto($codigo_producto, $nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento) {
        $sql = "UPDATE MMVK_PRODUCTO SET
                NOMBRE_PRODUCTO = :nombre,
                PESO_PRODUCTO = :peso,
                FECHA_FABRICACION_PRODUCTO = :fecha_fabricacion,
                PRECIO_COMPRA_PRODUCTO = :precio_compra,
                PRECIO_VENTA_PRODUCTO = :precio_venta,
                EXISTENCIA_PRODUCTO = :existencia,
                CODIGO_PAIS_ORIGEN_PRODUCTO = :codigo_pais,
                CODIGO_DEPARTAMENTO = :codigo_departamento
                WHERE CODIGO_PRODUCTO = :codigo_producto";
        
        $stmt = oci_parse($this->db, $sql);

        // Asignar los parámetros
        oci_bind_by_name($stmt, ':codigo_producto', $codigo_producto);
        oci_bind_by_name($stmt, ':nombre', $nombre);
        oci_bind_by_name($stmt, ':peso', $peso);
        oci_bind_by_name($stmt, ':fecha_fabricacion', $fecha_fabricacion);
        oci_bind_by_name($stmt, ':precio_compra', $precio_compra);
        oci_bind_by_name($stmt, ':precio_venta', $precio_venta);
        oci_bind_by_name($stmt, ':existencia', $existencia);
        oci_bind_by_name($stmt, ':codigo_pais', $codigo_pais);
        oci_bind_by_name($stmt, ':codigo_departamento', $codigo_departamento);

        try {
            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error al editar producto: " . $error['message']);
            }
            oci_free_statement($stmt);
            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método para eliminar un producto existente
    public function eliminarProducto($codigo_producto) {
        $sql = "DELETE FROM MMVK_PRODUCTO WHERE CODIGO_PRODUCTO = :codigo_producto";
        
        $stmt = oci_parse($this->db, $sql);

        // Asignar el parámetro
        oci_bind_by_name($stmt, ':codigo_producto', $codigo_producto);

        try {
            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error al eliminar producto: " . $error['message']);
            }
            oci_free_statement($stmt);
            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método para obtener todos los productos
    public function obtenerProductos() {
        $sql = "SELECT NOMBRE_PRODUCTO, PRECIO_VENTA_PRODUCTO FROM MMVK_PRODUCTO";
        $stmt = oci_parse($this->db, $sql);

        try {
            oci_execute($stmt);
            $productos = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $productos[] = $row;
            }
            oci_free_statement($stmt);
            return $productos;
        } catch (Exception $e) {
            $error = oci_error($this->db);
            die("Error al obtener productos: " . $error['message']);
        }
    }
}
?>
