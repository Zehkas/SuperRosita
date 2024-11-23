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
            //Verifica si existe un carrito con el mismo producto y cliente pendiente
            $sql = "SELECT CANTIDAD_CARRITO, PRECIO_CARRITO FROM MMVK_CARRITO WHERE CODIGO_PRODUCTO = :codigo_producto AND CODIGO_CLIENTE = :codigo_cliente AND ESTADO_CARRITO = 2";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
            oci_execute($stmt);
            $carritoExistente = oci_fetch_assoc($stmt);
    
            if ($carritoExistente) {
                //De existir coincidencia cambio los valores totales
                $nuevaCantidad = $carritoExistente['CANTIDAD_CARRITO'] + $cantidad;
                $nuevoPrecio = $carritoExistente['PRECIO_CARRITO'] + $precioTotal;
                $sqlUpdate = "UPDATE MMVK_CARRITO SET CANTIDAD_CARRITO = :nueva_cantidad, PRECIO_CARRITO = :nuevo_precio WHERE CODIGO_PRODUCTO = :codigo_producto AND CODIGO_CLIENTE = :codigo_cliente AND ESTADO_CARRITO = 2";
                $stmtUpdate = oci_parse($this->db, $sqlUpdate);
                oci_bind_by_name($stmtUpdate, ':nueva_cantidad', $nuevaCantidad);
                oci_bind_by_name($stmtUpdate, ':nuevo_precio', $nuevoPrecio);
                oci_bind_by_name($stmtUpdate, ':codigo_producto', $codigoProducto);
                oci_bind_by_name($stmtUpdate, ':codigo_cliente', $codigoCliente);
                $result = oci_execute($stmtUpdate);
                if (!$result) {
                    $error = oci_error($stmtUpdate);
                    throw new Exception("Error en actualizarProducto: " . $error['message']);
                }
                oci_free_statement($stmtUpdate);
            } else {
                //Si no existe, se crea un nuevo carrito
                $sqlInsert = "INSERT INTO MMVK_CARRITO(CODIGO_PRODUCTO, CODIGO_CLIENTE, CANTIDAD_CARRITO, PRECIO_CARRITO, ESTADO_CARRITO) VALUES (:codigo_producto, :codigo_cliente, :cantidad_carrito, :precio_total, 2)";
                $stmtInsert = oci_parse($this->db, $sqlInsert);
                oci_bind_by_name($stmtInsert, ':codigo_producto', $codigoProducto);
                oci_bind_by_name($stmtInsert, ':codigo_cliente', $codigoCliente);
                oci_bind_by_name($stmtInsert, ':cantidad_carrito', $cantidad);
                oci_bind_by_name($stmtInsert, ':precio_total', $precioTotal);
                $result = oci_execute($stmtInsert);
                if (!$result) {
                    $error = oci_error($stmtInsert);
                    throw new Exception("Error en agregarProducto: " . $error['message']);
                }
                oci_free_statement($stmtInsert);
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

    public function obtenerHistorialCompras($codigoCliente)
    {
        try {
            $sql = "SELECT P.NOMBRE_PRODUCTO as nombre, C.CANTIDAD_CARRITO as cantidad, C.PRECIO_CARRITO as precio, C.CODIGO_PRODUCTO as codigo, C.ESTADO_CARRITO as estado, C.CODIGO_CARRITO as codigo_carrito 
                    FROM MMVK_CARRITO C 
                    JOIN MMVK_PRODUCTO P ON C.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO 
                    WHERE (C.CODIGO_CLIENTE = :codigo_cliente AND (C.ESTADO_CARRITO = 1 OR C.ESTADO_CARRITO BETWEEN 4 AND 7))";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
            oci_execute($stmt);

            $historial = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $historial[] = $row;
            }

            oci_free_statement($stmt);
            return $historial;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function obtenerDevoluciones($codigoCliente)
    {
        try {
            $sql = "SELECT P.NOMBRE_PRODUCTO as nombre, C.CANTIDAD_CARRITO as cantidad, C.PRECIO_CARRITO as precio, C.CODIGO_PRODUCTO as codigo, C.ESTADO_CARRITO as estado, C.CODIGO_CARRITO as codigo_carrito 
                    FROM MMVK_CARRITO C 
                    JOIN MMVK_PRODUCTO P ON C.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO 
                    WHERE C.CODIGO_CLIENTE = :codigo_cliente AND C.ESTADO_CARRITO BETWEEN 4 AND 7";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
            oci_execute($stmt);

            $devoluciones = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $devoluciones[] = $row;
            }

            oci_free_statement($stmt);
            return $devoluciones;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function actualizarEstadoProducto($codigoCarrito, $nuevoEstado)
    {
        try {
            $sql = "UPDATE MMVK_CARRITO SET ESTADO_CARRITO = :nuevo_estado 
                    WHERE CODIGO_CARRITO = :codigo_carrito";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':nuevo_estado', $nuevoEstado);
            oci_bind_by_name($stmt, ':codigo_carrito', $codigoCarrito);

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

    public function eliminarProducto($codigoProducto,$codigoCliente)
    {
        try {
            $sql = "UPDATE MMVK_CARRITO SET ESTADO_CARRITO = 3 
                    WHERE (CODIGO_PRODUCTO = :codigo_producto AND CODIGO_CLIENTE = :codigo_cliente AND ESTADO_CARRITO = 2)";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, 'codigo_cliente', $codigoCliente);

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

    public function iniciarReembolso($codigoCarrito, $descripcion)
    {
        try {
            // Obtener los códigos de producto y cliente desde el carrito
            $sqlCarrito = "SELECT CODIGO_PRODUCTO, CODIGO_CLIENTE FROM MMVK_CARRITO WHERE CODIGO_CARRITO = :codigo_carrito";
            $stmtCarrito = oci_parse($this->db, $sqlCarrito);
            oci_bind_by_name($stmtCarrito, ':codigo_carrito', $codigoCarrito);
            oci_execute($stmtCarrito);
            $carritoInfo = oci_fetch_assoc($stmtCarrito);
            
            if (!$carritoInfo) {
                throw new Exception("Error: No se encontró el carrito con el código proporcionado.");
            }

            $codigoProducto = $carritoInfo['CODIGO_PRODUCTO'];
            $codigoCliente = $carritoInfo['CODIGO_CLIENTE'];
            
            // Insertar en la tabla de devoluciones
            $sql = "INSERT INTO MMVK_DEVOLUCION (FECHA_DEVOLUCION, DESCRIPCION_DEVOLUCION, CODIGO_PRODUCTO, CODIGO_CLIENTE)
                    VALUES (SYSDATE, :descripcion, :codigo_producto, :codigo_cliente)";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':descripcion', $descripcion);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);

            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en iniciarReembolso: " . $error['message']);
            }

            // Actualizar estado del producto en el carrito
            $this->actualizarEstadoProducto($codigoCarrito, 5);

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function editarReembolso($codigoCarrito, $descripcion)
    {
        try {
            $sqlCarrito = "SELECT CODIGO_PRODUCTO, CODIGO_CLIENTE FROM MMVK_CARRITO WHERE CODIGO_CARRITO = :codigo_carrito";
            $stmtCarrito = oci_parse($this->db, $sqlCarrito);
            oci_bind_by_name($stmtCarrito, ':codigo_carrito', $codigoCarrito);
            oci_execute($stmtCarrito);
            $carritoInfo = oci_fetch_assoc($stmtCarrito);
            
            if (!$carritoInfo) {
                throw new Exception("Error: No se encontró el carrito con el código proporcionado.");
            }
    
            $codigoProducto = $carritoInfo['CODIGO_PRODUCTO'];
            $codigoCliente = $carritoInfo['CODIGO_CLIENTE'];
    
            $sql = "UPDATE MMVK_DEVOLUCION SET DESCRIPCION_DEVOLUCION = :descripcion 
                    WHERE CODIGO_PRODUCTO = :codigo_producto AND CODIGO_CLIENTE = :codigo_cliente";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':descripcion', $descripcion);
            oci_bind_by_name($stmt, ':codigo_producto', $codigoProducto);
            oci_bind_by_name($stmt, ':codigo_cliente', $codigoCliente);
    
            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en editarReembolso: " . $error['message']);
            }
    
            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function cancelarReembolso($codigoCarrito)
    {
        try {
            $sql = "UPDATE MMVK_CARRITO SET ESTADO_CARRITO = 7 WHERE CODIGO_CARRITO = :codigo_carrito";
            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':codigo_carrito', $codigoCarrito);

            $result = oci_execute($stmt);
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en cancelarReembolso: " . $error['message']);
            }

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

public function obtenerTodasDevoluciones() { 
    try {
        $sql = "SELECT P.NOMBRE_PRODUCTO as nombre, C.CANTIDAD_CARRITO as cantidad, C.PRECIO_CARRITO as precio, C.CODIGO_PRODUCTO as codigo, C.ESTADO_CARRITO as estado, C.CODIGO_CARRITO as codigo_carrito, D.DESCRIPCION_DEVOLUCION as descripcion 
                FROM MMVK_CARRITO C 
                JOIN MMVK_PRODUCTO P ON C.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO 
                JOIN MMVK_DEVOLUCION D ON C.CODIGO_PRODUCTO = D.CODIGO_PRODUCTO AND C.CODIGO_CLIENTE = D.CODIGO_CLIENTE
                WHERE C.ESTADO_CARRITO BETWEEN 4 AND 7";
        $stmt = oci_parse($this->db, $sql);
        oci_execute($stmt);

        $devoluciones = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $devoluciones[] = $row;
        }

        oci_free_statement($stmt);
        return $devoluciones;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

public function aprobarReembolso($codigoCarrito) { 
    return $this->actualizarEstadoProducto($codigoCarrito, 4);
}

public function rechazarReembolso($codigoCarrito) { 
    return $this->actualizarEstadoProducto($codigoCarrito, 6);
}

}
?>
