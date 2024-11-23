<?php
require_once './connection.php';

class Producto
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    // Método para agregar un nuevo producto
    public function agregarProducto($nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento)
    {
        $sql = "BEGIN 
                MMVK_CRUD_PRODUCTO(NULL, :nombre, :peso, :fecha_fabricacion, NULL, :precio_compra, :precio_venta, :existencia, :codigo_pais, :codigo_departamento, 'I');
                END;";

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
    public function editarProducto($codigo_producto, $nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento)
    {
        $sql = "BEGIN 
                MMVK_CRUD_PRODUCTO(:codigo_producto, :nombre, :peso, :fecha_fabricacion, NULL, :precio_compra, :precio_venta, :existencia, :codigo_pais, :codigo_departamento, 'U');
                END;";
        $stmt = oci_parse($this->db, $sql);

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
    public function eliminarProducto($codigo_producto)
    {
        $sql = "BEGIN 
                MMVK_CRUD_CLIENTE(:codigo_producto, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'D');
                END;";

        $stmt = oci_parse($this->db, $sql);

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
    public function obtenerProductos()
    {
        $sql = "SELECT CODIGO_PRODUCTO, NOMBRE_PRODUCTO, PRECIO_VENTA_PRODUCTO FROM MMVK_PRODUCTO";
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
        public function obtenerTodosLosProductos() {
            try {
                $sql = "SELECT * FROM MMVK_PRODUCTO";
                $stmt = oci_parse($this->db, $sql);
                oci_execute($stmt);
    
                $productos = [];
                while ($row = oci_fetch_assoc($stmt)) {
                    $productos[] = $row;
                }
    
                oci_free_statement($stmt);
                return $productos;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }
    
        // Obtener productos aleatorios
        public function obtenerProductosAleatorios($cantidad) {
            try {
                $sql = "SELECT * FROM (
                            SELECT * FROM MMVK_PRODUCTO ORDER BY DBMS_RANDOM.VALUE
                        ) WHERE ROWNUM <= :cantidad";
                $stmt = oci_parse($this->db, $sql);
                oci_bind_by_name($stmt, ':cantidad', $cantidad);
                oci_execute($stmt);
    
                $productos = [];
                while ($row = oci_fetch_assoc($stmt)) {
                    $productos[] = $row;
                }
    
                oci_free_statement($stmt);
                return $productos;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }
    public function obtenerProductoPorCodigo($codigoProducto)
    {
        try {
            $sql = "SELECT * FROM MMVK_PRODUCTO WHERE CODIGO_PRODUCTO = :codigo_producto";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_execute($stmt);
            $producto = oci_fetch_assoc($stmt);
            oci_free_statement($stmt);
            return $producto;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>